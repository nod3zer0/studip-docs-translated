---
id: event-logging
title: Event-Logging
sidebar_label: Event-Logging
---


As of version 1.3, a central logging mechanism was implemented that records certain actions with a timestamp and user ID.

With version 3.0, logging has been extended so that any objects can generate log entries.

## Examples:

* "We use Stud.IP in some subjects for binding registration for courses, some of which are very competitive. Last time, there were about half a dozen times when students claimed that they had registered and had a place a week ago, but now it was gone. As things stand at the moment, we have no way of tracking this."
* "In case of doubt, the assignment of courses to study areas must be approved by several committees. Stud.IP does not (intentionally) offer any complete control mechanisms here. Therefore, in case of doubt, it is necessary to be able to trace who has made an assignment."
* "In connection with room bookings, there are often questions about who booked a room or when requests were changed."
* "We have around 80 administrators and it would be very helpful to be able to track who created, deleted, upgraded etc. a user."

## Basics:

* There is a general event system that can be called from various places in the system and provides an event entry
* The event system is database-based
* There is a root-accessible page on which log events can be searched for and displayed according to various criteria
* The display is easy to read, yet storage should be resource-efficient and information should be stored in a structured manner.
* In order to take data protection aspects into account appropriately, Root can switch the logging of individual events on and off centrally (available since version 1.3.) and an automatic deletion of individual events can be activated after a certain time.

### Screenshot:

![image](../assets/d853edb1d90bc82e3dc0d69fcb2927b4/image.png)

## Technical implementation

Logging is accessed via the API class StudipLog, which provides all methods for using logging.

Any object can log events (which usually concern the object itself) if it implements the Loggable interface. However, log actions registered in the database can in principle be used anywhere.

StudipLog uses two model classes:
* LogAction: To manage the description and data of actions. A LogAction object is therefore a template for an event that can be logged. E.g. "Deregistration of user X from event Y by user Z".
* LogEvent: The logged event or the event to be logged. It contains the data of a specific event and always refers to a LogAction. For the LogAction mentioned above, this would be the data for X, Y and Z as IDs of the respective objects.

The global function log_event(), which is still available, should no longer be used.

### Using the StudipLog class

#### Creating a new LogAction

#### Implementation of the Loggable interface

There are two new tables:

### log_actions

This table contains descriptions and data of actions, e.g. "Create a new event".

```sql
CREATE TABLE `log_actions` (
`action_id` INT( 10 ) NOT NULL AUTO_INCREMENT , // ID
`name` VARCHAR( 128 ) NOT NULL , // Identifier, is also used in the code
`description` VARCHAR(64), // Short description for search interface
`info_template` TEXT, // Template for plain text output
`active` TINYINT( 1 ) DEFAULT '1' NOT NULL , // currently active?
`expires` INT( 20 ) , // Number of seconds until automatic deletion
   PRIMARY KEY ( `action_id` )
  );
```

An entry then looks like this, for example:

```sql
action_id=3,
name=SEM_VISIBLE
description="Make event visible"
info_template="%user makes %sem(%affected) visible."
active=1
expires=NULL
```

The info template string can contain a few placeholders; overall, the text shown in the screenshot above is generated when the log is displayed.

expires can be used to automatically delete entries after a certain time, e.g. for data protection reasons or to save space. A special interface (not yet implemented) can be used to simply switch the logging of certain actions on and off.

The individual events are stored in a second table:

### log_events

```sql
CREATE TABLE `log_events` (
`event_id` INT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT , // ID
`timestamp` INT( 20 ) NOT NULL , // Unix timestamp
`user_id` VARCHAR( 32 ) NOT NULL , // Acting user (subject)
`action_id` INT( 10 ) NOT NULL , // Action (verb)
`affected_range_id` VARCHAR( 32 ) , // primary affected object (direct object)
`coaffected_range_id` VARCHAR( 32 ) , // secondarily affected object (indirect object)
`info` TEXT, // additional information text
`dbg_info` TEXT, // additional technical information
   PRIMARY KEY ( `event_id` )
);
```

With the chosen approach, logging achieves two things:

* Readable output in natural language
* Full searchability for affected objects (events, rooms, facilities)


## Call log_event()

### Normal call

In the code, the places where an action is triggered must be identified and usually a line like:

```php
log_event("SEM_CREATE",$sem_id);
```

are inserted. If more than two objects are affected, the additional information (not searchable) is added to the info text, e.g. the information about the booked time when resolving a room request. The debug information can include details about the location in the code from which the action was executed, complete queries can be stored, etc.

```php
function log_event($action, $affected=NULL, $coaffected=NULL, $info=NULL, $dbg_info=NULL, $user=NULL) {
...
}
```

| Variable | Description|
| ---- | ---- |
|$action|Text ID of the log event|
|$affected|ID of the object that ends up in the database field affected (can be event, institute, user, resource, ... depending on the event - nothing is checked but simply entered). depending on the event - nothing is checked, but simply entered)|
|$coaffected|ID of the object that ends up in the coaffected database field (can vary depending on the event, institution, user, resource, ... depending on the event - nothing is checked, but simply entered)|
|$info|Free text for info| field
|$dgb_info|Free text for debug info field|
|$user|Normally, the user_id of the actor is taken from the session. A different user_id can be specified here, e.g. for actions executed by "[=%%__SYSTEM__%%=]".

