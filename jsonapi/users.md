---
title: Users
---

Users of Stud.IP installations can be queried with the following routes
can be queried with the following routes.

## Schema

All users are mapped in Stud.IP with this schema. The `id`
corresponds to the `user_id` used in Stud.IP. The type is `users`.

### Attributes

Attribute | Description
-------- | ------------
username | the `username` is used during the login process
formatted-name | the formatted real name
family-name | the last name
given-name | the first name
name-prefix | possibly preceding titles
name-suffix | possibly trailing titles
permission | the global authorization level
email | the e-mail address
phone | the telephone number
homepage | the URL of the homepage
address | the private address

The permission level can be one of the following: `root`, `admin`,
`lecturer`, `tutor`, `author`

The visibility of the attributes `phone`, `homepage`, `address` follows
the visibility settings that users have made.

### Relations

:::info
Not all relations are accessible to all viewers.
:::

Relation | Description
-------- | ------------
activitystream | a link to the `activity stream`
blubber-postings | the blubber
contacts | the contacts
courses | the courses as `lecturer`
course-memberships | the participation in events
events | the calendar of events
institute-memberships | the institutes
schedule | the timetable


## All `users`

```shell
curl --request GET \
    --url https://example.com/jsonapi.php/v1/users \
    --header "Authorization: Basic `echo -ne "root@studip:testing" | base64`" \
```

  > The request returns JSON similar to this:

```json
{
  "meta": {
    "page": {
      "offset": 0,
      "limit": 30,
      "total": 5
    }
  },
  "links": {
    "first": "/?page[offset]=0&page[limit]=30",
    "last": "/?page[offset]=0&page[limit]=30"
  },
  "data": [
    {
      "type": "users",
      "id": "76ed43ef286fb55cf9e41beadb484a9f",
      "attributes": {
        "username": "root@studip",
        "formatted-name": "Root Studip",
        "family-name": "Studip",
        "given-name": "Root",
        "name-prefix": "",
        "name-suffix": "",
        "permission": "root",
        "email": "root@localhost",
        "phone": null,
        "homepage": null,
        "address": null
      },
      "relationships": {
        "activitystream": {
          "links": {
            "related": "jsonapi.php/v1/users/76ed43ef286fb55cf9e41beadb484a9f/activitystream"
          }
        },
        "blubber-postings": {
          "links": {
            "related": "jsonapi.php/v1/blubber-postings?filter[user]=76ed43ef286fb55cf9e41beadb484a9f"
          }
        },
        "contacts": {
          "links": {
            "related": "jsonapi.php/v1/users/76ed43ef286fb55cf9e41beadb484a9f/contacts"
          }
        },
        "courses": {
          "links": {
            "related": "jsonapi.php/v1/users/76ed43ef286fb55cf9e41beadb484a9f/courses"
          }
        },
        "course-memberships": {
          "links": {
            "related": "jsonapi.php/v1/users/76ed43ef286fb55cf9e41beadb484a9f/course-memberships"
          }
        },
        "events": {
          "links": {
            "related": "jsonapi.php/v1/users/76ed43ef286fb55cf9e41beadb484a9f/events"
          }
        },
        "institute-memberships": {
          "links": {
            "related": "jsonapi.php/v1/users/76ed43ef286fb55cf9e41beadb484a9f/institute-memberships"
          }
        },
        "schedule": {
          "links": {
            "related": "jsonapi.php/v1/users/76ed43ef286fb55cf9e41beadb484a9f/schedule"
          }
        }
      },
      "links": {
        "self": "jsonapi.php/v1/users/76ed43ef286fb55cf9e41beadb484a9f"
      },
      "meta": [

      ]
    },
    "[...]"
  ]
}
```

This endpoint provides all users in Stud.IP who can be seen with the
`credentials` of the JSON:API user can also be seen in Stud.IP itself.
may be seen in Stud.IP itself. The output is paginated and can be scrolled through by specifying
offset and limit.

### HTTP Request

GET /users

### Query parameters

```shell
curl --request GET \
     --url 'https://example.com/jsonapi.php/v1/users?filter[search]=test_author'\
     --header "Authorization: Basic `echo -ne "test_autor:testing" | base64`"
```

