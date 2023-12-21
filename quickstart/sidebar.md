---
id: sidebar
title: Access to the sidebar
sidebar_label: Sidebar
---

A sidebar is constructed using widgets, which are small, independent components of the sidebar. A sidebar is a singleton, so the only instance of the sidebar class can only be accessed via the static method `Get()`:

```php
<?php
$sidebar = Sidebar::Get();
```


#### Setting the title of the sidebar

The `setTitle()` method is used for this:
```php
<?php
Sidebar::Get()->setTitle('Hello Sidebar!');
```

The title can be removed again using the `removeTitle()` method.


#### Setting an image for the sidebar

There is space for an image at the top of the sidebar. To determine which image appears, the path to the image in the assets folder is passed to the `setImage` method:
```php
<?php
Sidebar::Get()->setImage('some/image');
```

The image can be removed again using the `removeImage()` method.


#### Setting an avatar for the context

In the header of the sidebar, there is the option of additionally displaying an avatar that belongs to the displayed context. This is done using the `setContextAvatar()` method, which is passed an object of type `Avatar`:
```php
<?php
Sidebar::Get()->setContextAvatar(Avatar::getAvatar(User::findCurrent()->id));
```

This avatar can be removed again using the `removeContextAvatar()` method.


#### Adding widgets

The `addWidget()` method of the `WidgetContainer` class, from which the Sidebar class is derived, takes care of adding widgets. Its first parameter is an object of the widget class, the optional second parameter gives the widget a name. If this is not set, the class name of the widget is used without the word widget as the name.

```php
<?php

$widget = new SearchWidget();
Sidebar::Get()->addWidget($widget, 'search1');
```


#### Adding a widget at a specific position

`insertWidget()` (also from the `WidgetContainer` class) allows you to add a widget and to specify the position at which the widget is to be added based on the name of another widget. The first parameter is an object of the widget class, the second parameter specifies before which other widget (identified by its name) the new widget should be added. The last parameter is again optional and specifies the name of the new widget.

```php
<?php

$widget1 = new SearchWidget();
$widget2 = new SearchWidget();
Sidebar::Get()->addWidget($widget1, 'search1');
Sidebar::Get()->insertWidget($widget2, 'search1', 'search2'); //widget2 (with name search2) is placed in front of widget1 (with name search1).
```



### JavaScript functions

The scrolling of the sidebar can be controlled via JS. The following function is available for this purpose:

* `STUDIP.Sidebar.setSticky(bool is_sticky = true)`
