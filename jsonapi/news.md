---
title: Announcements (News)
---

:::info
  Announcements inform Stud.IP users about the latest events concerning
  teaching. In Stud.IP announcements can be created system-wide (global) or for a specific
  a specific group of users.
:::

## Schema "news"

Announcements consist of their content and some meta data. The duration of the
visibility of an announcement is determined by its attributes publication-start and
end attributes (see relations).

### Attributes

Attribute | Description
-------- | ------------
title | Name of a news item
content | Content of a news item
mkdate | Creation date of a news item
chdate | Date of the last change
publication-start | Start of visibility for the user group of a news item
publication-end | End of visibility for the user group of a news item
comments-allowed | Determination of whether comments are allowed (Boolean)

An example of how to create a news item using the schema follows in Create news.

### Relations

 Relation | Description
-------- | ------------
author | creator of a news item
ranges | global, institute, semester, course, users

The range of a news item indicates where it is published and therefore for whom
it is visible.

## Schema "comments"

Comments are attached to an announcement in Stud.IP if the creator of the news has given
of the news has given permission.

### Attributes

Attribute | Description
-------- | ------------
content | Content of a comment
mkdate | Creation date
chdate | Date of last change

### Relations

 Relation | Description
-------- | ------------
author | The creator of the comment
news | The commented news

## Create news
  It is possible to create a news item in various contexts. It can be
  be created globally as system-wide news, course-internal or user-related.

## Create a global news item

### Route
   `POST /news`
