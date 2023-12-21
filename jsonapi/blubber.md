---
title: Blubber
---

:::info
Blubber allows you to chat with other Stud.IP participants within courses.
We differentiate between public, private and event-related Blubber.
:::

## Schema 'blubber-postings'
The content is saved as plain text and html. Meta data provides information about the time and
the topic of a message.

### Attributes

Attribute | Description
--------------- | ------------
context-type | the type of context; course ("course"), public ("global") or user ("user")
content | the text of the Blubber contribution; may contain Stud.IP markup
content-html | the text of the Blubber contribution; formatted as HTML
mkdate | creation date
chdate | date of the last change
discussion-time | date of the last activity
tags | a list of tags

### relations

 relation | description
--------- | ------------
author | author of the message
comments | subordinate blubber
context | Who the blubber is displayed to: users, courses, public
mentions | Topic of a Blubber entry
parent | Parent blubber entry
resharers |


## All entries

```shell
curl --request GET \
    --url https://example.com/blubber-postings \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

```javascript
fetch('https://example.com/blubber-postings', {
    method: 'GET',
    mode: 'cors',
    headers: new Headers({
        'Authorization': `Basic ${btoa('test_author:testing')}`
    })
}).then(response => console.log(response))
```

All Blubber contributions that could be seen in Stud.IP are displayed.

### HTTP Request

`GET /blubber-postings`

### URL parameters

Parameter | Description
--------- | -------
filter | filter option for the blubber postings to be displayed
include | dependent resources that are also returned ([JSON:API specification](http://jsonapi.org/format/#fetching-includes))
page | setting options [for pagination](#pagination)

#### URL parameter 'filter'

This URL parameter can be used to filter by type and date of the activities.
can be filtered. The following filters are possible:

Example URL: "https://example.com/blubber-postings?filter[user]=205f3efb7997a0fc9755da2b535038da"

Filter | Description
--------------- | ------------
filter[course] | Filters blubber entries for an event
filter[user] | Filter Blubber entries for a user

#### URL parameter 'include'

Adds the following attributes to the output.

value | description
--------- | ------------
author | The author of a blubber
comments | Attached blubber
context | Who the post is displayed to (users, courses, public)
mentions |
resharers |


## Read out post
Read a specific Blubber entry.
### HTTP request

`GET /blubber-postings/{id}`

### Parameter

Parameter | Description
--------- | -------
id | ID of the blubber post

### Authorization

This route can be used by all users.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Create post

   ```shell
   curl --request POST \
       --url https://example.com/blubber-postings \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
       --data \
       '{"data":{"type": "blubber-postings", "attributes":{"context-type": "course", "content": "A new blubber post"}, "relationships":{"context":{"data":{"type": "courses", "id":"<CID>"}}}}}'
   ```

This route can be used to create a bubble post. This can be
be a public or private post, but blubbers can also be created in
events can also be created via this route.

### HTTP Request

   POST /blubber-postings

### HTTP Request Body

In the request body, the new post must be specified as a ``resource object`` of type
"blubber-postings" type.

The attributes "content" and "context-type" are required.

Depending on the value of the "context-type" attribute, a "context" relation must also be specified.
"context" relation must also be specified.

If this attribute has the value "course", the "context" relation must be
a course must be specified as ``resource identified``.

### Parameters

   No parameters are required for this request.

### Authorization

This route can be used by all users.


## Edit post

  Updates a Blubber post.

### HTTP Request

   `PATCH /blubber-postings/{id}`

### Parameter

   Parameter | Description
   --------- | -------
   id | ID of the blubber post

### Authorization

The sender of the request must be the owner of the Blubber post or root.

   ```shell
   curl --request PATCH \
       --url https://example.com/blubber-postings/<blubber-id> \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "root@studip:testing" | base64`" \
       --data
       '{"data":{"type": "blubber-postings", "attributes":{"context-type": "course", "content": "A modified blubber post"}, "relationships":{"context":{"data":{"type": "courses", "id": "a07535cf2f8a72df33c12ddfa4b53dde"}}}}}'
   ```

## Delete entry

  Deletes a blubber entry.

### HTTP Request

   `DELETE /blubber-postings/{id}`

### Parameter

  Parameter | Description
  --------- | -------
  id | ID of the blubber post

### Authorization

The sender of the request must be the owner of the Blubber post or root.

   ```shell
   curl --request DELETE \
       --url https://example.com/blubber-postings/<blubber-id> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
   ```

## Relation 'author'

Returns the author of a Blubber post.

### HTTP request

   `GET /blubber-postings/{id}/relationships/author`

### Parameter

  Parameter | Description
  --------- | -------
  id | ID of the blubber post


### Authorization

This route can be used by all users.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/relationships/author \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```


## Comments of a Blubber post

Returns all comments of a Blubber post.

### HTTP Request

   `GET /blubber-postings/{id}/comments`

### Parameter

  Parameter | Description
  --------- | -------
  id | ID of the blubber post

### Authorization

This route can be used by all users.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/comments \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Comment post

Creates a comment on a Blubber post.

### HTTP Request

   `POST /blubber-postings/{id}/comments`

### Parameter

 Parameter | Description
 --------- | -------
 id | ID of the blubber post

### Authorization

This route can be used by all users.

   ```shell
   curl --request POST \
       --url https://example.com/blubber-postings/<posting-id>/comments \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "root@studip:testing" | base64`" \
       --data
       '{"data": {"type": "blubber-postings", "attributes": {"content": "A new blubber comment"}}}'
   ```

## Relation 'comments'

### HTTP Request
   `GET /blubber-postings/{id}/relationships/comments`

### Parameter

 Parameter | Description
 --------- | -------
 id | ID of the blubber post

### Authorization

This route can be used by all users.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/relationships/comments \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Relation 'context'

Returns the scope (visibility) of a Blubber post.

### HTTP Request

   `GET /blubber-postings/{id}/relationships/context`

### Parameter

    Parameter | Description
    --------- | -------
    id | ID of the blubber post

### Authorization

This route can be used by all users.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/relationships/context \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Mentions of a contribution
Specifies whether and in which contributions there is a reference to this contribution.

### HTTP request

   `GET /blubber-postings/{id}/mentions`

### Parameter

    Parameter | Description
    --------- | -------
    id | ID of the blubber post

### Authorization

This route can be used by all users.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/mentions \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Relation 'mentions'
Returns the reference of the posts in which this post is mentioned.

### HTTP Request

   `GET /blubber-postings/{id}/relationships/mentions`

### Parameter

    Parameter | Description
    --------- | -------
    id | ID of the blubber post

### Authorization

This route can be used by all users.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/relationships/mentions \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Relation 'resharers'

Returns the reference of users who shared this post.

### HTTP Request

   `GET /blubber-postings/{id}/relationships/resharers`

### Parameter

    Parameter | Description
    --------- | -------
    id | ID of the blubber post

### Authorization

This route can be used by all users.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/relationships/resharers \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Read blubber stream

Returns a sequence of blubber entries.

   `GET /blubber-streams/{id}`

### Parameter

  Parameter | Description
  --------- | -------
  id | ID of the blubber stream

### Authorization

This route can be used by all users.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/blubber-streams/<stream-id> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```
