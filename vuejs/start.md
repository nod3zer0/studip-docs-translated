---
title: Stud.IP VUEJS
slug: /vuejs/
sidebar_label: Introduction
---

Since Stud.IP 4.5 [Vue.js](https://vuejs.org/) is available in Stud.IP and is used for some components.


** Stud.IP.Vue**

With version 5.0 of Stud.IP, the use of Vue.js is to be further promoted. For this purpose, methods are provided via the object `STUDIP.Vue` in Javascript, which are intended to facilitate integration by abstracting or generalizing some things.

Vue.js is now loaded as a chunk and is therefore no longer immediately available on every page. All methods available via `STUDIP.Vue` take care of loading and are therefore the preferred way to use Vue.js.

** `STUDIP.Vue.load()` **

Vue.js is loaded via this method and returned via a Promise.

```Javascript
STUDIP.Vue.load().then(({Vue, createApp, eventBus, store}) => {
    // ...
});
```

** `Vue` **

The `Vue` object used can be used to register components or directives globally.

** `createApp(options)`**

This method loads the Vue chunk and creates an app. The parameters are as follows:

| Parameter | Description |
| ---- | ---- |
|`options`|options like `data`, `methods` or `computed`|

This app can then be mounted on the desired element using `app.$mount(element)`.

This abstraction is trivial, but is intended to encapsulate the creation of an app if the API of Vue.js changes and provide an easy way to get started.

** `eventBus` **

The `eventBus` can be used to send events globally or to listen for them.

** `store` **

The `store` is a [vuex](https://vuex.vuejs.org/guide/) instance that is responsible for data storage.

Further details will follow...

** `STUDIP.Vue.emit(eventName, ...args)` and `STUDIP.Vue.on(eventName, ...args)` **

These two methods can be used to exchange messages and data between Vue components or the surrounding system and Vue apps. An event bus is realized internally, which is provided via a global mixin in each Vue component using the methods `globalEmit(eventName, ...args)` or `globalOn(eventName, ...args)`.

** `[data-vue-app]` **

The HTML attribute `[data-vue-app]` can be used to create a Vue app without further initialization. The content of the attribute can be used to provide further information on the data or components used by passing a corresponding object. Possible options:

| Parameter | Description |
| ---- | ---- |
|`id`|The ID of the app. If this is set and there is a `STUDIP.AppData` object that contains data under the transferred ID, this data is transferred to the app |
|`components`|The components used by the app as an array.
