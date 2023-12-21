---
title: Stud.IP-System
---

All routes that have to do with the Stud.IP system itself are gathered in this category.

## Schemas

### Schema "studip-properties"

Selected configuration settings and features of the Stud.IP installation are mapped with this schema.

### ID

The ID of the setting is not an MD5 hash but a fixed abbreviation for a setting/feature.

### Attributes

Attribute | Description
-------- | ------------
description | a description of the setting/feature
value | the value of the setting/feature

### relations

no relations available

## Read all Stud.IP properties
```shell
curl --request GET \
    --url https://example.com/studip/properties \
    --header "Authorization: Basic `echo -ne "test_autor:testing" | base64`"
```

Returns all Stud.IP settings/characteristics

### HTTP Request

`GET /studip/properties`


### URL parameters

no URL parameters

### Authorization

Every logged in user may access this route.
