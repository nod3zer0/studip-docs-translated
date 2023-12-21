---
title: Icons & Graphics
sidebar_label: Icons & Graphics
---

Since version 2.0 Stud.IP comes with a standardized icon set. The set is characterized by a clear and uniform design, colour sets separated according to tasks and areas of use, barrier-free operation through clear shapes/visual additions for certain tasks and a number of other features that standardize the Stud.IP design language. Furthermore, all icons are already available as vector graphics so that they can be used in other contexts (e.g. print) and the icon size in Stud.IP can be changed in the future (e.g. for improved touch operation).
The icon set is generally used for all graphic buttons, markers, object representations etc. application. The only exceptions to this in Stud.IP are text buttons and a few special shapes or non-iconic graphic elements (e.g. lines or separators).


In general, all graphics are stored in the `assets` folder `images` in the `public` folder. These have been restructured and tidied up in 2.0. This results in the following structure:

| path | description |
| ---- | ---- |
| `public/assets/images/calendar` | All background graphics required for the calendar and timetable are stored here. |
| `public/assets/images/crowns` | The crowns that a user can receive as an award |
| `public/assets/images/header` | `deprecated` (These icons are still being deleted) |
| `public/assets/images/icons` | The new folder for all icons, see below for detailed information. |
| `public/assets/images/infobox` | All images with rounded corners in black and white for the infoboxes |
| `public/assets/images/languages` | Country icons for the available languages |
| `public/assets/images/locale` | Here are all language-dependent icons |
| `public/assets/images/logos` | Here are all logos that are used anywhere in Stud.IP |
| `public/assets/images/vendor` | This is where graphics are stored that belong to other packages, frameworks etc. and were not created by us (e.g. jQuery-UI) |

### Design

The icon landscape has been significantly streamlined, modernized and made more efficient. Scalability and a simple, clear design language were the focus of the development. The new icon set is functional and self-explanatory. This promotes the clarity of the functions and intuitive operation. The new icons now have a uniform look for all sizes and areas of application. The use of fixed basic shapes together with formal and color-coded additions that mark functions and states ensures low-barrier usability with a high recognition effect.

Icons in Studip 2.0 are designed according to a fixed grid and are minimalist in color and shape. They are always monochrome in fixed colors. Line thickness and color fillings are used to give the icons a uniform visual weight. These rules are to be applied to all icons used in Stud.IP in future.

In addition to normal project development, there are certainly many plug-ins and extensions for which there is a need to adapt existing icons or create new ones. The necessary work is carried out by Stud.IP e.V., which provides a certain amount of budget for the development. Coordinates the development via the Stud.IP e.V. board.

### Icon roles

From version 3.4, icons are addressed via the icon API. Previously, icons were integrated like file names. This meant that the color used was hard-coded. If adjustments were made to the university's color scheme in Stud.IP installations, the code had to be changed or unsightly "hacks" had to be made.

As of Stud.IP v3.4, icons are no longer referenced by their color, but by the role they want to take on.
An example: Previously, all icons that were displayed as or in a link were hardcoded in the code with the color blue: `Assets::img('icons/blue/seminar')` If a university preferred to display all link-like icons in the color
green, green icons had to be placed in the "blue" directory (which does not always work, however,
if, for example, the background color is the same as the icon color) or replace all corresponding occurrences of `blue` in the code with `green`.

With the new icon API, the role is now hard-coded. The global assignment of roles to colors then takes over the corresponding translation.

### Roles

Currently (Stud.IP v3.4) you can find the assignment of roles to colors
in the "Icon" class (`lib/classes/Icon.class.php`):

