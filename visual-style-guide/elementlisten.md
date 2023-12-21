---
title: Element lists
sidebar_label: Element lists
---

In Stud.IP various objects/elements are displayed in list form: People (e.g. event participants), events (e.g. on the "My events" page), facilities, news, votes, study programs and much more.

The consistent use of the new Stud.IP tables has already greatly improved the uniformity of the presentation. Some old pages still need to be converted to the new design or templateized (as of August 2015)


## Presentation
* The individual elements of a list are arranged **below each other** in individual rows.
* There is **no line break** in the lines of an element list.
* Each line contains the name of the element or as little information as possible that identifies the element (e.g. date and time for appointments).
* If the elements are to be subordinated to each other hierarchically, this **hierarchy is represented by indentations**
* The current table design of Stud.IP applies (see there).
* Each element list should be **tabular**. This increases the readability of the contents.
    * A table has a **table header** which provides a heading for each column of the table.
    * The **horizontal alignment** within each column should be chosen sensibly depending on the content and its purpose.
    * The alignment in the table header and table body should be the same.
    * For long element lists, it may be useful to interrupt the list with **subheadings**. In such cases, the table header should be repeated above each sub-list.
* If the element list is too long to be displayed on a single page, **pagination** should be provided.
    * For scrolling, the pages on which the entire list is distributed are displayed on the right below the element list.
    * Clicking on a page number takes you to the relevant page
    * There is also a "back" link to the left of the page numbers (except on page 1) and a "next" link to the right of the page numbers (except on the last page).
    * (for further details see pagination on score.php)

## Actions
* Single actions
    * Single actions are actions that refer to individual elements of a list.
    * A distinction must be made between standard actions and extended actions.
    * Standard actions are actions that can be applied to most types of list elements. They are triggered by icons (not buttons!) in the heading line of the list element. Standard actions are
        * Delete
        * Expand and collapse
        * Sort/change order
    * Extended individual actions are actions that go beyond the standard actions. They can only be used for individual types of list elements and/or require more extensive forms or similar, which would not fit into the header line simply because of the space available. Examples of extended actions are setting the duration of evaluations or booking a room for an appointment.
    * In order to be able to carry out extended individual actions, the user must first expand the respective list element. An area then opens under the list element in which the interaction elements are displayed with which the action can be carried out.
    * Delete
        * A list element can be deleted by clicking on a trash can symbol on the right in the title bar of the list element (not by clicking on a delete button, which only becomes visible after the list element has been expanded).
    * Expanding and collapsing
        * You often need the option to expand a list element. This is regularly the case ...
            * ... if you want to show additional information for an element that does not fit into the heading line of the list element (e.g. information about participants in an event)
            * ... if you want to provide extensive editing options that do not fit into the heading line of the list element (e.g. for surveys or evaluations)
            * ... if you want to show additional list elements that are hierarchically subordinate to the list element (e.g. in the event hierarchy)
            * ... if you want to show objects that are contained in the list element (e.g. persons in a status group)
            * ... if you want to show objects that are assigned to the list element in a different way (e.g. individual appointments of a regular event)
        * To expand and collapse, a ">" icon must be displayed at the left end of the title bar. Click on it to expand the respective list element. The icon is then replaced by one with the top pointing downwards.
        * If JavaScript is activated, the expansion and collapse should be realized without page reload.
        * In principle, it should be possible to change the basic information contained in the title bar of the list element by expanding it. For this purpose, a corresponding form field filled with the current values is displayed in the title bar where the respective information/property is displayed in the collapsed state.
        * Sometimes it makes sense to print all list elements at once. This is realized by an icon that shows an arrow pointing upwards and downwards. This icon is inserted in a line between the table header and the table body.
    * Sort/change order
        * An element list should always be sortable. This enables users to display the content according to their wishes and usage objectives. Sorting is not necessary for hierarchical or nested lists.
        * The list should be sortable according to the criteria represented by the headings in the table header, if this makes sense.
        * When the list is first called up, it should already be sorted according to a meaningful criterion.
        * The criterion and direction (ascending or descending) by which a list is sorted is indicated by a small blue triangle to the right of the relevant heading in the table header. Ascending sorting is indicated by a triangle pointing upwards, descending sorting by a triangle pointing downwards.
        * The list is sorted by clicking on the respective heading in the table header or on the yellow triangle. If the list is already sorted according to the
          If the list is already sorted by the criterion you click on, the sort order is reversed.
        * The possibility of clicking on the name is indicated by blue text (standard color for links), the color corresponds to the color of the triangle.
    * In certain cases, it may be useful to specify the order of the elements manually (i.e. no sorting according to a criterion).
        * If the user has activated JavaScript in his browser, he should be able to specify the order using drag and drop.
        * (specify)
        * If JavaScript is switched off in the user's browser, yellow sorting arrows should be available in the right-hand area of the respective line, which can be used to specify the order. These are arranged in two columns: The arrows pointing downwards are in the left-hand column, the arrows pointing upwards are in the right-hand column. The top line contains only a down arrow, the bottom line only an up arrow.
        * If the element list is very long, it can be difficult for the user to move individual list elements to a more distant position within the list. In such a case, the user can be provided with additional radio buttons and angled arrows with which individual list elements can be selected and sorted to a specific position (example: user management in facilities).
