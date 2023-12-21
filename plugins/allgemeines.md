---
id: plugins
title: Plugins
sidebar_label: General
---

### Introduction

Since every location where Stud.IP is used has its own requirements or restrictions, the plugin interface provides a mechanism for adding your own functions to Stud.IP without having to touch the core system. Components can be updated, removed or added during operation.

Plugins can offer their own pages in the Stud.IP system, which can be integrated into the navigation structure at certain points, e.g. as a new tab in a course. In addition, certain plugin types also have the option of influencing existing pages and thus displaying their own block on the Stud.IP start page, for example.

### What is a Stud.IP plugin?

A plugin is a ZIP file containing a description of the plugin (name, version, etc.), the program code and any resources (images, stylesheets, etc.) provided by the plugin. In detail, a plugin package can contain the following components:

* a manifest file with the name `plugin.manifest`
* at least one PHP class with the program code of the plugin
* optional additional files with static content (images, CSS stylesheets, JavaScript files) or PHP libraries
* optional SQL script to generate the database schema for the plugin
* optional SQL script for deleting the database schema for the plugin
* optionally a subdirectory with the name `migrations`, which contains [migration files](../functions/migrations.md)
* optionally a subdirectory named `locale`, which contains [translation files](../quickstart/internationalization.md)
* optionally a subdirectory named `templates` containing templates for display

An example of the directory structure in a plugin package:

```ini
MyPlugin.class.php
  plugin.manifest
  images/
    icon.png
  migrations/
    1_test.php
    2_foobar.php
  sql/
    install.sql
    uninstall.sql
  stylesheets/
    grid.css
  templates/
    page.php
```


### Components of a plugin

In the following sections, the various components of a plugin package are explained in sequence:

#### Plugin manifest

Every plugin package must contain a so-called "plugin manifest", which contains important information about the plugin for the installation and administration of the plugin. The plugin manifest is always located in the root directory of the plugin and has the name `plugin.manifest`. It is a text file and can or must contain the following entries:

| value | description |
| ---- | ---- |
|**pluginname** | The name of the plugin. This is used for unique identification in the system (i.e. two plugins with the same name cannot be installed). The plugin itself can also provide a different name for display within the system. |
|**pluginclassname** | The name of the plugin class, i.e. the PHP class derived from one of the base classes of the plugin interface. Several such plugin classes may be specified in the manifest, whereby the "main class" must be listed first. This is used to define several plugin entry points in a plugin package, e.g. the plugin could define an entry point via the start page and also via the events. |
|**origin** | The origin of the plugin, usually the name of the programmer or the institution or group to which the programmer belongs. |
|**version** | The version of the plugin. The version should be chosen so that a comparison with the PHP function `version_compare()` is possible. |
|**description (optional)** | A short description of the plugin. This is displayed to users in the system when the plugin is offered for activation. |
|**homepage (optional)** | A URL to the homepage of the plugin, which contains further information about this plugin. This can be, for example, the corresponding page about the plugin in the official plugin repository of Stud.IP. |
|**dbscheme (optional)** | Reference to a file with an SQL script that is located within the plugin package. This is executed when the plugin is installed (but not for updates). Tables and table contents required by the plugin can be created here. The file name must be specified in relative terms. |
|**uninstalldbscheme (optional)** | Reference to a file with an SQL script that is located within the plugin package. This is executed immediately before the plugin is removed. Tables and table contents created by the plugin can be deleted here. The file name must be specified in relative terms. |
|**updateURL (optional)** | Reference to a URL with update information for this plugin. If this entry is present, the plugin can be updated via the update function of the plugin administration. If the entry is missing, an automatic update is only possible if the central meta file of the plugin repository contains update information for this plugin. If the entry `updateURL` is set, it must refer to an XML file with the following structure: |

```xml
<?xml version="1.0" encoding="UTF-8"?>
<plugins>
  <plugin name="Demo">
    <release
      version="1.2"
      url="http://plugins.studip.de/uploads/Plugins/demo-1.2.zip"
      studipMinVersion="1.6.0"
      studipMaxVersion="1.8.5" />
    <release
      version="2.0"
      url="http://plugins.studip.de/uploads/Plugins/demo-2.0.zip"
      studipMinVersion="1.8.0" />
  </plugin>
</plugins>
```

-Such a file with meta data can also contain information about many different plugins (several `plugin` elements) and different versions of a plugin (i.e. several `release` elements).

