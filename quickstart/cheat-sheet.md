---
id: cheat-sheet
title: Cheat-Sheet
sidebar_label: Cheat-Sheet
---


## User


### Find current user
```php
User::findCurrent();
```


### Read value from the user configuration


```php
$value = UserConfig::get(User::findCurrent()->id)->getValue('WERT_NAME');
```


### Save value in the user configuration


```php
UserConfig::get(User::findCurrent()->id)->store('WERT_NAME', $value);
```


IMPORTANT: `$value` is stored as a string by default, unless otherwise specified in the config table!
Arrays should be coded beforehand with `json_encode($array);`!


### Check whether a user is an administrator


```php
$GLOBALS['perm']->have_perm('admin')
```


Returns true if the user has either 'root' or 'admin' permissions, otherwise false.




## SimpleORMap


### Courses


Courses (/lib/models/Course.class.php)


### Archived courses


ArchivedCourses (/lib/models/ArchivedCourse.class.php)


### Lecturers


User class, all objects for which the "perms" attribute is set to "lecturer".


### User


User (/lib/models/User.class.php), extension of AuthUserMd5, which contains the basic data (e.g. first name, last name, user name) of a user.


## Controller-relevant classes


### URLs


#### Generate URL


```php
URLHelper::getLink('dispatch.php/CONTROLLER);
```
where CONTROLLER is the controller to be called.


**IMPORTANT**: `getLink` modifies URLS so that they can be embedded in HTML code.
For example, & becomes &amp;. If you do not want this conversion, `URLHelper::getURL();` should be used.


#### URL generation in the plugin


```php
PluginEngine::getLink(PLUGIN, PARAMETER, PATH);
```


`PLUGIN = $this->plugin` (in the controller) or `$plugin` (in the template), PARAMETER = associative array, PATH = path to the controller)


**IMPORTANT**: getLink modifies URLS so that they can be embedded in HTML code. For example, & becomes &amp;.
If you do not want this conversion, `PluginEngine::getURL();` should be used.


As soon as you are in a Trails app, however, the controller method `url_for()` should be used, which encapsulates and simplifies the above call.


#### Icon creation


```php
Icon::create(SYMBOL, CATEGORY)->asImg(SIZE);
```
* SYMBOL = the icon to be displayed
* CATEGORY = color classification of the icon (e.g. `Icon::ROLE_CLICKABLE`),
* SIZE = specification in pixels (e.g. "12px")


#### Make URL parameters "permanent"


The addLinkParam method of URLHelper is used to pass a parameter when the next page is called:


`URLHelper::addLinkParam('name', VALUE);`


The name parameter with the value VALUE is now appended to the URL (e.g.: `http://example.org?name=WERT`).


## Display


#### Navigation elements


##### Add navigation element on the start page


```php
<?php
$navigation = new Navigation('LINK DESCRIPTION', 'ONE_URL'); //create the navigation element with a suitable text and the desired URL
Navigation::addItem('/start/ID', $navigation); //ID = unique name of the navigation element. /start/ must be there in any case so that the element is displayed on the start page
```


##### Create tab navigation (tabs)


```php
<?php
$navigation = new Navigation('LINK DESCRIPTION', 'ONE_URL');
Navigation::addItem('/ID', $navigation); //ID = unique path. This is located in the "root", i.e. not below other paths such as /start/
```


The navigation element must be activated in the controller that can be accessed via the navigation element above:
```php
<?php
Navigation::activateItem('/ID');
```


**Note:** Sub-paths are of course also possible here, e.g. /ID/NOCHEINEID.


##### Insert icon in tab navigation


When adding icons to the tab navigation, it must be noted that the icon of an active tab has a different color than the icon of an inactive tab. The icon must therefore be changed when a tab is activated.


Definition of the tab in the navigation structure:
```php
<?php
$navigation = new Navigation(
    'Text',
    PluginEngine::getUrl('on/link')
    );
$navigation->setImage(Icon::create('edit', Icon::ROLE_NAVIGATION));
Navigation::addItem('/navigations/path', $navigation);
```


#### Hint texts


Options for hint texts:


| type | description |
| ---- | ---- |
| `MessageBox::error` | error messages |
| `MessageBox::info` | Information |
| `MessageBox::warning` | warning messages (but no errors) |
| `MessageBox::success` | Success confirmations (actions that have been successfully completed) |




