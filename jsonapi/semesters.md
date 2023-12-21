---
title: Semester
---

Semesters indicate a specific study period in Stud.IP.
They also serve as filters for courses and searches.

## Schema "semesters"

In addition to the title and description, semesters contain meta data about the start and end time of the semester
### Attributes

Attribute | Description
-------- | ------------
title | Name of the semester
description | Further information about the semester
start | start time
end | end time

### Relations

none

## All semesters
   GET /semesters

   ```shell
   curl --request GET \
       --url https://example.com/semesters \
       --header "Authorization: Basic `echo -ne "test_autor:testing" | base64`" \
   ```

## One semester
   GET /semesters/{id}

   ```shell
   curl --request GET \
       --url https://example.com/semesters/<semester-id> \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
   ```