| Role | Color | Meaning |
| ---- | ---- | ---- |
| `Icon::ROLE_INFO` | black | Old description: *These icons are used exclusively in the info boxes and are never clickable. They graphically explain the actions that the info box offers. For example, actions can be shown here with the corresponding icon, information can be illustrated with an "I" or references to other system areas can be supplemented with the icons that match these areas.* | |
| `Icon::ROLE_CLICKABLE` | %blue%blue | Old description: *The blue icons are the standard for all clickable icons, regardless of the context in which they are used (exception: "My events" overview). Free-standing actions in particular should always display such an icon next to the link text. * |
| `Icon::ROLE_ACCEPT` | %green%green | Old description: *Green is only used in the event that the confirmation checkmark is shown.
| `Icon::ROLE_STATUS_GREEN` | %green%green | Old description: *Green is only used in the event that the confirmation tick is shown.
| `Icon::ROLE_INACTIVE` | %grey%grey | Old description: *This variant is used when icons are not clickable and are not used within infoboxes. They also often express a status and are used for all objects such as news, votes or files to classify such an object. One exception is "My events". Here, too, the gray icons express a status ("only known objects of one type") but can still be clicked, as the respective areas can also be accessed directly from here. However, this special case only applies to "My events "* |
| `Icon::ROLE_NAVIGATION` | %ltblue%lightblue | |
| `Icon::ROLE_NEW` | red | Old description: *The color red is used to represent or highlight something new. Red icons are used on "My events" when one of the areas of an event contains new content for the user. For reasons of barrier-free operation, the red color alone is not sufficient, the addition "new" must always be used to give the icon a unique shape, unless the combination of color and shape of the icon itself is unique (such as a red X).
| `Icon::ROLE_ATTENTION` | red | Old description: *The color red is used to represent or highlight something new. Red icons are used on "My events" when one of the areas of an event contains new content for the user. For reasons of barrier-free operation, the red color alone is not sufficient, the addition "new" must always be used to give the icon a unique shape, unless the combination of color and shape of the icon itself is unique (such as a red X).
| `Icon::ROLE_STATUS_RED` | red | Old description: *The color red is used to represent or highlight something new. Red icons are used on "My events" when one of the areas of an event contains new content for the user. For reasons of barrier-free operation, the red color alone is not sufficient, the addition "new" must always be used to give the icon a unique shape, unless the combination of color and shape of the icon itself is unique (such as a red X).
| `Icon::ROLE_INFO_ALT` | %bgcolor=black white%white | Old description: *The white variant is always used when icons are used within a dark environment (usually the header of free-floating windows with a dark blue line). Gray table headers may also have white icons. As a rule, these are not clickable. The white icon cannot be seen on a white background.
| `Icon::ROLE_SORT` | %bgcolor=black yellow%yellow | Old description: *Yellow icons are used exclusively for moving objects. Therefore, only triangles and arrows in all variants exist in this set.* |
| `Icon::ROLE_STATUS_YELLOW` | %bgcolor=black yellow%yellow | Old description: *Yellow icons are used exclusively for moving objects. Therefore, only triangles and arrows in all variants exist in this set.* |


### Additions

There are a number of graphical additions that can be used in a variety of ways to visualize actions behind icons or states. As a rule, additions are always shown in red. The yellow Move addition is an exception.

As of Stud.IP v3.4, additions to icons are referenced via the icon API. For example, if you want to add a `seminar` icon as a link with the addition `add` (i.e. add): `Icon::create('seminar+add')`

