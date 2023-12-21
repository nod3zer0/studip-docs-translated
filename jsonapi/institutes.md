---
title: Facilities
---


The facilities of the Stud.IP installation can be queried using the following routes
can be queried with the following routes.

## Schema 'institutes'

All institutions are mapped in Stud.IP with this schema. The `id`
corresponds to the `Institut_id` used in Stud.IP. The type is `institutes`.

### Attributes

Attribute | Description
-------- | ------------
name | the name of the institution
city | the city in which the institution is located
street | the address (street) of the institution
phone | the telephone number of the institution
fax | the fax number of the institution
url | the URL of the institution's website
mkdate | the creation date of the institution in Stud.IP
chdate | the last modification date of the institution data in Stud.IP

### relations

no relations


## Schema 'institute-memberships'

Membership of an institution is represented in Stud.IP with this
schema in Stud.IP.

### Attributes

Attribute | Description
-------- | ------------
permission | the role of the user in the institution
office-hours | the office hours of the user with regard to the facility
location | the room/location of the user with regard to the facility
phone | the user's telephone number in relation to the institution
fax | the user's fax number for the facility

### Relations

Relation | Description
--------- | ------------
institute | the institution of this membership
user | the user of this membership

## All institutions

This endpoint provides all institutions in Stud.IP that the
JSON:API user with his ``credentials`` can also see in Stud.IP itself.
itself. The output is paginated and can be scrolled through by specifying
offset and limit.

### HTTP Request

   GET /institutes

   ```shell
   curl --request GET \
       --url https://example.com/institutes \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
   ```

### URL parameters

Parameter | Default | Description
--------- | ------- | ------------
page[offset] | 0 | the offset
page[limit] | 30 | the limit

### Authorization

Any user may use this route.


## A setup

   ```shell
   curl --request GET \
       --url https://example.com/institutes/<INSTITUTE-ID> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
   ```

A specific institution can simply be read out via this route.

### HTTP Request

   `GET /institutes/{id}`


       Parameter | Description
      ---------- | ------------
      id | The ID of the institution

### URL parameters

no URL parameters

### Authorization

Every user may use this route.


## Memberships in an institution

   ```shell
   curl --request GET \
       --url https://example.com/institutes/<institute-id>/memberships \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

Returns all memberships with the respective user data.

### HTTP Request
   `GET /institutes/{id}/memberships`

### Parameter

Parameter | Description
--------- | -------
id | ID of the institution

### URL parameter

Parameter | Default | Description
--------- | ------- | ------------
filter[permission] | - | Role of the user in the institution

### Authorization

Every user may use this route.


## A membership

   ```shell
   curl --request GET \
       --url https://example.com/institute-memberships/<ID> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
   ```

This route can be used to read a membership in an institution.

### HTTP Request

`GET /institute-memberships/{id}`

       Parameter | Description
      ---------- | ------------
      id | The ID of the membership

### URL parameters

no URL parameters

### Authorization

Any user may use this route.
