---
id: visibility
title: VisibilityAPI
sidebar_label: VisibilityAPI
---

With the help of the VisibilityAPI, visibility settings for users can be added from anywhere in the system (including in plugins) without having to intervene directly in the visibility code. It is also possible to manage the setting options without interfering with existing code.

## Visibility levels

The VisibilityAPI offers the option of defining which visibility levels are available directly in the file system. The folder for this is lib/classes/visibility/visibilitySettings.
This contains the following visibility settings by default:

* Me
* Buddies
* Domain
* Studip
* External

Example
```php
class Visibility_Buddies extends VisibilityAbstract{

    // Should this status be able to be used
    protected $activated = true;

    // Which int representation in the database
    protected $int_representation = 2;

    // What is displayed in the settings
    protected $display_name = "Buddies";

    // What is displayed in Visibility::getStateDescription()
    protected $description = "only visible to my buddies";

    // When do two users have this status
    function verify($user_id, $other_id) {
        return CheckBuddy(get_username($other_id), $user_id) || $user_id == $other_id;
    }
}
```
A visibility setting must always contain the class Visibility_+"Name of file", which extends VisibilityAbstract. The following attributes and functions must also be present:
* **activated**: Defines whether the visibility setting should be available. Changes are only applied after a re-log, as the visibility settings are saved in the session for I/O cost reasons.
**int_representation**: Defines the value under which a visibility is saved in the database. There must be no overlap.
* **display_name**: Describes the name under which the item appears in the settings.
* **description**: Is a description of the setting that can be used to display the current setting to the user
** **function verify**: The main task of a visibility setting is the verify function. It must always receive a UserID (specifies the owner of the visibility object) and an OtherID (specifies the caller). The return value must be `true` if the caller (other_id) is in the correct relation to the owner (user_id).

A nobody visibility is also available. This is particularly useful for debugging.

## Visibility of the homepage elements

In principle, a visibility setting can be defined in two ways:
* **VisibilityID**: A unique ID that is generated during creation
**Identifier and UserID**: An identification string can be resolved to a visibility ID using a userID. Although this makes programming easier, it can lead to overlaps in identification strings under certain circumstances. Care should therefore be taken to ensure that this is as unique as possible.

### Adding a visibility setting
If a visibility setting is to be added for a user, the following call is sufficient:
```php
Visibility::addPrivacySetting($name, $identifier = "", $parent = null, $category = 1, $user = null, $default = null, $pluginid = null)
```
A visibility ID is generated and returned, which can be saved.
* **name**: The name under which the visibility setting appears to the user
* **identifier**: Identification string
* **parent**: The visibility settings offer to create a tree. If this is desired, the visibility ID or the identification string for the parent node must be specified here. Otherwise, zero must be entered here to create a root node. Attention! Root nodes are always a category, i.e. there are no setting options
* **category**: Distinguishes the type of visibility setting. 0 stands for a category that contains no setting options. 1 For a "normal" settings item.
* **user**: Specifies the user for whom the visibility is created. If this value is 'zero', the currently logged in user is used.
**default**: Can specify which visibility the value is initially set to. If this value is `null`, the default value of `user` is used
**pluginid**: Can be used to forcibly assign a pluginID to the visibility. If the function is called from a plugin, it is not necessary to specify a plugin ID, as the API finds this out automatically.

### Changing a visibility setting
The function `updatePrivacySetting` receives the same parameters as `addPrivacySetting` and is used to update a visibility setting. The old visibility setting is deleted and a new one is created. This makes it easier for the programmer not to have to check whether a visibility already exists. If a visibility is linked to an input field, the function `updatePrivacySettingWithTest` can be used, which also receives a string as the first parameter. If this string is empty, the visibility setting is only deleted.

### Deleting a visibility setting
The function
```php
Visibility::removePrivacySetting($id, $user = null)
```
deletes a visibility setting based on a visibility ID ($id) or based on an identification string ($id) and a user ID ($user)

### Bulk functions
During a migration, it may be necessary to add a new visibility setting to all users. The command
```php
Visibility::addPrivacySettingForAll($name, $identifier = "", $parent = null, $category = 1, $default = null, $pluginid = null)
```
can be used. To delete all entries of an identification string, the function
```php
Visibility::removeAllPrivacySettingForIdentifier($ident)
```
is used.

