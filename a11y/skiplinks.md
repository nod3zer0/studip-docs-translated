---
id: skiplinks
title: Skiplinks
sidebar_label: Skiplinks
---

Skiplinks enable keyboard users to navigate quickly within a page. In Stud.IP the skiplinks are initially hidden. The skiplinks are only displayed by pressing the tab key. The first link in the list becomes the focus, so that you can navigate through the list by pressing the Tab key again. Pressing the Enter key jumps to the target of the link.

This class makes it possible to place skiplinks at a central point in the page layout in Stud.IP. This location consists of a DIV container which is output fairly directly after the closing header tag. Basically, every page in Stud.IP that has a layout or includes `lib/include/html_head.inc.php` outputs this container.

This container contains the actual list with skiplinks as an enumerated list. Each list element consists of a skip link.

The SkipLinks class makes it possible to add skiplinks to this list at any time during the rendering process. It also provides methods for outputting the list, but these do not normally have to be called explicitly. As already mentioned, the output takes place in a central location.

## Methods of the class

The SkipLinks class cannot be instantiated, it only offers class methods, i.e. a method is called via `SkipLinks::`*Name*.

**addIndex($name, $id, $position = NULL, $overwritable = false)**

  Registers a link to a destination on the output page (anchor).

  `$name` is the name of the link to be output in the list.

  `$id` is the id of the HTML element that is to be jumped to by the skiplink. This element must have an attribute *id* with the value transferred here. The IDs must of course be unique on a page.

  `$position` is the position of the skiplink in the list. This parameter therefore allows the links to be sorted to a specific position. If no position is specified, the link is created after the first free position greater than 100.

  With `$overwritable` you can specify whether a link at a certain position can be overwritten by a skiplink added later in the page rendering process.

* **addLink($name, $url, $position = NULL, $overwritable = false)**

  Registers a link to another (also external) page.

  Instead of an id for an anchor, the parameter `$url` is used to pass any URL to the method to which the link is to refer. The other parameters have the same function as with addIndex().

**insertContainer()**

  Adds a container to the page layout that holds the list of ski links.

  This function is normally called automatically at the right place when a Stud.IP page is rendered, so that it is not necessary to call it manually.

* **getHTML()**

  Returns the registered skiplinks formatted according to the template `templates/skiplinks`. The template renders the skiplinks as an HTML list.

  This function is also called automatically when a Stud.IP page is rendered, so it is not necessary to call it manually.

## Pre-assigned positions

A page in Stud.IP usually always has a set of identical content areas. Ski links with a fixed position are automatically registered for these. In detail these are

* Main navigation in the header (position 1, cannot be overwritten)
* First tab navigation (position 10, cannot be overwritten)
* Second tab navigation (position 20, cannot be overwritten)
* Main content (position 100, can be overwritten)
* Info box (position 10000, cannot be overwritten)


These pre-assigned positions make it possible to set a skiplink between the second tab navigation and the main content, for example. However, care should be taken to ensure that the ski links to the main navigation areas are always placed uniformly at the top of the list on all pages so as not to confuse the user.

## Examples

Inserting the ski link for the main navigation:

```php
[...]

<div id="barTopFont">
<?= htmlentities($GLOBALS['UNI_NAME_CLEAN']) ?>
</div>
<? SkipLinks::addIndex(_("main navigation"), 'barTopMenu', 1); ?>
<ul id="barTopMenu" role="navigation">
<? $accesskey = 0 ?>
<? foreach (Navigation::getItem('/') as $nav) : ?>

[...]
```

Use of `addLink()` on the `index_nobody.php`. The links of the start page are transferred to the skiplink list so that the user can directly access the login page, for example:

```php
[...]

<? foreach (Navigation::getItem('/login') as $key => $nav) : ?>
    <? if ($nav->isVisible()) : ?> <? list($name, $title) = explode(' - ', $nav->getTitle()) ?>
        <div style="margin-left:70px; margin-top:10px; padding: 2px;">
            <? if (is_internal_url($url = $nav->getURL())) : ?>
                <a class="index" href="<?= URLHelper::getLink($url) ?>">
            <? else : ?>
                <a class="index" href="<?= htmlspecialchars($url) ?>" target="_blank">
            <? endif ?>
            <? SkipLinks::addLink($name, $url) ?>
            <font size="4"><b><?= htmlReady($name) ?></b></font>
            <font color="#555555" size="1"><br><?= htmlReady($title ? $title : $nav->getDescription()) ?></font>
            </a>
        </div>
    <? endif ?>
<? endforeach ?>

[...]
```

## A little magic

The list of skiplinks is first added to the end of the page. Javascript moves the list to the container at the top of the page. To make it easier for users of older screen readers to navigate and recognize content areas, each target of a skiplink is provided with the link text as an *h2* heading via Javascript. The linked HTML element is provided with the attribute `aria-labelledby`, which refers to the heading, so that the content area is named accordingly.
