---
id: notifications
title: Notifications
sidebar_label: Notifications
---

## Notifications - an event system for Stud.IP

Plugins can already do a lot in Stud.IP. They can be inserted as tabs for each course, they can cheat their way into the homepage of a user and display a separate section there, they can change the navigation and in this way replace complete functions of Stud.IP such as the forum. But sometimes you also need small plugins that do not change entire pages, but only do something when something very specific happens.

For example: If a user subscribes to an event and is at least on the waiting list, a person in the accounting department who is not familiar with Stud.IP should receive an automatic e-mail. This kind of functionality has not yet been integrated into Stud.IP, and the developer working on it wants to change as little as possible in the Stud.IP source code. All he has to do is find the relevant line of code and set an event with any name. This is done like this:

`NotificationCenter::postNotification("user_accepted_to_seminar", $username);`

The event is now called "user_accepted_to_seminar" and is always called at this point. $username is simply a variable that is available in the context. In this case, this is of course the user name and this is simply important so that the email sent later also contains which user has registered. But theoretically you could also leave this variable out.

Now the plugin still needs to be written. The plugin must first be initialized on the page so that it can register a function for this event. I suggest a SystemPlugin that registers itself in the constructor:

```php
class MyPlugin extends StudIPPlugin implements SystemPlugin {
    public function __construct() {
        NotificationCenter::addObserver($this, "send_mail_to_accepted_user", "user_accepted_to_seminar");
    }

    public function send_mail_to_accepted_user($username) {
        /* here is what should be done if the user is registered */
    }
}
```

At this point it becomes clear that the passed function should not simply be a function, but a method of an object. The specific object is therefore passed first and the name of the method is passed as the second parameter. Only the third parameter is the name of the event. In this case, the plugin is lazy and registers itself for the event using $this. However, another object can also be registered as a plugin.

As of Stud.IP 4.2 it is also possible to register [Closures](php.net/manual/class.closure.php) as an event handler via the function `on()`. The syntax follows the jQuery notation:

```php
NoticationCenter::on('UserDidDelete', function ($event, $user) {
    // ...
});
```

In Stud.IP 4.2 and 4.3 only the transfer of closures was possible here. As of Stud.IP 4.4, almost all callable types can be passed. Only strings cannot be mapped properly and should therefore be avoided.

### Return values of notifications

Unfortunately, notifications are not intended to return anything. But if you build plugins that do something, you might want to give a visual feedback like an error or success message.

You can't get arrays or other data objects back from an observer. But the observer is of course free to use the print or echo statement for itself to write text to the user. This is currently the only possible type of feedback from the observer; the possible return value of the transferred method is currently completely ignored.

So now the observer can write its success message a'la

`echo '<div class="messagebox messagebox_success">Hurray! Data successfully transferred.</div>';`

Note, however, that this echo may come at the wrong time if the script that posts the notification works with templates or is even a trails script. This is not a bad thing, of course, but it can lead to the error message appearing before the introductory `<html>`, i.e. at the top of the page. To avoid this, the executing script can write

```php
ob_start();
NotificationCenter::postNotification("user_accepted_to_seminar", $username);
$message = ob_get_contents();
ob_end_clean();
```

Thanks to the graciousness of PHP, this also works if another output buffer (for which the "ob_" stands) is active in the background. This way you can at least get a string from the observers to the executing script.

**WARNING:** Any thought of passing serialized arrays or PHP objects via this string is obvious but dirty, because it is always possible that a second or third observer is sending something. And two or three serialized objects in a string are at least not so easy to fish out with an `unserialize()`. So don't do that at all.

Tip: You can not only define observers for notifications via plugins, but of course also post notifications yourself. This allows you to enable plugins for your own plugin. This allows two plugins to communicate with each other if both are installed. And if only one is installed, nothing happens. This can be quite useful under certain circumstances.


### Create notifications yourself

#### How do I name a notification?

