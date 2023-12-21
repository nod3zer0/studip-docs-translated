---
title: Forum
---


The Stud.IP forum offers the possibility to create, comment and categorize posts.
and to categorize. Each forum is linked to exactly one course.
The schemes are divided into forum categories and forum entries.

## Schema "forum-categories"

Categories for entries have a name and indicate the hierarchy of the forum
based on their position.

### Attributes

Attribute | Description
-------- | ------------
title | Name of a category
position | Position of a category

### Relations

relation | description
-------- | ------------
course | The course of the forum in which the category is created
entries | All forum entries of a forum category

## Schema "forum-entries"

Forum entries are located at different levels. They can be created directly in
created directly in categories as topics or linked to existing entries.
to existing entries.

### Attributes

Attribute | Description
-------- | ------------
title | Name of an entry (should only be displayed for topics)
content | Indicates the content of an entry
area | This attribute is included (but is usually '0')

### Relations

Relation | Description
-------- | ------------
category | The forum category of the forum entry
entries | All subentries of a forum entry

## Read all forum categories of a course
   GET /courses/{id}/forum-categories

   Parameters | Description
  ---------- | ------------
  id | The ID of the course

   ```shell
   curl --request GET \
       --url https://example.com/courses/<COURSE-ID>/forum-categories \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "test_autor:testing" | base64`" \
       --data
   ```

### Authorization
         The user should be a member of the corresponding course.
   > The request returns JSON similar to this:

```json
{
  "data": [
    {
      "type": "forum-categories",
      "id": "d6b887a73f024cf31b4a01f41531b809",
      "attributes": {
        "title": "NewStuff",
        "position": 0
      },
      "relationships": {
        "course": {
          "data": {
            "type": "courses",
            "id": "1b7d3834e42c1569947e0eab7b63ed19"
          },
          "links": {
            "related": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19"
          }
        }
      },
      "links": {
        "self": "/stud35/plugins.php/argonautsplugin/forum-categories/d6b887a73f024cf31b4a01f41531b809"
      }
    },
    {
      "type": "forum-categories",
      "id": "3710de2efd59869ab7ed7e410f70947f",
      "attributes": {
        "title": "CatCreateRoute",
        "position": 1
      },
      "relationships": {
        "course": {
          "data": {
            "type": "courses",
            "id": "1b7d3834e42c1569947e0eab7b63ed19"
          },
          "links": {
            "related": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19"
          }
        }
      },
      "links": {
        "self": "/stud35/plugins.php/argonautsplugin/forum-categories/3710de2efd59869ab7ed7e410f70947f"
      }
    },
    {
      "type": "forum-categories",
      "id": "7684942d4a1d3f8ab0752165e22c31a6",
      "attributes": {
        "title": "TESTECASE ",
        "position": 2
      },
      "relationships": {
        "course": {
          "data": {
            "type": "courses",
            "id": "1b7d3834e42c1569947e0eab7b63ed19"
          },
          "links": {
            "related": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19"
          }
        }
      },
      "links": {
        "self": "/stud35/plugins.php/argonautsplugin/forum-categories/7684942d4a1d3f8ab0752165e22c31a6"
      }
    },
    {
      "type": "forum-categories",
      "id": "4ca16225a42c94957c4129da5f0bef2d",
      "attributes": {
        "title": "CatCreateRoute",
        "position": 3
      },
      "relationships": {
        "course": {
          "data": {
            "type": "courses",
            "id": "1b7d3834e42c1569947e0eab7b63ed19"
          },
          "links": {
            "related": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19"
          }
        }
      },
      "links": {
        "self": "/stud35/plugins.php/argonautsplugin/forum-categories/4ca16225a42c94957c4129da5f0bef2d"
      }
    },
    {
      "type": "forum-categories",
      "id": "1b7d3834e42c1569947e0eab7b63ed19",
      "attributes": {
        "title": "General",
        "position": 4
      },
      "relationships": {
        "course": {
          "data": {
            "type": "courses",
            "id": "1b7d3834e42c1569947e0eab7b63ed19"
          },
          "links": {
            "related": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19"
          }
        }
      },
      "links": {
        "self": "/stud35/plugins.php/argonautsplugin/forum-categories/1b7d3834e42c1569947e0eab7b63ed19"
      }
    }
  ]
}
```

## Read a forum category
    GET /forum-categories/{id}

    Parameters | Description
   ---------- | ------------
   id | The ID of the category

   ```shell
   curl --request GET \
       --url https://example.com/forum-categories/<FORUM-CATEGORY-ID> \
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
"type": "forum-categories",
"id": "1b7d3834e42c1569947e0eab7b63ed19",
"attributes": {
"title": "General",
"position": 4
},
"relationships": {
"course": {
"data": {
"type": "courses",
"id": "1b7d3834e42c1569947e0eab7b63ed19"
},
"links": {
"related": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19"
}
}
},
"links": {
"self": "/stud35/plugins.php/argonautsplugin/forum-categories/1b7d3834e42c1569947e0eab7b63ed19"
}
}
}
```

## Read a forum entry
       GET /forum-entries/{id}

       Parameters | Description
      ---------- | ------------
      id | The ID of the entry

  ```shell
  curl --request GET \
      --url https://example.com/forum-entries/<FORUM-ENTRY-ID> \
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
    "type": "forum-entries",
    "id": "1b7d3834e42c1569947e0eab7b63ed19",
    "attributes": {
      "title": "Overview",
      "area": 0,
      "content": ""
    },
    "relationships": {
      "category": {
        "data": {
          "type": "forum-categories",
          "id": null
        },
        "links": {
          "related": "/stud35/plugins.php/argonautsplugin/forum-categories/"
        }
      },
      "child-entries": {
        "data": [
          {
            "type": "forum-entries",
            "id": "2e6ff68d79c5e3f3ed24bd0274865e42"
          },
          {
            "type": "forum-entries",
            "id": "3e0ec8e69afe2502763730e17954d340"
          },
          {
            "type": "forum-entries",
            "id": "783e1b783a76f109eeb5fc19c43c2d08"
          },
          {
            "type": "forum-entries",
            "id": "9af47a2c1463b12a35e3a7c3a10bd53c"
          },
          {
            "type": "forum-entries",
            "id": "a5e119fc5b8cfc549ab9cc985e8609a1"
          },
          {
            "type": "forum-entries",
            "id": "b21ca75f9562d3a5751babaac49bbc9a"
          },
          {
            "type": "forum-entries",
            "id": "c2e21dfa7d071fb6f40ed29271f926aa"
          },
          {
            "type": "forum-entries",
            "id": "f5f8ea3da6fd945eb92dd0d1e1193132"
          }
        ],
        "links": {
          "related": "/stud35/plugins.php/argonautsplugin/forum-entries/1b7d3834e42c1569947e0eab7b63ed19/child-entries"
        }
      }
    },
    "links": {
      "self": "/stud35/plugins.php/argonautsplugin/forum-entries/1b7d3834e42c1569947e0eab7b63ed19"
    }
  }
}
```

## Read all forum entries of a category
       GET /forum-categories/{id}/entries

       Parameters | Description
      ---------- | ------------
      id | The ID of the category

  ```shell
  curl --request GET \
      --url https://example.com/forum-categories/<CATEGORY-ID>/entries \
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
    "type": "forum-entries",
    "id": "1b7d3834e42c1569947e0eab7b63ed19",
    "attributes": {
      "title": "Overview",
      "area": 0,
      "content": ""
    },
    "relationships": {
      "category": {
        "data": {
          "type": "forum-categories",
          "id": null
        },
        "links": {
          "related": "/stud35/plugins.php/argonautsplugin/forum-categories/"
        }
      },
      "child-entries": {
        "data": [
          {
            "type": "forum-entries",
            "id": "2e6ff68d79c5e3f3ed24bd0274865e42"
          },
          {
            "type": "forum-entries",
            "id": "3e0ec8e69afe2502763730e17954d340"
          },
          {
            "type": "forum-entries",
            "id": "783e1b783a76f109eeb5fc19c43c2d08"
          },
          {
            "type": "forum-entries",
            "id": "9af47a2c1463b12a35e3a7c3a10bd53c"
          },
          {
            "type": "forum-entries",
            "id": "a5e119fc5b8cfc549ab9cc985e8609a1"
          },
          {
            "type": "forum-entries",
            "id": "b21ca75f9562d3a5751babaac49bbc9a"
          },
          {
            "type": "forum-entries",
            "id": "c2e21dfa7d071fb6f40ed29271f926aa"
          },
          {
            "type": "forum-entries",
            "id": "f5f8ea3da6fd945eb92dd0d1e1193132"
          }
        ],
        "links": {
          "related": "/stud35/plugins.php/argonautsplugin/forum-entries/1b7d3834e42c1569947e0eab7b63ed19/child-entries"
        }
      }
    },
    "links": {
      "self": "/stud35/plugins.php/argonautsplugin/forum-entries/1b7d3834e42c1569947e0eab7b63ed19"
    }
  }
}
```

## Read all subentries of a forum entry
       GET /forum-entries/{id}/entries

       Parameter | Description
      ---------- | ------------
      id | The ID of the entry

  ```shell
  curl --request GET \
      --url https://example.com/forum-entries/<FORUM-ENTRY-ID>/entries \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
  ```
### Authorization
  The user should be a member of the corresponding course.

__
## Create a category within a course

       POST /courses/{id}/forum-categories

       Parameters | Description
      ---------- | ------------
      id | The ID of the course

  ```shell
  curl --request POST \
      --url https://example.com/courses/<COURSE-ID>/categories \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
      '{"data": {"type": "forum-categories", "attributes": {"title": "CreateCategoryTest", "content": "works"}
  }
}'

  ```
### Authorization
  The user should be a member of the corresponding course.

  > The request returns JSON similar to this:

```json
{
"data": {
"type": "forum-categories",
"id": "1b7d3834e42c1569947e0eab7b63ed19",
"attributes": {
"title": "General",
"position": 4
},
"relationships": {
"course": {
"data": {
"type": "courses",
"id": "1b7d3834e42c1569947e0eab7b63ed19"
},
"links": {
"related": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19"
}
}
},
"links": {
"self": "/stud35/plugins.php/argonautsplugin/forum-categories/1b7d3834e42c1569947e0eab7b63ed19"
}
}
}
```

## Post an entry to a category

       POST /forum-categories/{id}/entries

       Parameters | Description
      ---------- | ------------
      id | The ID of the category

  ```shell
  curl --request POST \
      --url https://example.com/forum-entries/<FORUM-CATEGORY-ID>/entries \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
      '{"data": {"type": "forum-entries", "attributes": {"title": "TestTheRoute", "content": "works!"}}}'

  ```
### Authorization
  The user should be a member of the corresponding course.


## Post an entry under an entry

       POST /forum-entries/{id}/entries

       Parameter | Description
      ---------- | ------------
      id | The ID of the entry

  ```shell
  curl --request POST \
      --url https://example.com/forum-entries/<FORUM-ENTRY-ID>/entries \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
      '{"data": {"type": "forum-entries", "attributes": {"title": "TestTheRoute", "content": "works!"}}}'

  ```
### Authorization
  The user should be a member of the corresponding course.


## Update a category

       PATCH /forum-categories/{id}

       Parameter | Description
      ---------- | ------------
      id | The ID of the category

  ```shell
  curl --request PATCH \
      --url https://example.com/forum-categories/<FORUM-CATEGORY-ID> \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
      '{"data": {"type": "forum-categories", "attributes": {"title": "UpdateCategory", "content": "time for a change"}

  ```
### Authorization
  The user should be a member of the corresponding course.
  The user should have the appropriate admin rights or be the creator of the
  category

  > The request returns JSON similar to this:

```json
{
"data": {
"type": "forum-categories",
"id": "1b7d3834e42c1569947e0eab7b63ed19",
"attributes": {
"title": "General",
"position": 4
},
"relationships": {
"course": {
"data": {
"type": "courses",
"id": "1b7d3834e42c1569947e0eab7b63ed19"
},
"links": {
"related": "/stud35/plugins.php/argonautsplugin/courses/1b7d3834e42c1569947e0eab7b63ed19"
}
}
},
"links": {
"self": "/stud35/plugins.php/argonautsplugin/forum-categories/1b7d3834e42c1569947e0eab7b63ed19"
}
}
}
```

## Update a forum entry

       PATCH /forum-entries/{id}

       Parameter | Description
      ---------- | ------------
      id | The ID of the entry

  ```shell
  curl --request PATCH \
      --url https://example.com/forum-entries/<FORUM-ENTRY-ID> \
      --header "Content-Type: application/vnd.api+json" \
      --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
      --data
      '{"data": {"type": "forum-entries", "attributes": {"title": "Update an entry", "content": "time for a change"}}}'

  ```
  ### Authorization
  The user should be a member of the corresponding course.
  The user should have the appropriate admin rights or be the creator of the entry.
  entry

## Remove a forum category

         DELETE /forum-categories/{id}

         Parameter | Description
        ---------- | ------------
        id | The ID of the category

  ```shell
  curl --request DELETE \
    --url https://example.com/forum-categories/<FORUM-CATEGORY-ID> \
    --header "Content-Type: application/vnd.api+json" \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --data
  ```
### Authorization
    The user should be a member of the corresponding course.
    The user should have the appropriate admin rights or be the creator of the
    category.

## Remove a forum entry

         DELETE /forum-categories/{id}

         Parameter | Description
        ---------- | ------------
        id | The ID of the entry

```shell
curl --request DELETE \
    --url https://example.com/forum-entries/<FORUM-ENTRY-ID> \
    --header "Content-Type: application/vnd.api+json" \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --data
```
### Authorization
    The user should be a member of the corresponding course.
    The user should have the appropriate admin rights or be the creator of the entry.
    entry.