### Verification
To check the visibility in the code, the following code can be used:
```php
//Verification with ID
if (Visibility::verify(1234)) {
 echo 'I am allowed to see VisibilityID 1234';
}

//verification with identifier and user name
if (Visibility::verify('homepageelement', $called_user->md5) {
  echo 'I am allowed to see the homepage element of '.$called_user.';
}

//Verification for other users
if (Visibility::verify('homepageelement', $called_user->md5, $test_user->md5) {
  echo $test_user.' is allowed to see the homepage element of '.$called_user;
}
```

# Old version

## Visibility levels
Extended options for defining personal privacy and visibility are available from Stud.IP version 2.0.

The functions for querying the visibility levels are defined in lib/user_visible.inc.php. The existing visibility levels are defined there as constants:

* **VISIBILITY_ME**: Only for the user themselves (and their possible standard representatives with homepage editing rights)
**VISIBILITY_BUDDIES**: for buddies from the address book
**VISIBILITY_DOMAIN**: for the user's own user domain(s)
**VISIBILITY_STUDIP**: for all users logged into Stud.IP
**VISIBILITY_EXTERN**: on external pages

# General visibility of an identifier
If the visibility of an identifier is to be queried, the methods `get_visibility_by_id` or `get_visibility_by_username` or `get_visibility_by_state` are available.

```php
// Returns true or false, depending on the visibility of the identifier
$visibility = get_visibility_by_username('tester');

/*
 * If the visibility stored in the database
 * already exists in the database, it can be queried as follows:
 */
// If the visibility is 'yes'
$db_vis = 'yes'

$visibility = get_visibility_by_state($db_vis, get_userid('tester'));
```
The result here is: "Can I see the identifier?", which depends not only on the visibility settings of the identifier, but also on my own rights (root sees everything).

To be able to explicitly query the global visibility, regardless of root rights or similar, there are the methods `get_global_visibility_by_id` and `get_global_visibililty_by_username`, which receive the user ID or the user name as a parameter and return the visibility stored in the database. A value from the set `{'yes', 'no', 'always', 'never', 'unknown', 'global'}` is therefore returned here

The methods `get_local_visibility_by_id` and `get_local_visibility_by_username` are available for querying the visibility in a specific area of Stud.IP. This can be used to query the visibility in this area by specifying the user ID or user name and the desired area. Valid areas are

**online** for the who is online list
**chat** for the visibility of your own chat room
**search** for the findability in the people search
**email** for the display of the email address
**homepage** for the visibility settings of the individual elements of the profile page

For example, if you want to know whether the user with the username 'tester' can be found via the people search, you can query this as follows:

```php
$search_visibility = get_local_visibility_by_username('tester', 'search');
```
Particularly on external pages, it is also useful to know which authorization the user to be queried has in the system. For this reason, you can optionally specify that this authorization should also be returned:

```php
$search_visibility = get_local_visibility_by_username('tester', 'search', true);
```
then leads to the output

```php
$search_visibility = Array(
  'perms' => 'tester',
  'search' => true
);
```

## Visibility of the homepage elements
On a person's profile page, all visibilities of the individual elements are loaded by default at the beginning. This minimizes the number of database queries by only having to execute one global query for all elements instead of one query per element.

The functions `is_element_visible_for_user` and `is_element_visible_externally` can then be used to check whether an individual element should be displayed for the current user based on its visibility settings.

Here is an example: The element private_phone (i.e. the private telephone number) was loaded from the database with the visibility 1 (=VISIBILITY_ME), i.e. it should only be displayed for the owner of the homepage itself. The method `is_element_visible_for_user` now receives the ID of the current user, the ID of the user to whom the currently visited homepage belongs and the value of the visibility, i.e. 1, as parameters. This is now used to determine whether the telephone number should be displayed or not.

In the code it looks like this:

```php
// The "owner" of the homepage has the ID '12345'
$visibilities = get_local_visibility_by_id('12345', 'homepage');
// The visitor of the homepage has the ID 'abcde'
$private_phone = is_element_visible_for_user('abcde', '12345', $visibilities['private_phone']);
```
If you are only interested in individual elements of the homepage, you can also explicitly query their visibility:

```php
// Homepage owner ID '12345' again
$private_phone_visibility = get_homepage_element_visibility('12345', 'private_phone');
```
For performance reasons, only the first variant is executed for an entire homepage, where all visibilities are loaded at once.

The method `get_visible_email` can be used to determine the externally visible email address. If a user has set that their own email address should not be displayed, an attempt is made instead to determine an email address via the device assignment of this identifier (only assignments with at least the right author). The email address of the first institution found is used first; if there are several institution assignments and one of them is defined as the default institution, this email is used. If no assignment is found, an empty string is returned as the email address.