| value | description |
| ---- | ---- |
|**studipMinVersion (optional)** | Specifies the minimum Stud.IP version with which this plugin is compatible. If you try to install it in an older version, you will receive a corresponding error message and the installation will fail. |
|**studipMaxVersion (optional)** | Specifies the maximum Stud.IP version with which this plugin is still executable. If you try to install it in a newer version, you will receive a corresponding error message and the installation will fail. |


With the redesign of the "+" page in version 3.2, new optional information has been added for the plugin manifest, which is used for visualization on the "+" page.

| value | description |
| ---- | ---- |
|**category (optional)** | The category under which the plugin should be displayed on the "+" page. The existing categories are: |

* *Teaching organization
* *Communication and collaboration
* *Tasks
* *Other*

If no category is specified, the plugin is automatically listed under Other. Specifying a non-existent category automatically adds this category.

| Value | Description |
| ---- | ---- |
|**displayname (optional)** | The name with which the plugin should be displayed on the "+" page. If no displayname is specified, the plugin name is used in the appropriate place. |
|**complexity (optional)** | Complexity level of the plugin, divided into 1 for standard, 2 for advanced or 3 for intensive. |
|**icon (optional)** | Path to the icon of the plugin for display on the "+" page. This path must be specified relative to the root directory of the plugin. |
|**descriptionshort (optional)** | A short description of the plugin, which can also be seen when the "+" page is collapsed. |
|**descriptionlong (optional)** | A detailed description of the plugin, which can be seen when the "+" page is expanded. |
|**screenshot (optional)** | Path to a screenshot of the plugin, which is used for display on the "+" page. Several such screenshots may be specified in the manifest, whereby the screenshot that is displayed in large format must be listed first. This serves to display several screenshots on the "+" page. The order of the screenshot entries in the manifest is also the order in which they are displayed. The file name without the file extension is displayed as the description text of the image. This path must be specified relative to the root directory of the plugin. |
|**keywords (optional)** | Keywords that briefly and concisely describe the plugin. These keywords are displayed as a list on the "+" page. Multiple entries must be separated by a semicolon. |
|**helplink (optional)** | Reference to a URL with help information about the plugin. If the URL is specified, a corresponding link is displayed on the "+" page. |

An example of a complete manifest:

```ini
pluginname=Demo
pluginclassname=DemoPlugin
origin=virtUOS
version=1.2
dbscheme=sql/install.sql
uninstalldbscheme=sql/uninstall.sql
updateURL=http://plugins.studip.de/svn/plugins/plugins.xml
studipMinVersion=1.6.0
studipMaxVersion=1.8.5

category=Other
displayname=Demo
complexity=1
icon=images/icons/demoicon.jpg
descriptionshort=Demonstration of a plugin
descriptionlong=This text can be a bit longer
screenshot=images/screenshots/demo.jpg
screenshot=images/screenshots/noch_eine_demo.png
keywords=demo;manifest;plugin
helplink=http://hilfe.studip.de/develop/Entwickler/PluginSchnittstelle
```

#### SQL script for generating the database schema

Within a plugin package there may be an SQL script which contains SQL commands terminated with a semicolon. This SQL script is used for the initial creation of one or more database tables for the plugin. It is executed during the installation of the Stud.IP plugin.

#### SQL script for deleting the database schema

Analogous to the rules for an SQL script for creating the data schema for the plugin, a script can also be defined which is executed immediately before the plugin is removed from Stud.IP.

### Plugin class

Each plugin must contain at least one class that implements the functions of the plugin required for embedding it in the Stud.IP environment. Of course, any number of additional classes can be included in the plugin package.

The plugin class must have the name specified in the manifest under **pluginclassname** and be derived from the class `StudIPPlugin`. In addition, the class should implement at least one interface in order to be able to link into an existing Stud.IP system at certain points.

#### Standard methods of a plugin

By deriving from the class `StudIPPlugin`, each plugin automatically has a number of methods:

| value | description |
| ---- | ---- |
|**getPluginId()** | Returns the ID of the plugin. The ID is used internally to manage the plugin. |
|**getPluginName()** | Returns the name of the plugin defined in the manifest. |
|**getPluginPath()** | Returns a file system path to the plugin directory. This can be used, for example, to load output templates. |
|**getPluginURL()** | Returns an (absolute) URL to the installation location of the plugin. If you want to refer to style sheets or images in the plugin, you should use this URL. |
|**isActivated($context = NULL)** | Checks whether the plugin is activated in the specified context (e.g. the current event) or not. If no context is passed, the currently selected event is meant. |
|**isActivatableForContext(Range $context)** | The plugin can use this method to decide for itself whether it can be activated in the specified context, i.e. whether it appears on the "More" page. |
|**deactivationWarning($context)** | Returns a warning text that is output before the plugin is deactivated in the specified context. This can be used, for example, to indicate possible data loss. The implementation of the base class must be overwritten for this. |
|**perform($unconsumed_path)** | Displays a page of the plugin. TODO: This needs to be described in more detail. |