* Collective actions
    * A collective action is an action that is applied to several list elements at the same time.
    * The list elements for a collective action are selected using checkboxes on the left in the title lines of the list elements.
    * A drop-down box is available below the element list from which you can select the desired action. Click on the "OK" button to execute the collection action.
    * In addition, options for changing the selection are available in the drop-down box (at least "Select all", "Select none" and "Reverse selection").

## Expand and collapse

* If there is more information about an element than can be displayed in a line of text, this should be displayed by expanding and collapsing the element. To do this, a triangle pointing to the right should be displayed on the far left of the corresponding line. Clicking on this triangle causes the detailed information to be displayed below the selected element, possibly with options for editing it. The elements below the expanded element slide down accordingly. The triangle that was clicked to expand the element points downwards when the element is expanded. Another click on it "closes" the expanded element again. It should also be possible to expand and collapse the element by clicking on the name of the element.
* How should the editing of element properties be implemented? Should it always work identically? If not: What deviations should be allowed and under what conditions should they be allowed?
* Variant 1: When expanding, the title bar remains unchanged, all information (including that shown in the title bar) is shown in form fields below the title bar and can be saved by clicking on the "Apply" button. This means that the information in the title bar and the form field in which this information is currently being changed temporarily contradict each other. (Example: Schedule, groups/functions in facilities)
* Variant 2: Initially, nothing can be edited when the form is expanded. To do this, you must first click on the "Edit" button (which only becomes visible when you expand the form). The attributes contained in the title bar are made editable in the title bar; additional information is made editable below this. Clicking on the "Apply" button (below the editable information) saves everything. (Example: file area, forum)
* Variant 3: Like variant 2, but the information from the title bar is not made editable in the title bar itself, but (as in variant 1) below the title bar. (Example: Literature management)
* Variant 4: All information is already made editable by expanding. The information from the title line is made editable itself. Clicking on the "Apply" button below all the information saves the changes and closes the element. (Example: Single appointment on spacetime page)
* Variant 5: By expanding (or by clicking on an edit icon in the title bar), the information from the title bar is made editable and saved by clicking on the "Apply" button (which is located within the title bar). At the same time, the element is collapsed. (Example: regular time on spacetime page)
* Variant 6: All information is made editable by expanding it. The information from the title bar is made editable, and additional editable information is displayed below the title bar. Clicking on the "Apply" button (below all the information) saves the changes and closes the element. (Example: Grouping and question blocks in evaluations)
* Variant 7: By expanding, additional information and properties are displayed and made editable and saved by clicking on the "Apply" button, whereby the element is closed at the same time. However, the information in the title bar cannot be changed. To do this, you must click on the "Edit" button in the title bar. This takes you to a new page where you can edit the information from the title bar as well as a range of other information/properties for the element. (Example: Evaluations and evacuation templates)
* Variant 8: Like variant 7, except that nothing is made editable by simply expanding it. Instead, you have to click on the "Edit" button in the title bar to edit all the information, which takes you to another page. (Example: Votings)
* Variant 9: The element cannot be expanded. To change the element properties, click on the "Edit" button in the title bar. This takes you to a new page where you can edit all the properties. Clicking on the "Apply" button (or on a text link "Back" or similar) takes you back to the previous page. (Example: News, exams and exercise sheets in Vips)
* Variant 10: There is an edit icon in the title bar. Clicking on it changes the background color of the title bar to indicate that it is in edit mode. At the top of the page, an existing form (which is used to define the properties of newly created elements) is replaced by a similar form, which is filled with the values of the selected element, allowing you to edit them. A click on the "Save" button within this form accepts the changes and resets the background color of the title bar and the form. (Example: groups/functions in events, group management in Vips works similarly)

