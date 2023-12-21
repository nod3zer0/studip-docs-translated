This tutorial is the second part of a plugin tutorial.
In the [first part](Plugin-Tutorial-I-(Plugin-Structure)) the basic plugin structure has already been created
and explained some basic concepts, both of which generally apply to every plugin.
This part now implements an explicit example of a plugin and is only intended to illustrate
how, for example, desired functionality can be implemented in a plugin.

It is of course strongly recommended that you have followed the first part
and the basic concepts such as [Trails](Trails) should have been understood.
At the end of this page there is another ZIP file with the created code and additional phpDoc attached.

Now the context of what is to be implemented becomes really relevant:
Users should be able to view all the "texts" created on an overview page.
They should also be given the opportunity
create texts themselves and edit their existing texts.


## Overview page
As a simple example of an action including its view,
we create an overview page on which all existing texts are displayed in a table.
To do this, we fill the `index_action` already created in the first part and its `index.php` view.

### index action
In the action method, we load all the necessary data that the view requires.
In this case, we only need all text objects,
which we can load with `$this->all_texts = \TextPlugin\Text::findBySQL("1");` and make available to the view.
We also use `Navigation::activateItem('text_root/text_overview');` to activate the current navigation element
and set the page title with `PageLayout::setTitle($this->_('Texts overview'));`,
which is displayed in the sidebar of the page.

```php
    public function index_action()
    {
        Navigation::activateItem('text_root/text_overview');
        PageLayout::setTitle($this->_('Texts overview'));

        $this->all_texts = \TextPlugin\Text::findBySQL("1");
    }
```

