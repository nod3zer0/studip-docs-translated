---
title: Trails for core development
sidebar_label: Trails (Web-Framework)
---

Trails is an independent MVC framework which is available fully configured in Stud.IP.

> Attention**: The following documentation refers to the use of Trails for Stud.IP core development. For the use of trails in plugins, please read [Trails in Plugins](TrailsInPlugins).

## Trails & Stud.IP core

The following is a short introduction to the development of Trails pages in Stud.IP.

Trails follows the [MVC paradigm](http://de.wikipedia.org/wiki/Model_View_Controller). Following this paradigm, there is a folder called `app` in the main directory of Stud.IP, which has the subfolders `controllers` and `views`. The models, on the other hand, are located under `lib/models`.

## The structure

The controller is the pivotal point for a page.

A new controller is created in the `app/controllers` directory. In the simplest case, a PHP file is created there directly. If it is a larger collection of controllers, you can also create subdirectories. The path structure there is transferred 1:1 to the URL.

A controller should never access the database directly and should also generate as few data structures as possible. It is responsible for creating the correct data from the correct model in the view. Models are located in the appropriate directory, namely 'app/models' and must be included in the controller for use using `require_once`. As of Stud.IP version 2.5, models are automatically loaded from 'app/models'.

In principle, output only occurs within the view in templates. The templates are located in `app/views` and have the following path structure underneath `/path_to_controller/name_of_controller/name_of_action.php`

## Example

To illustrate the whole concept and the possibilities, an example trails page is explained in detail below.

## The controller

### Only logged in to Stud.IP or freely available?

`app/controllers/example/page.php`

```php
require_once 'app/controllers/authenticated_controller.php';
require_once 'app/controllers/studip_controller.php';

class Example_PageController extends AuthenticatedController {
```

The first decision you have to make is whether you can only see this controller as a logged-in user or even if you are not logged in. To do this, you simply choose one of two classes from which you inherit.

* As the name suggests, the `AuthenticatedController` class is the one that ensures that only logged-in users can access this trails controller.
* If you inherit from `StudipController`, it is not necessary to log in (for the time being). The controller would then have to do this itself if required. The class name of the controller depends on the path of the file. The file `controllers/path1/path2/filename.php` must then read as follows: `Path1_Path2_FilenameController`.

### The index_action - most important action in the controller

```php
function index_action($param1 = false, $param2 = false)
    {
        // Get data

        $this->data = array('index', 'The template stored in views/exmaple/asite/index.php is automatically used here. The file name of the template is always the same as the action');
    }
```

This is now an action. There can be any number of these in each controller. The `index_action` has a special status. If no action is specified in the URL, it serves as a fallback.

### The url scheme

This action can now be called in Stud.IP as follows:

[`http://irgendeinstudip/dispatch.php/example/pag/index`](http://irgendeinstudip/dispatch.php/example/pag/index)

This url has the following scheme:

[`http://irgendeinstudip.de/dispatch.php/{path/to/controller}/{name_of_controller}/{name_of_action}[/parameter1][/parameter2][...]`](http://irgendeinstudip.de/dispatch.php/{path/to/controller}/{name_of_controller}/{name_of_action}\[/parameter1\]\[/parameter2\]\[...\])

### Templates for actions

The special thing about the Trails framework is that you do not have to get a template from the template factory first, but that (unless you say otherwise) a template that belongs to the action is implicitly displayed.

These templates are located under `app/views` and in this case under `example/page/index.php`.

Variables are transferred to this template by setting them using `$this`. In the example above, you can see that `$this->data` receives an array. In the template, you then automatically have a variable `$data` at hand, which contains the values assigned in the controller. More on this below in the output template.

### Manipulation of the control flow - I

With trails, an action can do more than just transfer data to an automatically loaded template. It can also influence the control flow.

See the following example actions:

```php
function redirect_action() {
    $this->redirect('example/asite/helloworld/Hello world! This time even redirected by redirect!');
}
```

```php
function backendwithmessage_action()
{
    // do something
    $this->flash['message'] = array('message' => array('This message has already been saved in the delete_action in the reserved variable flash!'));

    // return to index-action
    $this->redirect('example/asite/index');

}
```

This action contains two of the most important possibilities of trails.

### Routing in trails

Firstly, you can use `$this->redirect({path_to_controller}/{name_of_controller}/{action}[/parameter]);` to redirect to another action in any other controller. This allows you to have actions that do not need their own output, as they only delete an entry, for example, and then display the same page again. If there is a new action, you simply add another action and then forward it appropriately.

Since Stud.IP 5.1, `redirect()` can be used in the same way as `url_for()` or `link_for()`. This means that any number of parameters can be specified, which are then assembled to form the URL to be redirected to. The only exception: no URLs **and** other parameters can be passed.

### Persistent values

The possibility of routing leads us directly to another aspect of trails. What if such a "hidden" action wants to have a status message on the main page to which it is routed? For this and similar purposes, there is the special variable _flash_.

This variable can be directly assigned a value or a value at a position in an array (as used in the example). This value now remains stored in the variable `$flash` until it is read out.

In an action you can then access it using `$this->flash`, in the template simply `$flash`.

### Manipulation of the control flow - II

In addition to `$this->redirect`, there are other options for intervening in the control flow.

```php
function helloworld_action($text = 'Hello world!') {
    $this->render_text(
        'helloworld, $this->render_text(\*. htmlReady(urldecode($text)) .'\')<br>' .
        'Here this is just text, without layout<br><br>' .
        htmlReady(urldecode($text))
    );
}
```

If you call `$this->render_text(...)`, only the specified text is output without any Stud.IP context.

```php
function index2_action() {
    $this->data = array('index2, $this->render_action(\'index\')', 'Here the template for an action in this controller is rendered, with layout');
    $this->render_action('index');
}
```

`$this->render_action(*action*)` calls the template for an action in this controller and outputs it with Stud.IP context.

```php
function index3_action() {
    $this->data = array('index3: $this->render_template(\'example/asite/index\')', 'Only a template from view is rendered here, without layout');
    $this->render_template('example/asite/index');
}
```

`$this->render_template(*path_to_controller*/*name_of_controller*/*name_of_template*)` renders the specified template without Stud.IP context.

```php
function nihilist_action()
{
    $this->render_nothing();
}
```

With `$this->render_nothing()` you tell trails: Please do not output a template.

## The view

The following file is the view for our example.

### Variable access and partials

```php
// Output the variable set in the controller
var_dump($data);
?>

<!-- A little text/HTML -->
<b>Huhu</b>

<!-- A partial template -->
<?= $this->render_partial('example/asite/_feedback'); ?>
```

`var_dump($data)` outputs what we have assigned in the controller using `$this->data=*...*`. In this way, pre-assigned variables enter the template.

`$this->render_partial(*template*)` is already known from the normal templates. It allows you to include and display a subtemplate, a so-called partial, within a template. The content of these partials is explained below.\\ Special attention should be paid to the fact that `render_partial` returns a string that must first be output. In our example, this is done using `<?=`.

### URLs for actions in controllers

```php
<br>
This is how you get a path to a controller:<br>
$controller->url_for('example/asite/backendwithmessage');<br>
<br>
<a href="<?= $controller->link_for('example/asite/backendwithmessage') ?>"><button>Try it out</button></a>
```

`$controller->url_for('path_to_action')` is probably the most important function within a template. As the name suggests, you get a URL to a specific action in a specific controller here.\\ This is the URL that you insert into forms, links, etc. if you want to move within trails.

Please note that `$controller->link_for('path_to_action')` should be used in places where a link is output and `$controller->url_for('path_to_action')` in places where the URL is used by another API.

As of Stud.IP 4.3, links to controller actions can also be created by calling `$controller->*action*(<parameter>)`. The path to the controller and the controller itself no longer need to be specified. A corresponding URL is obtained by appending `URL` to the called method: `$controller->*action*URL($parameter);`

`$controller->url_for()` takes any number of parameters (as long as this is not a URL) and builds a correct URL to the controller action from them. In particular, the following rules apply:

- A call to `$controller->url_for()` without parameters generates a URL to the currently called action.
- If the last parameter is an array, the values of the array are appended to the URL as GET parameters.
- If a [SimpleORMap](SimpleORMap) object is passed, this parameter is replaced by the ID of the object.

### Access to persistent values in the template

`app/views/example/asite/_feedback.php`

```php
if ($flash['message']['message']) {
    foreach ($flash['message']['message'] as $message) {
        echo MessageBox::info($message);
    }
}
```

This is the partial already mentioned above. Partials automatically have access to all variables of their parent template (where render_partial was said). In this particular case, the automagic variable `$flash` defined in the `backendwithmessage_action` is accessed.

## Trails and SimpleORMap (as of Stud.IP 4.3)

As of Stud.IP 4.3, Trails and SimpleORMap are linked somewhat more closely. The SimpleORMap objects can be passed directly as parameters of `link_for()` and `url_for()` or the short `*action*()` or `*action*`URL()@@ calls, whereby their ID is used as a parameter:

```php
$controller->link_for('controller/edit', $sorm);
// or
$controller->edit($sorm);
```

The parameters of the action on the controller can also return SimpleORMap objects directly by giving them a corresponding typehint. In this case, the passed ID is used to load the object. As long as the corresponding parameter is not optional (`= null`), `SimpleORMap::find()` is used to load the object. If the object is marked as optional, the object is created using `new *SORM*($id);`.

The `_autobind` property on the controller can be used to control whether the objects created in this way are also automatically bound to the view using the name of the parameter so that they are also available there.

An example of this:

```php
# Controller

    protected $_autobind = true;

    // ...

    public function edit_action(SORM $sorm)
    {
    }

# view

<label>
    <?= _('Title') ?>
   <input type="text" name="title" value="<?= htmlReady($sorm->title) ?>">
</label>
```