| status | image | description
| ---- | ---- | ---- |
| `accept` | ![](https://develop.studip.de/studip/assets/images/icons/blue/accept/seminar.svg)  | **Accept**: The checkmark indicates that a confirmation is displayed here in the context of the object. |
| `add` | ![](https://develop.studip.de/studip/assets/images/icons/blue/add/seminar.svg) | **Add**: The plus sign indicates that a new object can be created here. The creation of an object or the jump to an area in which creation is possible is marked with this addition. It can be used on blue icons with a click or black and gray icons.
| `decline` | ![](http://develop.studip.de/studip/assets/images/icons/blue/icons/grey/decline/trash.svg) **Action blocked**: Sometimes actions are displayed as "not possible". In this case, an X is added to the action icons. |
| `edit` | ![](https://develop.studip.de/studip/assets/images/icons/blue/edit/seminar.svg) | **Edit**: The pencil on an object indicates that an object can be edited by clicking on this icon. |
| `export` | ![](https://develop.studip.de/studip/assets/images/icons/blue/export/seminar.svg)  | **Export**: This icon is used to export one or more objects of the corresponding type (e.g. as a CSV file)|
| `move_right` | ![](https://develop.studip.de/studip/assets/images/icons/blue/move_right/mail.svg) | Move**: To move an object, there is the addition of an arrow. Up to version 2.4 these additions were yellow, since version 2.5 all additions are red.
| `new` | ![](http://develop.studip.de/studip/assets/images/icons/blue/icons/red/new/seminar.svg) **New**: The asterisk indicates new content. The asterisk is combined with a red icon (except in the header).
| `remove` | ![](https://develop.studip.de/studip/assets/images/icons/blue/remove/seminar.svg) | **Delete/Remove**: The option to delete the corresponding object is marked with a minus sign.|
| `visibility-visible` | ![](https://develop.studip.de/studip/assets/images/icons/blue/visibility-visible/seminar.svg)  | **Visible**: An object is made visible by clicking on this icon. |
| `visibility-invisible` | ![](https://develop.studip.de/studip/assets/images/icons/blue/visibility-invisible/seminar.svg)  | **Invisible**: An object is made invisible when this icon is clicked.  |


The icon set contains further additions in the kit, but these are not currently used. There are arrows in all four directions, a pause sign, a confirm sign (as a counterpart to the "X"), a minus sign (as a counterpart to add) and refresh.

### Sizes

Icon sizes can be specified via the render methods of the Icon API. Icons are now delivered as freely scalable SVG.
Since version 5.0, the size is no longer specified in the icon (previously 16 * 16 pixels, unless otherwise specified).

### Directory structure

Whereas the modular directory structure used to be important, the icon class now hides these implementation details. When using the Icon API, you no longer come into contact with it.

Historically, the icons had roughly this directory structure:
`icons/<size>/<color>/<addition>/<icon name>.png`

### List of available icons

#### Master icons

For all objects available in Stud.IP there are root icons from which all other forms or variants are logically derived. The following root icons currently exist:

(In many cases there are both filled and transparent or inverted variants of a root icon. As a rule, the normal version should be used here and not the alternative).

| Image | License | Description
| ---- | ---- | ---- |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/60a.svg) | 60a | **License according to §60a** Document redistribution under the §60a license (current)|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/cc.svg) | CC | **License under CC** Document distribution under CC |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/license.svg) | license | **General license** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/own-license.svg) | own-license | **Your own license** Document distribution under your own license/created by yourself |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/public-domain.svg) | public-domain | **Free license** Document distribution under a free license|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/accept.svg) | accept | **Accept/accepted** This symbol is the basic form for positive feedback to the user. |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/action.svg) | action | **Action menu** Icon for initiating or anchoring the action menu|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/activity.svg) | activity | **Activity stream** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/no-activity.svg) | no-activity | **no activity in the activity stream** empty activity stream |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/add.svg) | add | **add** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/add-circle.svg) | add-circle | **Add** for popover |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/admin.svg) | admin | **Administration** All administrations of the system |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/archive.svg) | archive | **Archive** for everything that has to do with archiving |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/archive2.svg) | archive2 | **Archive alternative** Alternative that can also be used for folders or similar |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/archive3.svg) | archive3 | **Archive Alternative** Alternative, which can also be used for folders or similar |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/assessment.svg) | assessment | **Exams/Assessments** general icon for exams |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/audio.svg) | audio | **Audio element** general icon for audio content |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/audio3.svg) | audio3 | **audio element** general icon for audio content, variant for audio media object |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/billboard.svg) | billboard | **Blackboard** Icon for blackboards in Stud.IP |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-accordion.svg)           | block-accordion | **Block icon for accordion** Icon for the courseware block Accordions|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-canvas.svg) | block-canvas | **block icon for canvas** Icon for the courseware block canvas|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-comparison.svg)           | block-comparison | **Block icon for Comparison** Icon for the courseware block Image comparison|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-eyecatcher.svg)           | block-eyecatcher | **block icon for Eye-catcher** Icon for the courseware block Blickfang|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-eyecatcher2.svg)           | block-eyecatcher2 | **block icon for eye-catcher** Alternative icon for the courseware block Blickfang|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-gallery.svg) | block-gallery | **Block icon for gallery** Icon for the courseware block image gallery|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-gallery2.svg)           | block-gallery | **Block icon for gallery** Alternative icon for the courseware block image gallery|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-imagemap.svg)           | block-imagemap | **block icon for imagemap** Icon for the courseware block imagemap|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-imagemap2.svg)           | block-imagemap2 | **Block icon for canvas** Alternative icon for the courseware block Imagemap |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-tabs.svg) | block-tabs | **Block icon for tabs** Icon for the courseware block Tabs |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/block-typewriter.svg)           | block-tabs | **block icon for typewriters** Icon for the courseware block Typewriter |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/blubber.svg) | blubber | **Blubber** Icon for the blubber function |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/brainstorm.svg) | brainstorm | **Brainstorm** Icon for the brainstorm plugin |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/bus.svg) | bus | **Bus** Icon for navigation functions, e.g. in campus apps |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/campusnavi.svg) | campusnavi | **Campus-Navi** Icon for navigation functions in general, e.g. in campus apps |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/category.svg) | category | **category** general icon for categories |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/cellphone.svg) | cellphone | **Phone/mobile phone** Phone number, smartphone etc. |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/chat.svg) | chat | **Chat** Chat/Forum/Messenger) |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/check-circle.svg) | check-circle | **Accept/accept** for popover |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/checkbox-checked.svg)     | checkbox-checked | **marked checkbox** Checkbox in the form of an icon, marked |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/checkbox-unchecked.svg)   | checkbox-unchecked | **unchecked checkbox** Checkbox in the form of an icon, unchecked |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/checkbox-indeterminate.svg)   | checkbox-indeterminate | **indistinct checkbox** Checkbox in the form of an icon, indistinct (for multiple selection) |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/radiobutton-checked.svg) | radiobutton-checked | **marked radiobutton** Radiobutton in the form of an icon, marked |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/radiobutton-unchecked.svg) | radiobutton-unchecked| **unchecked radiobutton** Radiobutton in the form of an icon, unchecked |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/classbook.svg) | classbook | **classbook** classbook |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/clipboard.svg) | clipboard | **Clipboard** Copy to clipboard |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/cloud.svg) | cloud | **cloud service** generic icon for cloud services |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/code.svg) | code | **Program code** generic icon for program code |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/code-qr.svg) | code-qr | **QR code** QR code |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/computer.svg) | computer | **computer** generic computer icon |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/comment.svg) | comment | **Comment** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/comment2.svg) | comment2 | **comment** alternative for comments|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/community.svg) | community | **Community** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/computer.svg) | computer | **Computer** general icon for computers (analogous to phone/smartphone)|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/consultation.svg) | consultation | **consultation hours** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/content2.svg) | content | **content** general icon for content|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/courseware.svg) | courseware | **basic courseware icon** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/crop.svg) | crop | **Cropping** Cropping of images (e.g. avatar images) |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/crown.svg) | crown | **crown** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/date.svg) | date | **date** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/date-single.svg) | date-single | **single date** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/date-cycle.svg) | date-cycle | **regular date** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/date-block.svg) | date-block | **block date** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/decline.svg) | decline | **decline** This symbol is the basic form for negative feedback to the user. |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/decline-circle.svg) | decline-circle | **decline** Decline variant in a circle|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/dialog-cards.svg) | dialog-cards | **business cards** Icon for business cards/addresses |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/doctoral-cap.svg) | doctoral-cap | **exams/degrees** general icon for exams |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/doit.svg) | doit | **Do.IT**Do.IT plugin and other task-related functions |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/door-enter.svg) | door-enter | **Login/Enter** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/door-leave.svg) | door-leave | **Logout/Exit** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/download.svg) | download | **Download** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/dropbox.svg) | dropbox | **Cloud-Service Dropbox**Alternative |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/edit.svg) | edit | **edit** general edit icon |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/elmo.svg) | elmo | **Elmo** icon for Elmo plugin |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/eportfolio.svg) | eportfolio | **ePortfolio icon** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/euro.svg) | euro | **Euro** currency symbol/money |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/evaluation.svg) | evaluation | **Evaluation** general icon for evaluations |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/exclaim.svg) | exclaim | **Note** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/exclaim-circle.svg) | exclaim-circle | **Note** for popover |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/export.svg) | export | **Export** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/facebook.svg) | facebook | **Facebook** Facebook connection or link * |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/favorite.svg) | favorite | **Favorite/Like** favorite icon * |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file.svg) | file | **document** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/files.svg) | files | **Documents/File Area** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-archive.svg) | file-archive | **Zip file** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-audio.svg) | file-audio | **audio file** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-audio2.svg) | file-audio2 | **audio-file** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-sound.svg) | file-sound | **Audio file** Alternative, e.g. for loud audio files|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-pic.svg) | file-pic | **image file** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-pic2.svg) | file-pic2 | **image file** alternative|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-pdf.svg) | file-pdf | **PDF file** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-presentation.svg) | file-presentation | **presentation file** generic variant, without Power Point reference
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-spreadsheet.svg) | file-spreadsheet | **spreadsheet file** generic variant, without Excel-int reference
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-office.svg) | file-office | **Office document** Word/PowerPoint/Excel |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-excel.svg) | file-excel | **Excel document** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-video.svg) | file-video | **Video file** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-video2.svg) | file-video2 | **video-file** alternative |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-word.svg) | file-word | **Word document** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-ppt.svg) | file-ppt | **PPT-document** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-text.svg) | file-text | **Text file** (e.g. Word) |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/file-generic.svg) | file-generic | **generic file type** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/filter.svg) | filter | **search filter, view filter** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/filter2.svg) | filter | **search filter, view filter** alternative|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/fishbowl.svg) | fishbowl | **goldfish in a bowl** unused|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-broken.svg) | folder-broken | **unreachable folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-date-full.svg) | folder-date-full | **filled date folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-date-empty.svg) | folder-date-empty | **empty appointment folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-edit-empty.svg) | folder-edit-empty | **empty editable folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-edit-full.svg) | folder-edit-full | **full editable folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-empty.svg) | folder-empty | **empty folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-full.svg) | folder-full | **filled folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-group-empty.svg) | folder-group-empty | **empty group folder**|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-group-full.svg) | folder-group-full | **filled group folder**|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-home-empty.svg) | folder-home-empty | **empty home folder**|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-home-full.svg) | folder-home-full | **filled home folder**|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-inbox-full.svg) | folder-inbox-full | **filled Inbox folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-inbox-empty.svg) | folder-inbox-empty | **empty inbox-folder**|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-lock-full.svg) | folder-lock-full | **filled protected folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-lock-empty.svg) | folder-lock-empty | **empty protected folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-parent.svg) | folder-parent | **parent folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-plugin-market-empty.svg) | folder-plugin-market-empty.svg) | **empty plugin-marketplace folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-plugin-market-full.svg) | folder-plugin-market-full.svg) | **Folder Plugin-Marketplace filled** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-public-empty.svg) | folder-public-empty.svg) | **public folder, empty** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-public-full.svg) | folder-public-full.svg) | **public folder, filled** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-topic-empty.svg) | folder-topic-empty | **empty topic folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/folder-topic-full.svg) | folder-topic-full | **filled topic folder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/forum.svg) | forum | **forum** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/fullscreen-on.svg) | fullscreen-on | **fullscreen on** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/fullscreen-off.svg) | fullscreen-off | **fullscreen off** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/globe.svg) | globe | **globus/worldmap** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/glossary.svg) | glossary | **glossary** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/graph.svg) | graph | **graph/evaluation** general icon for graphical evaluations (e.g. evaluation evaluation) |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/group.svg) | group | **Permalink** new, formerly grouping |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/group2.svg) | group2 | **Group/gruppieren** Gruppen (Menschen) |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/group3.svg) | group3 | **group/hierarchy** groups/hierarchy |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/group4.svg) | group4 | **group/grouping** group by color |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/guestbook.svg) | guestbook | **guestbook** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/hamburger.svg) | hamburger | **Hamburger menu** for mobile view|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/home.svg) | home | **home page** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/info.svg) | info | **Information** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/info-circle.svg) | info-circle | **Information** for popover |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/infopage.svg) | infopage | **Free info page** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/inbox.svg) | inbox | **message inbox** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/outbox.svg) | outbox | **Messages outbox** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/install.svg) | install | **Plugin Installation** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/institute.svg) | institute | **Setup** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/item.svg) | item | **General object for comments** comment object |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/key.svg) | key | **Password** Password(-management) |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/knife.svg) | knife | **pocket knife/tool** alternative for tool icon |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/learnmodule.svg) | learnmodule | **Learning module** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/lightbulb.svg) | lightbulb | **lightbulb** e.g. for tips, ideas or brainstorming|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/link-extern.svg) | link-external | **external link** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/link-intern.svg) | link-internal | **internal link** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/link2.svg) | link2 | **external link** Alternative, right-oriented pages|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/link3.svg) | link3 | **external link** Alternative, left-oriented sites|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/literature-request.svg) | literature-request | **literature-request** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/literature.svg) | literature | **literature** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/lock-locked.svg) | lock-locked | **lock in closed state** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/lock-unlocked.svg) | lock-unlocked | **lock/lock in open state** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/log.svg) | log | **log/log** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/mail.svg) | mail | **message** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/maximize.svg) | maximize | **maximize** for widget system|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/medal.svg) | medal | **exams/achievements** general icon for exams |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/mensa.svg) | mensa | **Mensa** Mensa, vegetarian |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/mensa2.svg) | mensa2 | **Mensa** Mensa |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/metro.svg) | metro | **subway, train** e.g. for campus app |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/microphone.svg) | microphone | **microphone** e.g. for media |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/module.svg) | module | **Module** in contrast to learning module or plugin |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/money.svg) | money | **Mensa** payment processes, chargeable items |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/network.svg) | network | **Network** unused |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/news.svg) | news | **announcements** announcements |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/notification.svg) | notification | **notification** notification |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/notification2.svg) | notification2 | **Notification** Notification, Alternative |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/outer-space.svg) | outer-space | **Planet/Worldspace** unused icon, free for use |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/oer-campus.svg) | oer-campus | **OER-Campus** basic icon for the OER-Campus|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/opencast.svg) | opencast | **Opencast** Icon for the Opencast plugin|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/perle.svg) | perle | **Perle** Icon for Perle Plugin |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/permalink.svg) | permalink | **Permalink** Icon for retrieving/linking a permalink |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/person.svg) | person | **person/profile** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/persons.svg) | persons | **persons** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/person-online.svg) | person-online | **person online** person is online |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/picture.svg) | picture | **picture** general icon for pictures, e.g. in Courseware|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/phone.svg) | phone | **phone** classic phone, differentiated from cell phone|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/place.svg) | place | **Place** location/geoinformation/place |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/plugin.svg) | plugin | **Plugin** General icon for plugins |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/powerfolder.svg) | powerfolder | **Clound-Service Powerfolder** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/print.svg) | print | **Print** Print functions, print view |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/privacy.svg) | privacy | **Privacy settings** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/remove.svg) | remove | **Remove** Remove, also in the sense of moving (corresponds to the remove addition) |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/add.svg) | add | **Add** Add, also in the sense of increase (corresponds to the add addition) |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/question.svg) | question | **question** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/question-circle.svg) | question-circle | **question** for popover |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/ranking.svg) | ranking | **Ranking** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/radar.svg) | radar.              | **Radar** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/refresh.svg) | refresh | **update** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/resources.svg) | resources | **resource/resource management** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/resources-broken.svg) | resources-broken | **broken resource** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/resource-label.svg) | resource-label | **resource-label** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/rescue.svg) | rescue | **support/help alternative** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/roles.svg) | roles | **roles** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/roles2.svg) | roles2 | **role/rights alternative** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/rotate-left.svg) | rotate-left | **Rotate image left** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/rotate-right.svg) | rotate-right | **Rotate image editing right** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/room.svg) | room | **base icon for rooms** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/room2.svg) | room2 | **Base icon for rooms** Alternative |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/room-clear.svg) | room-clear | **room free** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/room-occupied.svg) | room-occupied | **room occupied** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/room-request.svg) | room-request | **room request** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/remove.svg) | remove | **Remove** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/remove-circle.svg) | remove-circle | **Remove** for popover |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/rotate-left.svg) | rotate-left | **rotate counter-clockwise** for image editing |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/rotate-right.svg) | rotate-right | **rotate clockwise** for image editing |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/rss.svg) | rss | **RSS-Feed** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/schedule.svg) | schedule | **calendar/schedule** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/settings.svg) | settings | **Settings** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/settings2.svg) | settings2 | **Settings** Alternative for settings |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/share.svg) | share | **Share/Export** general icon for sharing objects |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/search.svg) | search | **search** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/seminar.svg) | seminar | **event** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/seminar-archive.svg) | seminar-archive | **event archive** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/smiley.svg) | smiley | **Smiley/Emoji** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/skype.svg) | skype | **Skype** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/span-empty.svg) | span-empty | **Level/Progress: 0%** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/span-1quarter.svg) | span-1quarter | **Level/Progress: 25%** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/span-2quarter.svg) | span-2quarter | **Level/Progress: 50%** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/span-3quarter.svg) | span-3quarter | **Level/Progress: 75%** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/span-1third.svg) | span-1third | **Level/Progress: 33%** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/span-2third.svg) | span-2third | **Level/Progress: 66%** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/span-full.svg) | span-full | **Level/Progress: 100%** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/spiral.svg) | spiral | **Spiral** unused |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/sport.svg) | sport | **sport** e.g. for campus app |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/smiley.svg) | smiley | **Smiley** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/staple.svg) | staple | **attachment** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/star.svg) | star | **Rating star** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/stat.svg) | stat | **Statistics** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/studygroup.svg) | studygroup | **studygroup** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/support.svg) | support | **Support** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/table-of-contents.svg) | table-of-contents | **table-of-contents** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/tag.svg) | tag | **Tag** Tags on system objects |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/tan.svg) | tan | **TAN** Assignment, use of TANs, checks |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/tan2.svg) | tan2 | **TAN** Assignment, use of TANs, checks, alternative |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/test.svg) | test | **Test** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/timetable.svg) | timetable | **Timetable** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/tools.svg) | tools | **Tools** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/topic.svg) | topic | **Topic** for topics in events |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/trash.svg) | trash | **trashcan/delete** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/twitter.svg) | twitter | **Twitter** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/twitter2.svg) | twitter2 | **Alternative Twitter** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/twitter3.svg) | twitter3 | **Alternative Twitter** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/ufo.svg) | ufo | **Ufo** does it really exist?|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/unit-test.svg) | unit-test | **Unit-Tests** Unit-Tests |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/upload.svg) | upload | **Upload** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/vcard.svg) | vcard | **vCard/business card** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/video.svg) | video | **video** video |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/video2.svg) | video2 | **video** video/film |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/view-list.svg) | view-list | **switch list/tile list** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/view-wall.svg) | view-wall | **Switch List/Tiles Tiles/Wall** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/visibility-checked.svg) | visibility-checked | **visibility on** Visibility toggle: on |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/visibility-visible.svg) | visibility-visible | **visible/visibility on** Object is visible or visibility toggle: off |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/visibility-invisible.svg) | visibility-invisible | **invisible** Object is invisible |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/vote.svg) | vote | **poll** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/vote-stopped.svg) | vote-stopped | **paused poll** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/wiki.svg) | wiki | **Wiki** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/wizard.svg) | wizard | **wizard** Icon for wizards |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/youtube.svg) | youtube | **Youtube** |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/zoom-in.svg) | zoom-in | **zoom in** zoom for image upload |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/zoom-in2.svg) | zoom-in2 | **zoom in** zoom for image upload, alternative |
| ![](https://develop.studip.de/studip/assets/images/icons/blue/zoom-out.svg) | zoom-out | **zoom out** zoom for image upload|
| ![](https://develop.studip.de/studip/assets/images/icons/blue/zoom-out2.svg) | zoom-out2 | **zoom out**Zoom for image upload, alternative |


#### Play Pause and Stop

![](https://develop.studip.de/studip/assets/images/icons/blue/play.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/stop.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/pause.svg)


#### Lists and arrows
![](https://develop.studip.de/studip/assets/images/icons/blue/arr_1down.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_1left.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_1right.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_1up.svg) Arrows, scroll function (e.g. page forward, forward/back)\\

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_2down.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_2left.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_2right.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_2up.svg) Arrows for moving (e.g. enter object, swap objects use.)\\

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_eol-down.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_eol-left.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_eol-right.svg)

![](https://develop.studip.de/studip/assets/images/icons/blue/arr_eol-up.svg) Arrows for jumping to the end of a list (jumping to the end of a table, finer list of objects, etc.)
