---
id: user-lookup
title: Use of the class UserLookup
sidebar_label: Use of the UserLookup class
---

##

The new class (from version 2.1) is used to filter users according to special criteria.


### General
This class was developed to create a standardized, reusable solution for filtering or searching for users based on specific criteria.

Different criteria (even of the same type with different filter values) can be combined. Finally, the intersection of all the individual filter criteria applied is formed internally.

The following criteria can be filtered by default:

* Subject of study (*fach*)
* Degree (*degree*)
* Semester of study (*subject*)
* Institution affiliation (*institute*)
* User status (*status*)

### Methods of the 'UserLookup' class
The public operations of the class `UserLookup` are documented here.

#### Class methods
**UserLookup::getValuesForType($type)**
Returns all possible values for a given type. These values are kept in the cache for one hour with the default settings in order to reduce the load on the database.

**UserLookup::addType($name, $values_callback, $filter_callback)**
Adds a new filter criterion *type*. If a criterion with the same name already exists, it is overwritten.

*$values_callback* specifies which method is used to return all possible values for this criterion. If possible, this method **should** return a one-dimensional, associative array (key = Id of the value).

*$filter_callback* specifies which method is used to apply the filter criterion. This method **must** return a list of user IDs to which the filter criterion applies.

The two callback parameters must be valid [PHP callbacks](http://php.net/manual/language.pseudo-types.php#language.types.callback).

#### Instance methods
**setFilter($type, $value)**
Adds a new filter of the criterion *$type* to the current selection of filters. *$value* can either be an atomic value or, for simplification, an array of values.

* **clearFilter**
Deletes all set filter criteria.

* **execute($flags)**
Applies the current selection of filters. By default, an array containing all matching user IDs is returned.
The return can be further controlled via optional flags:

| Flag | Description |
| ---- | ---- |
|FLAG_SORT_NAME|The returned ids are sorted by the names of the associated users (ascending by surname and first name). |
|FLAG_RETURN_FULL_INFO|Instead of an array with the pure user IDs, an associative array is returned that contains the user ID as the key and an array with the following details of the respective user as the value: *username*, *firstname*, *lastname*, *email* and *perms* |


### Examples

A few small examples of the use of the `UserLookup` from practical use in Stud.IP should be collected here.

```php
# Create a new UserLookup object
$user_lookup = new UserLookup;

# Filter all users in their first to sixth semester
$user_lookup->setFilter('fachsemester', range(1, 6));

# Filter all users with the subject 'fach123' or 'fach456'
$user_lookup->setFilter('fach', array('fach123', 'fach456'));
/* Equivalent:
$user_lookup->setFilter('fach', 'fach123');
$user_lookup->setFilter('fach', 'fach456');
*/

# Filter all users that have an 'author' or 'tutor' permission
$user_lookup->setFilter('status', array('author', 'tutor'));

# Get a list of all matching user ids (sorted by the user's names)
$user_ids = $user_lookup->execute(UserLookup::FLAG_SORT_NAME);

# Get another list of all matching user ids but this time we want
# the complete unordered dataset
$user_ids = $user_lookup->execute(UserLookup::FLAG_RETURN_FULL_INFO);
```
