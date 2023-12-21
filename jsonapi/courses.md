---
title: Events
---

:::info
Events are groups for seminars, lectures,
exercises etc. Materials are shared within events,
plugins are used, dates are set and much more. Many functions of
Stud.IP are only visible for a specific course.
:::

## Schema "courses"

### Attributes

Attribute | Description
------------- | ------------
course-number | ID of the course
title | title of the course
subtitle | subtitle of the course
course-type | Type of course (seminar, lecture...)
description | Description of the course
location | location of the course
miscellaneous | other


### Relations

 relation | description
---------------- | ------------
institute | The assigned institution
start-semester | Start semester of the course
end-semester | End semester of the course
files | Reference to files within the course
documents | Reference to documents within the course
document-folders | Folder for files within the course

## Schema "course-memberships"

Indicates participation in a course with your role.

### Attributes

Attribute | Description
------------- | ------------
permission | Role of the user (author, lecturer, etc...)
position | Position in the list of participants
group | order in the list of participants
mkdate | creation date
label | the "function" of the participant (see web interface)
notification | Do I receive an e-mail notification about new content in this event once a day?
comment | Participant comment for teachers
visible | Visibility in the course

The "visible" field is only visible to you or the teachers of the course.
the course.

### Relations

 Relation | Description
---------------- | ------------
course | The course for the participants
user | User of the event

### URL parameters

Parameter | Default | Description
--------- | ------- | ------------
page[offset] | 0 | the offset (see pagination)
page[limit] | 30 | the limit (see pagination)
filter[q] | - | a search term (at least 3 characters)
filter[fields] | all | in which fields to search
filter[semester] | all | in which semester to search

The parameter "filter[fields]" can have the following values: 'all', 'title_lecturer_number', 'title', 'sub_title', 'lecturer', 'number', 'comment', 'scope'.

## All events
This route can be used to read all events.

### HTTP Request
  `GET /courses`

### Parameters

This route does not require any parameters


### Authorization

Any logged in user can use this route.

### Parameter

Parameter | Description
--------- | -------
id | ID of the course

### Authorization

Every participant of the course can use this route.

```shell
curl --request GET \
    --url https://example.com/courses \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

## An event

Returns an event.

### HTTP Request
   `GET /courses/{id}`

### Parameter

Parameter | Description
--------- | -------
id | ID of the course

### Authorization

Any participant in the course or root can use this route.

   ```shell
   curl --request GET \
       --url https://example.com/courses/<course-id> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## All events of a user
Returns all courses of a user.

### HTTP Request

   `GET /users/{id}/courses`

### Parameter

Parameter | Description
--------- | -------
id | ID of the user

### Authorization

Every logged in user can use this route.

   ```shell
   curl --request GET \
       --url https://example.com/users/<user-id>/courses \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Participations of an event

Returns all courses with the respective participant status of a user.

### HTTP Request
   `GET /courses/{id}/memberships`

### Parameter

Parameter | Description
--------- | -------
id | ID of the course

### URL parameter

Parameter | Default | Description
--------- | ------- | ------------
filter[permission] | - | Role of the user in the course

### Authorization

Users with at least admin status or participants of the course can use this route.

   ```shell
   curl --request GET \
       --url https://example.com/courses/<course-id>/memberships \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## IDs of the participations

Returns the references to the participants of a course.

### HTTP request
   `GET /courses/{id}/relationships/memberships`

### Parameters

Parameter | Description
--------- | -------
id | ID of the course

### Authorization

Users with at least admin status or participants of the course can use this route.

   ```shell
   curl --request GET \
       --url https://example.com/courses/<course-id>/relationships/memberships \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Read out a participation

   ```shell
   curl --request GET \
       --url https://example.com/course-memberships/<ID> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

Returns a participation.

### HTTP Request
   `GET /course-memberships/{id}`

### Parameter

Parameter | Description
--------- | -------
id | ID of participation

### Authorization

Only the participant himself can read the participation


## Change a participation

```shell
   curl --request PATCH \
       --url https://example.com/course-memberships/<ID> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
       --header "Content-Type: application/vnd.api+json" \
       --data '{"data": {
           "type": "course-memberships",
           "id": "<ID>",
           "attributes": {"group":2, "visible": "no"}
       }}'
```

This route can be used to change the attributes of a participation in an
change the attributes of an event.

### HTTP Request
   `PATCH /course-memberships/{id}`

### Parameter

Parameter | Description
--------- | -------
id | ID of participation

### Authorization

Only the participant himself can change the participation.
