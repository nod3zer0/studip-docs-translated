---
title: Discovery
---


Even though JSON:APIs are inherently a lot more discoverable than
conventional REST APIs, there is no harm in offering a special route to
to display all available routes.

## Schemas


### Schema "slim-routes"

Resources of type "slim-routes" represent the active routes of the Stud.IP-JSON:API.

### Attributes

Attribute | Description
-------- | ------------
methods | a vector of HTTP verbs such as GET, POST, PATCH and DELETE
pattern | a URI pattern such as "/file-refs/{id}"

### relations

no relations available


## Show all routes
```shell
curl --request GET \
    --url https://example.com/discovery \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

With this route you get a list of all active routes of the Stud.IP-JSON:API.

### HTTP Request

`GET /discovery`

### URL parameters

no URL parameters

### Authorization

Any logged-in user may access this route.
