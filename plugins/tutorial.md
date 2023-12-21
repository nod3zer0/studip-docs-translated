---
title: Plugin-Tutorial
sidebar_label: Tutorial
---


This tutorial introduces the creation of plugins. As there are different types of plugins in Stud.IP, these are illustrated using various examples.

### Building the basic framework

A Stud.IP plugin always consists of at least two files: The plugin class with the PHP code and a text file with information about the plugin (name, version, author etc.). In the following, the basic structure of a plugin is created, which does nothing more than register in the system.

#### Plugin class

The plugin class can look like this in the simplest case:

**MyPlugin.php**

```php
<?php

class MyPlugin extends StudipPlugin implements SystemPlugin
{
}
```

Important here:
* The file is named after the class ("`MyPlugin`") and has the extension "`.class.php`".
* Your own plugin class must be derived from the class `StudIPPlugin`.
* The plugin class must implement at least one of the interfaces that determine where it can become active in Stud.IP. In the example above, it is the interface `SystemPlugin`, with which the plugin should be active on every page (more on this later).

The plugin itself does nothing and therefore does not appear outside the plugin administration.

#### plugin.manifest

The file with the information about the plugin is used by Stud.IP (among other things) to display information about the plugin to the administrator. It always has the name "[`plugin.manifest`](PluginSchnittstelle#toc4)" and should contain at least the following entries:

* *pluginname*: The name for the plugin
* *pluginclassname*: The name of the plugin class (in PHP). If the extension .class.php is used, the .class can be omitted here.
* *origin*: The name or mail address of the author or their institution or group
* *version*: The version number of the plugin
* *description*: A short description of what the plugin does
* *studipMinVersion*: The minimum required Stud.IP version

A plugin.manifest file can have the following content, for example:

```ini
pluginname=MyPlugin
pluginclassname=MyPlugin
origin=elmar@example.com
version=1.0
description=A completely useless example plugin
studipMinVersion=2.0
```


#### Install plugin

An installation archive must now be created from the plugin files. To do this, pack all the plugin files into a ZIP archive. It is important not to include the folder in which the plugin is located, otherwise the installation will fail. The files plugin.manifest and the PHP file with the plugin class should therefore be directly visible in the root directory of the archive:

```text
Length Date Time Name
--------- ---------- ----- ----
       71 2011-04-20 11:26 MyPlugin.php
      152 2011-04-20 11:26 plugin.manifest
--------- -------
      223 2 files
```