More information: [MessageBox](MessageBox)


##### Output information texts from the controller


```php
<?php
PageLayout::postError(_('Error!'));
```


More about PageLayout: [PageLayout](PageLayout)


#### Sidebar


##### Add navigation area


```php
<?php
$navigation = new NavigationWidget();
$navigation->setTitle('Title of section');


//a link is added here:
$navigation->addLink(
    'A link title',
    PluginEngine::getURL($this->plugin, [], 'show')
);


Sidebar::Get()->addWidget($navigation); //Mount navigation area in the sidebar
```




##### Add link to dialog in ActionsWidget


```php
<?php
$actions = new ActionsWidget();
$actions->addLink(
    'Description',
    URLHelper::getURL('dispatch.php/CONTROLLER'),
    Icon::create(SYMBOL, CATEGORY)
)->asDialog();
```


CONTROLLER is the controller to be called, SYMBOL is the selected icon whose color is set by the category CATEGORY. The asDialog() method (LinkElement class in /lib/classes/sidebar) is used to set the HTML attribute "data-dialog" when creating the HTML code of the link.


##### Add quick search (search with drop-down menu) to a search field in the sidebar


```php
<?php
$searchWidget = new SearchWidget(PluginEngine::getLink($this->plugin, [], 'search'));
$searchWidget->setTitle(_('Search'));
$searchWidget->setMethod('post');

$sqlSearch = new SQLSearch("SELECT auth_user_md5.user_id as userId FROM auth_user_md5 " .
    "WHERE ((firstname like CONCAT('%', :input, '%') " .
    "OR (lastname like CONCAT('%', :input, '%')) ",
    _('username')
    );


//Add QuickSearch to the SearchWidget:
$searchWidget->addNeedle(
    _('username'),
    'userId',
    _('username'),
    $sqlSearch
    );
```


More about QuickSearch: [QuickSearch](QuickSearch)




#### Templates


##### Create button


```php
<?= \Studip\Button::create(_('Save')); ?>
```


##### Create button for dialog


```php
<div data-dialog-button>
    <?= \Studip\Button::create(_('Save')); ?>
</div>
```




### Plugins


#### Find out whether another plugin is activated


This is useful, for example, if a plugin is dependent on another plugin or its classes. The following code checks whether another plugin is activated:
```php
$pluginManager = PluginManager::getInstance();
$pluginManager->getPluginInfo('OtherPlugin');
//$pluginManager now contains data about the other plugin you are looking for.
if ($pluginManager['enabled']) {
    //the other plugin is enabled: Now, for example, classes of this plugin can be integrated or other things can be done that require this plugin
}
```


#### Deactivate all plugins for a page


To determine whether problems on a page are caused by a plugin, all plugins can be deactivated on this page by appending the URL parameter `disable_plugins=1`.




### JavaScript


#### foreach in JavaScript


```javascript
for (var element of someArray) {
    doSomething(element);
}
```


See: [https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/for...of](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/for...of)


Include #### JavaScript file of a plugin


The following code is inserted in the constructor of the plugin class if the JavaScript is to be available on all pages. Otherwise, the following code must be inserted in the `perform()` method of the plugin so that the JavaScript is only available on the plugin pages:
```php
<?php


PageLayout::addScript($this->getPluginURL() . '/assets/javascript/JavaScriptFile.js');
```


More about PageLayout: [PageLayout](PageLayout)


Create #### URL in JavaScript


A URLHelper is also implemented in JavaScript, which can be called in a similar way to the URLHelper in PHP:


`STUDIP.URLHelper.getURL(URL, {"parameter" : VALUE});`


The object, which is specified in JSON notation after the URL, contains parameters that are appended to the URL.


### Composer


#### How do I install the dependencies defined by Composer?


`composer install` or `make composer`


#### How do I install a new dependency using Composer?


`composer require <lib>`


#### How do I update a dependency defined by Composer?


`composer update <lib>`


Only individual dependencies may ever be updated as part of a TIC (or bug fix, if necessary), as the update may cause problems due to API changes or other critical changes. Never call `composer update` without specifying a lib for no reason.


#### Where can I find more information on how to use Composer?


[https://getcomposer.org/doc/01-basic-usage.md](https://getcomposer.org/doc/01-basic-usage.md)