### index view
The loaded `text` objects are now accessible in the `overview/index.php` via the variable `$all_texts`.
We fill the view with a table that displays the title, the type, the author and the creation date.
```html
<table class="default sortable-table">
    <caption>
        <?= $_('Texts') ?>
    </caption>
    <colgroup>
        <col>
        <col style="width: 80px">
        <col style="width: 20%">
        <col style="width: 80px">
    </colgroup>
    <thead>
    <tr>
        <th data-sort="text"><?= $_('Title'); ?></th>
        <th data-sort="text"><?= $_('Type'); ?></th>
        <th data-sort="text"><?= $_('Author'); ?></th>
        <th data-sort="digit"><?= $_('Created on'); ?></th>
    </tr>
    </thead>
    <tbody>
    <? if ($all_texts): ?>
        <? foreach ($all_texts as $text_obj) : ?>
            <tr>
                <td><?= htmlReady($text_obj->title); ?></td>
                <td><?= $text_obj->getTypeDescription(); ?></td>
                <td>
                    <a href="<?= URLHelper::getLink('dispatch.php/profile?username=' . $text_obj->author->username) ?>">">
                        <?= htmlReady($text_obj->author->getFullName()) ?>
                    </a>
                <td><?= strftime('%x', $text_obj->mkdate); ?></td>
            </tr>
        <? endforeach; ?>
    <? else: ?>
        <tr>
            <td colspan="4">
                <?= MessageBox::info($_('No texts were found.')) ?>
            </td>
        </tr>
    <? endif; ?>
    </tbody>
</table>

```
Details about the view:
* The table can be made sortable by using the class `sortable-table` for the `<table>` element
and specifying a type with `data-sort` for each of the `<th>` elements ([Tablesorter](Templates#Tables))
* Strings such as `title` are marked as translatable with `$_($string)`.
* `$all_texts` is the variable loaded from the action method and is an array of `text` objects,
which is why, for example, `$text_obj->title` can be used to output the title of the current `text` object.
Since we had specified in the `configure()` method of the text model class
that `author` is a `user` object,
which is identified with the foreign key `author_id`,
the `user` object can be accessed directly with `$text_obj->author
and thus the name can be output with `getFullName()` (see [Relations in SORM](SimpleORMap#Relations)).
* `$text_obj->getTypeDescription();` is explained in detail in the next subsection
* With `URLHelper::getLink('dispatch.php/profile?username=' . $text_obj->author->username)` a link is created,
which leads to the user's profile (see [URLHelper](URLHelper)).
* With `strftime('%x', $text_obj->mkdate)` the Unix timestamp stored in mkdate,
is output as a date (see [php strftime](https://www.php.net/manual/de/function.strftime.php)).
* If no texts exist, an info box is created with `MessageBox::info()` (see [MessageBox](MessageBox)).

### Type of a text object
In the migration, we defined the `type` of a `text` object as `TINYINT(2) NOT NULL DEFAULT 1`,
so that an integer is stored there.
The idea of the `type` field is that we can specify a text type such as "short story", "novel" etc..
You can use an `enum` for this, as we only allow predefined types.
However, this would mean that we would have to adapt the database every time,
if, for example, we want to add a new type or rename one.
Instead, we use an integer and define the types in the code,
in exactly one place so that we have to make minimal changes,
when we customize the available types.

In a static `getTypes()` method in the `text` model class, we define all available types.

```php
public static function getTypes(): array
{
    return [
        1 => dgettext(Plugin::GETTEXT_DOMAIN, 'Short story'),
        2 => dgettext(Plugin::GETTEXT_DOMAIN, 'novel'),
    ];
}
```
Here you can also see an example of how strings are marked as translatable within model classes.
In order to obtain the current type of a text as plain text and not as an integer,
we also add a `getTypeDescription()` method.

```php
public function getTypeDescription(): string
{
    return self::getTypes()[$this->type] ?? 'Unknown type!
}
```

If we now want to add a new type,
we can simply add it to the array within `Text::getTypes()`.
Since integers are used for identification, typos are also avoided when comparing the type,
e.g. `if ($text_obj->type === 'kurzgeshcichte') [...]`.
However, defining an enum can still be useful in some cases,
if, for example, it is clear that the enums will never be expanded.

## Sidebar
The overview page now shows all texts,
but there is still no option to create texts.
To do this, we create an action in the sidebar of the overview page,
which calls up a new view,
in which a text is then to be created.

In the `OverviewController` we create a new method,
which is only used to create the sidebar.
How to use the sidebar is explained in [Sidebar](Sidebar).
```php
    private function buildSidebar()
    {
        $sidebar = Sidebar::Get();

        $actionWidget = $sidebar->addWidget(new ActionsWidget());
        $actionWidget->addLink(
            $this->_('Create text'),
            $this->url_for('overview/edit_text'),
            Icon::create('add'),
            ['data-dialog' => true]
        );
    }
```
We create a new `ActionsWidget` and specify a link to a new action `edit_text`,
which we will create and fill later (see [Trails](Trails)).
An `add` icon is passed as [Icon](Icon).
An overview of the icons is available in [Visual-Style-Guide](Visual-Style-Guide#Icons).
In addition, the view for creating a text should be opened in a dialog.
Views can generally be opened in a dialog in Stud.IP,
by setting `data-dialog`.
Further information on dialogs in Stud.IP can be found in [ModalerDialog](ModalerDialog).
Finally, you should remember to call the newly created method in the `index_action` method,
so that the sidebar is also displayed on the overview page.

## Create texts
Now it's time to build the action and view to create a text.

### Create form
To enable the subsequent editing of texts and to avoid redundant code,
we directly combine the creation and editing of texts.
However, depending on the situation and context, it may also make sense to split creating
and editing into individual actions and views, e.g. if the two views are very different.
views are very different.
As we do not want to make a distinction between creating and editing,
we always call `edit_text_action` when we want to create a text.

In the `edit_text_action` we load all the necessary data again,
that the view requires.
In this case, this is simply a `text` object.

```php
    public function edit_text_action(string $text_id = '')
    {
        PageLayout::setTitle($this->_('Edit text'));
        $this->text_obj = \TextPlugin\Text::find($text_id);
        if (!$this->text_obj) {
            $this->text_obj = new \TextPlugin\Text();
        }
    }
```

Since the `edit_text_action` is responsible for creating and editing texts,
we first try to find a specified text to edit.
If no `$text_id` was specified, we create a `text` object instead.
It should be noted that only a `text` object is created here,
which is not yet saved in the database.

Since the `edit_text_action` is in the OverviewController,
we create an `edit_text.php` in the `views/overview` directory.
The view contains a text input for the title,
a text area for the description and a select tag for the type.

```html
<? use Studip\Button; ?>
<form class="default collapsable" action="<?= $controller->link_for('overview/store_text', $text_obj->id) ?>"
      method="post">
    <?= CSRFProtection::tokenTag() ?>
    <fieldset data-open="bd_basicsettings">
        <legend>
            <?= $_('Basic data') ?>
        </legend>

        <div>
            <label class="required">
                <?= $_('Title') ?>
            </label>
            <input name="title" required value="<?= $text_obj->title ?>">
        </div>

        <div>
            <label>
                <?= $_('Description') ?>
            </label>
            <textarea name="description"><?= $text_obj->description ?></textarea>
        </div>

        <div>
            <label class="required">
                <?= $_('Text type') ?>
            </label>
            <select name="type" required>
                <? foreach (\TextPlugin\Text::getTypes() as $type_key => $type_label): ?>
                    <option value="<?= $type_key ?>" <? if ($type_key == $text_obj->type) echo 'selected' ?>>
                        <?= $type_label ?>
                    </option>
                <? endforeach; ?>
            </select>
        </div>

    </fieldset>

    <footer data-dialog-button>
        <?= Button::create($_('Apply')) ?>
    </footer>
</form>
```
View details:
* With `$controller->link_for()` we directly create a link for the `<form>`-element,
which refers to a `store_text_action` in the OverviewController,
in which we later store the specified data.
* With `CSRFProtection::tokenTag()` we create a token,
which we use in the `store_text_action` to prevent "Cross-Site Request Forgery" ([CSRFProtection](CSRFProtection))
* A submit button is created with `Button::create($string)` (see [Buttons](Buttons)).
The submit-button is created in the `<footer>`-element with `data-dialog-button`,
so that it is displayed in dialog windows next to the "Close" button.

### Save form data
Finally, the user input must be saved.
To do this, we create a `store_text_action` in the OverviewController,
which the form in `edit_text.php` already redirects to on submit.

```php
    public function store_text_action(string $text_id = '')
    {
        CSRFProtection::verifyRequest();
        $this->text_obj = \TextPlugin\Text::find($text_id);
        if (!$this->text_obj) {
            $this->text_obj = new \TextPlugin\Text();
            $this->text_obj->author_id = $GLOBALS['user']->id;
        }
        $this->text_obj->setData([
            'title' => Request::get('title'),
            'description' => Request::get('description'),
            'type' => Request::int('type')
        ]);

        if ($this->text_obj->store() !== false) {
            PageLayout::postSuccess($this->_('The text was successfully saved'));
        } else {
            PageLayout::postError($this->_('An error occurred while saving the text'));
        }
        $this->redirect('overview/index');
    }
```
The `store_text_action` also stores texts that do not yet exist,
therefore a new object is created as in `edit_text_action`,
if no existing object is found.
With `setData()` the user input is assigned to the `text` object.
The user input can be accessed via `Request`,
where the specified parameter must match the html `name` of the input element (`<input>`, `<textarea>` etc.).
must match. Further information on `Request` is explained in [Request](Request).
An id for the primary key `text_id` and the `mkdate` is automatically created by SimpleORMap for new entries
and `chdate` is updated automatically.
However, the `author_id` is not set automatically,
so we enter it once for new texts.
Depending on whether the save is successful,
we display a success or error message on the next page (see [PageLayout](PageLayout)).
Saving a text does not require a view,
We only want to save the data and then call up the overview page again,
which is why we use `$this->redirect('overview/index');` to redirect to `index_action
and also do not need a `store_text.php`-view file.

### Enable editing
Texts are now technically editable,
but there is still no possibility for users,
call up the editing view for existing texts.
Therefore we add another column `Action` to the overview table in `overview/index.php`,
in which we enable actions for explicit texts, such as a detailed view, deletion or editing.

In the `<colgroup>` we add `<col style="width: 40px">`.
In the `<thead>` we add `<th data-sort="false"><?= $_('Action'); ?></th>`.
To insert actions, the [ActionMenu](ActionMenu) should be used,
which works very similarly to `ActionsWidget`.
So we create a new `ActionMenu` within the `<tbody>` and pass a link to the `edit_text_action`.
```php
<td>
    <? $actions = ActionMenu::get(); ?>
    <? $actions->addLink(
        $controller->url_for('overview/edit_text/' . $text_obj->id),
        $controller->_('Edit'),
        Icon::create('edit'),
        ['data-dialog' => true]
    ); ?>
    <?= $actions ?>
</td>

```
It is important here that we also pass the id of the respective `text` object,
so that no new object is created, but the existing one can be edited.
Since `ActionMenu` uses the method `__toString` for rendering,
we can also simply render the created menu with `<?= $actions ?>`.

### Permission for objects
What we have now achieved is that all users can edit all texts.
We should therefore ensure that only authorized users can edit the texts.
For a `text` object, we define that only the creator and root users are allowed to edit the object.
To do this, we create a `hasPermission` method in `models/Text.php`, in which we ensure exactly this.

```php
public function hasPermission(): bool
{
    return $GLOBALS['user']->id === $this->author_id || $GLOBALS['user']->perms === 'root';
}
```
With `$GLOBALS['user']` you can access the `user` object of the current user,
so that it can be checked whether it is the author or a `root` user.
In general, it makes sense to give root users all authorizations,
This facilitates development in test environments, as it is not necessary to constantly log in,
and helps the system administrators in production systems,
as you can then better track feedback from users.

We now have to check `hasPermission` everywhere,
where critical actions such as editing or deleting texts take place.
In our case, this is in the `store_text_action` of the OverviewController.
So we check directly after we have loaded the `text` object,
whether the user is allowed to save the object.
If there is no authorization,
we output an error and redirect to the overview page.

```php
    public function store_text_action(string $text_id = '')
    {
        CSRFProtection::verifyRequest();
        $this->text_obj = \TextPlugin\Text::find($text_id);
        if (!$this->text_obj) {
            $this->text_obj = new \TextPlugin\Text();
            $this->text_obj->author_id = $GLOBALS['user']->id;
        }
        if (!$this->text_obj->hasPermission()) {
            PageLayout::postError($this->_('You do not have permission to customize the text'));
            $this->redirect('overview/index');
            return;
        }
        $this->text_obj->setData([
        [...]
    }
```

We have now done the most necessary to prevent unauthorized changes to texts.
However, we should also prevent users from being able to call up the editing page at all,
so that they are not led to believe that they can edit texts,
only to display an error message.

To do this, we only add the `edit_text_action` to the `overview/index.php`,
if the current user has the relevant authorization.
Effectively, we only add an if query to the `hasPermission` method before adding the link.

```php
    <? $actions = ActionMenu::get(); ?>
    <? if ($text_obj->hasPermission()): ?>
        <? $actions->addLink(
            $controller->url_for('overview/edit_text/' . $text_obj->id),
            $controller->_('Edit'),
            Icon::create('edit'),
            ['data-dialog' => true]
        ); ?>
    <? endif; ?>
    <?= $actions ?>
```
Since users can still call the `edit_text_action` by manipulating the browser URL,
we check the authorization of the user in the `edit_text_action` method as in the `store_text_action` method
and issue an error if necessary. Since we are now preventing users from calling the action legitimately,
we can also throw an exception instead of displaying an error,
in both `edit_text_action` and `store_text_action`.

```php
public function edit_text_action(string $text_id = '')
{
    PageLayout::setTitle($this->_('Edit text'));
    $this->text_obj = \TextPlugin\Text::find($text_id);
    if (!$this->text_obj) {
        $this->text_obj = new \TextPlugin\Text();
        $this->text_obj->author_id = $GLOBALS['user']->id;
    }
    if (!$this->text_obj->hasPermission()) {
        throw new AccessDeniedException($this->_('You do not have permission to customize the text'));
    }
}
```

Since we know that the `author_id` must be set for `hasPermission`,
we set the `author_id` as in the `store_text_action`,
when we create a new `text` object.
This means that before we call `hasPermission` we should always make sure that an `author_id` is set,
make sure that an `author_id` is set.
Since this is very error-prone, we should instead specify
that the `author_id` is set automatically when a new `text` object is created.
This would make most sense in the `after_initialize` callback within the `configure()` method
method of the `Text.php` model class.
How `after_initialize` callbacks and other model-specific callbacks such as `after_store`, `before_delete`
are programmed can be found in [SimpleORMap](SimpleORMap).

## Further functionalities
The basic functionality is now available.
Users can create, edit and view all texts.
This section lists and explains examples of further functionalities,
which can be viewed additionally if interested or required.

The following is a brief overview of the sub-chapters:
* `I18n` allows users to enter texts in multiple languages.
The view for editing texts is therefore adapted with the help of `I18n` so
that users can enter the title and description of texts in several languages.
The texts are then displayed in the respective language,
that the respective users have set in Stud.IP.
* wysiwyg' is a detailed text editor and enables users to format texts,
the formatting of texts.
In this subsection, the description field of a text is converted into a wysiwyg field,
so that users can customize their texts with different formatting.
* We would like to introduce a detail page for texts,
where all users can view details of the respective texts.
This function is particularly important
if texts have long descriptions or will receive more attributes in the future,
as the table on the overview page then becomes confusing.
However, this section is generally relatively simple
and can certainly be created without explanation with the current knowledge.
* On the overview page, it should be possible to filter texts,
by searching for the title and description in the sidebar.
* Since a large number of texts could possibly be displayed on the overview page,
since all of them are loaded, a pagination should be added,
to load only a fixed number of texts per page and thus shorten the loading time.

### I18n
[gettext](Howto/Internationalization#internationalization-in-php-code) is used for this purpose,
strings that are permanently implemented by the developers to other languages.
I18n, on the other hand, offers users the possibility of
specify texts in different languages.
The texts are then output according to the language set by the user.

In the TextPlugin, users should be given the option of
specify the title of the text and the description in several languages.
As in [I18n](Howto/Internationalization#i18n), three points should be observed for this

Mark #### field as i18n_field
The respective fields should be marked as `i18n_field`.
We therefore add the following to the `configure()` method of the `text` model class
```php
$config['i18n_fields']['title'] = true;
$config['i18n_fields']['description'] = true;
```

#### Use I18n_ inputs
Corresponding I18n input elements must be used instead of html input elements.
We therefore adapt the `<input>` and the `<textarea>` element in the `edit_text.php`-view accordingly.
```php
<div>
    <label class="required">
        <?= $_('Title') ?>
    </label>
    <?= I18N::textarea('title', $text_obj->title, ['required' => true]) ?>
</div>

<div>
    <label>
        <?= $_('description') ?>
    </label>
    <?= I18N::textarea('description', $text_obj->description) ?>
</div>
```
#### Request::i18n
For `I18n` fields, `Request::i18n()` must be executed to obtain the respective form data.
In our plugin, this only affects the `store_text_action` in the `OverviewController`.
```php
$this->text_obj->setData([
    'title' => Request::i18n('title'),
    'description' => Request::i18n('description'),
    'type' => Request::int('type')
]);
```

### wysiwyg
Users should also be given various formatting options for the description of a text.
In Stud.IP, this is achieved using the [Wysiwyg](Wysiwyg) editor.
However, since users can also disable this in Stud.IP, we also offer a simpler toolbar as an alternative.

To do this, we enter `wysiwyg add_toolbar` as html class in the `<textarea>` of the description in `edit_text.php`.
```php
<?= I18N::textarea('description', $text_obj->description, ['class' => 'wysiwyg add_toolbar']) ?>
```
or without i18n-field
```html
<textarea name="description" class="wysiwyg add_toolbar"><?= $text_obj->description ?></textarea>
```

If the description is output, remember to use `formatReady()` instead of `htmlReady()`.

### Detail view
As a further example of an action with view, a detail view for texts is added,
in which significantly more information can be displayed than in the overview table.

To do this, we add a wide link to the new `view_text_action` in the `ActionMenu` of `index.php`.
```php
<? $actions = ActionMenu::get(); ?>
<? $actions->addLink(
    $controller->url_for('overview/view_text/' . $text_obj->id),
    $controller->_('View'),
    Icon::create('log'),
    ['data-dialog' => true]
); ?>
<? if ($text_obj->hasPermission()): ?>
    [...]
<? endif; ?>
<?= $actions ?>
```
Since all users can call up the detailed view first, we do not query `hasPermission` here.
We then create the `view_text_action` in the `OverviewController`.
```php
public function view_text_action(string $text_id)
{
    PageLayout::setTitle($this->_('view text'));
    $this->text_obj = \TextPlugin\Text::find($text_id);
    if (!$this->text_obj) {
        throw new InvalidArgumentException($this->_('The requested text could not be found'));
    }
}
```

Finally, we create the `view_text`-view, in which all desired attributes are simply output.
```html
<table class="default">
    <colgroup>
        <col style="width: 40%">
    </colgroup>
    <tbody>
    <tr>
        <td><strong><?= $_('Title') ?></strong></td>
        <td><?= htmlReady($text_obj->title) ?></td>
    </tr>
    <tr>
        <td><strong><?= $_('Description') ?></strong></td>
        <td><?= formatReady($text_obj->description) ?></td>
    </tr>
    <tr>
        <td><strong><?= $_('type') ?></strong></td>
        <td><?= $text_obj->getTypeDescription() ?></td>
    </tr>
    <tr>
        <td><strong><?= $_('Author') ?></strong></td>
        <td><?= htmlReady($text_obj->author->getFullname()) ?></td>
    </tr>
    <tr>
        <td><strong><?= $_('Created on') ?></strong></td>
        <td><?= strftime('%x', $text_obj->mkdate) ?></td>
    </tr>
    <tr>
        <td><strong><?= $_('Last modified on') ?></strong></td>
        <td><?= strftime('%x', $text_obj->chdate) ?></td>
    </tr>
    </tbody>
</table>
```
If the `wysywig` editor was provided for the description,
make sure to use `formatReady()` instead of `htmlReady()`.

### Search and filter texts
[TODO]

### Pagination
[TODO]

## Summary
In this part
* Created an overview page for all texts with corresponding action and view ([Trails](Trails))
* Created a sidebar widget ([Sidebar](Sidebar))
* Created an action and view for editing and an action for saving the form data

The complete code created so far plus phpDoc is available here ([TextPlugin.zip](../assets/0e34a84cb91701dda04150e39bd3067f/TextPlugin.zip)) as a ZIP file.
