---
id: troubleshooting
title: Troubleshooting
sidebar_label: Troubleshooting
---

### PHP in general

#### Wrong time differences? Use gmdate() instead of date() for time differences!

Time information in Stud.IP is often given as seconds in Unix time (seconds since 1.1.1970 0:00:00 UTC). As these are timestamps in normal positive integers, they can be used for calculations without any problems and the time difference between two time specifications can be calculated by a simple subtraction.

As the difference between two time specifications is in Unix time, i.e. in UTC, the gmdate() function must be used for the output instead of date(), as date() also takes the time zone of the transferred timestamp into account. On 1.1.1970, the Central European time zone (UTC + 1 hour) applied, which means that date() adds one hour to the difference. gmdate(), on the other hand, ignores the time zone and returns the correct time difference.

### Stud.IP classes and methods

#### in URLs with parameters `&` is replaced by `&amp;`

The method getLink() of the URLHelper class was probably used. The difference between getLink() and getURL() is that getLink() replaces all characters that could be problematic in HTML code with their HTML entities. The HTML entity of `&` is `&amp;` (for ampersand).

To solve the problem, getLink() is simply replaced by getURL().


### SimpleORMap
#### Error message "Invalid argument supplied for foreach()" in SimpleORMap

The following error messages appear:

```shell
Warning: Invalid argument supplied for foreach() in /var/www/studips/studip-trunk/lib/models/SimpleORMap.class.php on line 1619

Warning: in_array() expects parameter 2 to be array, null given in /var/www/studips/studip-trunk/lib/models/SimpleORMap.class.php on line 1311
```

The problem occurs when accessing a database table that does not have a primary key (which is usually called "id"). To add a primary key, execute the following command in the MySQL console on the affected table:
`ALTER TABLE tabellenname ADD PRIMARY KEY(id);`

The Stud.IP cache must then be deleted. If it is located under /tmp/studip-cache/, change to this directory and execute `rm -rf ./*` to delete the entire contents of /tmp/studip-cache/.

#### Error message "Passed variable is not an array or object, using empty array instead" when using a SimpleORMap relation

This problem can occur if you try to access a SimpleORMap relation in the plugin that was defined with "has_many". The foreign key was set correctly via the "foreign_key" parameter, the associated foreign key (parameter "assoc_foreign_key") was also set correctly, but is not taken into account.

The problem is that the target class of the relation has not been included in the controller in which the relation is accessed. If the target class of the "has_many" relation is included, the problem disappears. Other types of SimpleORMap relations can also be affected by the problem.


#### RuntimeException with the message: "assoc_func: ModelClass::findBySomeAttribute is not callable"

When using a "has_many" relation, this error message can occur when accessing the relation. The problem is that the model, which is the target of the relation, is not included anywhere and therefore the model class is not known.

The solution is to simply include the model class using require_once. This is best done in the file in which the relation is accessed.


#### My file types or similar structures provided by the plugin are not available in the API

The APIs (REST and JSON) do not load all system plugins. If new data structures are provided via a system plugin, the plugin must also implement the interface `RESTAPIPlugin` or `JsonApi\Contracts\JsonApiPlugin` so that these structures are also available within API calls.
