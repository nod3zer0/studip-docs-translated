This tutorial serves as a first contact with Stud.IP plugins.
A Stud.IP plugin is created as an example,
to show which components are relevant for a Stud.IP plugin.
The individual components are not explained again in detail,
Instead, reference is made to detailed explanations in the wiki, which should be looked at beforehand.
This tutorial is therefore a kind of guide that can be followed,
to get to know the various Stud.IP components and to bring them into a meaningful context.

This page is the first part of the tutorial,
by first explaining and creating the basic structure of a plugin.
This part is therefore largely independent of the actual functionality of the plugin
and is only concerned with creating a basis,
with which pleasant plugins can be created and further developed.
However, a little context for the functionalities of the plugin is necessary and useful for understanding.

The aim of the plugin is to enable any Stud.IP user to create "texts".
"Texts" initially only consist of a title, content and have a type.
Users should also be able to view an overview of all texts created.

A ZIP file with the existing code and additional phpDoc is attached at the end of this page,
so that you can check your own progress.
However, you should try to follow the plugin tutorial on your own.

In order to be able to follow this tutorial properly,
a running Stud.IP test environment (at least Stud.IP 4.6) including web server, php and database system is required.


## Studip coding style
In order to facilitate working with several developers,
a uniform coding style should be aimed for.
The coding style for Stud.IP can be found [here](CodingStyle).
This](https://www.php-fig.org/psr/psr-12/) article can be used as a second source.

## Basic framework
In order for a directory to be recognized as a plugin by Stud.IP,
two files are required in the root of the directory.
A `plugin.manifest` file, which contains meta data about the plugin
such as the plugin name or the current version number of the plugin, and a plugin class,
which is called as the initial instance of Stud.IP.

### Plugin manifest
Which meta data can be stored in the `plugin.manifest`,
can be seen in [Plugin-Manifest](PluginSchnittstelle#plugin-manifest).
For our example plugin, the `plugin.manifest` would look like this:
```
pluginname=TextPlugin
pluginclassname=TextPlugin
origin=UOL
version=1.0
studipMinVersion=4.6
```
### Main plugin class
This file contains the plugin class. It must have the class name
which was defined in `plugin.manifest` with `pluginclassname`.
The file name must be identical to the class name.
In the example, the file name must therefore be `TextPlugin.class.php` and contain a class `TextPlugin`.
By default, the plugin class inherits from the `StudipPlugin` class and implements one or more plugin interfaces.
The different types of plugins are explained in [Plugin-Interfaces](PluginSchnittstelle#plugin-interfaces).
As the TextPlugin should be accessible system-wide, it implements the `SystemPlugin` interface.
The plugin class then looks like this:
```php
<?php

class TextPlugin extends StudIPPlugin implements SystemPlugin
{

}
```

### Installation
As the plugin is now ready to be recognized as a plugin by Stud.IP, it can now be installed.
There are two main ways to do this: You can compress the plugin into a `.zip` file
and install it directly in Stud.IP or if there is a repository for the plugin,
the plugin can also be cloned first and then integrated into Stud.IP.

Installed plugins are independent of how they were installed,
can be found in the Studip directory under `public/plugin_packages/<origin>`,
where `<origin>` is the origin specified in the respective `plugin.manifest`.

Install #### plugin as a ZIP file
To install the plugin directly as a `.zip` file,
the active `root` user must navigate to `Admin` => `System` => `Plugins`.
The `.zip` file can then be selected in the sidebar on the left or installed using drag and drop.

Once the plugin has been installed, it still needs to be activated.
To do this, simply activate the plugin in the same view using the "Active" checkbox
and save the change at the bottom of the page.


Install #### plugin from a repo
If a repository exists for the plugin,
the repository can also be cloned and the plugin can then be installed.
You can then work with the repository as normal.

To do this, the repository of the plugin must be cloned into the corresponding directory.
In the case of the TextPlugin, the repo must be cloned to `public/plugin_packages/UOL`,
as the `origin` is defined as `UOL` in `plugin.manifest`.
Care should also be taken to ensure that the repository path name is the same,
as the defined `pluginname`.
Overall, the Stud.IP would then look like this:
```ini
<studip-directory>
  public\
    plugin_packages\
      core\
      UOL\
        TextPlugin\
          .git
          plugin.manifest
          TextPlugin.class.php
```
As the `root` user, you must then also navigate to `Admin` => `System` => `Plugins`
and select the "Register existing plugins" view in the sidebar on the left under "Views".
The TextPlugin should now be listed here as an installation option and installed.

As with installing the plugin as a ZIP file
the plugin must be activated after installation under `Admin` => `System` => `Plugins`.

#### Further work with the plugin
Now that the plugin is installed,
all subsequent changes can be made directly in the plugin directory
and are automatically recognized by Stud.IP.
The plugin therefore does not have to and should not be reinstalled for every change.

## Content of the plugin
Now that the plugin is installed and activated,
the actual functionality of the plugin can be taken care of.
We would like to create an overview page to display all texts as the initial point of contact for the plugin.

### Navigation
To do this, a navigation must first be created in order to be able to navigate to this page.
How navigation works in Stud.IP is explained in [Navigation](Navigation).
As the navigation to the overview page should always be created,
the navigation is created in the `__construct` method of the TextPlugin.

```php
    public function __construct()
    {
        parent::__construct();

        $root_nav = new Navigation('Texts', PluginEngine::getURL($this, [], 'overview'));
        $root_nav->setImage(Icon::create('file-text', Icon::ROLE_NAVIGATION));
        Navigation::addItem('/text_root', $root_nav);

        $navigation = new Navigation('Overview', PluginEngine::getURL($this, [], 'overview'));
        $root_nav->addSubNavigation('text_overview', $navigation);
    }
```
We simply call the initial tab of the plugin `texts`
and append the navigation element to the root of the navigation,
so that it appears in the main navigation tab.
We then attach all further navigation points to this navigation element.
So far we have only planned an overview page,
so we create another navigation element 'Overview',
which we attach to our 'Texts' navigation.
The main navigation item already appears in Stud.IP,
but it still links to a non-existent page.

### Plugin and controller classes
Before we add the missing page,
let's do a little work for our future self.
A plugin directory can generally contain several plugins
and will often contain several controller classes.
We'll write some code later,
that will require all of our plugin and controller classes
and to avoid redundant code,
we will create one class each,
from which all our plugin and controller classes can inherit.

We create the two files `Plugin.php` and `Controller.php` in a new `classes` directory.

```php
<?php

namespace TextPlugin;

use StudIPPlugin;

class Plugin extends StudIPPlugin
{

}
```
```php
<?php

namespace TextPlugin;

use PluginController;

class Controller extends PluginController
{

}
```

We also set a `namespace` for both classes,
to distinguish them from other classes with the same name.
We should also remember
that our `TextPlugin` class should now inherit from `\TextPlugin\Plugin`,
and no longer from `StudIPPlugin`.


### Autoload
Files in the plugin directory are usually not loaded automatically by Stud.IP.
For our new classes in the `classes` directory we have to tell Stud.IP explicitly,
that it should load the classes with our plugin so that we can also use them.

The loading of other classes is usually outsourced to a `bootstrap.inc.php` file,
which is then always loaded by the plugin with `require_once`.
We therefore create a `bootstrap.inc.php` file in the root of the plugin directory,
in which we load all files in the `models` directory with the `StudipAutoloader`.
The `namespace` of the classes should be specified as the prefix for the autoloader.

```php
<?php

StudipAutoloader::addAutoloadPath(__DIR__ . '/classes', 'TextPlugin');
```

The file is then added to `TextePlugin.class.php` with `require_once __DIR__ . '/bootstrap.inc.php';`,
preferably before the class definition.

### Trails
Now we are finally ready,
that we can take care of the overview page.
[Trails](Trails) is the model view controller framework of Stud.IP and determines, among other things
which page is called at which URL.

In general, a URL for a plugin always contains `plugins.php/<pluginname>/<controller-name>/<actions-name>`.
The `pluginname` is defined in the `plugin.manifest`.
The `controller-name` is the file name of the controller.
If the controller file is called `overview.php`,
the class in the file must be called `OverviewController`.
The `action-name` is the name of an "action", i.e. a method within the controller,
which ends with `_action`.

For example, the URL `plugins.php/textplugin/overview/index` would be a method `index_action()` in the controller `overview`
in the `TextPlugin` plugin.
The action `index` is always called,
if no `action-name` is specified in the URL.
Since we refer in our navigation with `PluginEngine::getURL($this, [], 'overview')` to an overview controller within our plugin
and have not specified an action, we should create a controller in a new directory `controllers` called `overview.php`
which contains the method `index_action`.

```php
<?php

class OverviewController extends \TextPlugin\Controller
{
    public function index_action()
    {

    }
}
```

All other details in the URL are entered as parameters in the actions.
So if an action `test_action($param1, $param2)` exists in the OverviewController
and the URL `plugins.php/textplugin/overview/test/hallo/welt` is called,
contains `$param1` the string `hello` and `$param2` the string `world`.
Since `/` is used for separating in the URL, this should also be avoided,
strings containing `/` as parameters.

After Trails has called the respective action method and it has run through,
a view is rendered, which also results from the URL.
A directory must exist in a `views` directory within the plugin,
which is named after the controller
and within this directory a `.php` file named after the action.
Controller classes in the `controllers` directory and view files in the `views` directory
are automatically loaded by Stud.IP,
so we **don't** have to load them with the autoloader.

If both the controller with the action method and the appropriate view have been created
and the naming convention has been adhered to,
Stud.IP should now display an empty page on the TextPlugin overview page.
The file structure for the plugin should look as follows up to this point:

```ini
TextPlugin\
  classes\
    Controller.php
    Plugin.php
  controllers\
    overview.php
  views\
    overview\
      index.php
  bootstrap.inc.php
  plugin.manifest
  TextPlugin.class.php
```

### Create database tables (migration)
Before the page can be filled with reasonable content,
the corresponding database tables must be created.
This is done in Stud.IP using [Migrations](Migrations).

For the first version of the plugin, we only need one table to save the created texts.
We create the migration file in a new directory `migrations` and name it `01_init_texte.php`.
The class within the file must be named `InitTexte` accordingly.

```php
<?php

class InitTexte extends Migration
{

    public function up()
    {
        $db = DBManager::get();

        $query = "CREATE TABLE IF NOT EXISTS tp_texte (
                    text_id CHAR(32) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
                    title TEXT NOT NULL,
                    description TEXT NULL DEFAULT NULL,
                    type TINYINT(2) NOT NULL DEFAULT 1,
                    mkdate INT(11) NOT NULL,
                    chdate INT(11) NOT NULL,
                    author_id CHAR(32) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
                    PRIMARY KEY (text_id)
                  )";
        $db->exec($query);
    }

    public function down()
    {
        // drop new tables
        DBManager::get()->exec("DROP TABLE IF EXISTS tp_texte");
    }
}
```

New migrations are also automatically recognized by Stud.IP like the other files,
however, migrations must be carried out or installed explicitly.
Migrations can be carried out as root user under `Admin` => `System` => `Plugins` in the `Schema` column.
The current migration version is specified in this column by default.
In our case, as we have not yet carried out a migration, the version is 0.
If Stud.IP recognizes new migration files in the plugin directory,
an icon is displayed in the column with which all new migrations can be carried out.
As explained on the [Migrations](Migrations) wiki page,
the number in the name of the migration file represents the version of the migration,
so that new migrations are to be provided with ascending numbers.


### Model classes
So that we do not have to write simple SQL queries ourselves and can use table entries as php objects,
we create a [SimpleORMap](SimpleORMap) class for each table in a new directory `models`.
We also give the model classes the `namespace` `TextPlugin`.
Since we only have one table, we create a model class `Text.php`:

```php
<?php

namespace TextPlugin;

use SimpleORMap;
use User;

class Text extends SimpleORMap

{
    protected static function configure($config = [])
    {
        $config['db_table'] = 'tp_texte';

        $config['belongs_to']['author'] = [
            'class_name' => User::class,
            'foreign_key' => 'author_id',
            'assoc_foreign_key' => 'user_id'
        ];

        parent::configure($config);
    }
}
```

So that we can use the model classes, we have to remember the same as with `classes`,
load them in `bootstrap.inc.php` with `StudipAutoloader::addAutoloadPath(__DIR__ . '/models', 'TextPlugin');`.
The plugin directory looks like this at this point:
```ini
TextPlugin\
  classes\
    Controller.php
    Plugin.php
  controllers\
    overview.php
  migrations\
    01_init_texts.php
  models\
    text.php
  views\
    overview\
      index.php
  bootstrap.inc.php
  plugin.manifest
  TextPlugin.class.php
```

### javascript and css files
If css and/or javascript files are to be used,
these should be stored in a new directory `assets`.
To then load the files,
either in the plugin class `$this->addStylesheet('<css-filepath>');`
or `$this->addScript(<js-filepath>');`
or in controller classes `PageLayout::addStylesheet($this->plugin->getPluginURL() . '/<css-filepath>');`
or `PageLayout::addScript($this->plugin->getPluginURL() . '/<js-filepath>');` can be called.


### Localization
Since Stud.IP is not only used by German-speaking users,
the plugin should also be translatable into other languages.
As explained in [Internationalization](Howto/Internationalization),
this is done in Stud.IP using the `gettext` package.

#### Mark texts as translatable
However, as strings may be output for which no translation yet exists in Stud.IP,
a translation file must be created within the plugin,
which translates exactly these new strings.
However, before this is done, the corresponding strings should be marked as translatable
and so that this does not have to be done retrospectively for all strings,
this is introduced before the actual functionality of the plugin is created.

Within our plugin, `bindtextdomain` must be used to specify
where the translation file is to be found and the character encoding must be defined with `bind_textdomain_codeset`.
In order not to specify this individually for all plugins within the plugin directory,
we use the previously created `Plugin` class, from which the `TextPlugin` inherits.

```php
<?php

namespace TextPlugin;

use StudIPPlugin;

class Plugin extends StudIPPlugin
{
    const GETTEXT_DOMAIN = 'TextePlugin';

    public function __construct()
    {
        parent::__construct();
        bindtextdomain(static::GETTEXT_DOMAIN, $this->getPluginPath() . '/locale');
        bind_textdomain_codeset(static::GETTEXT_DOMAIN, 'UTF-8');
    }
}
```

So that within the plugin only `$this->_()` or `$this->_n()` can be called for gettext
and not always `dgettext()` or `dngettext()`,
two more methods should be added to the plugin class:

```php
    public function _($string)
    {
        $result = dgettext(static::GETTEXT_DOMAIN, $string);

        if ($result === $string) {
            $result = _($string);
        }

        return $result;
    }

    public function _n($string0, $string1, $n)
    {
        if (is_array($n)) {
            $n = count($n);
        }

        $result = dngettext(static::GETTEXT_DOMAIN, $string0, $string1, $n);

        if ($result === $string0 || $result === $string1) {
            $result = ngettext($string0, $string1, $n);
        }

        return $result;
    }
```

To create the navigation in `TextPlugin.class.php` we had already created two texts ("Texts" and "Overview").
We can now make these translatable by calling `$this->_()` so that the TextPlugin looks like this:

```php
<?php

require_once __DIR__ . '/bootstrap.inc.php';

class TextPlugin extends \TextPlugin\Plugin implements SystemPlugin
{
    public function __construct()
    {
        parent::__construct();

        $root_nav = new Navigation($this->_('Texts'), PluginEngine::getURL($this, [], 'overview'));
        $root_nav->setImage(Icon::create('file-text', Icon::ROLE_NAVIGATION));
        Navigation::addItem('/text_root', $root_nav);

        $navigation = new Navigation($this->_('overview'), PluginEngine::getURL($this, [], 'overview'));
        $root_nav->addSubNavigation('text_overview', $navigation);
    }
}

```

Now we don't just want to translate the texts that we create in a plugin class,
but also in controller classes and views.
To do this, we redirect all calls to `_()` in Controllers to the plugin,
so that we can simply call `$this->_()` in Controllers.
To do this, we use the `Controller` class analogous to the `Plugin` class,
so that we can do this directly for all controllers.

```php
<?php

namespace TextPlugin;

use PluginController;
use RuntimeException;

class Controller extends PluginController
{

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);

        // Localization
        $this->_ = function ($string) use ($dispatcher) {
            return call_user_func_array(
                [$dispatcher->current_plugin, '_'],
                func_get_args()
            );
        };

        $this->_n = function ($string0, $tring1, $n) use ($dispatcher) {
            return call_user_func_array(
                [$dispatcher->current_plugin, '_n'],
                func_get_args()
            );
        };
    }

    public function __call($method, $arguments)
    {
        $variables = get_object_vars($this);
        if (isset($variables[$method]) && is_callable($variables[$method])) {
            return call_user_func_array($variables[$method], $arguments);
        }
        return parent::__call($method, $arguments);
    }

}
```

Now texts in plugins and controllers can be translated.
Texts in javascript files can be translated as in the core with `String.toLocaleString()`.
However, texts in model classes such as `Text.php` must still be translated directly
be marked as translatable by calling `dgettext(Plugin::GETTEXT_DOMAIN, $string)`.
In view files, `$controller->_($string)` or alternatively `$_($string)` can be used for this purpose.

#### Create translation file
The translation file is usually created simply using the Unix shell script `makeStudIPPluginTranslations.sh`,
which is available on the [developer installation of Stud.IP](https://develop.studip.de/studip/dispatch.php/document/download/1bea6c139b56abc3ef0c505731bcc6b6).
It collects all strings marked as translatable and creates a `.pot` file,
which is why the translation file should only be created
when the plugin has been completed.
With the help of a translation editor, the collected text strings of the `.pot` file can then be translated
and a machine-readable `.mo` file can be created.

## Summary
A

* A `plugin.manifest` with meta data was created ([plugin-manifest](PluginSchnittstelle#plugin-manifest))
* Created a plugin class for initialization ([Plugin-Interfaces](PluginSchnittstelle#plugin-interfaces))
* Installed and activated the plugin
* Created a navigation for a main page ([Navigation](Navigation))
* Created a parent class for each of the plugin and controller classes
* Automatically included the classes in `classes` and later in `models` using the autoloader
* Created a controller with an `action` and a matching `view` ([Trails](Trails))
* A migration for the database table structure ([Migrations](Migrations))
* Created a SORM class for the database tables ([SimpleORMap](SimpleORMap))
* Explains how to include js and cc files
* Created a base to translate strings ([Internationalization](Howto/Internationalization))

The complete code created so far is available here ([TextPlugin.zip](../assets/862928c482008450a61d0c4156987ene/TextPlugin.zip)) as a ZIP file.

To the [second part](Plugin-Tutorial-II-(example functionalities))