### Authorization
The creation of a global news item currently requires root rights.
It is being discussed whether admin rights are sufficient here.

   ```shell
   curl --request POST \
       --url https://example.com/news \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "root@studip:testing" | base64`" \
       --data
       '{"data": {"type": "news", "attributes": {"title": "New news", "comments-allowed": true, "publication-start": "2020-01-01T12:12:12+00:00", "publication-end": "2021-01-01T12:12:12+00:00", "content": "A new news item sees the light of day."}}}'
   ```

## Create a course news item

   `POST /courses/{id}/news`

   ```shell
   curl --request POST \
       --url https://example.com/courses/<COURSE-ID>/news \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "test_dozent:testing" | base64`" \
       --data
       '{"data": {"type": "news", "attributes": {"title": "New news", "comments-allowed": true, "publication-start": "2020-01-01T12:12:12+00:00", "publication-end": "2021-01-01T12:12:12+00:00", "content": "A new news item sees the light of day."}}}'
   ```
   Parameter | Description
  ---------- | ------------
  id | The ID of the course
### Authorization
The user must have at least instructor or admin rights within the course.
## Create a user news
   `POST /users/{id}/news`

   ```shell
   curl --request POST \
       --url https://example.com/users/<USER-ID>/news \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
       --data
       '{"data": {"type": "news", "attributes": {"title": "New news", "comments-allowed": true, "publication-start": "2020-01-01T12:12:12+00:00", "publication-end": "2021-01-01T12:12:12+00:00", "content": "A new news item sees the light of day."}}}'
   ```

   Parameter | Description
  ---------- | ------------
  id | The ID of the user
### Authorization
  The user must have at least user rights.

## Create a comment
   `POST /news/{id}/comments`

   ```shell
   curl --request POST \
       --url https://example.com/news/<NEWS-ID>/comments \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "test_dozent:testing" | base64`" \
       --data
       '{"data": {"type": "comments", "attributes": {"content": "A comment has been updated"}}}'
   ```
   ### Authorization
     The user must have at least user rights.

   Parameter | Description
  ---------- | ------------
  id | The ID of the news item
## Change a news item
   `PATCH /news/{id}`

   Parameter | Description
  ---------- | ------------
  id | The ID of the news item

  The data fields when updating a news item are optional.

  ```shell
  curl --request PATCH \
      --url https://example.com/news/<NEWS-ID> \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
      '{"data": {"type": "news", "attributes": {"title": "Changes", "comments-allowed": true, "publication-start": "2020-01-01T12:12:12+00:00", "publication-end": "2021-01-01T12:12:12+00:00", "content": "A news item has been changed."}}}'
  ```
### Authorization
    The user must be the owner of the news item or have the corresponding root rights.
    rights.
## View a news item
   `GET /news/{id}`

   Parameter | Description
  ---------- | ------------
  id | The ID of the news item
  ```shell
  curl --request GET \
      --url https://example.com/news/<NEWS-ID> \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
  ```
### Authorization
      The user must be the owner of the news or have the corresponding range rights
      have.
  > The request returns JSON similar to this:

```json
{
  "data": {
    "type": "news",
    "id": "6a8be7e4859e9c781ecc47a2c3498435",
    "attributes": {
      "title": "A testing title",
      "content": "Lorem ipsum dolor sit amet, consectetur adipisicing elit",
      "mkdate": "2019-04-23T12:10:26+02:00",
      "chdate": "2019-04-23T12:10:26+02:00",
      "publication-start": "2019-04-23T12:10:26+02:00",
      "publication-end": "2019-05-07T12:10:26+02:00",
      "comments-allowed": true
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
      "ranges": {
        "data": [

        ],
        "links": {
          "self": "jsonapi.php/v1/news/6a8be7e4859e9c781ecc47a2c3498435/relationships/ranges"
        }
      }
    },
    "links": {
      "self": "jsonapi.php/v1/news/6a8be7e4859e9c781ecc47a2c3498435"
    }
  }
}
```

## All course news
   `GET /courses/{id}/news`

   Parameters | Description
  ---------- | ------------
  id | The ID of the course

  ```shell
  curl --request GET \
      --url https://example.com/course/<COURSE-ID>/news \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
  ```
### Authorization
  The user must be at least a participant of the course.

  > The request returns JSON similar to this:

```json
{
  "meta": {
    "page": {
      "offset": 0,
      "limit": 30,
      "total": 4
    }
  },
  "links": {
    "first": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19/news?page%5Boffset%5D=0&page%5Blimit%5D=30",
    "last": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19/news?page%5Boffset%5D=0&page%5Blimit%5D=30"
  },
  "data": [
    {
      "type": "news",
      "id": "9dc34d7414e9d6c2789923649a64673e",
      "attributes": {
        "title": "Fakenews",
        "content": "This is fakenews232",
        "mkdate": "2018-06-20T10:40:43+02:00",
        "chdate": "2018-06-20T10:57:37+02:00",
        "publication-start": "2018-06-21T12:00:00+02:00",
        "publication-end": "2066-12-09T22:00:00+01:00",
        "comments-allowed": true
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
        "ranges": {
          "data": [
            {
              "type": "courses",
              "id": "1b7d3834e42c1569947e0eab7b63ed19"
            }
          ],
          "links": {
            "self": "/stud35/plugins.php/argonautsplugin/news/9dc34d7414e9d6c2789923649a64673e/relationships/ranges"
          }
        }
      },
      "links": {
        "self": "/stud35/plugins.php/argonautsplugin/news/9dc34d7414e9d6c2789923649a64673e"
      }
    },
    {
      "type": "news",
      "id": "0e8df7da383d7515c4dc081bfe889897",
      "attributes": {
        "title": "Fakenews",
        "content": "This is fakenews232",
        "mkdate": "2018-06-19T16:08:51+02:00",
        "chdate": "2018-06-20T09:50:02+02:00",
        "publication-start": "2018-06-19T16:08:51+02:00",
        "publication-end": "2066-12-05T15:08:51+01:00",
        "comments-allowed": true
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
        "ranges": {
          "data": [
            {
              "type": "courses",
              "id": "1b7d3834e42c1569947e0eab7b63ed19"
            }
          ],
          "links": {
            "self": "/stud35/plugins.php/argonautsplugin/news/0e8df7da383d7515c4dc081bfe889897/relationships/ranges"
          }
        }
      },
      "links": {
        "self": "/stud35/plugins.php/argonautsplugin/news/0e8df7da383d7515c4dc081bfe889897"
      }
    },
    {
      "type": "news",
      "id": "191ce64590a28b3e038b09e85ea53178",
      "attributes": {
        "title": "Fakenews",
        "content": "This is fakenews",
        "mkdate": "2018-05-08T11:42:11+02:00",
        "chdate": "2018-05-08T11:42:11+02:00",
        "publication-start": "2018-05-08T11:42:11+02:00",
        "publication-end": "2066-09-19T20:24:22+01:00",
        "comments-allowed": true
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
        "ranges": {
          "data": [
            {
              "type": "courses",
              "id": "1b7d3834e42c1569947e0eab7b63ed19"
            }
          ],
          "links": {
            "self": "/stud35/plugins.php/argonautsplugin/news/191ce64590a28b3e038b09e85ea53178/relationships/ranges"
          }
        }
      },
      "links": {
        "self": "/stud35/plugins.php/argonautsplugin/news/191ce64590a28b3e038b09e85ea53178"
      }
    },
    {
      "type": "news",
      "id": "a355b8d628d8656eb93dc527fe1209f3",
      "attributes": {
        "title": "Fakenews",
        "content": "This is fakenews",
        "mkdate": "2018-05-08T11:35:56+02:00",
        "chdate": "2018-05-08T11:35:56+02:00",
        "publication-start": "2018-05-08T11:35:55+02:00",
        "publication-end": "2066-09-19T20:11:50+01:00",
        "comments-allowed": true
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
        "ranges": {
          "data": [
            {
              "type": "courses",
              "id": "1b7d3834e42c1569947e0eab7b63ed19"
            }
          ],
          "links": {
            "self": "/stud35/plugins.php/argonautsplugin/news/a355b8d628d8656eb93dc527fe1209f3/relationships/ranges"
          }
        }
      },
      "links": {
        "self": "/stud35/plugins.php/argonautsplugin/news/a355b8d628d8656eb93dc527fe1209f3"
      }
    }
  ]
}
```


## All user news
   `GET /users/{id}/news`

   Parameter | Description
   ---------- | ------------
   id | The ID of the user

   ```shell
   curl --request GET \
       --url https://example.com/user/<USER-ID>/news \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
   ```
### Authorization
   The user must at least be logged in or have root rights.

   > The request returns JSON similar to this:

```json
{
  "data": {
    "type": "news",
    "id": "0e8df7da383d7515c4dc081bfe889897",
    "attributes": {
    "title": "New news",
    "content": "A new news item sees the light of day.",
    "mkdate": "2018-06-19T16:08:51+02:00",
    "chdate": "2018-08-15T14:22:38+02:00",
    "publication-start": "2018-06-19T16:08:51+02:00",
    "publication-end": "2066-12-05T15:08:51+01:00",
    "comments-allowed": true
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
      "ranges": {
        "data": [
        {
          "type": "users",
          "id": "1b7d3834e42c1569947e0eab7b63ed19"
        }
        ],
          "links": {
          "self": "/stud35/plugins.php/argonautsplugin/news/0e8df7da383d7515c4dc081bfe889897/relationships/ranges"
          }
      }
    },
    "links": {
      "self": "/stud35/plugins.php/argonautsplugin/news/0e8df7da383d7515c4dc081bfe889897"
    }
  }
}
```

## All news comments
   GET /news/{id}/comments

   Parameter | Description
   ---------- | ------------
   id | The ID of a news item

## All global news
   GET /studip/news

   ```shell
   curl --request GET \
       --url https://example.com/user/studip/news \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
   ```

### Authorization

    The user must at least be logged in or have root rights.

> The request returns JSON similar to this:

```json
{
  "data": {
    "type": "news",
    "id": "0e8df7da383d7515c4dc081bfe889897",
    "attributes": {
    "title": "Global News",
    "content": "A new news item sees the light of day.",
    "mkdate": "2018-06-19T16:08:51+02:00",
    "chdate": "2018-08-15T14:22:38+02:00",
    "publication-start": "2018-06-19T16:08:51+02:00",
    "publication-end": "2066-12-05T15:08:51+01:00",
    "comments-allowed": true
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
      "ranges": {
        "data": [
        {
          "type": "global",
          "id": "studip"
        }
        ],
          "links": {
          "self": "/stud35/plugins.php/argonautsplugin/news/0e8df7da383d7515c4dc081bfe889897/relationships/ranges"
          }
      }
    },
    "links": {
      "self": "/stud35/plugins.php/argonautsplugin/news/0e8df7da383d7515c4dc081bfe889897"
    }
  }
}
```


## Retrieve all news of the currently logged in user
    `GET /news`

  ```shell
  curl --request GET \
      --url https://example.com/news \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
  ```

### Authorization

    The user must at least be logged in or have root rights.

> The request returns JSON similar to this:

```json
{
  "data": {
    "type": "news",
    "id": "0e8df7da383d7515c4dc081bfe889897",
    "attributes": {
    "title": "New news",
    "content": "A new news item sees the light of day.",
    "mkdate": "2018-06-19T16:08:51+02:00",
    "chdate": "2018-08-15T14:22:38+02:00",
    "publication-start": "2018-06-19T16:08:51+02:00",
    "publication-end": "2066-12-05T15:08:51+01:00",
    "comments-allowed": true
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
      "ranges": {
        "data": [
        {
          "type": "users",
          "id": "1b7d3834e42c1569947e0eab7b63ed19"
        }
        ],
          "links": {
          "self": "/stud35/plugins.php/argonautsplugin/news/0e8df7da383d7515c4dc081bfe889897/relationships/ranges"
          }
      }
    },
    "links": {
      "self": "/stud35/plugins.php/argonautsplugin/news/0e8df7da383d7515c4dc081bfe889897"
    }
  }
}
```


## Delete a news item
   `DELETE /news/{id}`

   Parameter | Description
   ---------- | ------------
   id | The ID of the news item

### Authorization

This route can only be used by the user of the news in question.

   ```shell
   curl --request DELETE \
       --url https://example.com/news/<NEWS-ID> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
   ```
 ### Authorization

       The user must at least be logged in or have root rights.

## Delete a comment
   `DELETE /comments/{id}`

   Parameter | Description
   ---------- | ------------
   id | The ID of a comment

   ```shell
   curl --request DELETE \
       --url https://example.com/comments/studip/news \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
   ```
## All news ranges
   `GET /news/{id}/relationships/ranges`

   see http://jsonapi.org/format/#fetching-relationships

## Set news ranks
   `PATCH /news/{id}/relationships/ranges`

   see http://jsonapi.org/format/#crud-updating-to-many-relationships

## Add news-ranges
   `POST /news/{id}/relationships/ranges`

   see http://jsonapi.org/format/#crud-updating-to-many-relationships

## Delete news-ranges
   `DELETE /news/{id}/relationships/ranges`

   see http://jsonapi.org/format/#crud-updating-to-many-relationships
