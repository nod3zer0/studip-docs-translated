---
title: File area
---

In Stud.IP every user, every institution and every course has
has its own file area. File areas are (special) folders.
Folders can contain files and folders, which in turn can contain
files and folders.

There are different types of folders, which generally differ in terms of
who can see them and who has read and/or write
write access to these folders.

## Schemas


### Schema "file-refs"

From the user's point of view, files in Stud.IP are resources of the type
"file-refs". Technically speaking, however, they are references to the
resources of the type "files". The latter are the files actually stored on the
hard disk, which are only linked with the help of the "file-refs".
are only linked.

Put simply, "file-refs" are generally always used.

### Attributes

Attribute | Description
-------- | ------------
name | the name of the file
description | an optional description of the file
mkdate | the creation date of the file
chdate | the date of the last change of the metadata ('name', 'description', ...) of the file
downloads | How often was this file downloaded?
filesize | the size of the file in bytes
storage | TODO

### Relations

 relation | description
-------- | ------------
file | the actual file on the hard disk
owner | the user who owns this file
parent | the folder in which this file is located
range | the event, institution or user in whose file range this file is located
terms-of-use | the license under which this file is made available

### Meta

The metadata of files contains the "download-link" to download the content of the file.


### Schema "files"

Unlike resources of type "file-refs", resources of type "files" are not available via the graphical user interface. Technically, "files" are used to actually store the files on the hard disk (or a remote storage location).

Resources of type "files" only become visible when they are linked by "file-refs".

### Attributes

Attribute | Description
--------- | ------------
name | the name of the file
mime-type | the MIME type of the file
size | the size of the file in bytes
storage | TODO
mkdate | the creation date of the file
chdate | the date the file was last modified

### Relations

 Relation | Description
-------- | ------------
file-refs | all resources of type "file-refs" that refer to this file
owner | the user who owns this file

### Type "folders"

Resources of type "folders" are folders in the conventional sense and can contain further "folders" or resources of type "file-refs".

There are different types of "folders". In Stud.IP, however, "standard folders" are primarily used. All operations are possible for these. For other types, the implementations themselves decide whether the operation is possible.

### Attributes

Attribute | Description
------------ | ------------
folder-type | the type of folder
name | the name of the folder
description | the description of the folder
mkdate | the creation date of the folder
chdate | the date the folder was last modified
is-visible | Can the logged-in user see the folder?
is-readable | Can the logged-in user open the folder?
is-writable | Can the logged-in user create files in the folder?
is-editable | Can the logged-in user edit the folder?
is-subfolder-allowed | Is the logged-in user allowed to create additional folders in the folder?


### Relationships

Relation | Description
--------- | ------------
owner | the user who owns this folder
parent | the folder in which this folder is located
range | the event, the institution or the user in whose file area this folder is located
folders | the folders located in this folder
file-refs | the files located in this folder


### Type "terms-of-use"

Each file is subject to a license that regulates its use, distribution and modification.

### Attributes

Attribute | Description
------------ | ------------
name | the name of the license
description | the description of the license
icon | the icon used for the license
mkdate | the creation date of the license
chdate | the date the license was last modified


### Relationships

Licenses ('terms-of-use') have no relations.

## All licenses

```shell
curl --request GET \
    --url https://example.com/terms-of-use \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route requests all licenses of files registered in Stud.IP.

### HTTP Request

`GET /terms-of-use`

### Authorization

Every user may use this route.

## Read out a license

```shell
curl --request GET \
    --url https://example.com/terms-of-use/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

With this route any license can be read.

### HTTP Request

`GET /terms-of-use/{id}`

Parameter | Description
--------- | ------------
id | the ID of the license

### URL parameters

no URL parameters

### Authorization

Every user may use this route.


## All files of a file range

```shell
curl --request GET \
    --url https://example.com/<courses,institutes,users>/<ID>/file-refs \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route can be used to read *all* files in a file range.
can be read. The result is a flat list of all files, regardless of
the affiliation to folders of this file range.

### HTTP request

`GET /courses/{id}/file-refs`
`GET /institutes/{id}/file-refs`
`GET /users/{id}/file-refs`

Parameters | Description
--------- | -------
id | the ID of the event, the institution or the user

### URL parameter

Parameter | Default | Description
--------- | ------- | ------------
page[offset] | 0 | the offset (see pagination)
page[limit] | 30 | the limit (see pagination)

### Authorization

Every user can see the files of an institution. The files
of an event can be seen by all users who have access
have access to the event. The files of a user can be seen by all users
unless the user is invisible.

Otherwise, the access rules of the folders in which the files are located apply.


## All folders in a file area

```shell
curl --request GET \
    --url https://example.com/<courses,institutes,users>/<ID>/folders \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route can be used to read *all* folders in a file range.
