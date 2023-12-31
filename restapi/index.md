---
title: REST-API
sidebar_label: Overview
---

:::danger Deprecated feature

The REST-API was marked as deprecated for Stud.IP v5.0 and will be extended to version 6.0
will be extended. Please use the [JSONAPI](../jsonapi).

:::

### Introduction

Since version 3.0, the REST API has provided a comprehensive HTTP-based interface for the Stud.IP system. This can be used to query basic user and course data. The API also makes it possible to create and retrieve Blubber messages, forum posts and wiki pages.

### Administration of the REST API

The REST API is not activated in Stud.IP by default. To activate it, a root user must toggle a configuration variable on the administration page and activate REST routes.

#### Switching on the API

After logging in as a root user, select Admin -> System -> Configuration via the Stud.IP navigation and click on the "Global" area on the "System configuration management" page. A list of configuration variables appears. In this list, edit the variable API_ENABLED and set it to activated (tick the box) in the dialogue that opens after clicking on the edit icon. After applying the changes, the API is switched on. This can be recognised by the fact that a new item called "API" appears in the left-hand sidebar.

#### Enabling the REST routes

After the API has been activated, most routes are still not activated. To do this, as the root user, select the navigation points Admin -> System -> API and select the "Global access settings" view. Access can be controlled here for each route and each HTTP method via which the route can be reached. To allow access, the "Access" column must be ticked. To allow access to all routes (and all HTTP methods for the respective route), select the lowest tick at the bottom of the route table (above the word "All") and click on Save. All REST routes are now enabled.

The REST routes can also be activated programmatically, for example in a migration. Since Stud.IP 4.3, the `ConsumerPermissions` object has the methods `activateRouteMap()` or `deactivateRouteMap()`, which accepts a `RouteMap` and activates or deactivates all routes contained therein.

#### Setting up an application for OAuth authentication

In order to be able to use an application for the API with OAuth, the application must first be enabled for OAuth. This allows Stud.IP root users to determine which applications are permitted in the Stud.IP system.

To activate (or revoke) these, you navigate in the Stud.IP system as root user to the navigation point "Admin", then "System", then "API". You will see a list of registered consumers. Consumer here refers to an application that is activated (registered) for the API.

To activate a new application for the API, click on "Register new application" in the sidebar on the left and fill in the form that opens. The "Enabled" checkbox at the top of the dialogue must be ticked. The title of the application should be meaningful and correspond to the name of the application to avoid unnecessary confusion on the user side. After clicking on Save, the new application is activated.

An entry for the new application now appears in the list of registered consumers. Individual REST routes can be activated or deactivated for the application by clicking on the cogwheel symbol of the entry.

The application then uses the following configuration data:

* `consumer_key` - is generated when the consumer is set up
* `consumer_secret` - is generated when the consumer is set up
* `request_token_url` - `https://<my-stud.ip-url>/dispatch.php/api/oauth/request_token`
* `access_token_url` - `https://<meine-stud.ip-url>/dispatch.php/api/oauth/access_token`
* `authorise_url` - `https://<meine-stud.ip-url>/dispatch.php/api/oauth/authorise`
* `RESTAPI base URI` - `https://<my-stud.ip-url>/api.php/`

### Using the REST API

The REST API can be used via the HTTP methods `GET`, `POST`, `PUT` and `DELETE`.
GET' is used for reading. POST' and 'PUT' are intended for write access and 'DELETE' for deleting data in the Stud.IP system via the API.

If it is necessary to carry out a request with a method other than the one used for the API call, the HTTP method can be used via the API call,
the HTTP method can be explicitly set via the header `X-HTTP-Method-Override` as of Stud.IP 4.3.
This is necessary, for example, if you want to transmit more data via a `GET` request than the length of the request allows.
In this case, you can send a `POST` request and explicitly set the method to `GET` using the HTTP header `X-HTTP-Method-Override`.

### Login

Most REST routes can only be used sensibly in connection with a registered Stud.IP user.

#### Login via OAuth

When logging in via OAuth, a programme is authorised by the user to access data in the Stud.IP system with their user account via the API.

