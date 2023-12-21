---
id: admin-search
title: How do I extend the admin search?
sidebar_label: Admin-Search
---

From 3.1 there is a new admin area, which is also called Admin-MyEvents. From 3.2 and 3.3 there are new possibilities to extend the admin search with plugins. This should offer administrators options for all special plugins without having to leave their usual workplace (the admin area in Stud.IP).



## New filters in the sidebar

This is the easy part. You have to get a filter in the sidebar and we know the sidebar well. Only the timing should be considered. The constructor of the plugin is the wrong time. Instead, the plugin has to register for a notification (of the NotificationCenter). But at least it can register in the constructor. And this is something like this:

```php
if ((stripos($_SERVER['REQUEST_URI'], 'dispatch.php/admin/courses') !== false)) {
    NotificationCenter::addObserver(
        $this,
        'addLectureshipFilterToSidebar',
        'SidebarWillRender'
    );
}
```


And there is also a method of the plugin called "addLectureshipFilterToSidebar". It can look like this:

```php
public function addLectureshipFilterToSidebar()
{
    $widget = new OptionsWidget();
    $widget->setTitle(_('LectureshipFilter'));
    $widget->addCheckbox(
        _('Only with teaching assignment'),
        $GLOBALS['user']->cfg->getValue('LECTURESHIP_FILTER'),
        PluginEngine::getURL($this, [], 'toggle_lectureship_filter')
    );
    Sidebar::Get()->insertWidget($widget, 'editmode', 'filter_lectureships');
}
```

Finally, you can manipulate the sidebar at any time. It is usual to define a separate form for each of the sidebar filters, which refers to a special action in which a global parameter (here `$GLOBALS['user']->cfg->getValue("LECTURESHIP_FILTER")` ) of the UserConfig is then changed.

Consequently, my example plugin still has an action:

```php
public function toggle_lectureship_filter_action()
    {
        $oldvalue = (bool) $GLOBALS['user']->cfg->getValue("LECTURESHIP_FILTER");
        $GLOBALS['user']->cfg->store("LECTURESHIP_FILTER", $oldvalue ? 0 : 1);
        header("Location: ".URLHelper::getURL("dispatch.php/admin/courses"));
    }
```

This action only changes the UserConfig entry and sends the user straight back to the admin page.

This is now an example with a checkbox that only has two states. But you could accommodate free text fields or select boxes or any other complex form in the same way. The important thing is the sequence: Insert sidebar form, user clicks on it, page reloads and the global parameter is changed in a special action.

## The AdminCourseFilter class

Now this filter still needs to be applied. It can already be clicked, but it must still be able to actually change the results. The AdminCourseFilter class is important for this. It controls the entire query with which the courses are searched for. Shortly before the query is executed, a NotificationCenter notification called "AdminCourseFilterWillQuery" is triggered. A plugin that wants to apply a filter should register itself accordingly in the constructor (note, it should be a SystemPlugin). It works like this:

```php
NotificationCenter::addObserver($this, "addMyFilter", "AdminCourseFilterWillQuery");
```

The plugin should also have a method called "addMyFilter". This is of course only an example name and should be changed. This method could look like this:

```php
public function addLectureshipFilter($event, $filter)
{
    if ($GLOBALS['user']->cfg->getValue("LECTURESHIP_FILTER")) {
        $filter->settings['query']['joins']['lehrauftrag'] = [
            'join' => "INNER JOIN",
            'table' => "lectureship",
            'on' => "seminare.seminar_id = lehrauftrag.seminar_id"
        ]
    }
}
```

We will go into the details in a moment. First of all, it is important that the method receives a $filter object of the type "AdminCourseFilter" as the second parameter and can modify this object as required. The method specified here only modifies if a specific UserConfig parameter is set. This is the parameter that is set in the sidebar.

### Modifying the AdminCourseFilter object

How do you actually modify this AdminCourseFilter object? The result is an SQL query. The fact that you want to modify the object basically means that you want to modify all parts of the query, every JOIN and every SELECT and, of course, the WHERE. For this purpose, the $filter object has a public attribute $filter->settings, in which the entire query is stored in an array. All entries of the array are associative entries of associative arrays. In this way, you can add new entries, for example to include a WHERE clause, but you can also delete or change existing entries.

```php
$filter->settings = [
    'query' => [],
    'parameter' => []
];
```

The query part contains everything needed to generate a prepared SQL statement. The parameter part then contains the necessary parameters, which are inserted via `execute`.

```php
$filter->settings['query'] = array();
```

## Further administration modes

In the admin area, there is a selection box "Action area selection" in the sidebar, with which you can set what exactly you want to do with the filtered events. You can say that you want to edit the basic data or archive events. However, plugins can also be integrated here and define their own action area.

To do this, a plugin must implement the AdminCourseAction interface ( `class LehrauftragPlugin extends StudIPPlugin implements SystemPlugin, **AdminCourseAction**` ).

This interface consists of three methods that must be implemented. To do this, you need to understand what the admin area does in the action area. The admin area is first of all a large list of events with an action area on the right in the line of the event. It can contain a button or a checkbox.

In the case of checkboxes, however, a button is needed above and below the events to submit the entire area as a form. This raises the question of where the form is sent to.

So the first method of the "AdminCourseAction" interface is probably "`public function useMultimode()`", which only returns whether the buttons above and below are needed. If they are not needed, the method returns `false`, otherwise `true`. Alternatively, a string can be passed, which is the text of the button, so to speak. Something like "Archive events" or something like that.

The second method is "`public function getAdminActionURL()`", which defines the URL to which the form should be sent.

The third is the most interesting method because it actually defines the action area at the end. It is "`public function getAdminCourseActionTemplate($course_id, $values = null)`". The $course_id should be clear. $values as a parameter specifies what the AdminCourseFilter class has found for the course. This parameter is therefore an associative array with various data for the course. The name should appear in it, perhaps also the lecturers. Theoretically, this array can also be extended, as can be seen above. This would save individual queries.

The method `getAdminCourseActionTemplate` now takes the parameters and returns an object of type Flexi_Template (or null). This template is the action area, which is displayed as an HTML snippet within a table cell. It can contain a button as well as a checkbox or even both or completely different complex form fields.

The interface takes care of the rest. For example, it saves which action area has been selected so that an admin remains in the correct area.

## Adding columns to the table (from 4.1)

Plugins also have the option of adding more columns to the displayed table. This can be useful if, for example, an evaluator wants to see in a column who last edited the evaluation data. For this purpose, a plugin can implement another plugin interface called `AdminCourseContents`. This interface expects two methods from the plugin.

* `adminAvailableContents()`: The plugin returns here which other columns are actually possible to display in the table. The return value is an associative array, where the index is an internal name and the value is the visible name with all possible umlauts.
* `adminAreaGetCourseContent($course, $index)`: The `$index` is exactly the index returned by `adminAvailableContents`. The `$course` is an object of the `Course` class with the event in question. The function returns either a string or an object of the type `Flexi_Template`. So you are flexible for everything. If you want the column to also appear in the CSV file when exporting (which is possible), you should do without bells and whistles such as clickable buttons in the template.
