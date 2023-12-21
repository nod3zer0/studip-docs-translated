---
id: personal-notifications
title: Personal notifications
sidebar_label: Personal notifications
---

As of version 2.4, it is very easy to inform users in real time about new content or exciting actions that affect them. This is intended for cases where the user receives a personal message or someone responds to content they have posted. In any case, there should always be a direct link to the user.
In these cases, you may want to see immediately that something has been done and not only when the next page is reloaded. Especially when Stud.IP is open in one of 20 browser tabs, this can take some time. You want to be able to react immediately. This would be poison for chat-like communication, for example. The personal notifications are therefore THE element to enable real-time events.

You can read more about the feature, how the user sees it, and how he/she can react to it and set the feature in the user documentation.

This is mainly about the question: I am a developer and want to send a notification to the user. What do I have to do?

Actually, just insert a line. Let's assume that user A would like to be notified of all changes to a certain wiki page. And another user B changes this page. For example, you have to insert the following line into the code:

```php
PersonalNotifications::add(
      $user_A_user_id, //id of user A or array of 'multiple user_ids
      $url_of_wiki_page, //when user A clicks this URL he/she should jump directly to the changed wiki-page
      "User B changed wiki-page xyz", //a small text that describes the notification
      "wiki_page_1234", //an (optional) html-id of the content of the wiki page. If the user is looking at the content already, the notification will disappear automatically
      Icon::create("wiki", "clickable") //an (optional) icon that is displayed next to the notification-text
);
```


So all you need is the user_id of user A, the URL of the wiki page, a very short description text (please don't make it too long, otherwise it will blow up the layout) and optionally an HTML ID and an image.

Why the HTML ID? Especially in a chat, for example, you don't want to click away every sentence received from the other person as a notification at the end. The idea arose that if you have already seen something on the page, then the notification should take care of itself. So if user A happens to go to the page anyway (via a red icon in the my_seminars overview) and sees the changed wiki page, then he/she does not have to click away the notification again. Instead, an AJAX request is automatically fired, informing the user that he/she has already seen the content.

The programmer does not have to take special care of all this. All they have to do is either specify an HTML ID or not when calling `add`. It's as simple as that.