can be read. The result is a flat list of all folders, regardless of
of the folders belonging to this file range.

### HTTP Request

`GET /courses/{id}/folders`
`GET /institutes/{id}/folders`
GET /users/{id}/folders

Parameter | Description
--------- | -------
id | the ID of the event, the institution or the user

### URL parameter

Parameter | Default | Description
--------- | ------- | ------------
page[offset] | 0 | the offset (see pagination)
page[limit] | 30 | the limit (see pagination)

### Authorization

Every user can see the folders of an institution. The folders of an
event can be seen by all users who have access to the event.
have access to the event. The folders of a user are visible to all users, unless the user is
invisible.

The access rules of the folders in which the folders are located also apply.


## Create a folder

A folder can simply be created via this route.

```shell
   curl --request POST \
       --url https://example.com/courses/<ID>/folders \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "test_dozent:testing" | base64`" \
       --data '{"data": {"type": "folders", "attributes": {"name": "Name of the folder"}, "relationships": {"parent": {"data": {"type": "folders", "id":"<any-folder-id>"}}}}}'
```

### HTTP Request

`POST /courses/{id}/folders`
`POST /institutes/{id}/folders`
`POST /users/{id}/folders`

Parameter | Description
--------- | -------
id | the ID of the event, the institution or the user

The request body contains a "JSONAPI resource object" of the type "folders". Name and parent containing folder are required: The attribute "name" and the relation "parent", which refers to a "folders" object, are mandatory.

### URL parameters

no parameters

### Authorization

Whether a folder may be created is decided by the respective implementation of the target folder.


## Reading a file

```shell
curl --request GET \
    --url https://example.com/file-refs/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route can be used to read a file.

### HTTP request

`GET /file-refs/{id}`

Parameter | Description
--------- | -------
id | the ID of the file

### URL parameters

no parameters

### Authorization

The parent folder decides whether a file may be read.


## Change metadata of a file

```shell
curl --request PATCH \
    --url https://example.com/file-refs/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --header "Content-Type: application/vnd.api+json" \
    --data '{"data": {"type": "file-refs", "id":"<id-of-file>", \
             "attributes":{"name": "new-name.jpg"}}}'
```
This route can be used to change the name, description and/or license of a file. To do this, the customized "resource object" is sent to this route as is typical for JSONAPI.

### HTTP request

`PATCH /file-refs/{id}`

Parameter | Description
--------- | -------
id | the ID of the file

The request body contains the modified "resource object".

### URL parameters

no URL parameters

### Authorization

The parent folder decides whether a file may be modified.


## Delete a file

```shell
curl --request DELETE \
    --url https://example.com/file-refs/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route deletes a file.

### HTTP Request

`DELETE /file-refs/{id}`

Parameter | Description
--------- | -------
id | the ID of the file

### URL parameters

no URL parameters

### Authorization

The parent folder decides whether a file can be deleted.


## Read license of a file
```shell
curl --request GET \
    --url https://example.com/file-refs/<ID>/relationships/terms-of-use \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

To read the relation of a file to a license, use this route.

### HTTP Request

`GET /file-refs/<ID>/relationships/terms-of-use`

Parameter | Description
--------- | -------
id | the ID of the file

### URL parameters

no URL parameters

### Authorization

The parent folder of the file decides.


## Change license of a file
```shell
curl --request PATCH \
    --url https://example.com/file-refs/<ID>/relationships/terms-of-use \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --header "Content-Type: application/vnd.api+json" \
    --data '{"data": {"type": "terms-of-use", "id": "<id-of-license>"}}'
```

To change the relation of a file to a license, use this route. Deleting the relation to the file is excluded.

### HTTP request

`PATCH /file-refs/<ID>/relationships/terms-of-use`

Parameter | Description
--------- | -------
id | the ID of the file

The request body must contain a "resource identifier" of type "terms-of-use".

### URL parameters

no URL parameters

### Authorization

The parent folder of the file decides.


## Read the ETag of a file

:::danger
This route is not a JSON API-compliant route.
:::

