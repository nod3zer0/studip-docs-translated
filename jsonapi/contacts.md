---
title: Contacts
---


Users can remember other users as contacts in Stud.IP. For this
no new resource type is necessary.

## All contacts

```shell
curl --request GET \
    --url https://example.com/users/<ID>/contacts \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

With this route all contacts of a user can be read.

### HTTP Request

`GET /users/{id}/contacts`

Parameter | Description
--------- | ------------
id | the ID of the user

### URL parameters

no URL parameters

### Authorization

Each user can see their own contacts.


## All contact IDs of a user

```shell
curl --request GET \
    --url https://example.com/users/<ID>/relationships/contacts \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route can be used to read all IDs of a user's contacts.

(see http://jsonapi.org/format/#fetching-relationships)

### HTTP Request

`GET /users/{id}/relationships/contacts`

Parameter | Description
--------- | ------------
id | the ID of the user

### URL parameters

no URL parameters

### Authorization

Each user can see their own contacts.


## Set contacts of a user

```shell
curl --request PATCH \
    --url https://example.com/users/<ID>/relationships/contacts \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --header "Content-Type: application/vnd.api+json" \
    --data '{"data": [ \
        {"type": "users", "id":"<id1>"}, \
        {"type": "users", "id":"<id2>"}, \
        {"type": "users", "id":"<id3>"} \
        ]}'
```

With this route you can set all contacts of a user.

(see http://jsonapi.org/format/#crud-updating-to-many-relationships)


### HTTP Request

`PATCH /users/{id}/relationships/contacts`

Parameter | Description
--------- | ------------
id | the ID of the user

### URL parameters

no URL parameters

### Authorization

Each user can set their own contacts.


## Add contacts of a user

```shell
curl --request POST \
    --url https://example.com/users/<ID>/relationships/contacts \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --header "Content-Type: application/vnd.api+json" \
    --data '{"data": [ \
        {"type": "users", "id":"<id4>"} \
        ]}'
```

This route can be used to add a user's contacts.

(see http://jsonapi.org/format/#crud-updating-to-many-relationships)


### HTTP Request

`POST /users/{id}/relationships/contacts`

Parameter | Description
--------- | ------------
id | the ID of the user

### URL parameters

no URL parameters

### Authorization

Each user can set their own contacts.


## Delete a user's contacts

```shell
curl --request DELETE \
    --url https://example.com/users/<ID>/relationships/contacts \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --header "Content-Type: application/vnd.api+json" \
    --data '{"data": [ \
        {"type": "users", "id":"<id1>"}, \
        {"type": "users", "id":"<id4>"} \
        ]}'
```

This route can be used to delete a user's contacts.

(see http://jsonapi.org/format/#crud-updating-to-many-relationships)


### HTTP Request

`DELETE /users/{id}/relationships/contacts`

Parameter | Description
--------- | ------------
id | the ID of the user

### URL parameters

no URL parameters

### Authorization

Each user can delete their own contacts.
