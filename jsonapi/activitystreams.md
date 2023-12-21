---
title: Activity Streams
---

Stud.IP version 3.5 introduces a new API for creating, displaying and filtering
and filtering of context-relevant activities was introduced. This API
can be used to give users a quick overview of the information/activities
of the information/activities relevant to them.

## Schema "activities"

Activity streams contain objects of the type "activities". These give
contain a textual description of the activity, a date, a
(slightly) more detailed description and the type of activity (the
verb). Activities refer to an actor (usually a user), a context in which the
user), a context in which they take place and an object to which they refer.
to which they refer.

### Attributes

Attribute | Description
-------- | ------------
title | brief description of the activity: "Who is doing what with whom/what where?"
mkdate | date of the activity
content | somewhat more detailed description of the activity
verb | type of activity
activity-type | type of activity

The verbs used are standardized. The value range includes:

<code>answered, attempted, attended, completed, created, deleted,
edited, experienced, failed, imported, interacted, passed, shared,
sent, voided</code>

### Relationships

The relations cannot be changed and can only be read.

 Relation | Description
-------- | ------------
actor | If the actor of the activity is a user, this relation is used to refer to them.
context | the context in which the activity takes place; can be one of the following: event, institution, user or system.
object | the object with which the activity takes place; if possible, a route in the JSON:API is referenced here

## Read all activities

```shell
curl --request GET \
    --url https://example.com/jsonapi.php/v1/users/<USER-ID>/activitystream \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

```javascript
fetch('https://example.com/jsonapi.php/v1/users/<USER-ID>/activitystream', {
    method: 'GET',
    mode: 'cors',
    headers: new Headers({
        'Authorization': `Basic ${btoa('test_author:testing')}`
    })
}).then(response => console.log(response))
```


This route can be used to read out the activities that are visible to a user.
visible to a user. The activity stream is output paginated.
By default, only activities **from the last 6 months**
are displayed. This restriction can be changed using the URL parameter
'filter' to change this restriction.

### HTTP request

`GET /users/{id}/activitystream`

### URL parameter

Parameter | Description
--------- | -------
filter | filter option for the activities to be displayed (time and type)
include | enables the actor, context and object to be included in the JSON:API response
page | setting options [for pagination](#pagination)

#### URL parameter 'filter'

```shell
curl --request GET \
     --url 'https://example.com/jsonapi.php/v1/users/<USER-ID>/activitystream?filter\[start\]=1263078000&filter\[end\]=1409695200&filter[activity-type]=documents' \
     --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This URL parameter can be used to filter by type and date of the activities
can be filtered. The following filters are possible:

Filter | Description
--------------------- | ------------
filter[start] | time restriction: Start of the query interval
filter[end] | time restriction: end of the query interval
filter[activity-type] | only activities of this type/these types are returned

The query interval can be changed using the 'start' and 'end' parameters.
can be changed. By default, all activities from the last 6
months up to the current point in time are returned. With 'start' and
'end', these interval limits can be set as required. For these
only integer values can be specified for these two parameters, which
the number of seconds since 01.01.1970 up to the desired point in time ('unix
time ('unix epoch time').

The 'activity-type' parameter restricts the activities by type.
Possible values are:

`activity`, `documents`, `forum`, `literature`, `message`, `news`, `participants`, `schedule`, `wiki`

To filter by several activity types, several of these types can be used
types can be used, separated by commas.

#### URL parameter 'include'

```shell
curl --request GET \
     --url 'https://example.com/jsonapi.php/v1/users/<USER-ID>/activitystream?include=actor,context'\
     --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

Values | Description
---------- | ------------
actor | includes the actors of the delivered activities
context | includes the contexts of the delivered activities
object | includes the objects of the delivered activities

The 'include' parameter is used in accordance with the JSON:API specification.
is used. Multiple values can also be specified separated by commas.

### Meta information

So that it is clear which filters applied to the query,
this information is returned as a top-level 'meta' object.

### Authorization

With this route, only the user himself or root user can see
can see the activities that would be visible to a user.