```shell
curl --request HEAD \
    --url https://example.com/file-refs/<ID>/content \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

To get a fingerprint (ETag) of the actual content of a file, you can call this non-JSON API route.

### HTTP Request

`HEAD /file-refs/{id}/content`

Parameter | Description
--------- | -------
id | the ID of the file

### URL parameters

no URL parameters

### Authorization

The parent folder of the file decides.


## Download a file

:::danger
This route is not a JSON API-compliant route.
:::

```shell
curl --request GET \
    --url https://example.com/file-refs/<ID>/content \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route can be used to download the content of a file.

### HTTP Request

`GET /file-refs/{id}/content`

Parameter | Description
--------- | -------
id | the ID of the file

The request can include an ETag header in order to avoid redundant
avoid redundant data transmission.

### URL parameters

no URL parameters

### Authorization

The parent folder of the file decides.


## Update content of a file

:::danger
This route is not a JSON API-compliant route.
:::

```shell
curl --request POST --url https://example.com/file-refs/<ID>/content \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    -F 'myfile=@/path/to/local/file'
```

This route can be used to overwrite the content of an existing file.
can be overwritten. To do this, a single "multipart/form-data"-encoded file is sent to this route.
to this route.

### HTTP request

`POST /file-refs/{id}/content`

The request body must then contain a "multipart/form-data"-encoded file.
must be included.


### URL parameters

no URL parameters

### Authorization

The parent folder of the file decides.


## Reading a folder

```shell
curl --request GET \
    --url https://example.com/folders/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

With this route you can read the data of a folder.

### HTTP Request

`GET /folders/{id}`

Parameter | Description
--------- | -------
id | the ID of the folder

### URL parameters

no URL parameters

### Authorization

The type of folder determines the authorization.


## Change a folder
```shell
curl --request PATCH \
    --url https://example.com/folders/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --header "Content-Type: application/vnd.api+json" \
    --data '{"data": {"type": "folders", "id":"<id-of-license>", \
             "attributes":{"name": "New name"}}}'
```

This route can be used to change the name and/or description.
can be changed. You can also move the folder to another folder. To do this
change the "parent" relation.

### HTTP Request

`PATCH /folders/{id}`

Parameter | Description
--------- | -------
id | the ID of the folder

### URL parameters

no URL parameters

### Authorization

The type of folder determines the authorization.


## Delete a folder
```shell
curl --request DELETE \
    --url https://example.com/folders/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

With this route you can delete a folder.

### HTTP Request

`DELETE /folders/{id}`

Parameter | Description
--------- | -------
id | the ID of the folder

### URL parameters

no URL parameters

### Authorization

The type of folder determines the authorization.


## All files in a folder
```shell
curl --request GET \
    --url https://example.com/folders/<ID>/file-refs \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

With this route you get a list of all files that are located directly in
are located directly in a folder.

### HTTP Request

`GET /folders/{id}/file-refs`

Parameter | Description
--------- | -------
id | the ID of the folder

### URL parameter

Parameter | Default | Description
--------- | ------- | ------------
page[offset] | 0 | the offset (see pagination)
page[limit] | 30 | the limit (see pagination)

### Authorization

Whether you are allowed to see the list of files in a folder is determined by the
implementation of the folder.


## All folders of a folder
```shell
curl --request GET \
    --url https://example.com/folders/<ID>/folders \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route provides a list of all folders that are located directly in
are located directly in a folder.

### HTTP Request

`GET /folders/{id}/folders`

Parameter | Description
--------- | -------
id | the ID of the folder

### URL parameter

Parameter | Default | Description
--------- | ------- | ------------
page[offset] | 0 | the offset (see pagination)
page[limit] | 30 | the limit (see pagination)

### Authorization

Whether you are allowed to see the list of folders in a folder is determined by the
implementation of the folder.


## Create a file

A file is always created in a folder. As files consist of
metadata **and** content, a file must be created in two steps.
two steps. You can either

* upload the content first and then adjust the metadata (such as description and license) or
* first create the file with the metadata and then upload the content.

### Variant a.

```shell
curl --request POST --url "https://example.com/folders/<ID>/file-refs" \
     -F 'file=@/pfad/zu/einer-neuen-datei.jpg' \
     --header "Authorization: Basic `echo -ne "test_dozent:testing" | base64`"
```

First you send a ``POST`` request with ``Content-Type: multipart/form-data`` and the file in the request body to the specified URL.

If successful, you receive a status code 201 and a ``Location`` header, which takes you to the newly created document in the JSON:API.

The *filename* is taken from the upload by default and is also used for the name of the file.

If you want to use a different file name, you can use an HTTP header: ``Slug: new-filename.txt``.

