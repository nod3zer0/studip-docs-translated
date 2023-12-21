---
title: Folder structure
---

The Stud.IP directory tree contains a number of files in several subfolders of the main directory. The function of the individual subfolders (and possibly their subfolders) is explained in this article.

### app

This contains pages that have already been converted to [Trails](Trails).

#### controllers

The subfolder "controllers" in "app" contains Trails controllers for all Stud.IP pages that are loaded via Trails.

#### views

Each controller has a view, which is stored in this subfolder of "app".

### cli

PHP scripts for using Stud.IP on the command line are contained in this folder.

### config

Configuration files, including the templates of the two main configuration files config_local.inc.php and config.inc.php are stored here.

### data

This is where files are stored that should not be located in the web root of the web server and therefore cannot be accessed directly via the web server.

### db

This contains SQL scripts that can be used to set up a Stud.IP database. It also contains scripts with demo data and migration scripts for older Stud.IP versions.

### doc

Documentation for the installation of Stud.IP.

### lib

Stud.IP modules and libraries are contained here. This folder has a number of important subfolders:

#### classes

Contains class definitions for objects which are not stored in the database.

#### models

Most SimpleORMap (SORM) database models are stored here.

#### navigation

The various types of navigation objects are stored in this folder.

#### plugins

The definitions of the plugin interface are contained here.

#### locale

This folder contains the translation files of Stud.IP, as well as scripts for the Unix shell, which facilitate the automatic creation of the translation files for Stud.IP.

#### public

This folder contains files that can be loaded directly via the web server. In addition, the most important scripts (dispatch.php, plugins.php, ...) of the Stud.IP system are contained in this folder. The folder has three subfolders.

##### assets

This subfolder of "public" contains fonts, images (including icons), JavaScript files, sound files and stylesheet files, which can simply be loaded when loading a Stud.IP page.

##### pictures

Various background images for sidebars or certain elements on a page.

##### plugins_packages

Plugins are stored here. A separate subfolder is created for each origin description of a plugin ("origin" in the plugin.manifest file), in which the plugin is then stored.

#### templates

Templates for pages that have not yet been converted to [Trails](Trails).

#### vendor

Libraries that have been developed by external developers and are required in Stud.IP are contained in this folder.