There is no valid guideline for this yet. However, it appears that the name is written in CamelCase and describes an action. As the NotificationCenter is based on the original implementation from NextStep, it probably makes sense to use similar conventions as there. The article [NSNotfication Not Working, But Looks Right? :: Find That Bug!](http://www.goodbyehelicopter.com/?p=259) describes a BestPractice, according to which the Notifications:

`JJColorChange`

and:

`JJColorChanged`

are difficult to distinguish in the code, as they only differ by one letter. The recommendation is instead of:

* CamelCase
* CamelCased

rather:

* CamelCase
* CamelDidCase
* CamelWillCase
* CamelByCase

should be used. For these reasons, the names of the notifications for files, wiki pages and forum posts have been chosen accordingly.



### List of notifications built into Stud.IP

Here is a list of the available notifications, sorted by area. In the parameter list, please note that the first parameter of the function or method called by the notification contains the name of the notification.

#### Events

##### UserDidEnterCourse

**Additional parameters for observer method:**
* Event ID
* User ID

**Sending condition:** A user registers for an event.

##### UserDidLeaveCourse

**Additional parameters for observer method:**
* ?

**Sending condition:** ?

##### CourseDidChangeSchedule

**Additional parameters for observer method:**
* ?

**Send condition:** ?

##### CourseDidGetMember

**Additional parameters for observer method:**
* ?

**Send condition:** ?




#### Files

##### DocumentWillCreate

**Additional parameters for observer method:**
* StudipDocument instance

**Send condition**: A file is uploaded and has not yet been created.

##### DocumentDidCreate

**Additional parameters for observer method:**
* StudipDocument instance

**Sending condition**: A file has been uploaded and created.

##### DocumentWillUpdate

**Additional parameters for observer method:**
* StudipDocument instance

**Send condition**: A file is updated.

##### DocumentDidUpdate

**Additional parameters for observer method:**
* StudipDocument instance

**Send condition**: A file has been updated.

##### DocumentWillDelete

**Additional parameters for observer method:**
* StudipDocument instance

**Send condition**: A file is deleted.

##### DocumentDidDelete

**Additional parameters for observer method:**
* StudipDocument instance

**Send condition**: A file has been deleted.


#### Forum

##### PostingWillCreate

**Additional parameters for observer method:**
* ID of the forum post

**Sending condition**: A forum post has been created but not yet saved.

##### PostingDidCreate

**Additional parameters for observer method:**
* ID of the forum post

**Sending condition**: A forum post has been created and saved.

##### PostingWillUpdate

**Additional parameters for observer method:**
* ID of the forum post

**Sending condition**: A forum post has been changed, but the change has not yet been saved.

##### PostingDidUpdate

**Additional parameters for observer method:**
* ID of the forum post

**Sending condition**: A forum post has been changed and the change has been saved.

##### PostingWillDelete

**Additional parameters for observer method:**
* ID of the forum post

**Sending condition**: A forum post is deleted, but the deletion process has not yet started.

##### PostingDidDelete

**Additional parameters for observer method:**
* ID of the forum post

**Sending condition**: A forum post has been deleted.

#### Literature management

##### LitListDidInsert

**Additional parameters for observer method:**
* ?

**Sending condition:** ?

##### LitListDidUpdate

**Additional parameters for observer method:**
* ?

**Send condition:** ?

##### LitListDidDelete

**Additional parameters for observer method:**
* ?

**Send condition:** ?

##### LitListElementDidInsert

**Additional parameters for observer method:**
* ?

**Send condition:** ?

##### LitListElementDidUpdate

**Additional parameters for observer method:**
* ?

**Send condition:** ?

##### LitListElementDidDelete

**Additional parameters for observer method:**
* ?

**SendCondition:** ?


#### Blubber

##### PostingHasSaved

**Additional parameters for Observer method:**
* ?

**SendCondition:** ?


#### Messages

##### MessageDidSend

**Additional parameters for observer method:**
* ?

**Send condition:** ?


#### User migration

When migrating from one user account to another, the notification `UserWillMigrate` is sent before the action and the notification `UserDidMigrate` is sent after the action. Both notifications receive the ID of the account {+from+} to be migrated as `subject`, while the ID of the account {+in+} to be migrated is transferred as `$userdata`.


#### Wiki

If a wiki page has been created, the notification `PostingWillCreate` is sent before it is actually saved and `PostingDidCreate` is sent after it has been saved. The `subject` is an array with `range_id` and `keyword` of the wiki page.

If a wiki page has been changed, the notification `PostingWillUpdate` is sent before it is actually saved and `PostingDidUpdate` is sent after it has been saved. The `subject` is an array with `range_id` and `keyword` of the wiki page.

There are also notifications when a wiki page is deleted: `PostingWillDelete` and `PostingDidDelete`. An array with `range_id` and `keyword` of the wiki page is also passed there for the sake of completeness.


#### Event overview

When clicking on the link "Mark all as read" on the event overview, the notification `OverviewWillClear` is sent before the action and the notification `OverviewDidClear` is sent afterwards. Both notifications receive the user's ID as `subject`.


#### Module management

**Notifications: `CourseRemovedFromModule` and `CourseAddedToModule`**
```php
NotificationCenter::postNotification(
    'CourseRemovedFromModule',
    $studyarea,
    ['module_id' => $sem_tree_id, 'course_id' => $seminar_id]
);

NotificationCenter::postNotification(
    'CourseAddedToModule',
    $studyarea,
    ['module_id' => $sem_tree_id, 'course_id' => $seminar_id]
);
```

<TODO: elmar or anoack>


#### Sidebar

##### SidebarWillRender

**Additional parameters for observer method:**
* ?

**Sending condition:** ?


#### System configuration

**Notification: `ConfigValueChanged`**

This notification is sent if a parameter contained in the `Config` class has changed. The Config instance is given as `subject` and the new and old value as `userdata`.

<TODO: elmar or anoack>

```php
NotificationCenter::postNotification('ConfigValueChanged',
    $this,
    [
        'field' => $field,
        'old_value' => $old_value,
        'new_value' => $value_entry->value
    ]
);
```