## Delete

If it should be possible to delete individual list elements, this should be symbolized by a trash can icon. This icon is located on the far right of the respective line. Clicking on it causes the element to be deleted or removed from the respective abstract "container" (event, status group, etc.). The trash can icon is available both in the collapsed and expanded state.

## Sort

### Free
* If the order of the elements is to be changed permanently (i.e. if not only the current display of e.g. search results is to be changed), interaction elements must be provided with which this can be done.
* Examples of sortable elements: people, files, forum posts, events, literature, dates, topics, resources, status groups, news, votes, evaluations, messages, room requests, study areas, ...
* JavaScript activated
    * Drag and drop: For this purpose, a handle icon must be displayed on the left edge of the respective line. (If there is an expand and collapse triangle, the handle is displayed directly to the right of it). By clicking and holding this icon, you can move the respective line up or down in a straight line and sort it to another position by releasing it. This drag and drop is available both in the expanded and collapsed state.
* JavaScript deactivated
    * Sorting arrows: Yellow double triangles are to be displayed instead of the ripples. The top and bottom elements of a sortable list contain only one double triangle pointing downwards and upwards respectively. All other lines contain two double triangles, one of which points upwards and the other downwards. Clicking on a double triangle moves the respective element one position up or down, while the element previously above it takes the position of the moved element.
    * Radio buttons plus angle arrows: In certain contexts, it is sometimes necessary to move several elements one after the other by a large number of positions. An alternative sorting function can be offered for this purpose. With this solution, you select an entry using a radio button (to the left of the expand/collapse triangle) and click on an icon (in the form of an angled arrow to the left of the respective radio button) to select the position at which the selected entry is to be sorted.
### by criterion
* Sometimes you want to sort list items according to a specific criterion (name, file size, date, etc.). The following rules apply:
    * List elements can only be sorted according to criteria whose values are visible on the interface. For example, a list of files should not be able to be sorted by date if the date of the file is not also displayed.
    * Sorting is done by clicking on a column heading, below which the values of this criterion are listed for each list element.
    * In principle, it should be possible to sort the element list in ascending and descending order according to the respective criterion by clicking several times on a column heading.
### Problems/questions when sorting:
* How do you sort the elements of different levels within hierarchical structures (example: groups/functions in events)?
* How do you deal with pagination? Do you sort the entire list or only the visible elements?


# Messages

Where is feedback displayed?
* User is at the bottom of the page
* It makes no sense to display the info message at the top

Query when deleting objects, whether they should really be deleted?
* currently different in Stud.IP



## Security queries
* as a modal dialog?
* sometimes these are not displayed as a dialog, but on the page


Source: http://developer.android.com/design/patterns/confirming-acknowledging.html

## Further links
* http://patternry.com/p=feedback-messages/
* http://www.userfocus.co.uk/articles/errormessages.html
* http://uxmag.com/articles/are-you-saying-no-when-you-could-be-saying-yes-in-your-web-forms

Book:
Designed for Use Chapter 6 on Text Usability
