---
title: HTML output
---

## Flexi-Templates
Stud.IP uses templates for the output of HTML, more precisely an in-house development called [Flexi-Templates](./flexi-templates)

The only difference is that a TemplateFactory is already instantiated in Stud.IP, which you can simply use.

```php
$template = $GLOBALS['template_factory']->open('shared/searchbox.php');
echo $template->render();
```

## Templates and classes for all(s)

#### Messages

To display messages in Stud.IP, use the class [MessageBox](./message-box).

Here is a simple example:
```php
// Example of a simple info message
echo MessageBox::info('Message');
```

If you do not want to output the message immediately, but only together with the page layout, you should delegate the output to the [PageLayout](./page-layout) class:
```php
// Example of a simple info message
$info = MessageBox::info('Message');
PageLayout::postMessage($info);
```

All details and other types of MessageBoxes can be found in the [documentation](./message-box).

#### Searchbox

The `searchbox` template provides a standardized search mask for all pages on which a search is to be performed. The template is quite minimalistic and can be embedded in an HTML form.

Use in the template
```php
<form action="<?=URLHelper::getLink()?>" method=post>
    <?= $this->render_partial('shared/searchbox'); ?>
</form>
<?
$searchterm = Request::get('searchterm');
```

#### Pagination

The `pagechooser` template is for pages that are to have pagination. You give the template the number of elements, elements per page, the current selected one and a link with format markup where the page number should be, then you get the page chooser shown above.

Since Stud.IP 2.1 there is a global value in the database. This can be used with get_config('ENTRIES_PER_PAGE'). The default value is 20.

```php
<?= $this->render_partial("shared/pagechooser",
  array(
    "perPage" => get_config('ENTRIES_PER_PAGE'),
    "num_postings" => $numberOfPersons,
    "page"=>$page,
    "pagelink" => "score.php?page=%s"));
?>
```

#### Modal dialog

Sometimes it is necessary to use a [modal dialog](./modal-dialog) instead of a normal MessageBox for very important queries.

Example:
```php
$question = sprintf(_('Do you really want to delete the user **%s** ?'), $username);
echo createQuestion( $question,
  array(
    "studipticket" => get_ticket(),
    'u_kill_id' => $u_id
  ),
  array(
    'details' => $username
  )
);
```

As of version 4.2, PageLayout::postQuestion can be used instead.

```php
PageLayout::postQuestion($question, $accept_url = *, $decline_url = *)
```
