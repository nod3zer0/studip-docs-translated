---
title: News
---

:::info
Studip offers the possibility to send messages within the system
within the system between users and user groups.
The message system is structured like an internal mail service.
:::

## Schema "messages

The components of a message are the same as those of a typical mail.

### Attributes

Attribute | Description
-------- | ------------
subject | The subject of a message
message | The content of a message
mkdate | Creation date of a message
priority | Type of relevance
tags | Topics of the message

### relations

relation | description
-------- | ------------
sender | sending user
recipients | Receiver of a message

## All inbox messages

Returns all messages of a user.

### HTTP Request

   `GET /users/{id}/inbox`

#### Parameter

Parameter | Description
--------- | -------
id | ID of the user

#### URL parameter

Parameter | Description
--------- | ------------
filter[unread] | Should only unread messages be delivered?

If "filter[unread]" is not set, all messages are delivered. With "filter[unread]=1", only unread messages are returned.

### Authorization

This route can only be used by the owner of the relevant messages.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/users/<user-id>/inbox \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## All outbox messages
Returns all outbox messages of a user

### HTTP Request
   `GET /users/{id}/outbox`

### Parameter

Parameter | Description
--------- | -------
id | ID of the user

### Authorization

This route can only be used by the owner of the relevant messages.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/<posting-id>/users/<user-id>/outbox \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```

## Send a message

### HTTP Request

`POST /messages`

### Authorization

This route can be used by any Studip user.

### Parameters

This route does not require any parameters

   ```shell
   curl --request POST \
       --url https://example.com/messages \
       --header "Content-Type: application/vnd.api+json" \
       --header "Authorization: Basic `echo -ne "root@studip:testing" | base64`" \
       --data
       '{"data": {"type": "messages", "attributes": {"subject": "A new email", "message": "This is my first email - super easy thanks to the API.", "priority": "normal" }, "relationships": {"recipients": {"data": [{"type": "users", "id": "6235c46eb9e962866ebdceeceece739ace5"}]}}}}'
   ```

## View a message

### HTTP Request

   `GET /messages/{id}`

   Parameter | Description
   --------- | -------
   id | ID of the message

### Authorization

This route can be used by owners of the respective message.

   ```shell
   curl --request GET \
       --url https://example.com/blubber-postings/messages/<message-id>/ \
       --header "Authorization: Basic `echo -ne "test_author:testing" | base64`"
   ```


## Delete a message
Deletes a message

### HTTP request
   `DELETE /messages/{id}`

### Authorization

This route can be used by owners of the respective message.

  ```shell
    curl --request DELETE \
    --url https://example.com/messages/<messages-id> \
    --header "Authorization: Basic `echo -ne "test_author:testing" | base64`" \
  ```
