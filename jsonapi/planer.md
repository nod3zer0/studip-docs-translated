---
title: Planner
---


This category includes everything that has to do with the timetable or the
timetable or appointment calendar. The timetable is a
semester-dependent weekly schedule that contains recurring weekly appointments
which are either entered by the students themselves or are entered due to
entered by the students themselves or due to events attended.

## Schemas

### Schema "calendar-events"

Resources of this type are events entered in the calendar by the user.


#### Attributes

Attribute | Description
-------- | ------------
title | the title of the entry
description | the description of the entry
start | the start of the entry (ISO 8601)
end | the end of the entry (ISO 8601)
categories | the category of the entry (as a string)
location | the location of the entry
mkdate | the creation date of the entry
chdate | the date of the last change to the entry
recurrence | information about repetitions of the entry

#### Relations

Relation | Description
-------- | ------------
owner | the user who created the entry

### Schema "course-events"

Resources of this type represent one-off dates of booked courses.

#### Attributes

Attribute | Description
-------- | ------------
title | the title of the entry
description | the description of the entry
start | the start of the entry (ISO 8601)
end | the end of the entry (ISO 8601)
categories | the category of the entry (as a string)
location | the location of the entry
mkdate | the creation date of the entry
chdate | the date of the last change to the entry
recurrence | information about repetitions of the entry


#### Relations

Relation | Description
-------- | ------------
owner | the **event** to which the entry belongs


### Schema "schedule-entries"

Resources of this type represent entries in the timetable that a user has
a user has entered themselves.

#### Attributes

Attribute | Description
-------- | ------------
title | the title of the entry
description | the description of the entry
start | the time of the start of the entry ("hh:mm")
end | the time at which the entry ends ("hh:mm")
weekday | the day of the week of the entry (0-6)
color | the color of the entry ("#rrggbb")

#### Relations

Relation | description
-------- | ------------
owner | the user who created the entry


### Schema "seminar-cycle-dates"

Resources of this type represent entries in the timetable that consist of the regular
are made up of the regular dates of a course.

#### Attributes

Attribute | Description
-------- | ------------
title | the title of the entry
description | the description of the entry
start | the time of the start of the entry ("hh:mm")
end | the time at which the entry ends ("hh:mm")
weekday | the day of the week of the entry (0-6)
recurrence | information about recurrences of the entry
locations | all locations where this entry takes place

#### relations

relation | description
-------- | ------------
owner | the **event** to which the entry belongs

## Read all calendar entries

```shell
curl --request GET \
    --url https://example.com/users/<ID>/events \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route can be used to query the user's calendar. Without
parameters, all entries for the next two weeks are returned.
are returned.

### HTTP request

GET /users/{id}/events

Parameter | Description
--------- | ------------
id | the ID of the user

### URL parameter

Parameter | Description
--------- | ------------
filter[timestamp] | Start time of the calendar entries supplied (as seconds since 01.01.1970)

If "filter[timestamp]" is not set, all calendar entries from the next
calendar entries for the next two weeks are returned.

This start time can be changed using "filter[timestamp]".
can be changed. However, calendar entries from the next two weeks are always
weeks are always delivered.

### Authorization

Each user may use this route for themselves. Other users
only have access to their own calendars.


## All calendar entries (iCalendar)

:::danger
This route is not a JSON API compliant route.
:::

```shell
curl --request GET \
    --url https://example.com/users/<ID>/events.ics \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route can be used to query the user's calendar. The
data is delivered in iCalendar data format. All**
calendar entries are returned.

### HTTP request

`GET /users/{id}/events.ics`

Parameter | Description
--------- | ------------
id | the ID of the user

### URL parameters

no URL parameters

### Authorization

Each user may use this route for themselves. Other users
only have access to their own calendars.


## All dates of an event

```shell
curl --request GET \
    --url https://example.com/courses/<ID>/events \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

With this route all dates of an event can be queried.

### HTTP Request

`GET /courses/{id}/events`

Parameter | Description
--------- | ------------
id | the ID of the event

### URL parameter

Parameter | Default | Description
--------- | ------- | ------------
page[offset] | 0 | the offset (see pagination)
page[limit] | 30 | the limit (see pagination)


### Authorization

The dates of an event are visible to all participants.


## Read timetable
```shell
curl --request GET \
    --url https://example.com/users/<ID>/schedule \
    --header "Authorization: Basic `echo -ne "test_autor:testing" | base64`"
```

With this route you can query your timetable. If no
filter parameter is specified, the timetable for the current semester is
semester will be delivered.

### HTTP Request

`GET /users/{id}/schedule`

Parameter | Description
--------- | ------------
id | the ID of the user

### URL parameter

Parameter | Default | Description
--------- | ------- | ------------
filter[timestamp] | start of the current semester | start time of the desired semester (in seconds since 01.01.1970)

### Authorization

Only your own timetable can be read.


## Own timetable entries
```shell
curl --request GET \
    --url https://example.com/schedule-entries/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

With this route you can read a single, self-written entry
in the timetable.

### HTTP Request

`GET /schedule-entries/{id}`

Parameter | Description
--------- | ------------
id | the ID of the entry

### URL parameters

no URL parameters

### Authorization

Only the user who created the entry is authorized to read the entry.

## Read out regular event dates
```shell
curl --request GET \
    --url https://example.com/seminar-cycle-dates/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

Description

### HTTP Request

`GET /seminar-cycle-dates/{id}`

Parameter | Description
--------- | ------------
id | the ID of the event

### URL parameters

no URL parameters

### Authorization

All participants of an event can see the event.
