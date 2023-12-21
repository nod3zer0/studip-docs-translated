---
title: Overview
slug: /quickstart/
---

Congratulations! If you have accessed this page, you are
about to get started with Stud.IP development. Yes, you are! Don't look away bashfully or be afraid of the big task:
If you have a basic knowledge of PHP and MySQL and are willing to familiarize yourself with a few principles of Stud.IP development first,
you have everything you need.

## Development forum

Stud.IP is an open source project. For us, this means that the developers decide freely and from their own motivation,
and organize themselves.
The very first step: Get an account on the [developer server](http://develop.studip.de/studip).
This is where all discussions about further development take place. Your first port of call is the "Developer Board".
Here you can ask questions and present your ideas. Be brave, just say hello.

## Permissions

Open source does not automatically mean chaos. We also have people who are allowed to have the last word and
have to bear more responsibility than others.

**This is the core group**.

At the moment there are about 16 people who have been with us for a long time and have proven through their continued personal commitment that they care about Stud.IP,
that Stud.IP is close to their hearts. Most of them are paid by their employer, e.g. a university that uses Stud.IP,
for their work on Stud.IP.
However, this is not a requirement and membership is linked to individuals, not employers:
Like members of the Bundestag, core group members are only bound by their conscience.

The core group members decide democratically which further developments are included in the Stud.IP release and which rules apply to development.
and which rules apply to the development. In return, they are obliged to test carefully, regularly
on the developer server and to deal with all questions of further development.

At the beginning of your development activities, you will not yet belong to the core group. This means that others will look at what you develop and decide whether it will be included in the official Stud.IP version as it is. Of course, this is done transparently and in a way that you can understand at all times. If you follow the instructions here, you won't end up with a finished, busy development and hear: "No, we don't want that." But this also means that you don't have to take care of everything, but can choose the areas in which you feel particularly competent or which you particularly enjoy. If you stand out for your commitment and useful ideas and contributions, sooner or later you will be asked if you want to take on more responsibility and become a Core Group member.

## Groups of people

These instructions describe the aspects of Stud.IP development that are referred to as "programming" in the narrower sense.
However, the Stud.IP project not only needs computer scientists and
PHP hackers, but also urgently needs committed people with other interests and skills. These are e.g:

| Group | Description |
| ---- | ---- |
| Tests | New things are constantly being created and the programmers themselves are often bad testers. The focus is too narrow on what they have developed themselves to always be able to take into account the way in which the "normal user" interacts with the system. If you enjoy putting new functions through their paces and giving them a thorough grumble: get in touch on the developer board!
| Graphics and Design | Stud.IP aims to be attractively designed. To achieve this, we need people who enjoy working with colors, icons, controls, photos and fonts. If you have any suggestions for improvement, if something really bothers you or if you want to get involved with image editing and web design: Let us know on the developer board! |
| Information texts, error messages and other texts in Stud.IP must be formulated concisely and accurately. Then there is the help and the translation into English or other languages. If you would like to get involved in this playground: Report to the developer board! |
| Didactics and Pedagogy | Stud.IP is an e-learning application. Students, teachers and other users should use it as a tool to design teaching and learning processes. If you have any ideas, suggestions for improvement or suggestions: Report them to the developer board! |


The rest of this guide is actually intended for those who
want to dig in the dirt themselves, i.e. want to work with PHP, JavaScript and SQL.
All others please do not feel excluded, but referred to the developer board.

## Technical basis

Stud.IP is a **PHP** application that uses a **MySQL/MariaDB* database.
If you want to help develop it, you need to know PHP, be familiar with SQL and know a bit about Apache or Nginx configuration.
And, as always with web applications: All output is in HTML, formatted using CSS (SCSS and LESS).
Some functions also use JSON as an intermediate format, and JavaScript and AJAX are also present in many places.
If you are familiar with all of this, you are well equipped.

## Git repository

Since many developers work on Stud.IP at different locations
in different places, a version management system is used. We
have opted for Git (Gitlab). All information that is important for the start can be found on the page [Set up development system](development environment).

## BIESTs, StEPs and Lifters

Quality assurance is a top priority at Stud.IP. The developers have created a comprehensive set of rules to make the various requirements for further development manageable. Three terms stand for the most important processes:

| type | description |
| ---- | --- |
| BIESTs | A beast is a recognized error in the software. Nasty and must be eliminated. All bugs are collected in the "bug board" on the developer server and then wait to be dealt with. If you think you have discovered a bug, you can report it there using a form. |
| StEPs | Further development means: adding new functions, changing existing ones. If you have an idea, formulate it in a "Stud.IP Enhancement Proposal" (StEP for short). The StEPs are then discussed in the "StEP forum" on the developer server: Does the proposal make sense, has everything been considered and is there a sensible plan for implementation? Take a look at the wiki to see the current StEPs, i.e. the concrete planned further developments. If you want to make your own suggestion, you need to find a "sponsor" from the core group. This is not harassment, but helps us to sort ideas and help newcomers find their way around. |
| Lifters | Some conversions cannot be completed in one go. StEPs must always be fully implemented for a specific release, but more fundamental conversion work sometimes takes longer. Example: The complete conversion to a template system. At the beginning you will probably not formulate your own lifters ("Ongoing technical renovation for Stud.IP"), but the existing lifters are binding for all developers. In these instructions, reference is made to the Lifter documentation if you have to observe a convention during development. |
