---
id: icon
title: Icon
sidebar_label: Icon
---

## Using the Icon class

Stud.IP provides a rich selection of icons. As of Stud.IP v3.4, these icons are addressed with a PHP API. In the earlier versions, you had to know a path in the file system. Now it is sufficient to know the shape of the icon in order to integrate it.

The class `Icon` was created for this purpose. Instances of this class have 3 properties:

* the shape of the icon
* the role (and thus implicitly the color) of the icon
* semantic attributes such as `title`.

### Quick Start

We want to integrate the "start page" icon (which can be found at the top left of every Stud.IP page). To do this, simply write

```php
<?= Icon::create('home', Icon::ROLE_NAVIGATION, ['title' => _("Zur Startseite")]) ?>`
```

And this gives you an `img` element like this:

https://develop.studip.de/studip/assets/images/icons/lightblue/home.svg

The shape of the icon is "home". The role/function of the icon is "navigation". The icon should have a semantic attribute, a title: _("To home page").

The signature of `Icon::create` therefore looks like this:

```php
public static function create($shape, $role = Icon::DEFAULT_ROLE, $attributes = array())
```

In the past, if you wanted to use icons (`Assets::img("icons/16/lightblue/home.png")`), the color was hard-coded there.
If you wanted to redesign your installation, this was more than unsightly.
For this reason, the colors were banned from the PHP code and switched to roles.

Currently, the mapping of roles to colors is located in the variable `Icon::$roles_to_colors`.

If you want to use your own icons, simply write

```php
Icon::create($ownURL)
```

### Icon API in detail

The `Icon` class only offers a few methods.

#### Factory methods

```php
Icon::create($shape, $role = Icon::DEFAULT_ROLE, $attributes = [])
```

This is *the* method to instantiate an icon. The `$shape` specifies the shape of the icon without going into further detail about coloring etc.

The role `$role` defines the context in which the icon is to be used. For example, the Stud.IP style guide specifies that all icons in links should be uniformly colored (usually blue). The role helps to avoid hard-coding color values and still differentiate them uniformly throughout the system.

The `$attributes` properties contain **only** semantic attributes such as `title`. Non-semantic values such as CSS classes, sizes or `data` attributes may not be entered here.

#### Output methods

```php
$icon->asImg($size = null, $view_attributes = [])
```

This method outputs the icon as an `img` element:

```php
Icon::create('vote', Icon::ROLE_CLICKABLE)->asImg(16)
```

generated:

```html
<img width="16" height="16" src="images/icons/blue/vote.svg" alt="vote" class="icon-role-clickable icon-shape-vote">
```

The first parameter `$size` defines the `width/height` of the `img` element. The `$view_attributes` can be filled with any attributes such as `class` etc.

```php
$icon->asInput($size = null, $view_attributes = [])
```

A variation of `Icon::asImg`, which outputs the icon as an `input` element:

```php
Icon::create('upload', Icon::ROLE_CLICKABLE)->asInput(20, ['class' => 'text-bottom'])
```

results in:

```html
<input type="image" class="text-bottom icon-role-clickable icon-shape-upload" width="20" height="20" src="images/icons/blue/upload.svg" alt="upload">
```

The parameters work in the same way as for `Icon::asImg`.

```php
$icon->asCSS($size = null)
```

This (rarely used) method outputs the icon as a CSS style specification via @background-image@@:

```php
Icon::create('vote+add')->asCSS(17)
```

generated:

```css
background-image:url(images/icons/17/blue/add/vote.png);background-image:none,url(images/icons/blue/add/vote.svg);background-size:17px 17px;
```

The parameter `$size` defines the `background-size`.

```php
$icon->asImagePath()
```

With this method you simply get the path to the SVG that stands for the desired icon:

```php
Icon::create('vote+add')->asImagePath() === 'images/icons/blue/add/vote.svg'
```

```php
$icon->__toString()
```

The magic `__toString` method is just an alias with default values for `Icon::asImg`, so the following:

```php
echo Icon::create('vote+add')
```

just:

```html
<img width="16" height="16" src="images/icons/blue/vote.svg" alt="vote" class="icon-role-clickable icon-shape-vote">
```

outputs.

#### Getter

* `$icon->getShape()`
* `$icon->getRole()`
* `$icon->getAttributes()`

These methods simply return the corresponding values.

#### "Setter"

* `$anotherIcon = $icon->copyWithShape($shape)`
* `$anotherIcon = $icon->copyWithRole($role)`
* `$anotherIcon = $icon->copyWithAttributes(array $attributes)`

These methods do not change the `$icon`, but return a new icon with changed `Shape/Role/Attributes`. Instances of `Icon` are `immutable`, so that unwanted side effects cannot occur.

## What is still missing?

* `$size === false`
* Additions
* CSS mixins
* Role-to-color mapping