Parameter | Default | Description
--------- | ------- | ------------
page[offset] | 0 | the offset
page[limit] | 30 | the limit
filter[search] | %%% | the search term to find users; at least 3 characters

### Authorization

This route can only be used by users with the "root" authorization level.



## Read out yourself

```shell
curl --request GET \
    --url https://example.com/jsonapi.php/v1/users/me \
    --header "Authorization: Basic `echo -ne "test_dozent:testing" | base64`"
```

> The request returns JSON similar to this:

```json
{
  "data": {
    "type": "users",
    "id": "205f3efb7997a0fc9755da2b535038da",
    "attributes": {
      "username": "test_dozent",
      "formatted-name": "Testaccount lecturer",
      "family-name": "Lecturer",
      "given-name": "Testaccount",
      "name-prefix": "",
      "name-suffix": "",
      "permission": "lecturer",
      "email": "dozent@studip.de",
      "phone": null,
      "homepage": null,
      "address": null
    },
    "relationships": {
      "activitystream": {
        "links": {
          "related": "jsonapi.php/v1/users/205f3efb7997a0fc9755da2b535038da/activitystream"
        }
      },
      "blubber-postings": {
        "links": {
          "related": "jsonapi.php/v1/blubber-postings?filter[user]=205f3efb7997a0fc9755da2b535038da"
        }
      },
      "contacts": {
        "links": {
          "related": "jsonapi.php/v1/users/205f3efb7997a0fc9755da2b535038da/contacts"
        }
      },
      "courses": {
        "links": {
          "related": "jsonapi.php/v1/users/205f3efb7997a0fc9755da2b535038da/courses"
        }
      },
      "course-memberships": {
        "links": {
          "related": "jsonapi.php/v1/users/205f3efb7997a0fc9755da2b535038da/course-memberships"
        }
      },
      "events": {
        "links": {
          "related": "jsonapi.php/v1/users/205f3efb7997a0fc9755da2b535038da/events"
        }
      },
      "institute-memberships": {
        "links": {
          "related": "jsonapi.php/v1/users/205f3efb7997a0fc9755da2b535038da/institute-memberships"
        }
      },
      "schedule": {
        "links": {
          "related": "jsonapi.php/v1/users/205f3efb7997a0fc9755da2b535038da/schedule"
        }
      }
    },
    "links": {
      "self": "jsonapi.php/v1/users/205f3efb7997a0fc9755da2b535038da"
    },
    "meta": [

    ]
  }
}
```

With this endpoint you get the Stud.IP user who
authorized to access this endpoint - i.e. himself.

### HTTP Request

`GET /users/me`

### Query parameters

No query parameters are supported.

### Authorization

This route can be used by any authorized user.


## Read out individual users

```shell
curl --request GET \
    --url https://example.com/jsonapi.php/v1/users/<ID> \
    --header "Authorization: Basic `echo -ne "test_dozent:testing" | base64`"
```

This route returns individual, arbitrary users. Invisible
users can only see themselves.

### HTTP request

`GET /users/{id}`

### Query parameters

No query parameters are supported.

### Authorization

You can see yourself. `root` may see all users. Locked
and invisible users are otherwise not visible.


## Delete users

```shell
curl --request DELETE \
    --url https://example.com/jsonapi.php/v1/users/<ID> \
    --header "Authorization: Basic `echo -ne "test_dozent:testing" | base64`"
```

This route deletes any user.

### HTTP Request

`DELETE /users/{id}`

### Query parameters

No query parameters are supported.

### Authorization

This route is only activated if the Stud.IP configuration
"JSONAPI_DANGEROUS_ROUTES_ALLOWED" is set.

If this is the case, users with the `root` rights level may delete other users.
delete other users. You **cannot** delete yourself.


## Memberships in institutions

```shell
curl --request GET \
    --url https://example.com/jsonapi.php/v1/users/<ID>/institute-memberships \
    --header "Authorization: Basic `echo -ne "test_dozent:testing" | base64`"
```

This route is used to obtain memberships in user institutions.

### HTTP Request

`GET http://example.com/api/users/{id}/institute-memberships`

### Query parameters

No query parameters are supported.

### Authorization

A user can only view their own memberships in institutions.