You can then install the plugin in Stud.IP via [Plugin management](http://docs.studip.de/admin/Admins/PluginVerwaltung). The easiest way to do this is to simply drag the ZIP archive into the drag & drop area on the left-hand side of the plugin administration.

Attach:plugin-install.png

After installation, the plugin must be activated on the plugin management page so that it is actually loaded. You can also manage the rights for the plugin in the "Actions" column. By default, a plugin can be used by all logged-in users in the system.

#### Expanding the plugin

After installing the plugin, it is located in the folder `/public/plugins_packages/<origin>/<plugin name>/`. "origin" is the value of the configuration parameter "origin" from the plugin.manifest file. During the development of the plugin, it can be easier if you continue to edit the installed version of the plugin below the Stud.IP directory, as you can then save reinstalling the plugin after each change.

Depending on the type of plugin you want to program, the plugin must be derived from different classes and different methods must be implemented.

### Presentation of different plugin types

#### SystemPlugin

Suppose we want the login ID of the user currently logged in to be displayed after the name of the Stud.IP installation in the header. How does this work?

* We have to make sure that the plugin is active on every page.
* We must somehow determine the ID of the logged-in user.
* We need to be able to include this text in the header.

Fortunately, point 1 is already done: Since our plugin implements the 'SystemPlugin' interface, it is automatically active on every page.

Point 2 leads us directly into the depths of the Stud.IP API. Fortunately, there is a very easy-to-use function that provides us with the current user ID: `get_username()`.

For point 3, you can use some CSS to change content on a page:

```css
#id:after {
    content: "some text";
}
```

This piece of CSS ensures that the text "some text" is displayed after the element with the ID "`id`" in the HTML. In our case, the element we are looking for in the header has the ID "barTopFont". So far, so good. But how do we get the CSS onto the Stud.IP page?

There is an API in Stud.IP that allows plugins to intervene in the page layout: [PageLayout](PageLayout). Among other things, you can use it to add your own CSS to the generated page. Together it looks something like this:

```php
class MyPlugin extends StudipPlugin implements SystemPlugin
{
    public function __construct()
    {
        parent::__construct();

        $username = get_username();
        $css = "#barTopFont:after { content: '[$username]'; }";
        PageLayout::addStyle($css);
    }
}
```

As a system plugin does not have a special entry point for Stud.IP, the code is integrated directly into the constructor. Of course, you should not forget to call the constructor of the base class here... The finished plugin then looks like this:

Attach:myplugin.png

##### Source code of the plugin

Attach:MyPlugin.zip

#### PortalPlugin Your own plugin on the start page

Let's now move on to another example that shows how a plugin can display its own content on the start page. As an example, we want to display a random quote from a well-known TV series each time the page is called up. And this for both logged-in and non-logged-in users.

##### The `PortalPlugin` interface

The PortalPlugin plugin interface offers each plugin the option of placing its own box on the start page, similar to the existing boxes for events, system-wide announcements or surveys. This looks as follows:

```php
interface PortalPlugin
{
    /**
     * Return a template (an instance of the Flexi_Template class)
     * to be rendered on the start or portal page. Return NULL to
     * render nothing for this plugin.
     *
     * The template will automatically get a standard layout, which
     * can be configured via attributes set on the template:
     *
     * title title to display, defaults to plugin name
     * icon_url icon for this plugin (if any)
     * admin_url admin link for this plugin (if any)
     * admin_title title for admin link (default: Administration)
     *
     * @return object template object to render or NULL
     */
    function getPortalTemplate();
}
```

Obviously you need a *Template* object for the output, and you can still specify certain properties of the generated box (icon, title, etc.).

##### Important: Flexi-Templates

Text or HTML output in Stud.IP should always be done via *Templates*, these are templates (written in PHP) for the output, which can be equipped with placeholders to bring values from the program code to certain places in the output. A complete description with many examples can be found here in the wiki under [FlexiTemplates](FlexiTemplates).

In our case, we only need a small area in which a pre-formatted text can be displayed. The template for this is in a separate PHP file and can look something like this:

**templates/fortune.php**

```php
<pre>
<?= htmlReady($fortune) ?>
</pre>
```

The "`<pre>`" shouldn't be surprising, but why does it say *htmlReady()*? Well, so that special characters in the text to be output are not inadvertently evaluated by the browser as HTML markup (imagine the $fortune variable itself contained things like "`<b>`"), they must be encoded accordingly before output. "`<b>`" would then become "&lt;b&gt;", which would be displayed by the browser as "`<b>`" again.

It is therefore important to remember to use *htmlReady()* (or *formatReady()* if you are working with Stud.IP formatting) every time you output a value in a template. The only exceptions are values that already contain ready-made HTML fragments for display. We will see an example of this later.

##### The plugin class

As in the first example, the plugin is derived from the class `StudipPlugin`, but now implements the interface `PortalPlugin`, as discussed above. In addition, we now need a separate *TemplateFactoy* for our plugin so that the template can be loaded from the plugin's folder. It is usual to store all templates for a plugin in a folder called "`templatess`". The code for loading the template then looks like this:

```php
$template_path = $this->getPluginPath().'/templates';
$template_factory = new Flexi_TemplateFactory($template_path);
$template = $template_factory->open('fortune');
```

The *getPluginPath()* method of a plugin returns a file system path to the installation folder of the plugin; template files, for example, can then be loaded relative to this location. However, this path is only valid on the server side and cannot be used to generate URLs.

Finally, our plugin should have an icon and a title for the display. To do this, the corresponding attributes "icon_url" and "title" must be set in the template. We simply enter the name of the plugin as the title. For the URL of the icon, we need a URL to a resource in the plugin. There is also a method *getPluginURL()* - analogous to *getPluginPath()* for server-side paths - which returns a URL to the installation location of the plugin that is valid for the user. This URL can then be used as the basis for the icon URL:

```php
$template->title = $this->getPluginName();
$template->icon_url = $this->getPluginURL() . '/images/icon.gif';
```

Finally, the template variable *$fortune* from the template file must be filled with a quote text. A simple possibility is to simply call the *fortune* command (hopefully installed on the computer), which brings along a corresponding quotation database. The complete plugin class then looks like this at the end:

```php
class Fortune extends StudipPlugin implements PortalPlugin
{
    public function getPortalTemplate()
    {
        $template_path = $this->getPluginPath().'/templates';
        $template_factory = new Flexi_TemplateFactory($template_path);
        $template = $template_factory->open('fortune');

        $template->title = $this->getPluginName();
        $template->icon_url = $this->getPluginURL() . '/images/icon.gif';

        $template->fortune = shell_exec('/usr/games/fortune startrek');
        return $template;
    }
}
```

The following template file named fortune.php must now be created in the template directory of the plugin:

```php
<div id="fortune">
            <?= $fortune ?>
</div>
```

#### HomepagePlugin - A plugin on the profile page

The next example is about a plugin on the profile page. Our task is to recreate a simple version of the profile's "My Categories" in a plugin: A (possibly formatted) text is to be displayed in a box in the user profile, which can also be edited there by the user. In contrast to the "Own categories", however, there is only ever one box and neither the title nor the visibility can be set. We have to deal with the following new questions:

# Displaying content on the profile page
# Dealing with Stud.IP formatting
# Output and evaluation of forms (user interaction)
# Saving user entries in the database

##### The `HomepagePlugin` interface

Just like displaying your own content on the start page, there is also a corresponding interface for displaying content on the profile page: `HomepagePlugin`. It offers the same options and is used in exactly the same way:

```php
interface HomepagePlugin
{
    /**
     * Return a template (an instance of the Flexi_Template class)
     * to be rendered on the given user's home page. Return NULL to
     * render nothing for this plugin.
     *
     * The template will automatically get a standard layout, which
     * can be configured via attributes set on the template:
     *
     * title title to display, defaults to plugin name
     * icon_url icon for this plugin (if any)
     * admin_url admin link for this plugin (if any)
     * admin_title title for admin link (default: Administration)
     *
     * @return object template object to render or NULL
     */
    function getHomepageTemplate($user_id);
}
```

Here too, of course, you need a template object and corresponding template files for the output.

#### Output of formatted text

The template for the display can again be kept very simple:

Attach:editbox.png

**templates/fortune.php**

```php
<div id="edit_box">
    <?= formatReady($text) ?>
</div>
```

Unlike the previous example, the *formatReady()* function is called here to prepare the text to be output for display. While *htmlReady()* only takes care of the coding of special characters, *formatReady()* also evaluates the Stud.IP formatting syntax, i.e. certain [markings in the text](http://docs.studip.de/help/2.0/de/Basis/VerschiedenesFormat) lead to special highlighting in the display. In addition, lists, tables, links, images and other items can also be displayed. The "*id*" on the surrounding DIV is used later to be able to jump to this point on the profile page.

To create the template, you can essentially reuse the code from the last example (we are dispensing with an icon here). The content to be displayed comes from a separate method *getContents()*, which will later read the text from the database:

```php
class EditBox extends StudipPlugin implements HomepagePlugin
{
    public function getHomepageTemplate($user_id)
    {
        $template_path = $this->getPluginPath().'/templates';
        $template_factory = new Flexi_TemplateFactory($template_path);
        $template = $template_factory->open('edit_box');

        $template->title = $this->getPluginName();
        $template->text = $this->getContents($user_id);
        return $template;
    }
}
```

##### Forms and URLs

The template shown above does not yet allow editing of the displayed content. This is what this section is about: We still need an appropriate template for the input - i.e. an HTML form - as well as some logic in our plugin to evaluate this input. If the Stud.IP user calls up his own profile page, he should be able to activate a special editing mode of the plugin (more on this in a moment), which then displays a corresponding form:

Attach:editbox-edit.png

**templates/edit_mode.php**

```php
<form id="edit_box" action="<?= URLHelper::getLink('#edit_box') ?>" method="POST">
    <textarea name="text" style="display: block; width: 80%; height: 8em;"><?=
        htmlReady($text)
    ?></textarea>
    <?= makeButton('apply', 'input', false, 'save') ?>
    <?= makeButton('cancel', 'input', false, 'cancel') ?>
</form>
```

The form has a very simple structure: There is a TEXTAREA to edit the content and two buttons to save or discard the changes. Of course, the *htmlReady()* must not be missing here either. A *formatReady()* would be wrong at this point, as we do not want to edit the already formatted view. There is an auxiliary function *[makeButton()](http://hilfe.studip.de/api/language_8inc_8php.html#a029ae0013a8aa35f8cea9e5ab43cda16)* in Stud.IP for displaying form buttons, which we also use here. This is an example of a function that already returns finished HTML fragments, so the result of *makeButton()* must no longer be treated with htmlReady(). The HTML generated by makeButton() will look something like this afterwards:

```php
<input class="button" type="image" src="[...]/take-button.png" name="save">
<input class="button" type="image" src="[...]/cancel-button.png" name="cancel">
```

When submitting the form, our plugin should be able to process the data entered, so we have to make sure that the profile page (where the plugin lives) is displayed again, and the plugin box should also be jumped to directly. As URL for the form we have to generate a suitable URL to the profile page, including a jump point on the page. URLs to pages in Stud.IP are always generated via the [URLHelper](URLHelper) class - with a few special exceptions. In the simplest case, you just enter the name of the corresponding PHP script for the page in the call to *URLHelper::getURL()* and get the corresponding URL back. Here it is even easier: We are already on the profile page, so we only have to specify the starting point on the current page: "`#edit_box`".

The following also applies here: When inserting values in HTML, you must always consider whether an *htmlReady()* is still required. Normally this would be the case, but since the creation of links occurs quite frequently, there is a helper function in the `URLHelper` that takes care of the coding at the same time: *URLHelper::getLink()*. The result of this function can therefore (like *makeButton()*) always be used directly in the output.

##### Processing user input

Our plugin recognizes two types of user interaction: activating *edit mode* and saving or discarding form entries. Both are only permitted for the owner of the displayed profile. To be able to react to this, we have to add a few lines of code to our plugin method (in the getHomepageTemplate function between the lines $template = $template_factory->open('edit_box'); and $template->title = $this->getPluginName();) :

```php
if ($user_id == $GLOBALS['user']->id) {
    if (Request::int('edit')) {
        $template = $template_factory->open('edit_mode');
    } else if (Request::submitted('save')) {
        $this->setContents($user_id, Request::get('text'));
    }

    $template->admin_url = URLHelper::getURL('#edit_box', array('edit' => 1));
    $template->admin_title = 'Edit content';
}
```

The user currently logged into Stud.IP is stored in the global variable `$user`. We can therefore easily check whether the current user is also the owner of the displayed profile. If so, the edit mode can be selected via a special icon in the title bar of the box for the plugin (on the far right, analogous to the icon for own announcements and surveys). The template can be used to specify the link, possibly with additional URL parameters, and a tooltip for the icon. If the editing mode has been activated, the corresponding template is loaded.

To query URL and form parameters in Stud.IP, you should always use the [Request](Request) class, which also allows type-safe access to the parameter values. The names of the parameters correspond to those in the template, i.e. "`Request::submitted('save')`" determines whether the button with the name "save" has been clicked in the form.


#### Plugins with navigation

The examples of plugins shown so far have all been integrated into existing pages, but a plugin can also offer completely separate pages in Stud.IP or even deliver content that does not use the Stud.IP design at all, such as web services or file downloads. How this works is described in this section. Again, we start with a small example, this time the task should look like this:

* The plugin should have its own icon in the navigation.
* The plugin should be linked as a point on the start page.
* It should display a completely independent page when called up, including an info box:

Attach:demoplugin.png


### Further development steps

#### Access to the database

The database is accessed in Stud.IP via [SimpleORMap](SimpleORMap) (SORM). Plugins can define their own SimpleORMap class and thus use the advantages of SimpleORMap.

##### Creating a new table via migration

A migration can also be used to create a new database table. This means that the creation of the database table and subsequent migrations are carried out in the same way.

To create a migration, a new folder called "migrations" is created in the plugin directory. This folder contains numbered migration files, which are PHP scripts that contain a single class and whose file name corresponds to a special scheme. The file name may only contain lower case letters, otherwise no migration can be carried out. For example, 01_initial.php would be a valid file name, while 01_Initial.php (capital "i") would result in an error.

A class is now created in the migration file which extends the "Migration" class. For reasons of simplicity, its name can be chosen in the same way as the file name after the numbering, i.e. "Initial" in the example (capital letters are permitted here). The class must implement the methods up() and down(). up() is used to perform a migration, while down() undoes the changes to database tables that were made with up(). To access the database, the DBManager class is used, which returns a database connection via the get() method:

`$db = DBManager::get();`

SQL code can now be executed on the database with `$db->exec`, as the following example shows:

```php
$db->exec("CREATE TABLE edit_box (
    user_id varchar(32) NOT NULL,
    content text NOT NULL,
    PRIMARY KEY (user_id)
);"
          );
```

The table has been created, but is still empty. At this point, the table can of course be filled with a second `$db->exec` call and an `INSERT INTO` SQL statement. This completes the up() method. Now the down() method must be written. As this is the first migration, the table can be deleted when the migration is undone:

```php
$db = DBManager::get();
$db->exec("DROP TABLE hallo_welt;");
```

The migration file is now complete.


##### Creating a SimpleORMap class

Now a SORM class must be created in the plugin with which entries from the database table can be converted into objects. To do this, create the models subfolder in the plugin directory and a PHP file in it containing the data class that you would like to have in the database. The name of the PHP file must correspond to the name of the class. The class definition can look like this in a simple case:

```php
class EditBox extends SimpleORMap {

    static protected function configure($config = array()) {

        $config['db_table'] = 'edit_box';

        parent::configure($config);
    }
}
```

This allows objects of the EditBox class to be retrieved from the database, provided the database table exists.


**Note:** When creating new tables, you should always use the default settings of the database if possible, i.e. do not specify any character encoding or storage engine.

##### Reading and writing from the database

Read and write accesses are explained in the wiki article [SimpleORMap](SimpleORMap).


#### Localization with gettext

Several steps are necessary to localize a plugin. First, the templates must be prepared for translation. These are used to generate the translation files.

##### Translations in templates

The dgettext function is used to be able to use translations in the templates of a plugin. This works almost like gettext, with the difference that dgettext is first given the translation domain before the string to be translated is passed. This is due to the fact that the strings to be translated by the plugin cannot be found in the Stud.IP translation files and therefore separate translation files must be created in the plugin.
A text to be translated is rewritten as follows:
before: `echo "Hello world!";`
after: `echo dgettext("MyPlugin", "Hello world!");`

Using dgettext, it was specified that the translation of the character string "Hello world" can be found in the translation domain "MyPlugin", which is only used in the plugin.

##### Creating the translation files

To create the translation files, the Unix shell script makeStudIPPluginTranslations.sh can be used, which can be used for translation into several languages and can be easily adapted for other projects. It is located on the developer installation of Stud.IP: [https://develop.studip.de/studip/dispatch.php/document/download/1bea6c139b56abc3ef0c505731bcc6b6](https://develop.studip.de/studip/dispatch.php/document/download/1bea6c139b56abc3ef0c505731bcc6b6)

The folder structure in which the translation files are located below the plugin directory must correspond exactly to the following scheme: /locale/<abbreviation of the language>/LC_MESSAGES/. In addition to English, other languages are of course also possible. As only German and English are currently (June 2016) available as languages in Stud.IP, additional languages into which the plugin has been translated cannot be activated.
After running the script, a file is available in the LC_MESSAGES folder: MyPlugin.pot. This can now be edited with a translation editor such as Poedit. The editor should automatically create a .mo file from the pot file when saving the translations so that the translation is available in machine-readable form.

#### Adjustments in the constructor of the plugin class

To specify that translations in the plugin should be obtained from your own translation domain, the following code must be inserted in the constructor of the plugin class:
`bindtextdomain('MyPlugin', __DIR__ . '/locale');`

The translation domain is set to "MyPlugin" using bindtextdomain. So that gettext also knows where to find the associated translation files, the absolute path to the subfolder of the plugin in which the translations are located is required.
IMPORTANT: `$this->getPluginPath()` only returns a relative path, which starts in a subfolder of the Stud.IP installation directory and therefore cannot be used at this point to specify the path to the translation files!

After these steps, all requirements for localizing a plugin are met.

Create #### controller in the plugin

It may be necessary for a plugin to have its own controllers, which provide their own pages in the plugin. Such pages are realized in [Trails](Trails). Trails is a framework that implements the MVC paradigm so that the program logic (controller) is separated from the HTML output (view) and the database model (models). As SimpleORMap is already used in Stud.IP for the implementation of models, only views and controllers remain, which have to be implemented via trails.

##### Creating a controller

A subfolder called "controllers" is created in the plugin directory. In this folder, a separate PHP file is created for each controller, each containing only one controller class. The file name in which the controller is contained is kept in lower case. The class that implements the controller is written in the usual notation (capital letters at the beginning of each word, without underscores). It extends the PluginController class.

The following line should be inserted before the class definition for reasons of compatibility with old Stud.IP versions:
`require_once('app/controllers/plugin_controller.php');`

A simple controller can look like this:

```php
<?php
class HelloController extends PluginController {
    public function index_action() {
        $this->text = dgettext('MyPlugin', 'Hello world!');
    }
}
```

##### Creating a view

Each view must be created in the "views" subfolder of the plugin folder. A separate subfolder is created there for each controller, in which the individual views are then stored. A separate view is created for each action of a controller, whereby each view has its own PHP file. Any HTML code can be stored in a view. Attributes of the controller can be called up as simple variables in normal PHP syntax.

The controller above has only one action: "index". It is also called "HalloController" and its PHP file is therefore called hallo.php and is located in the /controllers/ folder. The corresponding view must be located in the /views/hallo/index folder. For the above controller, the view can be limited to the following code:

```php
<strong><?= $text; ?></strong>
```

The attribute `$this->text` from the controller has simply become `$text`. Other attributes of the controller class are also passed to the view, for example the attribute `$this->plugin`, which is predefined.

#### Access for users who are not logged in

If we want our plugin to be visible to users who are not logged in, we have to select in the rights settings during installation that the role "nobody" (this role is specifically for users who are not logged in) can also use the plugin in addition to the preset standard roles:

Attach:roles-nobody.png

The installed plugin then looks like this when called in the system:

Attach:fortune.png

##### Source code of the plugin

Attach:Fortune.zip

### Publication

If your own plugin is functional, it can be uploaded to the Stud.IP marketplace so that others can test and use the plugin.

In order to upload a plugin to the marketplace, a user account on the Stud.IP installation [https://develop.studip.de](https://develop.studip.de) is required. There is a tab called PluginMarketplace in the top bar. On this tab there is a tab called "My plugins", under which the HalloWelt plugin can be uploaded. After clicking on "My plugins", select "Add new plugin" in the left-hand area and fill in the dialog that opens. Screenshots of the plugin quickly show other users what the plugin can do and should therefore be added.

Under "Add release", select "as file". Now pack the finished plugin into a ZIP file again and select it after clicking on the "Browse" button in the dialog. After clicking on "Save", the plugin has been uploaded and must be activated by a marketplace administrator. As soon as this has been done, the plugin will be listed on the start page of the plugin marketplace under "Newest plugins".

**Congratulations on the first published Stud.IP plugin!

Moritz Strohm has created another tutorial for the creation of plugins
created. You can find it here: https://develop.studip.de/studip/dispatch.php/document/download/5747961f81b385b1520cf7dc393f1db6