The function checks whether logging is switched on and the desired event is active.

TODO: Examples... Until then: Search for log_event(...) in the code ;-)

### Error during logging

If an event is not found in the log_actions table, the event call is not discarded, but saved under the LOG_ERROR event with all transferred parameters in the info text:

```php
log_event("LOG_ERROR",NULL,NULL,NULL, "log_event($action,$affected,$coaffected,$info,$dbg_info) for user $user");
```

## Add a new action

### Entry in log_actions

Event templates (actions) are not created via the interface (would be of little use, as code has to be touched anyway), but are written directly to the database with an SQL statement. The action_id (MD5 key) can be freely selected, but must be unique. It is advisable to use md5(name).

### Triggering events

Events for the new action can then be triggered as described above using `log_event(<ACTION_NAME>, ...)`. The semantics of the other parameters result from the template in log_actions.

### Retrieving the events

The events are then automatically available to all root users via the log tool (Manage global settings -> Tools -> Log). The new actions are contained in the selection box on the left, where the description entry is displayed.

### Example: Action for "Change e-mail address"

You want to log who changed whose e-mail address, when and to which value.

#### Idea

* WHO changed is stored as the user_id of the event
* FOR WHOM was changed as affected_id
* The NEW VALUE is not a Stud.IP object, so must be stored as free text in info. Then even better: Store new AND old value, e.g. "from tobias.thelen@beispiel.test to thelen@anderesbeispiel.test".

#### The database entry for log_actions:

| table | value |
| ---- | ---- |
|action_id|21b0b3fc30605876686617a1aec92321|
|name|CHANGE_EMAIL|
|description|Change email address|
|info_template|`%user changes/sets email address for %user(%affected): %info.`
|active|1|
|expires|0|

#### Use of the event:

`log_event("CHANGE_EMAIL",$user_id,*, "from tobias.thelen@beispiel.test to thelen@anderesbeispiel.test");`

## Standard events

```sql
CREATE TABLE `log_actions` (
  `action_id` varchar(32) NOT NULL default *,
  `name` varchar(128) NOT NULL default *,
  `description` varchar(64) default NULL,
  `info_template` text,
  `active` tinyint(1) NOT NULL default '1',
  `expires` int(20) default NULL,
  PRIMARY KEY (`action_id`)
);

INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('0ee290df95f0547caafa163c4d533991', 'SEM_VISIBLE', 'Make event visible', '%user switches %sem(%affected) visible.', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('a94706b41493e32f8336194262418c01', 'SEM_INVISIBLE', 'Make event invisible', '%user hides %sem(%affected) (invisible).', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('bd2103035a8021942390a78a431ba0c4', 'DUMMY', 'Dummy action', '%user does something.', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('4490aa3d29644e716440fada68f54032', 'LOG_ERROR', 'General log error', 'General logging error, see debug info for details', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('f858b05c11f5faa2198a109a783087a8', 'SEM_CREATE', 'Create event', '%user creates %sem(%affected)', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('5b96f2fe994637253ba0fe4a94ad1b98', 'SEM_ARCHIVE', 'Archive event', '%user archives %info (ID: %affected).', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('bf192518a9c3587129ed2fdb9ea56f73', 'SEM_DELETE_FROM_ARCHIVE', 'Delete event from archive', '%user deletes %info from archive (ID: %affected).', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('4869cd69f20d4d7ed4207e027d763a73', 'INST_USER_STATUS', 'Change institution user status', '%user changes status for %user(%coaffected) to institution %inst(%affected): %info.', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('6be59dcd70197c59d7bf3bcd3fec616f', 'INST_USER_DEL', 'Delete user from institution', '%user deletes %user(%coaffected) from institution %inst(%affected).', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('cf8986a67e67ca273e15fd9230f6e872', 'USER_CHANGE_TITLE', 'Change academic title', '%user changes/set academic title for %user(%affected) - %info.', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('ca216ccdf753f59ba7fd621f7b22f7bd', 'USER_CHANGE_NAME', 'Change personal name', '%user changes/sets name for %user(%affected) - %info.', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('8aad296e52423452fc75cabaf2bee384', 'USER_CHANGE_USERNAME', 'Change user name', '%user changes/sets user name for %user(%affected): %info.', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('59f3f38c905fded82bbfdf4f04c16729', 'INST_CREATE', 'Create institution', '%user creates institution %inst(%affected).', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('1a1e8c9c3125ea8d2c58c875a41226d6', 'INST_DEL', 'Delete institution', '%user deletes institution %info (%affected).', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('d18d750fb2c166e1c425976e8bca96e7', 'USER_CHANGE_EMAIL', 'Change email address', '%user changes/set email address for %user(%affected): %info.', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('a92afa63584cc2a62d2dd2996727b2c5', 'USER_CREATE', 'Create user', '%user creates user %user(%affected).', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('e406e407501c8418f752e977182cd782', 'USER_CHANGE_PERMS', 'Change global user status', '%user changes/sets global status of %user(%affected): %info', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('63042706e5cd50924987b9515e1e6cae', 'INST_USER_ADD', 'Add user to facility', '%user adds %user(%coaffected) to facility %inst(%affected) with status %info', 1, NULL);
INSERT INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('4dd6b4101f7bf3bd7fe8374042da95e9', 'USER_NEWPWD', 'New password', '%user generates new password for %user(%affected)', 1, NULL);
```
