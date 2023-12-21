---
id: multi-person-search
title: MultiPersonSearch class
sidebar_label: MultiPersonSearch class
---

`lib/classes/MultiPersonSearch.class.php` provides a class that can be used to create a dialog for adding multiple persons. If JavaScript is activated, a [Modal dialog](ModalDialog#toc5) is opened, otherwise a fallback is displayed.

Attach:MultiPersonSearch.png

## Installation in the view

The following source code is used to create a MultiPersonSearch object and output it as a link.

```php
$mp = MultiPersonSearch::get('unique_id')
    ->setLinkText(_('Example link'))
    ->setTitle(_('Title of the dialog'))
    ->setDefaultSelectedUser($defaultSelectedUser)
    ->setExecuteURL($this->url_for('controller'))
    ->setSearchObject($searchObj)
    ->addQuickfilter(_('Name of the quick filter'), $userArray)
    ->render();

print $mp;
```


### Overview of important methods

* *setLinkText($text)* sets the name of the link that opens the dialog.
* *setTitle($title)* sets the name of the dialog title.
* *setDescription($desc)* sets the description of the dialog that is displayed under the title.
* *setDefaultSelectedUser($userArray)* sets all users who have already been added (e.g. all participants who are already registered in an event). *$userArray* is an array consisting of user IDs.
* *setDefaultSelectableUser($userArray)* sets a set of persons that are displayed on the left side of the dialog by default. *$userArray* is an array consisting of user IDs.
*setExecuteURL($action)* sets the link of the controller that processes the selection.
* *setSearchObject($searchType)* sets a *SearchType* object (e.g.: SQLSearch) that is used to search for persons.
*addQuickfilter($title, $userArray)* adds a quick filter consisting of a title and an array of user IDs.
* *setJSFunctionOnSubmit($function_name)* adds a JavaScript function that is executed as soon as the save button is clicked.
* *setLinkIconPath($path)* sets a link icon (default value: icons/16/blue/add/community.png).

## Processing
In order to save the persons selected via the dialog, a corresponding URL (e.g. to a controller) must be provided using `setExecuteURL($action)`.

The `MultiPersonSearch` object can now be loaded in the controller using `load($name)`. The function `getAddedUsers()` returns an array with all newly selected user IDs.
```php
$mp = MultiPersonSearch::load('unique_id');

foreach ($mp->getAddedUsers() as $userId) {
    do_something($userId);
}
```
