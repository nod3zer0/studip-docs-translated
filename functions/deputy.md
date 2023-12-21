---
id: deputy
title: deputy
sidebar_label: deputy
---


# General


As of Stud.IP 2.0, functionality is available to define deputies of persons who, for example, have the full rights of lecturers within courses but are not visible to the outside world. In addition, standard substitutions of a person can also be defined, which are then automatically added to courses as substitutions if the person in question is a lecturer. Furthermore, such default substitutes can be given the right to fully edit the profile page of the person whose default substitute they are.


Associated functions are defined in the file `lib/deputies_functions.inc.php`.


For the substitution functionality to be active, the variable `DEPUTIES_ENABLE` must be set in the global configuration. In order for people to be able to define their standard substitutions, `DEPUTIES_DEFAULTENTRY_ENABLE` must also be activated, and `DEPUTIES_EDIT_ABOUT_ENABLE` must be activated in order to be able to transfer rights to edit one's own profile to the substitution.


# Query and change substitutions


### Query existing substitutions


To query the existing substitutions for an event or person, there is the method `getDeputies`, which receives an event or person ID and optionally a name format (as usual something like "full" or "full_rev", default is "full_rev") and then returns an array of the substitutions:
```php
// Define a seminar ID here...
$seminar_id = '9a739ae7fffab0c2347a783cef0f69be';
// ... and get the substitutions in this event
$deputies = getDeputies($seminar_id);
```
leads to the following result:


```php
$deputies = Array
(
    [a272fef013c2b9367d1525daeb307c95] => Array
        (
            [user_id] => a272fef013c2b9367d1525daeb307c95
            [username] => tester
            [first name] => Toni
            [last_name] => tester
            [edit_about] => 0
            [perms] => tutor
            [fullname] => Tester, Toni
        )


)
```
As can be seen, an array is returned with the respective user IDs as keys and the following data:


* User ID
* Username
* First name
* Last name
* May the person edit the profile page of the "boss" (always 0 for events, of course)
* Global permission level of the representative
* Full name according to the format specified in the call


## Query of which persons a user is a substitute


For the reverse case, i.e. to determine who a particular person is the default substitute for, there is the `getDeputyBosses` function:


```php
// Get all persons of whom user ID #12345' is the deputy:
$bosses = getDeputyBosses('12345');
```
Here (analogous to `getDeputies`) an array with user data is returned.


## Adding or removing deputies


The functions `addDeputy`, `deleteDeputy` and `deleteAllDeputies` exist for changing existing deputies.


Here are examples of their use:


```php
// Add user with ID '12345' to the event with ID '67890' as a deputy:
addDeputy('12345', '67890');


// Remove user with ID '12345' as deputy from the event
// event '67890':
deleteDeputy('12345', '67890');


// Delete all deputies from the event '67890':
deleteAllDeputies('67890');
```


The function `setDeputyHomepageRights` is used to give or remove the rights to edit the profile page from a person's default deputy:


```php
// Substitute with ID '12345' gets the right to edit the
// edit the profile page of user ID 'abcde':
setDeputyHomepageRights('12345', 'abcde', 1);
```


# Query whether representation
The `isDeputy` method can be used to query whether a person is a substitute in an event or for a specific other person. The user ID of the person to be queried and the ID of the event or person where person 1 is to be a substitute are specified as parameters. Optionally, you can also query whether the person is a substitute with profile editing rights.


```php
// If person 1 with ID '12345'...
$person1_id = '12345';
// ... Substitute for person 2 with ID 'abcde' ...
$person2_id = 'abcde';
// ... whether with profile editing rights or not.
$result = isDeputy('12345', 'abcde');


// Also check whether person 1 is allowed to edit the profile page of
// Person 2 is allowed to edit:
$result = isDeputy('12345', 'abcde', true);
```
A similar query is made for events; here, of course, the query may only be made without the profile editing rights.


# Other functions
The `getValidDeputyPerms` function can be used to query the minimum authorization a person must have in order to be entered as a substitute (the maximum authorization is always 'lecturer'). At the moment, the authorization 'tutor' is permanently implemented here, i.e. only persons with the global authorization 'tutor' or 'dozent' are permitted.


The function `haveDeputyPerm` checks for a specified user ID whether this person has the necessary rights to be entered as a substitute.


Database queries are stored in `getMyDeputySeminarsQuery`, which are used to find the courses in which the current user is a substitute. Depending on the context, this is important for My Events, grouping and notification settings as well as the notification cronjob. The resulting database query is linked to the normal query for the respective data via "UNION".