#### Plugin interfaces

In order to become active in certain places in Stud.IP, a plugin must implement one or more of the plugin interfaces. The following interfaces are available in version 1.11:

##### HomepagePlugin: Homepage of a user

Homepage plugins are only loaded in the homepage context. You can show your own navigation points on the homepage and display an information block on the homepage overview page.

This interface contains the following method:

| Value | Description |
| ---- | ---- |
|**getHomepageTemplate($user_id)** | Returns a template that is displayed on the user's overview page. If the plugin should not be displayed there, the method should return `NULL`. To configure the display area, the plugin can set some special values in the template in addition to its own placeholders (default settings in square brackets): |

| value | description |
| ---- | ---- |
| *title* | Display title [name of the plugin] |
| *icon_url* | plugin icon [no icon] |
| *admin_url* | administration link [no link] |
| *admin_title* | Label for the administration link [administration] |

##### PortalPlugin: Homepage (portal page)

Portal plugins are loaded on the start page, even if the user is not (yet) logged in. You can show your own navigation points on the login and start page and display an information block on the start page.

This interface contains the following method:

| Value | Description |
| ---- | ---- |
|**getPortalTemplate()** | Returns a template that is displayed on the start page of the system. If the plugin should not be displayed there, the method should return `NULL`. To configure the display area, the plugin can set some special values in the template in addition to its own placeholders (default settings in square brackets): |

| value | description |
| ---- | ---- |
| *title* | Display title [name of the plugin] |
| *icon_url* | plugin icon [no icon] |
| *admin_url* | administration link [no link] |
| *admin_title* | Label for the administration link [administration] |

##### StandardPlugin: Events and facilities

Standard plugins are only loaded in the event and institution context (but not currently in the admin area). You can display your own navigation points in the event or institution and display an icon with a link to the plugin on the "My events" page.

This interface contains the following methods:

| Value | Description |
| ---- | ---- |
|**getIconNavigation($course_id, $last_visit)** | Returns a navigation object for the plugin's icon on the "My events" page. If the plugin should not be displayed there, the method should return `NULL`. *$last_visit* is the time of the user's last visit to the event (or the plugin). If there has been new or changed content since this time, this should be indicated to the user via a special icon. |
|**getInfoTemplate($course_id)** | Returns a template that is displayed on the short info page of the course or institution. If the plugin is not to be displayed there, the method should return `NULL`. To configure the display area, the plugin can set some special values in the template in addition to its own placeholders (default settings in square brackets): |

| value | description |
| ---- | ---- |
| *title* | Display title [name of the plugin] |
| *icon_url* | plugin icon [no icon]|
| *admin_url* | administration link [no link]|
| *admin_title* | Label for the administration link [administration]|


##### StudyModuleManagementPlugin: Study module search

The StudyModuleManagementPlugin is used in the display of study areas in order to display further module-specific information.

This interface contains the following methods:

| Value | Description |
| ---- | ---- |
|**getModuleTitle($module_id, $semester_id = null)** | Returns the title for a module. |
|**getModuleDescription($module_id, $semester_id = null)** | Returns the short description for a module. |
|**getModuleInfoNavigation($module_id, $semester_id = null)** | Returns an object of the type Navigation, which can contain the title, link and icon for a module, e.g. to display an info icon. |

##### SystemPlugin: system-wide extensions

System plugins are loaded on every page in Stud.IP (with the exception of pages that use a completely different display, e.g. print views). You can display your own navigation points anywhere in the system.

This interface does not contain any methods.

##### WebServicePlugin: Web services from plugins

Plugins have the option of extending the SOAP/XMLRPC web services that Stud.IP can provide with their own services. All a plugin has to do is implement the WebServicePlugin interface:

| value | description |
| ---- | ---- |
|**getWebServices()** | Should load plugin services and then returns a list of service class names that supplement the native ones. |

Example:

```php
[...]
function getWebServices()
{
  require 'MyService1.php';
  require 'MyService2.php';
  return array('MyService1', 'MyService2');
}
[...]
```

