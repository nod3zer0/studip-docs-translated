---
title: VUEX
slug: /vuejs/vuex
sidebar_label: VUEX
---

Of course, we also want to use the Stud.IP JSONAPI in Vue.js. This is easily possible with the familiar tools such as XMLHttpRequest and fetch or with wrapper libraries such as axios. But can we make this a little more practical?

In this context, it is important to note that Vue.js is a component-based framework. The generated HTML is created from reusable components that ultimately form a component tree.

### State management with vuex

A frequently recurring problem in component-based web frameworks is the question of state management. How do I organize access to the state, the data, of my application? The usual approach used in Vue.js is to pass relevant data from parent components to their child components.

Example:

* Two component nodes need the same data.
* However, they are only very distantly related to each other, e.g. they are each deeply connected to two different main branches

As a rule, this means that the data is then provided by the next common relative and must then be passed through the complete relationship lines, although the nodes in between have nothing to do with this data.

This is where `vuex` comes in and provides a central store that **all** components can access.

When we read data from the JSONAPI, it is therefore worth feeding it into the store so that, among other things, there are clearly defined points at which the JSONAPI is accessed in order to prevent duplications that cause performance problems.

**So we definitely want to have a combination of JSONAPI and vuex.

### `reststate-vuex`

Fortunately, there are already a few libraries for linking JSONAPI and `vuex`. For the implementation of *Courseware 5* we decided to use a library which we have extended by further possibilities and which is currently maintained by ELAN e.V.:

https://github.com/elan-ev/reststate-vuex

### Setup

The setup to use `reststate-vuex` configures an `axios` instance with our JSONAPI interface. As a rule, we only see this code very rarely.

```javascript
const getHttpClient = () =>
    axios.create({
        baseURL: STUDIP.URLHelper.getURL(`jsonapi.php/v1`, {}, true),
        headers: {
            'Content-Type': 'application/vnd.api+json',
        },
    });

// [ ]

const store = new Vuex.Store({
    modules: {
        courseware: CoursewareModule,
        ...mapResourceModules({
            names: [
                'courses',
                'courseware-blocks',
                'courseware-block-comments',
                'courseware-containers',
                'courseware-instances',
                'courseware-structural-elements',
                'courseware-user-data-fields',
                'courseware-user-progresses',
                'files',
                'file-refs',
                'folders',
                'users',
            ],
            httpClient,
        }),
    },
});
```

In the code above, we make the JSONAPI schemas "courses", "users", "files" etc. known. What can we do with them now?

### Read out

Perhaps we would like to read out all users and output them in a Vue.js component:

```php
<template>
<div>
  <ul>
    <li v-for="user in allUsers" :key="user.id">
      {{ user.attributes['formatted-name'] }}
    </li>
  </ul>
</div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'users-list',
    mounted() {
        this.loadAllUsers();
    },
    methods: {
        ...mapActions({
            loadAllUsers: 'users/loadAll',
        }),
    },
    computed: {
        ...mapGetters({
            allUsers: 'users/all',
        }),
    },
};
</script>
```

 This already provides a good overview of what `reststate-vuex` can offer us. Here are the most important points:

* We use `mapActions` and `mapGetters` as usual to call actions and getters of `reststate-vuex`.
* The `loadAll` action loads all data of the respective JSONAPI schema and saves it in the store.
* We use the `all` getter to get all resources of a JSONAPI schema from the store.
* A `user` resource is then accessed as expected with `user.id` or in the `user.attributes` object.

Individual resources can be loaded into the store via the action `users/loadById` from the JSONAPI backend and retrieved from the store via the getter `users/byId`.

### Loading progress and errors

Only a few changes are necessary so that we do not have to implement load progress and error handling again and again. First, we add the existing getters:

```javascript
...mapGetters({
+ isLoading: 'users/isLoading',
+ isError: 'users/isError',
       allUsers: 'users/all',
     })
```

and can then access these in the template:

```php
<template>
   <div>
- <ul>
+ <p v-if="isLoading" v-translate>Loading...</p>
+ <p v-else-if="isError" v-translate>Error while loading.</p>
+ <ul v-else>
       <li
         v-for="user in allUsers"
```

### Creation of resources

With `reststate-vuex` we can:

* create resources in the JSONAPI backend via `axios`
* and of course also make them accessible in the `vuex` store

To do this, we only need to add one action. Here is an example for the courseware to create blocks:

```javascript
methods: {
     ...mapActions({
+ createBlock: 'courseware-blocks/create',
     }),
```

We can now use this action in the JavaScript code:

```javascript
// The `container` comes from the store, but this can also be done manually.
const container = { type: 'courseware-containers', id: '17' };

// We create a JSON representation of a courseware block:
// - with a sample block type.
// - without `payload`
// - with a link to a courseware container
const block = {
    attributes: {
        'block-type': 'text',
        'payload': null,
    },
    relationships: {
        container: {
            data: { type: container.type, id: container.id },
        },
    },
};

this.createBlock('courseware-blocks/create', block);
```


### Deleting resources

To delete resources in the JSONAPI backend and in the `vuex` store, we simply use the corresponding action:

```javascript
methods: {
     ...mapActions({
+ deleteBlock: 'courseware-blocks/delete',
     }),
```

and then simply use this action in our Vue.js component:

```php
+ <button @click="deleteBlock(block)" v-translate>
+ delete block
+ </button>
```

### Changing resources

There is also a corresponding action for changing resources in the JSONAPI backend and in the `vuex` store:

```javascript
methods: {
     ...mapActions({
+ updateBlock: 'courseware-blocks/update',
     }),
```

and use this action in the JavaScript code

```javascript
const block = this.getBlock({ id: '17' });
block.attributes.payload = { foo: 'bar' };
this.updateBlock(block);
```

Since the `vuex` store is reactive, all components that work with this resource and of course in the JSONAPI backend will change as usual.

### More

The `reststate-vuex` library offers many other possibilities offered by the JSONAPI, well documented at https://vuex.reststate.codingitwrong.com/
