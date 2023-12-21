---
id: buttons
title: Buttons
sidebar_label: Buttons
---


Buttons are created in Stud.IP via a separate class, which is available in the [Studip namespace](Studip namespace). They are derived from the Interactable class, the description of which is available at the following URL:
https://hilfe.studip.de/api/class_studip_1_1_interactable.html


Buttons are particularly useful when developing views.


### Types of buttons


#### Button


This refers to a simple button, which is displayed as a `<button>` element in HTML. In the simplest case, only a label, a name and an array of attributes are required to create a button. Here, for example, a simple button is created in a view:


```php
<?= \Studip\Button::create('Click me!', 'clickMyButton', ['data-dialog-button' => '1', 'data-hello' => 'world']); ?>
```


#### LinkButton


In contrast to a simple button, a LinkButton is displayed as an `<a>` element (link) in HTML. However, the same static method is used to create it. Where the name is entered in the standard button, the URL to be called up is entered in the LinkButton.


```php
<?= \Studip\LinkButton::create('Click me!', 'http://example.org', ['data-dialog' => '1', 'data-hello' => 'world']); ?>
```


Instead of a name, the second parameter specifies a URL to be visited when clicking. Of course, the URL for a Stud.IP controller or the controller of a plugin can also be specified here.


#### ResetButton


A ResetButton is particularly useful in HTML forms, as it is drawn in HTML as an `<input>` element of type "reset", so that it resets a form when clicked.


```php
<?= \Studip\ResetButton::create('Click me!', 'clickMeButton', ['data-dialog-button' => '1', 'data-hello' => 'world']); ?>
```
