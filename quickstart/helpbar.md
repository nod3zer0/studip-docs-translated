---
id: helpbar
title: Integration of help content
sidebar_label: Help content
---




The help content is displayed in the helpbar. This includes:


*The link to the help wiki
*Short help texts that were displayed in the info box in previous versions
*Tours


## Help texts
The help texts are loaded from the database and are always valid for a route.


It is possible to make the presence of request parameters a condition for the display, e.g: "wiki.php?view=edit".


## Help tours
Tours can also be defined for a route. A tour consists of individual steps which (like tooltips) are related to elements on a page.


There are two different types of tours (tour and wizard): the modal *tour* does not allow any input, with the *wizard* Stud.IP remains actively operable. It is possible to configure tours so that they are started automatically when the page is called up (for the first time).


There is a control bar with fixed control elements (Next, Back, Exit) for switching between tour steps.
Tours can lead over several Stud.IP pages.


## Help tab for plugins


Currently you simply call `Helpbar::get()->addPlainText`. See [example](https://gist.github.com/luniki/2ca7d97317c697702795)


## Hide help tab


To hide the help tab, call `Helpbar::get()->shouldrender(false);`.
