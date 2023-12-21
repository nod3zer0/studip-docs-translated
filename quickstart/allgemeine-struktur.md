---
Title: General structure
---



Anyone who has ever started with a programming language knows what you start with: a hello world program. Yes, you may not see them anymore, but they are useful to understand the rough syntax and minimal conventions.



Let's look at a completely blank page in Studip that contains only one character. To see how much uniform design and session routines take up, just imagine how many lines of program such a "naked" Studip page is small.



The following file could easily be located in the Studip public folder:





















```php
<?php
/*
test.php - Display of an empty Stud.IP scaffold page
Copyright (C) 2009 Rasmus Fuhse <ras@fuhse.org>



This program is free software; you can redistribute it and/or
You can distribute it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License or (at your option) any later version.



This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY.
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.



You should have received a copy of the GNU General Public License
with this program; if not, write to the Free Software Foundation, Inc.
Foundation, Inc, 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.



$Id: test.php 12381 2009-06-03 16:57:46Z Krassmus $
*/



//Afrom here we start thinking about the code.



//// Initializations: Include path etc.
require '../lib/bootstrap.php';



//// A session is started here.
page_open(array('sess' => 'Seminar_Session',
  auth' => 'Seminar_Default_Auth',
  'perm' => 'Seminar_Perm',
  'user' => 'Seminar_User'));
$auth->login_if($_REQUEST['again'] && ($auth->auth["uid"] == "nobody"));
$perm->check("user");



include ('lib/seminar_open.php'); // Initialize Stud.IP session






//// Variables that help with the display.
$HELP_KEYWORD="Basis.Testseite"; // If you click on Help, you will be redirected to the Basis.Testseite.
$CURRENT_PAGE = _("Test page"); // Shows the name of this page






//// From here, the first text is written in the HTML document



//HTML header up to the <body> instructions
include 'lib/include/html_head.inc.php';



//Studip header, i.e. the navigation icons that appear above almost every page.
include 'lib/include/header.php';






//Here comes the actual message
$output_format = '<table class="blank" width="100%%"
border="0" cellpadding="0" cellspacing="0">
<tr><td class="topic"><b>&nbsp;%s </b>%s</td></tr>
<tr><td class="steel1">&nbsp;</td></tr><tr><td class="steel1"><blockquote>%s</blockquote></td></tr>
<tr><td class="steel1">&nbsp;</td></tr>
</table><br>'."\n";



printf ($output_format, htmlReady( _("and now") ), *, formatReady( _("Hello World!") ));






// Save data back to the database.
page_close();



?>
```






# Important elements of the test page: Bootstrap
[#bootstrap](#bootstrap)



The first statement of a script in `public` is always:



```php
// Initializations: Include path etc.
require '../lib/bootstrap.php';
```



This sets the $STUDIP_BASE_PATH, adjusts the include path and loads all important configuration and system class files.



# Important elements of the test page: Sessions
[#page_open](#page_open)



For one reason or another, Studip does things differently from other PHP modules. This starts, for example, with the session. PHP has a built-in session management, which theoretically allows variables to be stored globally on the server about what the user is currently doing, what data they have entered and so on. Unfortunately, this is only possible from PHP4 onwards. Since Studip was created under PHP3, a session management system is still used today that is based on the PHPLIB extension and looks a little old-fashioned to modern PHP developers. However, it is basically the same as a normal PHP session and is also easy to use. On the test page, this session is represented by



```php
page_open(array('sess' => 'Seminar_Session',
  auth' => 'Seminar_Default_Auth',
  'perm' => 'Seminar_Perm',
  'user' => 'Seminar_User'));
```



and closed by



`close_page();`



closed again. The PHPLIB session must be closed so that all variables are really available again in the next session (on the next Stud.IP page).



# Security check



Immediately after the page_open(...) follows the security check whether the user is allowed to see the page at all. With



`$perm->check("user");`



is used, for example, to check whether the viewer of the page has the rights of a "user". For a page that should only be viewed with admin rights, this would be



`$perm->check("admin");`



be displayed.
There are five security levels: Guest (i.e. without special rights, who may only view public events for which the security query is missing from the code), "user", "tutor", "lecturer" and "admin". Immediately after the security prompt, the include file



`include ('lib/seminar_open.php');`



is filled with all possible variables that are relevant on most pages, but not yet on our small test page.



# Structure of the headers



Studip strives for a uniform, solid design. This means that all pages (with the exception of the messenger, for example) have the same header and the same style instructions. This is done in the lines:



`include 'lib/include/html_head.inc.php';`



for the basic HTML structure from `<html>` to `<body>` including all CSS files and so on and



`include 'lib/include/header.php';`



which represents the actually visible header with the icons for the start page, news, homepage, Studip logo etc. The header also contains the name of the page and a link to the help page, which also contains information about what exactly a help page should display. Both pieces of information are set in the header.php via two variables. For this reason, the code should already contain this information BEFOREhand:



```php
$HELP_KEYWORD="Basis.testpage";
$CURRENT_PAGE = _("Test page");
```



# Text modules in Studip



What we will explain in more detail [later in this help] (quick start/internationalization) are the text modules, such as the just appeared



```php
_("test page")
```



A Studip newcomer will inevitably wonder what this underscore function is supposed to be. Normal text would certainly suffice here. In a way, the problem is the possibility to display every Studip page in English or theoretically in any other language. The corresponding translation work is not done in the code (which would make the code even more confusing than it already is), but in the declaration of the underscore "_" or gettext() function. Therefore, developers must ensure that any piece of text that is not an HTML statement is passed through the _("...") function.



The example above is a good example of what exactly needs to be set by gettext and what does not. The variable `$CURRENT_PAGE` is written as actual visible text in the header and the variable `$HELP_KEYWORD` is only used as a link parameter, which is not visible but only passed to the help page in the browser address bar.
