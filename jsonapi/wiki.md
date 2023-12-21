---
title: Wiki
---

:::info
Wikis offer lecturers and students the opportunity to
to create joint documents. Wikis can be created and maintained for various topics in the
courses, wikis can be created and maintained. A
version control makes it possible to view changes between the
document versions.
:::

## Schema "wiki-pages"
In addition to the content and name, each wiki page contains metadata on the current version.
### Attributes

Attribute | Description
-------- | ------------
keyword | The name of the wiki page
content | The content of the wiki page
chdate | The date of the last change
version | The current version number

### Relations

 Relation | Description
-------- | ------------
author | The author of the wiki page
range | The range of a wiki page is the corresponding course

## Wiki pages of a course
   `GET /courses/{id}/wiki-pages`

### Parameter

   Parameter | Description
  ---------- | ------------
  id | The ID of the course

  ```shell
  curl --request GET \
      --url https://example.com/courses/<COURSE-ID>/wiki-pages \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
  ```
### Authorization
      The user should be a member of the corresponding course.

> The request returns JSON similar to this:

```json
{
  "meta": {
    "page": {
      "offset": 0,
      "limit": 30,
      "total": 2
    }
  },
  "links": {
    "first": "/?page[offset]=0&page[limit]=30",
    "last": "/?page[offset]=0&page[limit]=30"
  },
  "data": [
    {
      "type": "wiki-pages",
      "id": "a07535cf2f8a72df33c12ddfa4b53dde_ulyq",
      "attributes": {
        "keyword": "ulyq",
        "content": "There are in this team at the moment, oh, some players forget their pro what they are. I don't read a lot of newspapers, but I've seen a lot of situations. First of all, we didn't play offense.",
        "chdate": "2019-04-23T12:10:26+02:00",
        "version": 1
      },
      "relationships": {
        "author": {
          "data": {
            "type": "users",
            "id": "e7a0a84b161f3e8c09b4a0a2e8a58147"
          },
          "links": {
            "related": "jsonapi.php/v1/users/e7a0a84b161f3e8c09b4a0a2e8a58147"
          }
        },
        "range": {
          "data": {
            "type": "courses",
            "id": "a07535cf2f8a72df33c12ddfa4b53dde"
          },
          "links": {
            "related": "jsonapi.php/v1/courses/a07535cf2f8a72df33c12ddfa4b53dde"
          }
        }
      }
    },
    {
      "type": "wiki-pages",
      "id": "a07535cf2f8a72df33c12ddfa4b53dde_yxilo",
      "attributes": {
        "keyword": "yxilo",
        "content": "There are in this team at the moment, oh, some players forget their pro what they are. I don't read a lot of newspapers, but I've seen a lot of situations. First of all, we didn't play offense.",
        "chdate": "2019-04-23T12:10:26+02:00",
        "version": 1
      },
      "relationships": {
        "author": {
          "data": {
            "type": "users",
            "id": "e7a0a84b161f3e8c09b4a0a2e8a58147"
          },
          "links": {
            "related": "jsonapi.php/v1/users/e7a0a84b161f3e8c09b4a0a2e8a58147"
          }
        },
        "range": {
          "data": {
            "type": "courses",
            "id": "a07535cf2f8a72df33c12ddfa4b53dde"
          },
          "links": {
            "related": "jsonapi.php/v1/courses/a07535cf2f8a72df33c12ddfa4b53dde"
          }
        }
      }
    },
    "[...]"
  ]
}
```

## Wiki page

Returns a wiki page.

   `GET /wiki-pages/{id}`

   Parameter | Description
  ---------- | ------------
  id | The ID of the wiki page


  ```shell
  curl --request GET \
      --url https://example.com/wiki-pages/<ID> \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
  ```
### Authorization
        The user should be a member of the corresponding course.
  > The request returns JSON similar to this:

```json
{
  "data": {
    "type": "wiki",
    "id": "48101a5a47c34f80999cc01266b32536_tastyTest",
    "attributes": {
      "keyword": "tastyTest",
      "content": "This is dsdsadsad",
      "chdate": "2018-06-05T14:12:29+02:00",
      "version": 1
    },
    "relationships": {
      "author": {
        "data": {
          "type": "users",
          "id": "76ed43ef286fb55cf9e41beadb484a9f"
        },
        "links": {
          "related": "/stud35/plugins.php/argonautsplugin/users/76ed43ef286fb55cf9e41beadb484a9f"
        }
      },
      "range": {
        "data": {
          "type": "courses",
          "id": "48101a5a47c34f80999cc01266b32536"
        },
        "links": {
          "related": "/stud35/plugins.php/argonautsplugin/courses/48101a5a47c34f80999cc01266b32536"
        }
      }
    },
    "links": {
      "self": "/stud35/plugins.php/argonautsplugin/courses/48101a5a47c34f80999cc01266b32536/wiki/tastyTest"
    }
  }
}
```


## Create wiki page

Creates a wiki page.

   `POST /courses/{id}/wiki-pages`

   Parameters | Description
  ---------- | ------------
  id | The ID of the course

  ```shell
  curl --request POST \
      --url https://example.com/courses/<COURSE-ID>/wiki \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
      '{"data": {"type": "wiki-pages", "attributes": {"keyword": "testing", "content": "wiki created"}}}'
  ```
### Authorization
          The user should be a member of the corresponding course.


## Change wiki page

Updates a wiki page.

   `PATCH /wiki-pages/{id}`

   Parameter | Description
  ---------- | ------------
  id | The ID of the wiki page

```
curl --request PATCH \
      --url https://example.com/wiki-pages/<ID> \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
      '{"data": {"type": "wiki-pages", "attributes": {"content": "wiki changed"}}}'
```

### Authorization
The user should be a member of the corresponding course.
