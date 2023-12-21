---
id: wysiwyg
title: Wysiwyg-Editor
sidebar_label: Wysiwyg-Editor
---

## The Wysiwyg editor in Stud.IP

### General

%rfloat margin-left=1ex% Attach:wysiwyg-demo.png
In Stud.IP there is also a Wysiwyg editor that can be used to enter formatted content (text, images, links, tables etc.). This editor replaces HTML text fields with a graphical interface that is similar to common word processing systems (e.g. [LibreOffice](http://www.libreoffice.org/)). The acronym WYSIWYG stands for "*What You See Is What You Get*". The component we use is the [**ckeditor**](http://ckeditor.com) and can be used as an alternative to entering Stud.IP formatting. Content in the system is either classic Stud.IP formatting or HTML content from the Wysiwyg editor. There is no mixing: Stud.IP formatting is not evaluated in content created with the editor (with the exception of special markup plugins) and HTML content is not evaluated in Stud.IP formatting.

### Configuration

%rfloat margin-left=1ex% Attach:wysiwyg-konfig.png
The editor can be activated system-wide via the setting `WYSIWYG` in the global configuration. Please note that content created with the editor is saved as HTML in the database, which remains behind when the editor is switched off. If the editor is activated system-wide, it is offered to all users by default - but they can deactivate it for themselves individually. From the developer's point of view, there is therefore no guarantee that all newly created content will actually be available in HTML if the editor is activated system-wide. For this reason, the PHP API is designed in such a way that it can deal with the various constellations in a largely transparent manner.

### Usage

If an input field is to use the editor, it must be marked with the CSS class "`wysiwyg`" (possibly in addition to "`add_toolbar`" for the classic formatting toolbar). The content must be prepared for the *textarea* element using the `wysiwygReady()` function, which works in the same way as `htmlReady()`, but translates Stud.IP formatting into HTML before editing if necessary.

Example:
```php
<textarea class="add_toolbar wysiwyg" name="content">
    <?= wysiwygReady($content) ?>
</textarea>
```

The corresponding code in the controller, which receives and processes the input, should run the user input through the *HTMLPurifier*. For this purpose, there is the function `Studip\Markup::purifyHtml()`, which performs a corresponding filtering if the input is actually HTML:

Example:
```php
$content = Studip\Markup::purifyHtml(Request::get('content'));
```

However, the use of the editor for single-line input fields (i.e. `<input>`) is currently not supported.

#### Further functions of the class `Studip\Markup`

In the vast majority of cases, the API described above should be sufficient. For special use cases, however, there are further functions in the `Markup` class, which are briefly described here:

* `Studip\Markup::editorEnabled()`\\
  This function returns `true` if the editor is enabled system-wide and at user level.

* `Studip\Markup::isHtml($text)`\\
  This function returns `true` if the transferred content is interpreted by Stud.IP as HTML.

* `Studip\Markup::markAsHtml($text)`\\
  Marks a content as HTML. If the content is already marked accordingly, it is not changed.

* `Studip\Markup::purifyHtml($html)`\\
  If the content is marked as HTML, it is filtered with the *HTMLPurifier*. Other content is not changed.

* `Studip\Markup::markupToHtml($text, $trim = true, $mark = true)`\\
  Converts content from Stud.IP formatting to HTML so that it can be edited in the editor, for example. If the content was already HTML, it is only filtered by the *HTMLPurifier*. Normally the result is also immediately marked as HTML, but this behavior can be switched off.

* `Studip\Markup::removeHtml($html)`\\
  Removes all HTML elements from the content, e.g. so that it can then be edited again without the Wysiwyg editor. If the content is not HTML, it is not changed.

Example 1: Generate predefined content as HTML that can be edited via the editor, e.g. as a default value for an input field with the Wysiwyg editor:

```php
$html = Studip\Markup::markAsHtml('<h1>' . htmlReady($title) . '</h1>');
```

Example 2: Merging formatted content so that the result is text or HTML depending on the user settings of the editor (an example of this is merging a message created in the editor with the user's signature):

```php
if (Studip\Markup::editorEnabled()) {
    $result = Studip\Markup::markupToHtml($part1) . Studip\Markup::markupToHtml($part2);
} else {
    $result = Studip\Markup::removeHtml($part1) . Studip\Markup::removeHtml($part2);
}
```

#### Javascript API

In addition to the regular Javascript API of the ckeditor, which can also be used in Stud.IP (of course only if the editor is activated), there are a small number of settings and auxiliary functions in Stud.IP:

* `STUDIP.wysiwyg_enabled`\\
  This property is `true` if the editor is activated system-wide.

* `STUDIP.editor_enabled`\\
  This property is `true` if the editor is active in the current context - i.e. it is activated system-wide, the user has not switched it off and the editor works on the client (or at least thinks it does).

* `STUDIP.wysiwyg.isHtml(text)`\\
  This function returns `true` if the passed content is interpreted as HTML by Stud.IP.

* `STUDIP.wysiwyg.markAsHtml(text)`\\\
  Marks content as HTML. If the content is already marked accordingly, it is not changed.

Example:

```javascript
posting = jQuery('textarea[name=content]').val();
if (STUDIP.editor_enabled) {
    posting = STUDIP.wysiwyg.markAsHtml(posting);
}
```