In order for an application to be used via OAuth, it must first be activated for this purpose and authorised via an OAuth `consumer key`.
and OAuth `consumer secret` (see section ["Setting up an application for OAuth authentication"](#setup-an-application-for-oauth-authentication)).

After authorisation by the user, the application receives its own access data, which is stored permanently and can be used to log in.


#### Login with username and password

If a login is carried out via username and password, the access data must be sent for each API request.
The username and password of a Stud.IP user are sent to the API via HTTP Basic Authentication.
Since HTTP Basic Authentication does not encrypt the data or hash the password,
the API should be accessed via HTTPS to make it more difficult for third parties to access the user data.

Although logging in via username and password is easier to realise on the client side, it should not be used,
as the username and password are placed in the hands of other programmes, which can pose a security risk.


### Queries

REST routes can be accessed under the path /api.php/ of the Stud.IP installation. If a Stud.IP system is located at the address https://studip.example.org, its API would be accessible at https://studip.example.org/api.php/. In Stud.IP systems whose URL redirects are not defined, the API would be accessible at https://studip.example.org/public/api.php/, for example.

A query is made by calling a route, for example /user. The complete path to the route could be as follows: https://studip.example.org/api.php/user. This request can be executed via `GET`.

#### Parameters that can be used for list queries

The following parameters are useful when querying routes that return lists of objects.

| Parameters | Description |
| ---- | ---- |
| `offset` | specifies the start position within the list |
| `limit` | specifies the maximum number of elements to be returned |

### Response formats

#### Single object

The response formats of the API are different. If only one object is requested, this is returned directly. The request for the REST route /user, for example, returns the following data:

```json
    {
        "phone" : "",
        "datafields" : [],
        "privadr" : "",
        "username" : "root@studip",
        "name" : {
            "username" : "root@studip",
            "formatted" : "rootstudip",
            "suffix" : "",
            "family" : "Studip",
            "prefix" : "",
            "given" : "Root"
        },
        "perms" : "root",
        "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
        "skype_show" : null,
        "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
        "homepage" : "",
        "email" : "root@localhost",
        "user_id" : "76ed43ef286fb55cf9e41beadb484a9f",
        "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
        "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
        "skype" : ""
    }
```

This is a user object. Individual event or bubble objects can also be queried in the same way via the respective routes, for example.

#### List of objects

There are REST routes that return a list of objects, for example all Blubber objects of an event. In this case, the response format is different. A list object is returned, which has the following structure:

```json
{
        "pagination" : {
            "total" : 0,
            "limit" : 20,
            "offset" : 0
        },
        "collection" : {
        }
    }
```

The returned object has two sub-objects, the first of which provides information on pagination and the second contains the actual objects. The pagination can be used to limit and control the number of objects to be loaded.

The "collection" sub-object has a separate attribute for each list object. The attribute refers to the API route with which the respective object could be queried directly.

##### Example of calling the API

First, the `/discovery` route is called to find out which routes have been activated.
This route returns a list with the activated routes that can be used by the application.

In the example, all blubber streams of the currently logged in user are to be determined. To do this, the route `/user` is called first,
to find information about the current user.
The response contains the user ID, which is relevant for the next query: retrieving all blubber streams of a user.
The route `/user/:user_id/blubber` is used for this, which returns all blubber objects of a user.


#### Pagination

The request parameters `offset` and `limit` can be used to control the pagination of the data. The "offset" can be used to
start position within a data collection.
The `limit` parameter specifies the maximum number of entries to be returned.

#### Status codes

The API returns HTTP status codes that can be used to determine whether the request was executed successfully or not.

#### Error behaviour

In the event of an error, no JSON is returned. Instead, simple, short character strings are returned that describe the error.

In the event that the REST route causes a `PHP Fatal Error`, this is displayed.


### Example: Creating an application for the Stud.IP API (including OAuth)

Depending on the programming language, the Stud.IP API can be accessed with relatively little code.
The creation of a small application for Stud.IP is described here, which authenticates itself via OAuth and then requests data from the logged-in user.
of the logged-in user.


#### Required modules

The Python modules json, requests and rauth are required for this application. Python3 should be used.


#### Import and initialisation

First, the required modules are loaded and an object for OAuth1 authentication is built:

```python
import json
import requests
from rauth import OAuth1Service

studip = OAuth1Service(
    name='Stud.IP',
    consumer_key='CONSUMER_KEY_DER_ANWENDUNG',
    consumer_secret='CONSUMER_SECRET_DER_ANWENDUNG',
    request_token_url='http://studip.example.org/dispatch.php/api/oauth/request_token',
    access_token_url='http://studip.example.org/dispatch.php/api/oauth/access_token',
    authorise_url='http://studip.example.org/dispatch.php/api/oauth/authorize',
    base_url='http://studip.example.org/api.php/'
)
```


#### Requesting request tokens and authorisation

A request token can then be requested and the URL for authorisation retrieved. This must be opened in the browser. If you are not logged into the Stud.IP system, you must enter your username and password on the Stud.IP installation page to authorise the application.

```python
request_token, request_token_secret = studip.get_request_token()

authorise_url = studip.get_authorise_url(request_token)

print('Please call the following URL: ' + authorise_url)

input('If the URL has been called and this application has been authorised, please press Enter to continue!')
```


#### Creating an authenticated session

After authorisation, an authenticated session can be started with which queries can be made to the Stud.IP API:

```python
session = studip.get_auth_session(
    request_token,
    request_token_secret,
    method='`POST`',
    data={'oauth_verifier': *}
)
```

As Stud.IP also allows API queries without oauth_verifier, this parameter can be omitted.


#### Querying the API

The session created in the section above can now be used to query the Stud.IP API routes. For example, the /user route can be called to obtain data on the logged-in user:

```python
user_data = session.`GET`('user')
```



### REST-API routes

The available REST-API routes and the HTTP methods that can be used are shown below.

#### System routes

##### `GET` /discovery

Returns a list of available routes.

###### Response format

```json
{
   "/studip/settings" : {
      "`GET`" : "Basic system settings"
   },
   "/user" : {
      "`GET`" : "getUser - retrieves data of a user"
   },
   "/discovery" : {
      "`GET`" : "Interface description"
   },
   "/messages" : {
      "`POST`" : "Writes a new message."
   },
   "/studip/news" : {
      "`GET`" : "Read global news",
      "`POST`" : "Create news"
   }
}
```


##### `GET` /studip/colors

Returns the colour settings of the Stud.IP system. There are three fixed colour values for the background and for dark and light areas of the Stud.IP system.

###### Response format

```json
{
   "background" : "#e1e4e9",
   "dark" : "#34578c",
   "light" : "#899ab9"
}
```


##### `GET` /studip/news

Returns a list of global announcements (announcements for the entire Stud.IP system).

###### Response format

```json
{
   "pagination" : {
      "limit" : 20,
      "total" : 1,
      "offset" : 0
   },
   "collection" : {
      "/api.php/news/29f2932ce32be989022c6f43b866e744" : {
         "comments" : "/api.php/news/29f2932ce32be989022c6f43b866e744/comments",
         "news_id" : "29f2932ce32be989022c6f43b866e744",
         "expire" : "14562502",
         "date" : "1468409976",
         "chdate_uid" : "",
         "body_html" : "<div class=\"formatted-content\">The Stud.IP team welcomes you. <br>Please feel free to take a look around! <br><br>If you have installed the system yourself and see this news, you have added the demonstration data to the database. If you want to work productively with the system, you should delete this data later, as the passwords of the accounts (especially the root account) are publicly known.</div>",
         "mkdate" : "1468409976",
         "topic" : "Welcome!",
         "body" : "The Stud.IP team welcomes you. \r\nPlease feel free to look around!\r\n\r\nIf you have installed the system yourself and see this news, you have added the demonstration data to the database. If you want to work productively with the system, you should delete this data later, as the passwords of the accounts (especially the root account) are publicly known.",
         "ranges" : [
            "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f/news",
            "/api.php/studip/news"
         ],
         "comments_count" : 0,
         "allow_comments" : "1",
         "chdate" : "1468409976",
         "user_id" : "76ed43ef286fb55cf9e41beadb484a9f"
      }
   }
}
```


##### `POST` /studip/news

Creates a new global announcement.

The title of the announcement is set via the parameter `topic`, the content via `body`.
There are two optional parameters, `expire` and `allow_comments`. "expire" specifies the time span in seconds from the current date,
on which the announcement is to expire. If the `allow_comments` parameter is set to 1, comments are allowed.
It is set to 0 by default.

###### Parameter

|**`POST`-Parameter** |**Format** |**Description**
| ---- | ---- | ---- |
| `topic` | `string` | The title of the announcement
| `body` | `string` | The content of the announcement
| `expire` | `Integer` | Expiration date of the message (calculated in seconds from the current date)
| `allow_comments` | `Integer` | Indicates whether comments are allowed: 1 = allowed, 0 = not allowed



##### `GET` /studip/settings

Returns the values of certain configuration variables.

###### Response format
```json
{
   "TERMIN_TYP" : {
      "2" : {
         "name" : "pre-meeting",
         "colour" : "#b02e7c",
         "session" : 0
      },
      "4" : {
         "name" : "Excursion",
         "colour" : "#f26e00",
         "session" : 0
      },
      "6" : {
         "name" : "Special session",
         "colour" : "#a85d45",
         "session" : 0
      },
      "7" : {
         "name" : "Lecture",
         "colour" : "#ca9eaf",
         "session" : 1
      },
      "3" : {
         "colour" : "#129c94",
         "name" : "exam",
         "session" : 0
      },
      "1" : {
         "session" : 1,
         "colour" : "#682c8b",
         "name" : "session"
      },
      "5" : {
         "session" : 0,
         "name" : "other appointment",
         "colour" : "#008512"
      }
   },
   "PERS_TERMIN_KAT" : {
      "10" : {
         "colour" : "#66b570",
         "name" : "appointment"
      },
      "5" : {
         "colour" : "#f26e00",
         "name" : "Excursion"
      },
      "12" : {
         "colour" : "#d082b0",
         "name" : "Family"
      },
      "2" : {
         "colour" : "#682c8b",
         "name" : "Session"
      },
      "4" : {
         "colour" : "#129c94",
         "name" : "Exam"
      },
      "1" : {
         "name" : "Other",
         "colour" : "#008512"
      },
      "14" : {
         "name" : "Travel",
         "colour" : "#f7a866"
      },
      "15" : {
         "name" : "Lecture",
         "colour" : "#ca9eaf"
      },
      "8" : {
         "colour" : "#d60000",
         "name" : "Phone call"
      },
      "9" : {
         "colour" : "#ffbd33",
         "name" : "Meeting"
      },
      "11" : {
         "colour" : "#a480b9",
         "name" : "Birthday"
      },
      "13" : {
         "name" : "Holiday",
         "colour" : "#70c3bf"
      },
      "3" : {
         "colour" : "#b02e7c",
         "name" : "Preliminary meeting"
      },
      "7" : {
         "colour" : "#6ead10",
         "name" : "Exam"
      },
      "6" : {
         "colour" : "#a85d45",
         "name" : "Special session"
      }
   },
   "SEM_TYPE" : {
      "9" : {
         "class" : "2",
         "name" : "Project group"
      },
      "11" : {
         "class" : "3",
         "name" : "Cultural Forum"
      },
      "13" : {
         "name" : "other",
         "class" : "3"
      },
      "7" : {
         "name" : "other",
         "class" : "1"
      },
      "3" : {
         "class" : "1",
         "name" : "exercise"
      },
      "6" : {
         "class" : "1",
         "name" : "Research group"
      },
      "10" : {
         "class" : "2",
         "name" : "other"
      },
      "5" : {
         "class" : "1",
         "name" : "Colloquium"
      },
      "12" : {
         "class" : "3",
         "name" : "Event Board"
      },
      "4" : {
         "class" : "1",
         "name" : "Internship"
      },
      "2" : {
         "name" : "Seminar",
         "class" : "1"
      },
      "1" : {
         "name" : "Lecture",
         "class" : "1"
      },
      "8" : {
         "class" : "2",
         "name" : "Committee"
      },
      "99" : {
         "name" : "Study group",
         "class" : "99"
      }
   },
   "SUPPORT_EMAIL" : "<please insert your general contact mail-adress here>",
   "ALLOW_CHANGE_NAME" : true,
   "ALLOW_CHANGE_USERNAME" : true,
   "ALLOW_CHANGE_EMAIL" : true,
   "UNI_NAME_CLEAN" : "Stud.IP trunk",
   "TITLES" : {
      "accepted" : [
         "Provisionally accepted person",
         "provisionally accepted persons"
      ],
      "deputy" : [
         "deputy",
         "Deputies"
      ],
      "author" : [
         "students",
         "students"
      ],
      "lecturer" : [
         "lecturer",
         "lecturer"
      ],
      "user" : [
         "reader",
         "readers"
      ],
      "tutor" : [
         "tutor",
         "tutor"
      ]
   },
   "SEM_CLASS" : {
      "2" : {
         "areas" : "0",
         "create_description" : "",
         "description" : "Here you will find virtual events on various committees at the university",
         "name" : "Organisation",
         "scm" : null,
         "title_author_plural" : null,
         "wiki" : "CoreWiki",
         "id" : "2",
         "write_access_nobody" : "0",
         "default_write_level" : "2",
         "default_read_level" : "2",
         "schedule" : "CoreSchedule",
         "studygroup_mode" : "0",
         "title_tutor_plural" : "Members",
         "modules" : {
            "CoreResources" : {
               "sticky" : "0",
               "activated" : "1"
            },
            "CoreAdmin" : {
               "activated" : "1",
               "sticky" : "1"
            },
            "CoreSchedule" : {
               "sticky" : "0",
               "activated" : "1"
            },
            "CoreForum" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreParticipants" : {
               "sticky" : "0",
               "activated" : "1"
            },
            "CoreScm" : {
               "activated" : "0",
               "sticky" : "1"
            },
            "CoreElearningInterface" : {
               "sticky" : "1",
               "activated" : "0"
            },
            "CoreStudygroupParticipants" : {
               "activated" : "0",
               "sticky" : "1"
            },
            "CoreLiterature" : {
               "activated" : "0",
               "sticky" : "1"
            },
            "CoreDocuments" : {
               "sticky" : "0",
               "activated" : "1"
            },
            "CoreCalendar" : {
               "activated" : "0",
               "sticky" : "1"
            },
            "CoreWiki" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreStudygroupAdmin" : {
               "activated" : "0",
               "sticky" : "1"
            },
            "CoreOverview" : {
               "sticky" : "1",
               "activated" : "1"
            }
         },
         "title_dozent" : "Leader",
         "module" : "0",
         "forum" : "CoreForum",
         "documents" : "CoreDocuments",
         "elearning_interface" : null,
         "overview" : "CoreOverview",
         "topic_create_author" : "0",
         "admission_type_default" : "0",
         "title_dozent_plural" : "Leaders",
         "course_creation_forbidden" : "0",
         "admin" : "CoreAdmin",
         "title_tutor" : "Member",
         "chdate" : "1366882198",
         "compact_mode" : "1",
         "mkdate" : "1366882120",
         "literature" : null,
         "visible" : "1",
         "resources" : "CoreResources",
         "admission_prelim_default" : "0",
         "calendar" : null,
         "turnus_default" : "-1",
         "workgroup_mode" : "1",
         "show_browse" : "1",
         "participants" : "CoreParticipants",
         "show_raumzeit" : "1",
         "title_author" : null,
         "only_inst_user" : "0"
      },
      "3" : {
         "write_access_nobody" : "1",
         "default_write_level" : "1",
         "id" : "3",
         "wiki" : "CoreWiki",
         "title_author_plural" : null,
         "scm" : null,
         "name" : "Community",
         "description" : "Here you will find virtual events on various topics",
         "areas" : "0",
         "create_description" : "",
         "overview" : "CoreOverview",
         "documents" : "CoreDocuments",
         "elearning_interface" : null,
         "module" : "0",
         "title_dozent" : null,
         "forum" : "CoreForum",
         "title_tutor_plural" : null,
         "modules" : {
            "CoreAdmin" : {
               "activated" : 1,
               "sticky" : 1
            },
            "CoreOverview" : {
               "sticky" : 1,
               "activated" : 1
            }
         },
         "studygroup_mode" : "0",
         "schedule" : "CoreSchedule",
         "default_read_level" : "1",
         "admission_prelim_default" : "0",
         "calendar" : null,
         "literature" : "CoreLiterature",
         "visible" : "1",
         "resources" : "CoreResources",
         "compact_mode" : "1",
         "mkdate" : "1366882120",
         "admin" : "CoreAdmin",
         "chdate" : "1366882120",
         "title_tutor" : null,
         "title_dozent_plural" : null,
         "course_creation_forbidden" : "0",
         "topic_create_author" : "0",
         "admission_type_default" : "0",
         "only_inst_user" : "0",
         "title_author" : null,
         "participants" : "CoreParticipants",
         "show_raumzeit" : "1",
         "workgroup_mode" : "0",
         "show_browse" : "1",
         "turnus_default" : "-1"
      },
      "99" : {
         "show_raumzeit" : "0",
         "participants" : "CoreStudygroupParticipants",
         "title_author" : "Member",
         "only_inst_user" : "0",
         "show_browse" : "0",
         "workgroup_mode" : "0",
         "turnus_default" : "0",
         "visible" : "0",
         "resources" : null,
         "literature" : null,
         "calendar" : null,
         "admission_prelim_default" : "0",
         "title_dozent_plural" : "Group founders",
         "course_creation_forbidden" : "1",
         "admission_type_default" : "0",
         "topic_create_author" : "1",
         "mkdate" : "1366882120",
         "compact_mode" : "0",
         "chdate" : "1462287763",
         "title_tutor" : "Moderator",
         "admin" : "CoreStudygroupAdmin",
         "elearning_interface" : null,
         "documents" : "CoreDocuments",
         "overview" : "CoreOverview",
         "studygroup_mode" : "1",
         "schedule" : "CoreSchedule",
         "default_read_level" : "0",
         "forum" : "CoreForum",
         "module" : "0",
         "title_dozent" : "group_founder",
         "modules" : {
            "CoreScm" : {
               "sticky" : "0",
               "activated" : "1"
            },
            "CoreElearningInterface" : {
               "sticky" : "1",
               "activated" : "0"
            },
            "CoreAdmin" : {
               "sticky" : "1",
               "activated" : "0"
            },
            "CoreSchedule" : {
               "sticky" : "0",
               "activated" : "1"
            },
            "CoreResources" : {
               "sticky" : "1",
               "activated" : "0"
            },
            "CoreForum" : {
               "sticky" : "0",
               "activated" : "1"
            },
            "CoreParticipants" : {
               "sticky" : "1",
               "activated" : "0"
            },
            "CoreWiki" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreStudygroupAdmin" : {
               "activated" : "1",
               "sticky" : "1"
            },
            "CoreOverview" : {
               "activated" : "1",
               "sticky" : "1"
            },
            "CoreLiterature" : {
               "sticky" : "1",
               "activated" : "0"
            },
            "CoreStudygroupParticipants" : {
               "sticky" : "1",
               "activated" : "1"
            },
            "CoreCalendar" : {
               "activated" : "0",
               "sticky" : "1"
            },
            "CoreDocuments" : {
               "activated" : "1",
               "sticky" : "0"
            }
         },
         "title_tutor_plural" : "Moderators",
         "wiki" : "CoreWiki",
         "title_author_plural" : "Members",
         "default_write_level" : "0",
         "write_access_nobody" : "0",
         "id" : "99",
         "description" : "",
         "name" : "Study groups",
         "create_description" : "",
         "areas" : "0",
         "scm" : "CoreScm"
      },
      "1" : {
         "overview" : "CoreOverview",
         "elearning_interface" : "CoreElearningInterface",
         "documents" : "CoreDocuments",
         "modules" : {
            "CoreScm" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreElearningInterface" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreResources" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreSchedule" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreAdmin" : {
               "activated" : "1",
               "sticky" : "1"
            },
            "CoreParticipants" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreForum" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreWiki" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreOverview" : {
               "activated" : "1",
               "sticky" : "1"
            },
            "CoreStudygroupAdmin" : {
               "sticky" : "1",
               "activated" : "0"
            },
            "CoreStudygroupParticipants" : {
               "sticky" : "1",
               "activated" : "0"
            },
            "CoreLiterature" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreDocuments" : {
               "activated" : "1",
               "sticky" : "0"
            },
            "CoreCalendar" : {
               "activated" : "1",
               "sticky" : "0"
            }
         },
         "title_tutor_plural" : null,
         "forum" : "CoreForum",
         "module" : "0",
         "title_dozent" : null,
         "default_read_level" : "1",
         "schedule" : "CoreSchedule",
         "studygroup_mode" : "0",
         "id" : "1",
         "default_write_level" : "1",
         "write_access_nobody" : "0",
         "title_author_plural" : null,
         "wiki" : "CoreWiki",
         "scm" : "CoreScm",
         "create_description" : "",
         "areas" : "1",
         "description" : "Here you can find all courses registered in Stud.IP",
         "name" : "Teaching",
         "title_author" : null,
         "only_inst_user" : "1",
         "show_raumzeit" : "1",
         "participants" : "CoreParticipants",
         "turnus_default" : "0",
         "workgroup_mode" : "0",
         "show_browse" : "1",
         "calendar" : "CoreCalendar",
         "admission_prelim_default" : "0",
         "visible" : "1",
         "resources" : "CoreResources",
         "literature" : "CoreLiterature",
         "title_tutor" : null,
         "chdate" : "1366882169",
         "admin" : "CoreAdmin",
         "mkdate" : "1366882120",
         "compact_mode" : "0",
         "admission_type_default" : "0",
         "topic_create_author" : "0",
         "title_dozent_plural" : null,
         "course_creation_forbidden" : "0"
      }
   },
   "ALLOW_CHANGE_TITLE" : true,
   "INST_TYPE" : {
      "1" : {
         "name" : "institution"
      },
      "8" : {
         "name" : "Workgroup"
      },
      "2" : {
         "name" : "Centre"
      },
      "4" : {
         "name" : "Department"
      },
      "5" : {
         "name" : "Department"
      },
      "6" : {
         "name" : "Seminar"
      },
      "7" : {
         "name" : "Faculty"
      },
      "3" : {
         "name" : "Chair"
      }
   }
}
```




#### User - Data on a user

##### `GET` /user

Returns data for the current user.

This REST route is comparable to the call `User::findCurrent()` in the Stud.IP system.
It returns an object with data of the user who has logged in via the API.


###### Response format
```json
{
   "name" : {
      "family" : "Studip",
      "given" : "Root",
      "formatted" : "Root Studip",
      "prefix" : "",
      "username" : "root@studip",
      "suffix" : ""
   },
   "username" : "root@studip",
   "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
   "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
   "email" : "root@localhost",
   "homepage" : "",
   "privadr" : "",
   "phone" : "",
   "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
   "user_id" : "76ed43ef286fb55cf9e41beadb484a9f",
   "skype" : "",
   "datafields" : [],
   "skype_show" : null,
   "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
   "perms" : "root"
}
```

##### `GET` /user/:user_id

Returns data of a user, which is referenced by its user ID, comparable to User::find() in the Stud.IP system.

###### Parameter

**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| `user_id` |string, 32 characters | The user ID of the user

###### Response format

```json
{
   "name" : {
      "family" : "Studip",
      "given" : "Root",
      "formatted" : "Root Studip",
      "prefix" : "",
      "username" : "root@studip",
      "suffix" : ""
   },
   "username" : "root@studip",
   "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
   "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
   "email" : "root@localhost",
   "homepage" : "",
   "privadr" : "",
   "phone" : "",
   "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
   "user_id" : "76ed43ef286fb55cf9e41beadb484a9f",
   "skype" : "",
   "datafields" : [],
   "skype_show" : null,
   "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
   "perms" : "root"
}
```


##### `DELETE` /user/:user_id

If the API user has root permissions and does not intend to delete himself, he can delete a user by calling this API route. If the user attempts to delete themselves, error code 400 is returned; if the user does not have root authorisations, error code 401 is returned.

###### Parameter

**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id |string, 32 characters | The user ID of the user


##### `GET` /user/:user_id/blubber

This route returns all blubber objects that were created in a user's blubber stream.

###### Parameter


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id |string, 32 characters | The user ID of the user


###### Response format

```json
{
    "pagination" : {
        "total" : 2,
        "limit" : 20,
        "offset" : 0
    },
    "collection" : {
        "/api.php/blubber/posting/ecab929ef0dfaeca159802c018826a25" : {
            "chdate" : "1477299897",
            "blubber_id" : "ecab929ef0dfaeca159802c018826a25",
            "mkdate" : "1477299897",
            "content" : "Another test blubber",
            "comments" : "/api.php/blubber/posting/ecab929ef0dfaeca159802c018826a25/comments",
            "content_html" : "<div class=\"formatted-content\">Another test blubber</div>",
            "tags" : [],
            "root_id" : "ecab929ef0dfaeca159802c018826a25",
            "comments_count" : "0",
            "context_type" : "public",
            "author" : {
                "name" : {
                "prefix" : "",
                "username" : "root@studip",
                "family" : "Studip",
                "formatted" : "Root Studip",
                "suffix" : "",
                "given" : "root"
                },
                "id" : "76ed43ef286fb55cf9e41beadb484a9f",
                "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
                "href" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
                "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
                "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
                "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0"
            },
            "reshares" : []
        },
        "/api.php/blubber/posting/5f5f5d4f49f6122a0f2761ecf887d912" : {
            "comments" : "/api.php/blubber/posting/5f5f5d4f49f6122a0f2761ecf887d912/comments",
            "content_html" : "<div class=\"formatted-content\">This is a test blubber</div>",
            "content" : "This is a test blubber",
            "mkdate" : "1477299878",
            "blubber_id" : "5f5f5d4f49f6122a0f2761ecf887d912",
            "chdate" : "1477299878",
            "reshares" : [],
            "author" : {
                "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
                "href" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
                "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
                "id" : "76ed43ef286fb55cf9e41beadb484a9f",
                "name" : {
                "prefix" : "",
                "family" : "Studip",
                "username" : "root@studip",
                "given" : "root",
                "suffix" : "",
                "formatted" : "Root Studip"
                },
                "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
                "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0"
            },
            "context_type" : "public",
            "tags" : [],
            "root_id" : "5f5f5d4f49f6122a0f2761ecf887d912",
            "comments_count" : "0"
        }
    }
}
```


##### `POST` /user/:user_id/blubber

Creates a new blubber object in a user's blubber stream.

The content of the blubber is simply sent as a parameter called "content" of the HTTP `POST` request. No special formatting is necessary.

###### Parameter


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id |String, 32 characters | The user ID of the user


| **`POST`-Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| content | String | The content of the blubber


##### `GET` /user/:user_id/contacts

A list of a user's contacts is returned, which contains the name and the URLs to the profile picture in several sizes. In addition, the user ID and API URL for calling up further user data are provided.

###### Parameter


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id |String, 32 characters | The user ID of the user

###### Response format

```json
{
   "collection" : [
      {
         "name" : {
            "prefix" : "",
            "username" : "test_admin",
            "family" : "Admin",
            "formatted" : "Testaccount Admin",
            "given" : "Testaccount",
            "suffix" : ""
         },
         "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
         "href" : "/api.php/user/6235c46eb9e962866ebdceece739ace5",
         "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
         "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
         "id" : "6235c46eb9e962866ebdceece739ace5",
         "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962"
      },
      {
         "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
         "id" : "e7a0a84b161f3e8c09b4a0a2e8a58147",
         "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
         "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
         "href" : "/api.php/user/e7a0a84b161f3e8c09b4a0a2e8a58147",
         "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
         "name" : {
            "formatted" : "Testaccount Author",
            "given" : "Testaccount",
            "suffix" : "",
            "prefix" : "",
            "username" : "test_author",
            "family" : "Author"
         }
      },
      {
         "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
         "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
         "id" : "205f3efb7997a0fc9755da2b535038da",
         "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
         "name" : {
            "given" : "Testaccount",
            "suffix" : "",
            "formatted" : "Testaccount Lecturer",
            "username" : "test_dozent",
            "prefix" : "",
            "family" : "Lecturer"
         },
         "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da",
         "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0"
      },
      {
         "id" : "7e81ec247c151c02ffd479511e24cc03",
         "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
         "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
         "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
         "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
         "href" : "/api.php/user/7e81ec247c151c02ffd479511e24cc03",
         "name" : {
            "formatted" : "Testaccount Tutor",
            "given" : "Testaccount",
            "suffix" : "",
            "prefix" : "",
            "username" : "test_tutor",
            "family" : "Tutor"
         }
      }
   ],
   "pagination" : {
      "offset" : 0,
      "total" : 4,
      "limit" : 20
   }
}

```


##### `GET` /user/:user_id/courses

Returns a list of a user's courses. The courses can be filtered by semester using the "semester" parameter. This parameter must contain the semester ID. A list of all semesters including the associated IDs can be queried via the /semesters route.

###### Parameter


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id | String, 32 characters | The user ID of the user
| semester | String, 32 characters | The ID of a semester

###### Response format

```json

{
   "pagination" : {
      "total" : 4,
      "limit" : 20,
      "offset" : 0
   },
   "collection" : {
      "/api.php/course/984e34196f2e6ea6e1b2cc58f432fb8d" : {
         "course_id" : "984e34196f2e6ea6e1b2cc58f432fb8d",
         "type" : "1",
         "end_semester" : "/api.php/semester/eb828ebb81bb946fac4108521a3b4697",
         "lecturers" : {
            "/api.php/user/205f3efb7997a0fc9755da2b535038da" : {
               "name" : {
                  "given" : "Testaccount",
                  "formatted" : "Testaccount Lecturer",
                  "suffix" : "",
                  "family" : "Lecturer",
                  "username" : "test_dozent",
                  "prefix" : ""
               },
               "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
               "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
               "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
               "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da",
               "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
               "id" : "205f3efb7997a0fc9755da2b535038da"
            }
         },
         "title" : "At the chair s&#65533;gen 1",
         "group" : 6,
         "subtitle" : "",
         "members" : {
            "user" : "/api.php/course/984e34196f2e6ea6e1b2cc58f432fb8d/members?status=user",
            "tutor" : "/api.php/course/984e34196f2e6ea6e1b2cc58f432fb8d/members?status=tutor",
            "dozent_count" : 1,
            "autor" : "/api.php/course/984e34196f2e6ea6e1b2cc58f432fb8d/members?status=autor",
            "tutor_count" : 0,
            "author_count" : 0,
            "lecturer" : "/api.php/course/984e34196f2e6ea6e1b2cc58f432fb8d/members?status=dozent",
            "user_count" : 0
         },
         "location" : "",
         "modules" : {
            "documents" : "/api.php/course/984e34196f2e6ea6e1b2cc58f432fb8d/files",
            "wiki" : "/api.php/course/984e34196f2e6ea6e1b2cc58f432fb8d/wiki",
            "forum" : "/api.php/course/984e34196f2e6ea6e1b2cc58f432fb8d/forum_categories"
         },
         "number" : "ALS1",
         "start_semester" : "/api.php/semester/eb828ebb81bb946fac4108521a3b4697",
         "description" : ""
      },
      "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde" : {
         "start_semester" : "/api.php/semester/f2b4fdf5ac59a9cb57dd73c4d3bbb651",
         "description" : "",
         "number" : "12345",
         "modules" : {
            "documents" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/files",
            "wiki" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/wiki",
            "forum" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/forum_categories"
         },
         "location" : "",
         "members" : {
            "user_count" : 0,
            "lecturer" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/members?status=dozent",
            "tutor_count" : 1,
            "author" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/members?status=author",
            "author_count" : 1,
            "tutor" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/members?status=tutor",
            "dozent_count" : 1,
            "user" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/members?status=user"
         },
         "subtitle" : "a normal course",
         "group" : 5,
         "title" : "Test course",
         "lecturers" : {
            "/api.php/user/205f3efb7997a0fc9755da2b535038da" : {
               "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
               "id" : "205f3efb7997a0fc9755da2b535038da",
               "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
               "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da",
               "name" : {
                  "suffix" : "",
                  "username" : "test_dozent",
                  "family" : "lecturer",
                  "prefix" : "",
                  "given" : "Testaccount",
                  "formatted" : "Testaccount Lecturer"
               },
               "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
               "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962"
            }
         },
         "end_semester" : "/api.php/semester/f2b4fdf5ac59a9cb57dd73c4d3bbb651",
         "type" : "1",
         "course_id" : "a07535cf2f8a72df33c12ddfa4b53dde"
      },
      "/api.php/course/29d755d51d2bf920ef2017db3359bdb2" : {
         "type" : "1",
         "course_id" : "29d755d51d2bf920ef2017db3359bdb2",
         "title" : "Factorising faculties, optional faculty lecture five",
         "end_semester" : "/api.php/semester/eb828ebb81bb946fac4108521a3b4697",
         "lecturers" : {
            "/api.php/user/205f3efb7997a0fc9755da2b535038da" : {
               "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
               "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
               "name" : {
                  "given" : "Testaccount",
                  "formatted" : "Testaccount Lecturer",
                  "suffix" : "",
                  "family" : "Lecturer",
                  "username" : "test_dozent",
                  "prefix" : ""
               },
               "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
               "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
               "id" : "205f3efb7997a0fc9755da2b535038da",
               "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da"
            }
         },
         "location" : "",
         "members" : {
            "user" : "/api.php/course/29d755d51d2bf920ef2017db3359bdb2/members?status=user",
            "dozent_count" : 1,
            "tutor" : "/api.php/course/29d755d51d2bf920ef2017db3359bdb2/members?status=tutor",
            "author_count" : 0,
            "tutor_count" : 0,
            "autor" : "/api.php/course/29d755d51d2bf920ef2017db3359bdb2/members?status=autor",
            "dozent" : "/api.php/course/29d755d51d2bf920ef2017db3359bdb2/members?status=dozent",
            "user_count" : 0
         },
         "subtitle" : "",
         "group" : 6,
         "start_semester" : "/api.php/semester/eb828ebb81bb946fac4108521a3b4697",
         "description" : "",
         "number" : "4F5",
         "modules" : {
            "wiki" : "/api.php/course/29d755d51d2bf920ef2017db3359bdb2/wiki",
            "forum" : "/api.php/course/29d755d51d2bf920ef2017db3359bdb2/forum_categories",
            "documents" : "/api.php/course/29d755d51d2bf920ef2017db3359bdb2/files"
         }
      },
      "/api.php/course/9bbd57993e9cf6e1ed82ab5273af09ad" : {
         "lecturers" : {
            "/api.php/user/205f3efb7997a0fc9755da2b535038da" : {
               "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
               "id" : "205f3efb7997a0fc9755da2b535038da",
               "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da",
               "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
               "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
               "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
               "name" : {
                  "suffix" : "",
                  "family" : "lecturer",
                  "username" : "test_dozent",
                  "prefix" : "",
                  "formatted" : "Testaccount Lecturer",
                  "given" : "Testaccount"
               }
            }
         },
         "end_semester" : "/api.php/semester/eb828ebb81bb946fac4108521a3b4697",
         "title" : "Set up external facilities externally, introductory event one",
         "course_id" : "9bbd57993e9cf6e1ed82ab5273af09ad",
         "type" : "1",
         "modules" : {
            "documents" : "/api.php/course/9bbd57993e9cf6e1ed82ab5273af09ad/files",
            "wiki" : "/api.php/course/9bbd57993e9cf6e1ed82ab5273af09ad/wiki",
            "forum" : "/api.php/course/9bbd57993e9cf6e1ed82ab5273af09ad/forum_categories"
         },
         "number" : "5E-1",
         "start_semester" : "/api.php/semester/eb828ebb81bb946fac4108521a3b4697",
         "description" : "",
         "group" : 6,
         "subtitle" : "",
         "members" : {
            "lecturer" : "/api.php/course/9bbd57993e9cf6e1ed82ab5273af09ad/members?status=dozent",
            "user_count" : 0,
            "author_count" : 0,
            "tutor_count" : 0,
            "author" : "/api.php/course/9bbd57993e9cf6e1ed82ab5273af09ad/members?status=author",
            "dozent_count" : 1,
            "tutor" : "/api.php/course/9bbd57993e9cf6e1ed82ab5273af09ad/members?status=tutor",
            "user" : "/api.php/course/9bbd57993e9cf6e1ed82ab5273af09ad/members?status=user"
         },
         "location" : ""
      }
   }
}

```


##### `GET` /user/:user_id/events

Reads the list of a user's calendar entries for the next 2 weeks.
If the route /user/:user_id/events.ics is called instead of this route, the calendar entries are obtained in iCal format.

###### Parameter


**Parameters** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id |String, 32 characters | The user ID of the user



##### `GET` /user/:user_id/institutes

Returns a list of the user's institutions. The returned "collection" object does not contain the routes to the individual objects, but two attributes with the names "work" and "study", which in turn contain an array with institute objects. This means that institutes are returned sorted according to their function for the selected user.

###### Parameter


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id |String, 32 characters | The user ID of the user

###### Response format

```json

{
   "collection" : {
      "study" : [],
      "work" : [
         {
            "fax" : "",
            "street" : "Geismar Landstr. 17b",
            "room" : "",
            "faculty_street" : "Geismar Landstr. 17b",
            "faculty_city" : "37083 Göttingen",
            "city" : "37083 Göttingen",
            "consultation" : "",
            "faculty_name" : "Test Fakultät",
            "perms" : "dozent",
            "institute_id" : "1535795b0d6ddecac6813f5f6ac47ef2",
            "name" : "test faculty",
            "phone" : ""
         },
         {
            "room" : "",
            "faculty_street" : "Geismar Landstr. 17b",
            "faculty_city" : "37083 Göttingen",
            "fax" : "",
            "street" : "",
            "phone" : "",
            "consultation" : "",
            "city" : "",
            "institute_id" : "2560f7c7674942a7dce8eeb238e15d93",
            "faculty_name" : "Test Faculty",
            "perms" : "lecturer",
            "name" : "Test organisation"
         },
         {
            "name" : "Test Chair",
            "faculty_name" : "Test Faculty",
            "institute_id" : "536249daa596905f433e1f73578019db",
            "perms" : "lecturer",
            "city" : "",
            "consultation" : "",
            "phone" : "",
            "street" : "",
            "fax" : "",
            "faculty_city" : "37083 Göttingen",
            "faculty_street" : "Geismar Landstr. 17b",
            "room" : ""
         },
         {
            "faculty_street" : "",
            "faculty_city" : "",
            "room" : "",
            "fax" : "",
            "street" : "",
            "phone" : "",
            "faculty_name" : "external educational institutions",
            "institute_id" : "7a4f19a0a2c321ab2b8f7b798881af7c",
            "perms" : "lecturer",
            "name" : "external institution A",
            "consultation" : "",
            "city" : ""
         }
      ]
   },
   "pagination" : {
      "limit" : 20,
      "total" : 2,
      "offset" : 0
   }
}

```


##### `GET` /user/:user_id/news

Read announcements of a user.

###### Parameters


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id |String, 32 characters | The user ID of the user


###### Response format

```json

{
   "collection" : {
      "/api.php/news/29f2932ce32be989022c6f43b866e744" : {
         "chdate_uid" : "",
         "news_id" : "29f2932ce32be989022c6f43b866e744",
         "chdate" : "1476445862",
         "mkdate" : "1476445862",
         "date" : "1476445862",
         "expire" : "14562502",
         "comments_count" : 1,
         "body_html" : "<div class=\"formatted-content\">The Stud.IP team welcomes you. <br>Please feel free to look around!<br><br>If you have installed the system yourself and see this news, you have added the demonstration data to the database. If you want to work productively with the system, you should delete this data later, as the passwords of the accounts (especially the root account) are publicly known.</div>",
         "allow_comments" : "1",
         "topic" : "Welcome!",
         "user_id" : "76ed43ef286fb55cf9e41beadb484a9f",
         "comments" : "/api.php/news/29f2932ce32be989022c6f43b866e744/comments",
         "ranges" : [
            "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f/news",
            "/api.php/studip/news"
         ],
         "body" : "The Stud.IP team welcomes you. \r\nPlease feel free to look around!\r\n\r\nIf you have installed the system yourself and see this news, you have added the demonstration data to the database. If you want to work productively with the system, you should delete this data later, as the passwords of the accounts (especially the root account) are publicly known."
      }
   },
   "pagination" : {
      "offset" : 0,
      "total" : 1,
      "limit" : 20
   }
}

```


##### `POST` /user/:user_id/news

Creates a new announcement from the user. This route behaves like the `POST` route /studip/news.

###### Parameters


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id | String, 32 characters | The user ID of the user


| **`POST`-Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| topic | String | The title of the announcement
| body | String | The content of the announcement
| expire | Integer | Expiration date of the message (calculated in seconds from the current date)
| allow_comments | Integer | Specifies whether comments are allowed: 1 = allowed, 0 = not allowed


##### `GET` /user/:user_id/schedule and `GET` /user/:user_id/schedule/:semester_id

Reads the user's weekly timetable from the current semester. If, on the other hand, the timetable for a specific semester is to be read, the route /user/:user_id/schedule/:semester_id is used.

This route does not return a "collection" list, but a JSON object that has the attributes 1-7. These each refer to an array that contains the timetable entries.

###### Parameter


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| user_id | String, 32 characters | The user ID of the user


#### Course - Data on an event

##### `GET` /course/:course_id

An event object is returned which contains all the basic data for an event. Comparable to the call of Course::find() in the Stud.IP system.

###### Parameter


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course

###### Response format

```json

{
   "start_semester" : "/api.php/semester/f2b4fdf5ac59a9cb57dd73c4d3bbb651",
   "number" : "12345",
   "lecturers" : {
      "/api.php/user/205f3efb7997a0fc9755da2b535038da" : {
         "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
         "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
         "name" : {
            "formatted" : "Testaccount Lecturer",
            "username" : "test_dozent",
            "given" : "Testaccount",
            "family" : "Lecturer",
            "prefix" : "",
            "suffix" : ""
         },
         "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
         "id" : "205f3efb7997a0fc9755da2b535038da",
         "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
         "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da"
      }
   },
   "members" : {
      "tutor" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/members?status=tutor",
      "dozent_count" : 1,
      "tutor_count" : 1,
      "lecturer" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/members?status=dozent",
      "user" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/members?status=user",
      "author_count" : 1,
      "user_count" : 0,
      "author" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/members?status=author"
   },
   "modules" : {
      "forum" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/forum_categories",
      "documents" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/files",
      "wiki" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/wiki"
   },
   "type" : "1",
   "location" : "",
   "title" : "Test course",
   "end_semester" : "/api.php/semester/f2b4fdf5ac59a9cb57dd73c4d3bbb651",
   "description" : "",
   "subtitle" : "a normal course",
   "course_id" : "a07535cf2f8a72df33c12ddfa4b53dde"
}

```


##### `GET` /course/:course_id/blubber

A list of blubber objects is returned.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course

###### Response format

```json
{
   "pagination" : {
      "limit" : 20,
      "offset" : 0,
      "total" : 1
   },
   "collection" : {
      "/api.php/blubber/posting/6b7ff409eeaa73cb8927ef27ac623f6d" : {
         "comments_count" : "1",
         "reshares" : [],
         "author" : {
            "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
            "id" : "205f3efb7997a0fc9755da2b535038da",
            "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da",
            "name" : {
               "family" : "Lecturer",
               "given" : "Testaccount",
               "formatted" : "Testaccount Lecturer",
               "username" : "test_dozent",
               "prefix" : "",
               "suffix" : ""
            },
            "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
            "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
            "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962"
         },
         "blubber_id" : "6b7ff409eeaa73cb8927ef27ac623f6d",
         "tags" : [],
         "chdate" : "1478853090",
         "root_id" : "6b7ff409eeaa73cb8927ef27ac623f6d",
         "mkdate" : "1478853090",
         "comments" : "/api.php/blubber/posting/6b7ff409eeaa73cb8927ef27ac623f6d/comments",
         "content_html" : "<div class=\"formatted-content\">There is a test blubber in this event</div>",
         "context_type" : "course",
         "course_id" : "a07535cf2f8a72df33c12ddfa4b53dde",
         "content" : "There is a test bubble in this course"
      }
   }
}

```


##### `POST` /course/:course_id/blubber

A new blubber is added to the course. This route behaves in the same way as the /user/:user_id/blubber route.


**Parameters** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course


| **`POST`-Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| content | String | The content of the blubber (`POST` parameter)


##### `GET` /course/:course_id/events

Returns a list of calendar entries for an event.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the event

###### Response format

```json

{
   "collection" : [
      {
         "event_id" : "b99e85888891b41e1e814b7934a6791a",
         "start" : "1460358000",
         "title" : "Mon , 11.04.2016 09:00 - 12:00",
         "description" : "",
         "categories" : "Session",
         "end" : "1460368800",
         "room" : "Room: Lecture theatre 1",
         "canceled" : false,
         "deleted" : null
      },
      {
         "deleted" : null,
         "canceled" : false,
         "categories" : "session",
         "end" : "1461236400",
         "room" : "Room: Seminar room 1",
         "description" : "",
         "title" : "Thu , 21.04.2016 09:00 - 13:00",
         "event_id" : "86abb6bfe25cc2ac254b91474c95549f",
         "start" : "1461222000"
      },
      {
         "deleted" : null,
         "canceled" : false,
         "room" : "Room: Lecture theatre 1",
         "end" : "1461578400",
         "categories" : "Session",
         "description" : "",
         "title" : "Mon , 25.04.2016 09:00 - 12:00",
         "start" : "1461567600",
         "event_id" : "61eb9961107feca471b5db61bce1a76c"
      },
      {
         "description" : "",
         "title" : "Thu , 05.05.2016 09:00 - 13:00",
         "event_id" : "d08073764f751b3c54db4337a7113e77",
         "start" : "1462431600",
         "deleted" : true,
         "canceled" : "Ascension",
         "categories" : "session",
         "room" : "(Ascension)",
         "end" : "1462446000"
      },
      {
         "description" : "",
         "title" : "Mon , 09.05.2016 09:00 - 12:00",
         "event_id" : "6061c46538054821deb1031d70997ee9",
         "start" : "1462777200",
         "deleted" : null,
         "canceled" : false,
         "categories" : "session",
         "end" : "1462788000",
         "room" : "Room: Lecture theatre 1"
      },
      {
         "title" : "Thu , 19.05.2016 09:00 - 13:00",
         "event_id" : "b6b3767d913faad2bbb44135a6321295",
         "start" : "1463641200",
         "description" : "",
         "canceled" : false,
         "categories" : "session",
         "room" : "Room: Seminar room 1",
         "end" : "1463655600",
         "deleted" : null
      },
      {
         "event_id" : "d0d018a4aed955ecf85e01e07d81147a",
         "start" : "1463986800",
         "title" : "Mon , 23.05.2016 09:00 - 12:00",
         "description" : "",
         "categories" : "Session",
         "room" : "Room: Lecture theatre 1",
         "end" : "1463997600",
         "canceled" : false,
         "deleted" : null
      },
      {
         "deleted" : null,
         "canceled" : false,
         "categories" : "Session",
         "room" : "Room: Seminar room 1",
         "end" : "1464865200",
         "description" : "",
         "title" : "Thu , 02.06.2016 09:00 - 13:00",
         "event_id" : "12a11ff3f258ab706e77f85d7f1f28f9",
         "start" : "1464850800"
      },
      {
         "deleted" : null,
         "canceled" : false,
         "categories" : "session",
         "end" : "1465207200",
         "room" : "Room: Lecture theatre 1",
         "description" : "",
         "title" : "Mon , 06.06.2016 09:00 - 12:00",
         "event_id" : "124609f704bff3379705bb8a8fce3ae1",
         "start" : "1465196400"
      },
      {
         "categories" : "session",
         "room" : "Room: Seminar room 1",
         "end" : "1466074800",
         "canceled" : false,
         "deleted" : null,
         "event_id" : "4be63f71cf1d08b87b7d731c3a28a572",
         "start" : "1466060400",
         "title" : "Thu , 16/06/2016 09:00 - 13:00",
         "description" : ""
      },
      {
         "description" : "",
         "event_id" : "98a296243626ea32243fb3507af38bce",
         "start" : "1466406000",
         "title" : "Mon , 20 Jun 2016 09:00 - 12:00",
         "deleted" : null,
         "categories" : "Session",
         "room" : "Room: Lecture theatre 1",
         "end" : "1466416800",
         "canceled" : false
      },
      {
         "title" : "Thu , 30 Jun 2016 09:00 - 13:00",
         "start" : "1467270000",
         "event_id" : "cedcf924d509a3c1acc8fcde47ab198b",
         "description" : "",
         "canceled" : false,
         "end" : "1467284400",
         "room" : "Room: Seminar room 1",
         "categories" : "Session",
         "deleted" : null
      },
      {
         "deleted" : null,
         "canceled" : false,
         "categories" : "Session",
         "room" : "Room: Lecture theatre 1",
         "end" : "1467626400",
         "description" : "",
         "title" : "Mon , 04.07.2016 09:00 - 12:00",
         "event_id" : "341e8520fe69a90fb437cb9e0ba3368c",
         "start" : "1467615600"
      },
      {
         "title" : "Thu , 14 Jul 2016 09:00 - 13:00",
         "event_id" : "bab7532584170e5aaf44eb217b1d19e0",
         "start" : "1468479600",
         "description" : "",
         "canceled" : false,
         "categories" : "session",
         "room" : "no room specified",
         "end" : "1468494000",
         "deleted" : null
      }
   ],
   "pagination" : {
      "limit" : 20,
      "offset" : 0,
      "total" : 14
   }
}

```


##### `GET` /course/:course_id/files

Returns a list of files and folders of a course.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course

###### Response format

```json

{
   "collection" : {
      "/api.php/file/ad8dc6a6162fb0fe022af4a62a15e309" : {
         "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde",
         "permissions" : [
            "visible",
            "writable",
            "readable"
         ],
         "chdate" : 1343924877,
         "mkdate" : 1343924873,
         "author" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
         "name" : "Homework",
         "folder_id" : "ad8dc6a6162fb0fe022af4a62a15e309",
         "range_id" : "373a72966cf45c484b4b0b07dba69a64",
         "documents" : [],
         "description" : ""
      },
      "/api.php/file/ca002fbae136b07e4df29e0136e3bd32" : {
         "chdate" : 1343924894,
         "mkdate" : 1343924407,
         "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde",
         "permissions" : [
            "visible",
            "readable",
            "extendable"
         ],
         "name" : "General file folder",
         "author" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
         "folder_id" : "ca002fbae136b07e4df29e0136e3bd32",
         "description" : "File for general folders and documents of the event",
         "documents" : {
            "/api.php/file/6b606bd3d6d6cda829200385fa79fcbf" : {
               "filesize" : 314146,
               "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde",
               "filename" : "mappe_studip-el.pdf",
               "chdate" : 1343924841,
               "mkdate" : 1343924827,
               "file_id" : "6b606bd3d6d6cda829200385fa79fcbf",
               "author" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
               "name" : "Stud.IP product brochures in PDF format",
               "downloads" : 0,
               "protected" : false,
               "range_id" : "ca002fbae136b07e4df29e0136e3bd32",
               "content" : "/api.php/file/6b606bd3d6d6cda829200385fa79fcbf/content",
               "description" : ""
            }
         },
         "range_id" : "a07535cf2f8a72df33c12ddfa4b53dde"
      },
      "/api.php/file/df122112a21812ff4ffcf1965cb48fc3" : {
         "range_id" : "2f597139a049a768dbf8345a0a0af3de",
         "documents" : [],
         "description" : "Storage for folders and documents in this group",
         "folder_id" : "df122112a21812ff4ffcf1965cb48fc3",
         "name" : "File folder of the group: Students",
         "author" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
         "chdate" : 1343924860,
         "mkdate" : 1343924860,
         "permissions" : [
            "visible",
            "writable",
            "readable",
            "extendable"
         ],
         "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde"
      }
   },
   "pagination" : {
      "limit" : 20,
      "total" : 3,
      "offset" : 0
   }
}

```


##### `GET` /course/:course_id/forum_categories

Returns the list of forum categories for a course. To access the individual forum posts, the route /forum_category/:category_id/areas must be called.


**Parameters** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course

###### Response format

```json

{
   "pagination" : {
      "offset" : 0,
      "limit" : 20,
      "total" : 1
   },
   "collection" : {
      "/api.php/forum_category/a07535cf2f8a72df33c12ddfa4b53dde" : {
         "entry_name" : "General",
         "pos" : "0",
         "areas_count" : 1,
         "seminar_id" : "a07535cf2f8a72df33c12ddfa4b53dde",
         "areas" : "/api.php/forum_category/a07535cf2f8a72df33c12ddfa4b53dde/areas",
         "category_id" : "a07535cf2f8a72df33c12ddfa4b53dde",
         "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde"
      }
   }
}

```


##### `POST` /course/:course_id/forum_categories

A new forum area is created. The `POST` request requires the parameter "name", which specifies the name of the new category.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course


| **`POST`-Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| name | String | The name of the new forum category (`POST` parameter)


##### `GET` /course/:course_id/members

Returns the participants of an event. The participants can be filtered according to their status using the "status" parameter. The "status" parameter may only consist of one of the following words: "user", "author", "tutor", "lecturer".


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course

###### Response format

```json

{
   "pagination" : {
      "offset" : 0,
      "limit" : 20,
      "total" : 3
   },
   "collection" : {
      "/api.php/user/205f3efb7997a0fc9755da2b535038da" : {
         "member" : {
            "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da",
            "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
            "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
            "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
            "id" : "205f3efb7997a0fc9755da2b535038da",
            "name" : {
               "given" : "Testaccount",
               "family" : "Lecturer",
               "suffix" : "",
               "username" : "test_dozent",
               "prefix" : "",
               "formatted" : "Testaccount Lecturer"
            },
            "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0"
         },
         "status" : "lecturer"
      },
      "/api.php/user/e7a0a84b161f3e8c09b4a0a2e8a58147" : {
         "status" : "author",
         "member" : {
            "href" : "/api.php/user/e7a0a84b161f3e8c09b4a0a2e8a58147",
            "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
            "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
            "id" : "e7a0a84b161f3e8c09b4a0a2e8a58147",
            "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
            "name" : {
               "given" : "Testaccount",
               "suffix" : "",
               "family" : "author",
               "username" : "test_author",
               "prefix" : "",
               "formatted" : "Testaccount author"
            },
            "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0"
         }
      },
      "/api.php/user/7e81ec247c151c02ffd479511e24cc03" : {
         "member" : {
            "name" : {
               "given" : "testaccount",
               "username" : "test_tutor",
               "suffix" : "",
               "family" : "Tutor",
               "prefix" : "",
               "formatted" : "Testaccount Tutor"
            },
            "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
            "id" : "7e81ec247c151c02ffd479511e24cc03",
            "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
            "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
            "href" : "/api.php/user/7e81ec247c151c02ffd479511e24cc03",
            "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962"
         },
         "status" : "tutor"
      }
   }
}

```


##### `GET` /course/:course_id/news

Returns the announcements of a course.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course

###### Response format

```json

{
   "collection" : {
      "/api.php/news/d8d92fb84dbb1150f09199f923c54aa8" : {
         "expire" : "691140",
         "chdate_uid" : "",
         "topic" : "Cancellation of the event",
         "ranges" : [
            "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/news"
         ],
         "user_id" : "205f3efb7997a0fc9755da2b535038da",
         "date" : "1478818800",
         "chdate" : "1478853886",
         "mkdate" : "1478853886",
         "news_id" : "d8d92fb84dbb1150f09199f923c54aa8",
         "body" : "The event is cancelled on 11.11. due to the start of the "5th season"!",
         "body_html" : "<div class=\"formatted-content\">The event is cancelled on 11.11. due to the start of the 5th season!"</div>",
         "allow_comments" : "0"
      }
   },
   "pagination" : {
      "limit" : 20,
      "offset" : 0,
      "total" : 1
   }
}

```


##### `POST` /course/:course_id/news

Creates a new announcement in the course. This route behaves like the `POST` route /studip/news.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course


| **`POST`-Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| topic | String | The title of the announcement
| body | String | The content of the announcement
| expire | Integer | Expiration date of the message (calculated in seconds from the current date)
| allow_comments | Integer | Specifies whether comments are allowed: 1 = allowed, 0 = not allowed


##### `GET` /course/:course_id/wiki

Returns the list of all pages of the wiki for a course, with the most recent version of each page being returned. If there are no pages, the list of pages consists only of the main page.


**Parameters** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course

###### Response format

```json

{
   "pagination" : {
      "limit" : 20,
      "total" : 3,
      "offset" : 0
   },
   "collection" : {
      "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/wiki/further-page" : {
         "version" : "1",
         "keyword" : "More page",
         "range_id" : "a07535cf2f8a72df33c12ddfa4b53dde",
         "chdate" : "1478854048",
         "user" : {
            "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
            "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
            "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
            "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
            "name" : {
               "family" : "Lecturer",
               "prefix" : "",
               "formatted" : "Testaccount Lecturer",
               "username" : "test_dozent",
               "suffix" : "",
               "given" : "Testaccount"
            },
            "id" : "205f3efb7997a0fc9755da2b535038da",
            "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da"
         }
      },
      "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/wiki/WikiWikiWeb" : {
         "keyword" : "WikiWikiWeb",
         "version" : "1",
         "user" : {
            "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
            "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
            "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
            "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
            "id" : "205f3efb7997a0fc9755da2b535038da",
            "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da",
            "name" : {
               "family" : "lecturer",
               "username" : "test_dozent",
               "formatted" : "Testaccount Lecturer",
               "prefix" : "",
               "suffix" : "",
               "given" : "Testaccount"
            }
         },
         "chdate" : "1478854031",
         "range_id" : "a07535cf2f8a72df33c12ddfa4b53dde"
      },
      "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde/wiki/WikiWikWeb" : {
         "keyword" : "WikiWikWeb",
         "version" : 0,
         "chdate" : null,
         "range_id" : "a07535cf2f8a72df33c12ddfa4b53dde"
      }
   }
}

```


##### `GET` /course/:course_id/wiki/:page_name

Returns a wiki page with the specified page name.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course
| page_name | String | The name of the wiki page

###### Response format

```json

{
   "content_html" : "<div class=\"formatted-content\">This is another wiki page.</div>",
   "chdate" : "1478854048",
   "version" : "1",
   "keyword" : "Another page",
   "user" : {
      "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
      "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
      "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
      "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
      "id" : "205f3efb7997a0fc9755da2b535038da",
      "name" : {
         "family" : "Lecturer",
         "given" : "Testaccount",
         "prefix" : "",
         "suffix" : "",
         "formatted" : "Testaccount Lecturer",
         "username" : "test_dozent"
      },
      "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da"
   },
   "content" : "This is another wiki page.",
   "range_id" : "a07535cf2f8a72df33c12ddfa4b53dde"
}

```



##### `PUT` /course/:course_id/wiki/:page_name

Updates an existing wiki page or creates a new one. The parameter "content" must be set and contain the content of the wiki page.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course
| page_name | String | The name of the wiki page

**`PUT`-Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| content | String | The new content of the wiki page


##### `GET` /course/:course_id/wiki/:page_name/:version

Returns a specific version of a wiki page.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| course_id | String, 32 characters | The ID of the course
| page_name | String | The name of the wiki page

###### Response format

```json

{
   "range_id" : "a07535cf2f8a72df33c12ddfa4b53dde",
   "content" : "This is another wiki page that has been changed several times!",
   "version" : "1",
   "content_html" : "<div class=\"formatted-content\">This is another wiki page that has been changed several times!</div>",
   "chdate" : "1478854411",
   "keyword" : "Another page",
   "user" : {
      "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
      "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
      "name" : {
         "prefix" : "",
         "suffix" : "",
         "family" : "lecturer",
         "username" : "test_dozent",
         "given" : "Testaccount",
         "formatted" : "Testaccount Lecturer"
      },
      "id" : "205f3efb7997a0fc9755da2b535038da",
      "href" : "/api.php/user/205f3efb7997a0fc9755da2b535038da",
      "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
      "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962"
   }
}

```


#### Blubber

In addition to the methods for fetching all blubbers of a user or an event, there are the following REST routes. As blubber comments are also blubber objects, all routes that are intended for blubber objects can also be used for blubber comments.


##### `POST` /blubber/postings

Creates a new blubber. Parameters must be specified so that a new blubber can be created. The "content" parameter is used to set the content of the blubber. The "context_type" is used to specify whether the blubber is public (public), private (private) or belongs to an event (course).

If the blubber belongs to an event, the course_id parameter must be set and this must contain an event ID.

If the blubber is private, a list of user IDs of the users who are authorised to see the blubber must be passed via the "private_adresses" parameter. Please note that users who are referenced in the blubber text with an @-reference can automatically see the blubber.


**`POST`-Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| content | String | The content of the blubber
| context_type | String | The context in which a blubber is created: "course", "private", "public"
| course_id | String, 32 characters | The event ID (only required if context_type = course is set)
| private_adresses | Array | A list of user IDs of the users who are allowed to see this bubble (only required if context_type = private)


##### `GET` /blubber/stream/:stream_id

Returns a list of blubbers in a specific blubber stream.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| stream_id | String, 32 characters | The ID of the blubber stream


##### `GET` /blubber/posting/:blubber_id/comments

Returns a list of comments ("reply blubber") for a blubber.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| blubber_id | String, 32 characters | The ID of the blubber

###### Response format

```json

{
   "collection" : {
      "/api.php/blubber/comment/8340654c6fce2441e967ae3a9bf350eb" : {
         "root_id" : "ecab929ef0dfaeca159802c018826a25",
         "mkdate" : "1478854991",
         "content" : "Test2",
         "content_html" : "<div class=\"formatted-content\">Test2</div>",
         "chdate" : "1478854991",
         "author" : {
            "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
            "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
            "id" : "76ed43ef286fb55cf9e41beadb484a9f",
            "name" : {
               "given" : "Root",
               "suffix" : "",
               "prefix" : "",
               "formatted" : "Root Studip",
               "username" : "root@studip",
               "family" : "Studip"
            },
            "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962",
            "href" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
            "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962"
         },
         "blubber_id" : "8340654c6fce2441e967ae3a9bf350eb",
         "context_type" : "public"
      }
   },
   "pagination" : {
      "total" : 1,
      "limit" : 20,
      "offset" : 0
   }
}

```


##### `POST` /blubber/posting/:blubber_id/comments

Creates a new comment for a blubber. The content of the comment is set using the "content" parameter.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| blubber_id | String, 32 characters | The ID of the blubber


| **`POST`-Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| content | String | The content of the blubber


##### `GET` /blubber/posting/:blubber_id and `GET` /blubber/comment/:blubber_id

Returns data for a blubber or a comment. The returned JSON object has the same structure in both cases.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| blubber_id | String, 32 characters | The ID of the blubber

###### Response format

```json

{
   "mkdate" : "1478854991",
   "chdate" : "1478854991",
   "blubber_id" : "8340654c6fce2441e967ae3a9bf350eb",
   "content_html" : "<div class=\"formatted-content\">Test2</div>",
   "content" : "Test2",
   "author" : {
      "href" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
      "avatar_original" : "https://studip.example.org/pictures/user/nobody_original.png?d=0",
      "avatar_normal" : "https://studip.example.org/pictures/user/nobody_normal.png?d=1476444962",
      "id" : "76ed43ef286fb55cf9e41beadb484a9f",
      "avatar_small" : "https://studip.example.org/pictures/user/nobody_small.png?d=1476444962",
      "name" : {
         "username" : "root@studip",
         "suffix" : "",
         "formatted" : "Root Studip",
         "family" : "Studip",
         "given" : "Root",
         "prefix" : ""
      },
      "avatar_medium" : "https://studip.example.org/pictures/user/nobody_medium.png?d=1476444962"
   },
   "context_type" : "public",
   "root_id" : "ecab929ef0dfaeca159802c018826a25"
}

```


##### `PUT` /blubber/posting/:blubber_id and `PUT` /blubber/comment/:blubber_id

Edits a blubber. The content of the blubber can be changed using the "content" parameter.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| blubber_id | String, 32 characters | The ID of the blubber


| **`PUT`-Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| content | String | The new content of the blubber or blubber comment


##### `DELETE` /blubber/posting/:blubber_id and `DELETE` /blubber/comment/:blubber_id

Deletes a blubber.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| blubber_id | String, 32 characters | The ID of the blubber


#### File - Files and folders

##### `GET` /file/:file_id

Returns the metadata of a file or folder.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| file_id | String, 32 characters | The ID of the file or folder


###### Response format

```json

{
   "file_id" : "6b606bd3d6d6cda829200385fa79fcbf",
   "chdate" : 1343924841,
   "description" : "",
   "range_id" : "ca002fbae136b07e4df29e0136e3bd32",
   "protected" : false,
   "content" : "/api.php/file/6b606bd3d6d6cda829200385fa79fcbf/content",
   "mkdate" : 1343924827,
   "downloads" : 0,
   "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde",
   "author" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
   "filesize" : 314146,
   "filename" : "mappe_studip-el.pdf",
   "name" : "Stud.IP product brochure in PDF format"
}

```


##### `GET` /file/:file_id/content

Returns the content of a file as binary data.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| file_id | String, 32 characters | The ID of the file

###### Response format

The requested file.


##### `DELETE` /file/:file_id

Deletes the file or folder.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| file_id | String, 32 characters | The ID of the file or folder


##### `PUT` /file/:file_id

Updates the file or folder. Either only the metadata can be changed using the "name" or "description" parameters (see below) or a new version of the file can be appended for files. Please note that the request must be executed as a multipart request.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| file_id | String, 32 characters | The ID of the file or folder


##### `POST` /file/:folder_id

Creates a file or folder.

The "name" parameter is mandatory for a new folder and specifies its name. For files, this parameter can be used to overwrite the file name. An optional description can be supplied via the "description" parameter.

If a file is to be created, it must be attached as a multipart request, otherwise a folder will be created.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| folder_id | String, 32 characters | The ID of the folder in which a new file or folder is to be created


| **`POST` parameter** | **format** | **description**
| ---- | ---- | ---- |
| name | String | Name of the file or folder
| (file) | binary | data of the file as multipart request


#### Forum

A forum of an event or an organisation is divided into several categories, which have forum posts. Forum posts are organised in a tree structure. At the top level are the sections, e.g. "General discussion". Below this, the actual topics can be found as child elements of the "Area post".

##### `GET` /forum_category/:category_id

Read out a category of a forum.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| category_id | String, 32 characters | The ID of the forum category

###### Response format

```json

{
   "areas_count" : 1,
   "entry_name" : "General",
   "seminar_id" : "a07535cf2f8a72df33c12ddfa4b53dde",
   "areas" : "/api.php/forum_category/a07535cf2f8a72df33c12ddfa4b53dde/areas",
   "category_id" : "a07535cf2f8a72df33c12ddfa4b53dde",
   "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde",
   "name" : "General",
   "pos" : "0"
}

```


##### `PUT` /forum_category/:category_id

Update a forum category. The name of the category can be changed using the "name" parameter.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| category_id | String, 32 characters | The ID of the forum category


| **`PUT`-Parameter | **Format** | **Description**
| ---- | ---- | ---- |
| name | String | The new name of the category


##### `DELETE` /forum_category/:category_id

Deletes a category of a forum.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| category_id | String, 32 characters | The ID of the forum category


##### `GET` /forum_category/:category_id/areas

Returns a list of forum posts in a forum category. Only the most necessary forum post data is returned. To obtain more data on a forum post, the REST route /forum_entry/:entry_id should be called (see below).


**Parameters** | **Format** | **Description**
| ---- | ---- | ---- |
| category_id | String, 32 characters | The ID of the forum category


###### Response format

```json

{
   "collection" : {
      "/api.php/forum_entry/fa431efbfa909ed48fbae10fef316222" : {
         "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde",
         "depth" : "1",
         "content" : "Here is space for general discussion",
         "topic_id" : "fa431efbfa909ed48fbae10fef316222",
         "subject" : "General discussion",
         "content_html" : "<div class=\"formatted-content\">Here is space for general discussions</div>",
         "user" : "/api.php/user/",
         "anonymous" : "0",
         "mkdate" : "1477315889",
         "chdate" : "1477315889"
      }
   },
   "pagination" : {
      "total" : 1,
      "offset" : 0,
      "limit" : 20
   }
}

```


##### `POST` /forum_category/:category_id/areas

Creates a new forum post. The "subject" parameter specifies the topic of the post. The content of the post is set via the "content" parameter. The optional parameter "anonymous" can be used to set whether the post should be created anonymously. To do this, "anonymous" must be set to 1.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| category_id | string, 32 characters | The ID of the forum category


| **`POST` parameter | **format** | **description**
| ---- | ---- | ---- |
| subject | String | The subject of the forum post
| content | String | the content of the forum post
| anonymous | Integer | Specifies whether the entry should be made anonymous or not: 1 = anonymous, 0 = not anonymous


##### `GET` /forum_entry/:entry_id

Returns data on a forum entry, including its content.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| entry_id | String, 32 characters | The ID of the forum post

###### Response format

```json

{
   "topic_id" : "fa431efbfa909ed48fbae10fef316222",
   "user" : "/api.php/user/",
   "chdate" : "1477315889",
   "subject" : "General discussion",
   "content_html" : "<div class=\"formatted-content\">Here is space for general discussions</div>",
   "children" : [
      {
         "content_html" : "<div class=\"formatted-content\"><div>Test content of the test topic.</div></div>",
         "subject" : "<div class=\"formatted-content\">Test topic</div>",
         "chdate" : "1477316401",
         "user" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
         "topic_id" : "d4dc64e82387ad5df7d388377e70a54b",
         "anonymous" : "0",
         "depth" : "2",
         "mkdate" : "1477316401",
         "content" : "<div class=\"formatted-content\">Test content of the test topic.</div>",
         "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde"
      }
   ],
   "content" : "Here is space for general discussion",
   "course" : "/api.php/course/a07535cf2f8a72df33c12ddfa4b53dde",
   "mkdate" : "1477315889",
   "depth" : "1",
   "anonymous" : "0"
}

```


##### `PUT` /forum_entry/:entry_id

Updates a forum entry. The parameter "subject" sets the title, the parameter "content" sets the content.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| entry_id | String, 32 characters | The ID of the forum post


| **`PUT`-Parameter | **Format** | **Description**
| ---- | ---- | ---- |
| subject | String | The subject of the forum post
| content | String | The content of the forum post


##### `DELETE` /forum_entry/:entry_id

Deletes a forum entry.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| entry_id | String, 32 characters | The ID of the forum post


##### `POST` /forum_entry/:entry_id

Adds a new forum post (a new reply) to a forum post.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| entry_id | string, 32 characters | The ID of the forum post


| **`POST` parameter | **format** | **description**
| ---- | ---- | ---- |
| subject | String | The subject of the forum post
| content | String | the content of the forum post
| anonymous | Integer | Specifies whether the post should be displayed anonymously or not: 1 = anonymous, 0 = not anonymous


#### Messages

##### `POST` /messages

Writes a new message. The "subject" parameter sets the title, while the "message" parameter sets the content of the message. The recipients of the message must also be specified. This is done using the "recipients" parameter, which is passed the user IDs of the recipients.


**`POST` parameter | **Format** | **Description**
| ---- | ---- | ---- |
| subject | String | The subject of the message
| message | String | The content of the message
| recipients | array | The user IDs of the recipients of the message


##### `GET` /message/:message_id

Returns a message.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| message_id | String, 32 characters | The ID of the message


##### `PUT` /message/:message_id

This route can be used to mark a message as unread or read or to move it to another message folder.

To mark the message as unread, the "unread" parameter must be set to 1. In the opposite case, the parameter is set to 0.

To move the message to another message folder, the user ID, the ID of the folder and the message area must be specified so that a path with the following structure must be specified:


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| message_id | String, 32 characters | The ID of the message


| **`PUT` parameter | **format** | **description**
| ---- | ---- | ---- |
| unread | Integer | Specifies whether the message should be marked as unread (1) or read (0)

The parameters can be sent via a JSON object in the body with the `Content-Type: application/json` set or as with `POST` requests. The parameter cannot be set explicitly via `GET` parameters.

##### `DELETE` /message/:message_id

Deletes a message.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| message_id | String, 32 characters | The ID of the message


#### Semester

##### `GET` /semesters

Returns a list of all semesters.

###### Response format

```json

{
   "pagination" : {
      "offset" : 0,
      "limit" : 20,
      "total" : 2
   },
   "collection" : {
      "/api.php/semester/eb828ebb81bb946fac4108521a3b4697" : {
         "begin" : 1475272800,
         "end" : 1490997599,
         "seminars_end" : 1486162799,
         "title" : "WS 2016/17",
         "description" : "",
         "id" : "eb828ebbb81bb946fac4108521a3b4697",
         "seminars_begin" : 1476655200
      },
      "/api.php/semester/f2b4fdf5ac59a9cb57dd73c4d3bbb651" : {
         "description" : "",
         "title" : "SS 2016",
         "seminars_begin" : 1460325600,
         "id" : "f2b4fdf5ac59a9cb57dd73c4d3bbb651",
         "seminars_end" : 1468619999,
         "end" : 1475272799,
         "begin" : 1459461600
      }
   }
}

```


##### `GET` /semester/:semester_id

Returns a single semester. This REST route returns the same amount of data for a semester as calling the /semesters route.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| semester_id | String, 32 characters | The ID of the semester

###### Response format

```json

{
   "begin" : 1459461600,
   "title" : "SS 2016",
   "seminars_begin" : 1460325600,
   "seminars_end" : 1468619999,
   "end" : 1475272799,
   "id" : "f2b4fdf5ac59a9cb57dd73c4d3bbb651",
   "description" : ""
}

```


#### Announcements

The routes to announcements of a user, a course or the Stud.IP system are described in the respective sections above this section.

##### `GET` /news/:news_id

Returns an announcement.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| news_id | String, 32 characters | The ID of the announcement

###### Response format

```json

{
   "news_id" : "29f2932ce32be989022c6f43b866e744",
   "chdate" : "1476445862",
   "comments" : "/api.php/news/29f2932ce32be989022c6f43b866e744/comments",
   "expire" : "14562502",
   "mkdate" : "1476445862",
   "topic" : "Welcome!",
   "chdate_uid" : "",
   "allow_comments" : "1",
   "comments_count" : 1,
   "user_id" : "76ed43ef286fb55cf9e41beadb484a9f",
   "ranges" : [
      "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f/news",
      "/api.php/studip/news"
   ],
   "body" : "The Stud.IP team welcomes you. \r\nPlease feel free to look around!\r\n\r\nIf you have installed the system yourself and see this news, you have added the demonstration data to the database. If you want to work productively with the system, you should delete this data later, as the passwords of the accounts (especially the root account) are publicly known.",
   "body_html" : "<div class=\"formatted-content\">The Stud.IP team welcomes you. <br>Please feel free to look around!<br><br>If you have installed the system yourself and see this news, you have added the demonstration data to the database. If you want to work productively with the system, you should delete this data later, as the passwords of the accounts (especially the root account) are publicly known.</div>",
   "date" : "1476445862"
}

```


##### `PUT` /news/:news_id

Updates an announcement. The "topic" parameter can be used to set the title of the announcement, while the "body" parameter can be used to change the content of the announcement.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| news_id | String, 32 characters | The ID of the announcement


| **`PUT` parameter | **format** | **description**
| ---- | ---- | ---- |
| topic | String | The title of the announcement
| body | String | The content of the announcement
| expire | Integer | Expiration date of the message (calculated in seconds from the current date)
| allow_comments | Integer | Specifies whether comments are allowed: 1 = allowed, 0 = not allowed


##### `DELETE` /news/:news_id

Deletes an announcement.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| news_id | String, 32 characters | The ID of the announcement


##### `GET` /news/:news_id/comments

Returns a list of comments for an announcement.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| news_id | String, 32 characters | The ID of the announcement

###### Response format

```json

{
   "collection" : {
      "/api.php/comment/a97b1b623d92f11293d4a8de52724087" : {
         "chdate" : "1477320607",
         "comment_id" : "a97b1b623d92f11293d4a8de52724087",
         "object_id" : "29f2932ce32be989022c6f43b866e744",
         "content" : "Test comment",
         "content_html" : "Test comment",
         "mkdate" : "1477320607",
         "user_id" : "76ed43ef286fb55cf9e41beadb484a9f"
      }
   },
   "pagination" : {
      "total" : 1,
      "limit" : 20,
      "offset" : 0
   }
}

```


##### `POST` /news/:news_id/comments

Creates a new comment for an announcement. The content of the comment is set via the "content" parameter.


**Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| news_id | String, 32 characters | The ID of the announcement


| **`POST` parameter | **format** | **description**
| ---- | ---- | ---- |
| content | String | The content of the comment


##### `GET` /comment/:comment_id

Reads a comment for an announcement.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| news_id | String, 32 characters | The ID of the comment

###### Response format

```json

{
   "content" : "Test comment",
   "comment_id" : "a97b1b623d92f11293d4a8de52724087",
   "content_html" : "<div class=\"formatted-content\">Test comment</div>",
   "chdate" : "1477320607",
   "author" : "/api.php/user/76ed43ef286fb55cf9e41beadb484a9f",
   "mkdate" : "1477320607",
   "news" : "/api.php/news/29f2932ce32be989022c6f43b866e744"
}

```


##### `DELETE` /comment/:comment_id

Deletes a comment on an announcement.


| **Parameter** | **Format** | **Description**
| ---- | ---- | ---- |
| news_id | String, 32 characters | The ID of the comment


### Extension of the REST API in the core

To implement new REST routes in the core, a class must be written that uses the namespace \RESTAPI\Routes and extends the class \RESTAPI\RouteMap. In the RouteMap class (lib/classes/restapi/RouteMap.php) there is a comprehensive description in the comments that explains how REST routes can be created.


The file containing the new class must be located under app/routes/, have the same name as the class it contains and its name must be entered in the Router class (lib/classes/restapi/Router.php) in the setupRoutes() method. This method contains the following line of code:

```php
$routes = words('Activity Blubber Contacts ...');
```

The name of the file with the new REST API class must be entered within the character string that is passed to words.


### Extension of the REST API with plugins

The new plugin class `RESTAPIPlugin` has been created so that the REST API can also be extended by plugins. Plugins of this type must implement a single method called `getRouteMaps()`, which returns all REST-API routes (called RouteMaps) that the plugin implements.

#### REST-API plugin structure

To provide routes, the plugin must contain a class that extends the RouteMap class. At least the `before` method must be implemented in this class. This method can be left empty for simple routes.

Methods must then be written for the routes to be implemented. One method can implement several routes. The comment above a method specifies the routes that lead to the method being called. Parameters and parameter conditions can also be defined in the comment. A description text for the route must also be included in the comment.

#### Outputting data

Data that is to be output via the API is simply returned by the method (via return). This can be simple strings, numbers or objects.

The `paginated` method should be used to send a list of objects via the API, as the following example shows:

```php
return $this->paginated($data, $total, $uriParameters);
```

$data contains the data to be sent, $total contains the number of data records (total data set) and $uriParameters contains the necessary parameters that are required when generating URIs for the current route. The parameters "offset" and "limit" are already handled internally by the RouteMap class and do not need to be specified separately.


#### Overwriting core routes

It is possible to overwrite existing core routes. However, it should always be ensured that the return corresponds to the actual format.

#### Example of a REST-API plugin

The following example shows a REST API plugin that implements its own API routes:

First the plugin class:

```php
<?php
/**
 * HelloAPIPlugin.class.php
 *
 * @author Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @author Moritz Strohm <strohm@data-quest.de>
 * @version 1.0
 */

require_once __DIR__ . '/HelloMap.class.php';

class HelloAPIPlugin extends StudIPPlugin implements RESTAPIPlugin
{
    public function getRouteMaps()
    {
        return new HelloMap();
    }
}
```

The plugin class simply returns an instance of the HelloMap class when the getRouteMaps method is called.

The HellpMap class looks like this:

```php
class HelloMap extends \RESTAPI\RouteMap
{
    // Called before the route is executed
    public function before() {}

    /**
     * Greets the caller
     *
     * @`GET` /hello
     * @`GET` /hello/:name
     * @condition name ^\w+$
     */
    public function sayHello($name = 'world')
    {
        return sprintf('Hello %s!', $name);
    }

    // Called after the route is executed
    public function after() {}
}
```

The plugin provides the routes /hello and /hello:name via the HelloMap class. Calling these two routes leads to the `sayHello` method being called. If the "name" parameter is set, the corresponding name is returned.
