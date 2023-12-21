---
title: Routes
slug: /jsonapi/routes
sidebar_label: Routes
---

This documentation deals with the development of JSON:API routes.

The Stud.IP JSON:API can be reached under the URI:

`https://<my.studip.installation.de>/<eventual-with-path>/jsonapi.php/v1/<routes>`

For the developer server, for example, under:

`https://develop.studip.de/studip/jsonapi.php/v1/semesters`


### What is the Stud.IP JSON:API?
The Stud.IP JSON:API implements an external interface for accessing Stud.IP data structures and corresponds to the JSON:API specification (https://jsonapi.org/). It is recommended to read this specification to understand it.

If a JSON:API request is received, the following steps are run through one after the other:

* Route mapping: which code is executed for which URI and which HTTP verb?
* Route handler: Delivers a JSON:API-compliant response. Often one or more Stud.IP objects are returned.
* Schema mapping: Which schema class can convert objects of a specific Stud.IP class into JSON?
* Schemas: Defines the mapping of a Stud.IP object to JSON.


![image](../assets/3a528f8f2de835a0ba5c4e342929179a/image.png)
JSON:API flow

### Route mapping

In the file `/lib/classes/JsonApi/RouteMap.php` URIs are mapped to responsible code. All URIs always start with `<STUDIP-URI>/jsonapi.php/v1/`. As soon as a request is sent to such URIs, the corresponding code, the route handler, is retrieved and called using the `RouteMap`.

Routes may require users to be logged in: In this case, the routes are defined in the `RouteMap#authenticatedRoutes` method. If no user login is required, the routes are defined in `RouteMap#unauthenticatedRoutes`.

Since `Slim` is used for routing, it is worth taking a look at the corresponding [Docs](https://www.slimframework.com/docs/v3/objects/router.html)

Excerpt from the file `RouteMap.php`:

```php
namespace JsonApi;

class RouteMap
{
    public function authenticatedRoutes()
    {
        //
        $this->app->get('/blubber-comments', Routes\Blubber\CommentsIndex::class);
        $this->app->get('/blubber-comments/{id}', Routes\Blubber\CommentsShow::class);
        $this->app->patch('/blubber-comments/{id}', Routes\Blubber\CommentsUpdate::class);
        $this->app->delete('/blubber-comments/{id}', Routes\Blubber\CommentsDelete::class);
        //
    }
    //
}
```


### Route handlers

Route handlers are subclasses of `JsonApi\JsonApiController` and implement the magic method `__invoke`. Route handlers behave in a JSON:API-compliant manner and use the inherited methods in particular:

* `getContentResponse`
* `getPaginatedContentResponse`
* `getCreatedResponse`
* `getCodeResponse`

The most important methods are `getContentResponse` and `getPaginatedContentResponse`, as they are used to return Stud.IP objects. The difference is already clear from the name. The paginated variant only works with lists of Stud.IP objects.

Both methods are used if Stud.IP data structures are to be read in the JSON:API, i.e. if a `GET` request has been sent to the Stud.IP JSON:API.

Simply pass the Stud.IP object to this method and you're done:

```php
    // in the RouteMap
    $this->app->get('/blubber-threads/{id}', Routes\Blubber\ThreadsShow::class);
```

```php
class ThreadsShow extends JsonApiController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        if (!$resource = \BlubberThread::find($args['id'])) {
            throw new RecordNotFoundException();
        }

        if (!Authority::canShowBlubberThread($this->getUser($request), $resource)) {
            throw new AuthorizationFailedException();
        }

        return $this->getContentResponse($resource);
    }
}
```

Here you can see the general call of the method `getContentResponse`.

* The route handler `ThreadsShow` is a subclass of `JsonApi\JsonApiController`.
* The route handler implements the magic method `__invoke`.
* Here comes the typical rule of three: read, authorize, return.
* In order to be able to read the `BlubberThread`, we take the parameter `id` from the URI. This was defined in the RouteMap.
* Now we check whether the logged-in user is allowed to read this data. We use the method `JsonApiController#getUser` for this.
* Finally, we pass the read `BlubberThread` to `getContentResponse` and the result is then also the result of the request.

### Schema mapping

How can the Stud.IP-JSON:API know how to turn a Stud.IP-`BlubberThread` object into specification-compliant JSON?

First of all, the schema mapping is important. This can be found in the file `/lib/classes/JsonApi/SchemaMap.php`. And this is where Stud.IP classes are mapped to schema classes:

```php
\BlubberThread::class => \JsonApi\Schemas\BlubberThread::class,
```

If, for example, a `BlubberThread` object is delivered using `getContentResponse`, the schema class `JsonApi\Schemas\BlubberThread` is used for the conversion.

### Schema classes

Schema classes turn a Stud.IP object into a JSON:API-compliant representation. For example, the `User` schema class turns this object into:

```php
$me = \User::findCurrent();
```

this representation, which is JSON:API-compliant:

```javascript
{
  "data": {
    "type": "users",
    "id": "205f3efb7997a0fc9755da2b535038da",
    "attributes": {
      "username": "test_dozent",
      "formatted-name": "Testaccount lecturer",
      "family-name": "Lecturer",
      "given-name": "Testaccount",
      "name-prefix": "",
      "name-suffix": "",
      "permission": "lecturer",
      "email": "dozent@studip.de",
      "phone": null,
      "homepage": null,
      "address": null
    },
    "relationships": {
      "activitystream": {
        "links": {
          "related": "jsonapi.php/v1/users/205f3efb7997a0fc9755da2b535038da/activitystream"
        }
      },
    }
}
```

If you want to understand the schema classes in detail, you should first read the corresponding part of the JSON:API specification: https://jsonapi.org/format/#document-structure

The schema classes offer all the options presented in the specification. However, the ID, type, attributes and relationships of a `Resource Object` are certainly the most important.

First an example: This schema class describes the conversion of Stud.IP's `Semester` objects into a specification-compliant JSON form.

```php
<?php

namespace JsonApi\Schemas;

class Semester extends SchemaProvider
{
    const TYPE = 'semesters';

    // [A]: Type
    protected $resourceType = self::TYPE;

    // [B]: ID
    public function getId($semester)
    {
        return $semester->id;
    }

    // [C]: Attributes
    public function getAttributes($semester)
    {
        return [
            'title' => (string) $semester->name,
            'description' => (string) $semester->description,
            'start' => date('c', $semester->start),
            'end' => date('c', $semester->end),
        ];
    }
}
```

#### ID and type
According to [Specification](https://jsonapi.org/format/#document-resource-object-identification), every `Resource Object` requires an ID and a type. In the example above, the type is defined at position A and the ID at position B. The following applies to all Stud.IP JSON:API types:

* The type is always in the plural.
* The type must be written in `kebap-case`.

The ID is defined via the overridden method `getId` and must return a string.

#### Attributes
The [Specification](https://jsonapi.org/format/#document-resource-object-attributes) is very clear about the attributes of `Resource Objects`. In the Stud.IP JSON:API they are defined by overriding the `getAttributes` method.

* The return value must be a PHP array.
* Keys and values must be UTF-8 encoded.
* Allowed characters for keys are defined in the [Specification](https://jsonapi.org/format/#document-member-names).
* The following keys cannot be selected: `type`, `id`, `data`.
* The foreign keys `<something>_id` frequently used directly in Stud.IP-SORM should generally not be attributes but relations.
* Any human-readable versions of attributes must have the addition `-readable` to the attribute name.

#### Relationships
The [Relationships](https://jsonapi.org/format/#document-resource-object-relationships) are a very powerful feature of the JSON:API specification. It is highly recommended that you read the relevant chapters to familiarize yourself with the various terms.

Finally, the `getRelationships` method, which returns an array of relationships, must also be overwritten here. Essential for a relationship are certainly:

* The relationship wants to provide data: `data`
* The relationship wants to provide a link to the relation itself: `links[self]`
* The relationship wants to provide a link to the linked object: `links[related]`

A relationship with these three characteristics at the same time looks like this in the example:
```php
<?php

namespace JsonApi\Schemas;

class BlubberThread extends SchemaProvider
{
    //
    public function getRelationships($resource, $isPrimary, array $includeList)
    {
        $relationships = [];

        //

        $course = \Course::find($resource['context_id']);
        $relationships[self::REL_CONTEXT] = [
            self::SHOW_SELF => true,
            self::LINKS => [
                Link::RELATED => new Link('/courses/'.$course->id)
            ],
            self::DATA => $course
        ];

        //

        return $relationships;
    }
}
```

The `context` relationship of a BlubberThread wants to:
* provide data and stores it under the key `self::DATA` in the relationship.
* provide a link to the relationship itself and therefore adds: `self::SHOW_SELF => true`
* provide a link to the linked object and therefore sets a corresponding entry in the `self::LINKS` array.

#### What about plugins?

Plugins may also register routes and schemas. To do this
a plugin only needs to implement the plugin interface `JsonApi\Contracts\JsonApiPlugin`.

An example:

```php
<?php

use JsonApi\Contracts\JsonApiPlugin;

class MyPlugin extends StudIPPlugin implements StandardPlugin, JsonApiPlugin
{
    //

    public function registerAuthenticatedRoutes(\Slim\App $app)
    {
        $app->get('/whiteboards', WhiteboardsIndex::class);
        $app->get('/whiteboards/{id}', WhiteboardsShow::class);
    }

    public function registerUnauthenticatedRoutes(\Slim\App $app)
    {
        $app->get('/whiteboard-colors', WhiteboardColorsIndex::class);
    }

    public function registerSchema()
    {
        return [
           Whiteboard::class => WhiteboardSchema::class,
           WhiteboardColor::class => WhiteboardColorSchema::class
        ];
    }
}
```

The routes and schemes specified therein are then implemented as described above.

An example of integration can be found here:

https://gitlab.studip.de/marcus/studip-plugin-jsonapi-example
