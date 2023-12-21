---
title: Stud.IP JSON:API
slug: /jsonapi/
sidebar_label: Introduction
---

Welcome to the documentation of the Stud.IP JSON:API! With this API
many data of a Stud.IP installation can be accessed.
The API conforms to the <a rel="noopener noreferrer" href="http://jsonapi.org/">JSON:API specification</a>.

# Authentication

Stud.IP JSON:API uses three different methods to authenticate users
authenticate users:

* HTTP Basic access authentication
* Stud.IP session cookies
* OAuth2](../functions/oauth2)

For HTTP Basic Access Authentication you need the access data that is also used for a
used for a "normal" login.

# Pagination

Many Stud.IP JSON:API routes deliver their results page by page.
The page to be viewed and the number of entries of pages
can be influenced by URL parameters.

Routes that deliver their results page by page contain
corresponding `meta` and `links` in their responses:

```json title="GET jsonapi.php/v1/courses"
{
  "meta": {
    "page": {
      "offset": 0,
      "limit": 30,
      "total": 347
    }
  },
  "links": {
    "first": "/studip/jsonapi.php/v1/courses?page%5Boffset%5D=0&page%5Blimit%5D=30",
    "last": "/studip/jsonapi.php/v1/courses?page%5Boffset%5D=300&page%5Blimit%5D=30",
    "next": "/studip/jsonapi.php/v1/courses?page%5Boffset%5D=30&page%5Blimit%5D=30"
  },
  "data": [
    "[...]"
  ]
}
```

In this case, all events were queried, but the route
only returns the first page with 30 of the 347 entries.
Below `left` the URLs of the first, last and next page are referenced.
next page. In each case, these URLs contain the
URL parameters `page[offset]` and `page[limit]`.

The entirety of all results is distributed over several pages and
only a section is obtained in each case. This section can be influenced by
be influenced by these URL parameters

Page parameters | Description
-------------- | ------------
page[offset] | the pagination offset
page[limit] | the pagination limit

The `page` parameter is used in accordance with the JSON:API specification.

```json title="GET jsonapi.php/v1/courses?page[offset]=7&page[limit]=17"
{
  "meta": {
    "page": {
      "offset": 7,
      "limit": 17,
      "total": 347
    }
  },
  "links": {
    "first": "/studip/jsonapi.php/v1/courses?page%5Boffset%5D=0&page%5Blimit%5D=17",
    "last": "/studip/jsonapi.php/v1/courses?page%5Boffset%5D=323&page%5Blimit%5D=17",
    "next": "/studip/jsonapi.php/v1/courses?page%5Boffset%5D=24&page%5Blimit%5D=17"
  },
  "data": [
    "[...]"
  ]
}
```