### Plugin actions

In addition to the type-specific capabilities described above, i.e. in particular embedding in existing pages in Stud.IP, each plugin has the option of offering completely separate pages - including their own navigation. For this purpose, there is a mechanism in the plugin interface that translates URLs called by the user into method calls in the plugin, referred to in this description as "plugin actions". Such a plugin action is a normal (public) method in the plugin class whose name ends with "`_action`":

```php
class TestPlugin extends StudipPlugin implements SystemPlugin
{
    [...]
    public function delete_action($id)
    {
        [...]
    }
}
```

An action can have function parameters (here in the example: `$id`), but can also process request parameters (e.g. when submitting a form).

#### Navigation in the plugin

The creation of navigation points for plugins is described [elsewhere](Navigation) and works in exactly the same way as in the Stud.IP core system. The associated URLs usually lead to certain actions in the plugin, the creation of which is described in the following section.

#### Creating URLs for plugin actions

So that the user can call up a specific action, the plugin must of course also know the associated URL. To be able to create URLs for these plugin actions, there are two helper functions in the `PluginEngine` class:

| Function | Description |
| ---- | ---- |
|**getLink($plugin_action, $params = array())** | Returns the URL to an action in a plugin. The action is specified by the class name of the plugin, the name of the action and other function parameters, each separated by a slash ("`/`"). The name of the action may be missing, in which case the default action with the name "`show`" in the specified plugin class is used. An array with request parameters (i.e. GET parameters) can optionally be specified as the second argument.

```php
<a href="<?= PluginEngine::getLink("testplugin/delete/$id") ?>"> Delete entry </a>
```

The result of this function is an *entity-encoded URL*, i.e. it can be used directly in attributes in the HTML (*action* of a FORM, *href* of an A element). If you need the unencoded URL, `getURL()` should be used.

| function | description |
| ---- | ---- |
|**getURL($plugin_action, $params = array())** | This function works exactly like `getLink()`, but does not return an entity-encoded value, but the unencoded URL. This can then be used for calls via JavaScript or redirects, for example. Example: [<<](<<) |

```php
header('Location: ' . $PluginEngine::getURL('testplugin/show'));
```


### Interaction with other plugins

Sometimes it is also desirable to be able to interact with other plugins. The `PluginEngine` class also offers a number of auxiliary functions for this purpose:

| Function | Description |
| ---- | ---- |
|**getPlugin($class)** | Returns the plugin with the specified class name. If such a plugin is not installed or the user does not have the necessary rights, only a `NULL` value is returned instead of the plugin instance. |
|**getPlugins($type, $context = NULL)** | Returns all plugins of the specified type (name of a plugin interface) as an array. Of course, only those plugins are found that the current user is allowed to see. |
|**sendMessage($type, $method, ...)** | Calls the specified method for all plugins of a certain type (name of a plugin interface). Arguments following the name are passed on to each plugin as method parameters. The result is an array with the results of the individual method calls. |
|**sendMessageWithContext($type, $context, $method, ...)** | Calls the specified method for all plugins of a certain type that are activated in a certain context (e.g. the ID of an event or institution). Arguments following the name are passed on to each plugin as method parameters. The result is an array with the results of the individual method calls. |

### CSS and Javascript in plugins

The base class `StudIPPlugin` provides the method `addStylesheet()` and from version 4.4 the method `addScript()`. A file name relative to the plugin path can be specified for these methods in order to integrate CSS/LESS stylesheets and JavaScript files. CSS and Javascript files are output without further processing, while LESS files are compiled. If the system is in development mode, the LESS is recompiled each time the file is changed. In production mode, LESS is only recompiled each time the plugin version in the manifest is changed.

As of version 4.4, the base class also offers the methods `addStylesheets()` and `addScripts()` to include several files at once. In development mode, these are included as if the methods for the individual methods were being called. In production mode, however, the included files are appended to each other and output as a single file.

All methods mentioned here accept the `$link_attr` parameter, which can be used to attach attributes to the generated HTML element. For LESS stylesheets, there is also the `$variables` parameter, which can be used to pass in variables that are then available in LESS.

### Further plugin methods

| Function | Description |
| ---- | ---- |
|**onEnable($plugin_id)** | When a plugin is activated, this method of the class is called so that the plugin can react accordingly or check dependencies. In the event that the plugin wants to prevent activation (e.g. because the required configurations have not yet been made), the `onEnable` method must return the value `false`. |
|**onDisable($plugin_id)** | When a plugin is deactivated, this method of the class is called so that the plugin can react accordingly or check dependencies. In the event that the plugin wants to prevent deactivation, the `onDisable` method must return the value `false`. |