The JSON:API representation of the uploaded file is obtained via the URL from the ``Location`` header received.

Modifications to the metadata (such as description etc.) can now be made using a (JSON:API-typical) ``PATCH`` request to this route.

### Variant b.

```shell
curl --request POST --url https://example.com/folders/<ID>/file-refs \
    --header "Content-Type: application/vnd.api+json" \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --data '{"data": { "type": "file-refs", "attributes": { "name": "iason.txt", "description": "Iason's CV"}, "relationships":{ "terms-of-use": { "data": { "type": "terms-of-use", "id": "FREE_LICENSE"}}}}}'
```

First you send a ``POST`` request with ``Content-Type: application/vnd.api+json`` to the URL.

The request body must then contain a JSON:API-typical representation of the new file. If successful, you will then receive a representation of the newly created file, which currently has no content.

The content must therefore be uploaded in a second request. To do this, a ``POST`` request is sent to the ``download-url`` as described under "Updating the content of a file".

### HTTP request
`POST /folders/{id}/file-refs`

The request body then contains either a
"multipart/form-data"-encoded file or a JSON-API-specific "resource object".
"resource object".

If you send a JSON API resource object, you **must** use the
relation `terms-of-use` (the license) must be included. Without a license
no files can be created.

### URL parameter

Parameter | Description
--------- | -------
id | the ID of the folder

### Authorization

The implementation of the folder determines whether a file may be created.

## Copying a file

To copy a file, use the ["variant b."](#variante-b) to create files.
creating files.

First you need the "resource identifier" of the `file` relation of the file to be
file to be copied. Then you send a JSON API "resource object"
to the URL for creating a file and set this "resource
identifier" as the `file` relation of the new file.

If you yourself are the owner of the source file, the reference to the
to the `file` remains. If you are not the owner of the source file,
the `file` is also copied and you become its owner.

## Copy a folder

:::danger
This route is not a JSON-API-compliant route.
:::

```shell
curl -F "destination=<destination-ID>" \
     --url "https://example.com/folders/<source-ID>/copy" \
     --header "Authorization: Basic `echo -ne "test_dozent:testing" | base64`"
```

To copy a folder, this route is used, which is
is not JSON-API compliant. To do this, a POST request is sent to
the route of the folder, in whose request body the target folder is specified.
is specified in the request body. The request body must be derived from the
"multipart/form-data" coding.

### HTTP request

POST /folders/{id}/copy`

The "Content-Type" of the request must be "multipart/form-data". In the
request body must contain the ID of the destination folder under the key
destination folder in the request body.

If the request was successful, you will receive a status code 201
and a `Location` header, which points to the new, copied folder
points to the new, copied folder.

### Authorization
Any user who is authorized to open the source folder and write to the destination folder
can call up this route.


## Create a folder
```shell
curl --request POST \
    --url https://example.com/folders/<ID>/folders \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
    --data '{"data": { "type": "folders", "attributes": {"name": "New folder"}}}'
```

With this route you can create a new folder.

### HTTP Request

`POST /folders/{id}/folders`

Parameter | Description
--------- | -------
id | the ID of the parent folder

### URL parameters

no URL parameters

### Authorization

The implementation of the parent folder determines whether a folder may be created.


## Read a "file
```shell
curl --request GET \
    --url https://example.com/files/<ID> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

The files mentioned in the above routes are technically only references to actual files on the hard disk or similar. The actual files can also be read. This route is used for this purpose.

### HTTP request

`GET /files/{id}`

Parameter | Description
--------- | -------
id | the ID of the "files"

### URL parameters

no URL parameters

### Authorization

A user can see a "file" if one of the files referring to it can be seen by the user.


## All files of a "file"
```shell
curl --request GET \
    --url https://example.com/files/<ID>/file-refs \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route can be used to read all files that refer to this "file".

### HTTP request

`GET /files/{id}/file-refs`

Parameter | Description
--------- | -------
id | the ID of the "file"

### URL parameters

no URL parameters

### Authorization

The route can be called meaningfully if you can see one of the files referring to it.


## All file IDs of a "file"
```shell
curl --request GET \
    --url https://example.com/files/<ID>/relationships/file-refs \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
```

This route is used to get all IDs of the files that refer to this "file".

### HTTP request

`GET /files/{id}/relationships/file-refs`

Parameter | Description
--------- | -------
id | the ID of the "file"

### URL parameters

no URL parameters

### Authorization

The route can be called sensibly if you can see one of the files referring to the "file".
