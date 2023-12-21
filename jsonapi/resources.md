---
title: Rooms/Buildings
---


This category contains everything that has to do with resource management.


## Schema "resources-objects"

All resource objects of resource management are represented with this schema.

### Attributes

Attribute | Description
-------- | ------------
name | the name of the resource
description | the description of the resource
is-room | Is this resource a room?
multiple-assign | Can this resource be assigned multiple times at the same time?
requestable | Can a room request be made for this resource?
lockable | Is this resource affected by a global locking period?
mkdate | Creation date
chdate | Modification date

### Relations

Relation | Description
-------- | ------------
category | Category of the resource


## Schema "resources-categories"

This schema describes resource types.

### Attributes

Attribute | Description
-------- | ------------
name | the name of the type
description | the description of the type
system |
is-room | Is this species a room?
icon | number of the icon to be used

### relations

no relations


## Schema "resources-assign-events"

All resource assignments are mapped with this schema.

### Attributes

Attribute | Description
-------- | ------------
repeat-mode | the interval and frequency at which this resource allocation is executed
start | the date of the start of the allocation
end | the date of the end of the allocation
owner-free-text | Free text specification for the owner of this allocation

### Relations

relation | description
-------- | ------------
owner | (optional) the owner of the allocation
resources-object | the assigned resource


## All resources
```shell
curl --request GET \
    --url https://example.com/resources-objects \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route returns all resource objects.

### HTTP Request

`GET /resources-objects`

### URL parameters

no URL parameters

### Authorization

Every logged-in user can see the list of resource objects.


## All assignments of a resource
```shell
curl --request GET \
    --url https://example.com/resources-objects/<ID>/assignments \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route can be used to query the assignments of a resource.

### HTTP Request

`GET /resources-objects/{id}/assignments`

Parameter | Description
--------- | ------------
id | the ID of the resource object

### URL parameter

Parameter | Default | Description
--------- | ------- | ------------
filter[start] | (today) | optional; time (in seconds since 1.1.1970), from which the occupancy should be displayed
filter[end] | (tomorrow) | optional; time (in seconds since 1.1.1970) up to which the assignments are to be displayed

The parameters "filter[start]" and "filter[end]" must be specified as integers (seconds since 1.1.1970 00:00:00 UTC). If they are not specified, today's occupancy is displayed.

### Authorization

Every logged-in user can see the list of resource allocations.