### Temporary deactivation of all plugins

Root administrators can use the URL parameter `disable_plugins=1` to temporarily deactivate all plugins (i.e. for the current session) if problems occur after updating a plugin and the system can no longer be used with activated plugins. The parameter can also be set before login (if the login page can no longer be called up) and then applies to the subsequent session until logout. Via `disable_plugins=0` it can also be reset without logout.


## Data protection in plugins

In order for Stud.IP to be able to deliver the user-related data stored in the system from plugins on user request, it is necessary that the plugin implements the interface `PrivacyPlugin` and has the function `exportUserData(StoredUserData $storage)`. This function receives an instance of the `StoredUserData` class and can store the saved personal data (both tabular data and files) in it.

When persons are deleted, the event `UserDidDelete` is sent, whereupon a plugin should also delete its user-related data for this person from the system.
If individual parts of personal data are deleted, for example to anonymize a person, the event `UserDataDidRemove` is sent. This event also provides the type of the deleted personal data as an additional parameter. The available types can be seen in the example below. Which of these types are relevant for the plugin depends on the data stored by the plugin.

A plugin could look like this:
```php
class MyPlugin extends StudIPPlugin implements StandardPlugin, PrivacyPlugin
{

    public function __construct()
    {
        parent::__construct();
        NotificationCenter::addObserver($this, 'deleteUser', 'UserDidDelete');
        NotificationCenter::addObserver($this, 'removeData', 'UserDataDidRemove');
    }
    ...

    /**
     * Export available data of a given user into a storage object
     * (an instance of the StoredUserData class) for that user.
     *
     * @param StoredUserData $store object to store data into
     */
    public function exportUserData(StoredUserData $storage)
    {
        $db = DBManager::get();

        $table_data = $db->fetchAll('SELECT * FROM my_table WHERE user_id = ?', [$storage->user_id]);
        $storage->addTabularData('DisplayTitle', 'my_table', $table_data);

        $file_data = $db->fetchAll('SELECT * FROM my_files WHERE user_id = ?', [$storage->user_id]);
        foreach ($file_data as $file) {
            $storage->addFileAtPath($file['name'], $file['path']);
        }
    }

    /**
    * delete given user from plugin
    *
    * @param String $event name of the notification event
    * @param User $user
    */
    public function deleteUser($event, $user)
    {
        ...
        PageLayout::postInfo('User X deleted from MyPlugin.');
    }

    /**
    * delete data of given user from plugin
    *
    * @param String $event name of the notification event
    * @param String $user_id
    * @param String $type of data that should be removed
    */
    public function removeData($event, $user_id, $type)
    {
        switch ($type) {
            case 'course_documents':
            case 'personal_documents':
                ...
                PageLayout::postInfo('Documents of user X deleted from MyPlugin');
                break;
            case 'course_contents':
            case 'personal_contents':
                ...
                PageLayout::postInfo('Contents of user X deleted from MyPlugin');
                break;
            case 'names':
                ...
                PageLayout::postInfo('Names of user X deleted from MyPlugin');
                break;
            case 'memberships':
                ...
                PageLayout::postInfo('Event assignments of user X deleted from MyPlugin');
                break;
        }
    }
}
```

## Plugin migration from Stud.IP v4.6 to v5.0

### Breaking Changes

#### Changed API of the JSUpdater

With the STUDIP.JSUpdater plugins can participate in the regular polling of the server, as is already used for Blubber or the PersonalNotifications.

To participate in the JSUpdater in the plugin, there were previously two options:

* Calling STUDIP.JSUpdater.register
* Automatic use by implementing a JS function called "periodicalPushData"

The second option has been removed and must be replaced by an explicit registration. Everything else under [Developer/UpdateInformation](UpdateInformation)


# Internationalization of plugins
The PluginEngine supports an internationalization of the plugin based on gettext. The usual steps are required to translate the plugin:
```shell
xgettext -n PLUGINPAKET/*.php
```
Translate the messages.po generated in this way

```shell
msgfmt messages.po
```
Then rename the resulting messages.mo to gtdomain_PLUGINCLASSNAME.mo

* Place the .mo file in the following directory structure:
  * PLUGINPAKET/locale/SPRACHKÜRZEL/LC_MESSAGES/
* If necessary, restart the server to display changes
