---
title: Action menu
sidebar_label: Action menus
---
# ActionMenu

At `ActionMenu` action icons can be summarized in a fold-out menu.

## PHP

The class can be used directly within a view because it generates and outputs the necessary HTML code.

Via `ActionMenu::get()` you get an instance of the class, which can be filled with content.

The `addLink` method is used to add new action links, which are specified as usual with the parameters URL, label, icon and other options.

There is also the `addMultiPersonSearch` method, which receives a `MultiPersonSearch object` as a parameter and adds this search as an action.

A minimal call with a single action could therefore look like this:

```php
<?php
$menu = ActionMenu::get();
$menu->addLink(
    $controller->url_for('controller/action'),
    _('add'),
    Icon::create('add'),
    ['data-dialog' => 'size=auto']
);
$menu->addLink(
    $controller->url_for('controller/second_action'),
    _('edit'),
    Icon::create('edit'),
    ['data-dialog' => 'size=auto']
);
$menu->addButton(
    'delete',
    ('delete'),
    Icon::create('trash'),
    [
        'data-confirm' => _('Do you really want to delete?'),
        'formaction' => $controller->url_for('controller/delete')
    ]
);
$menu->addMultiPersonSearch(
    MultiPersonSearch::get('add_users')
        ->setTitle(_('Add persons'))
        ->setLinkText(_('add persons'))
        ->setSearchObject($array)
        ->setDefaultSelectedUser($array_selected_user)
        ->setDataDialogStatus(Request::isXhr())
        ->setJSFunctionOnSubmit(Request::isXhr() ? 'STUDIP.Dialog.close();' : false)
        ->setExecuteURL($controller->url_for('controller/add_member/'))
        ->addQuickfilter(_('Title'), $array)
);
echo $menu->render();
?>
```

## Vue

The Vue component is integrated via the `StudipActionMenu` tag and has the following properties:

```json
{
  "collapseAt": "Threshold value from which the menu is displayed as an actual menu [Number] (optional)",
  "context": "Optional context that is displayed above the entries [String]",
  "items": [],
  "title": "Title of the action menu [String] (optional, default: 'Action menu')"
}
```

The value for `collapseAt` is optional and if it is not specified, the Stud.IP default is used.

The `items` array consists of entries in the following format:

```json
{
  "label": "Text of the entry [String]",
  "url": "URL to be called when the entry is selected [String] (optional, default: '#')",
  "emit": "Event to be fired when the entry is selected [String] (optional)",
  "emitArgument": "Arguments to be given to the event when it is fired [Array] (optional)",
  "icon": "Icon to be displayed for the entry [object: {shape: String}] or false if no icon is to be specified (optional, default: false)",
  "type": "Possible type of entry: 'link', 'button', 'separator' [String] (optional, default: 'link')",
  "name": "Name of the button; entry automatically becomes a button if no 'url' is set [String] (optional)",
  "classes": "CSS classes that should be set for the entry [String] (optional)",
  "attributes": "Other HTML attributes that should be set for the entry [object] (optional)",
  "disabled": "Specifies whether the entry should be disabled [Boolean] (optional)"
}
```
