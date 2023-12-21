---
title: Development environment
---

To co-develop yourself, you need a local test system on a computer for which you have all the necessary rights.

In detail, this means that you need the following

* A web server (Apache or Nginx), preferably with write access to the central configuration files
* PHP version 7 or higher
* Write permissions in the file system both for files that are accessible to the web server and for those that are outside of it
* Full access to a MySQL database (MySQL or MariaDB)
* A Git client to check out the latest developer version of Stud.IP
* An editor or a development environment (e.g. PHPStorm) for editing files

## Server environment

To develop for Stud,IP you need access to a web server and its file system.
You can set this up on your own computer or use an existing server that you can access via SSH or other remote access.
Some tried and tested solutions for various server operating systems are listed below.

### Linux

Do you use Linux? Then one could almost assume that you know how to install a web server. But as an example for an Ubuntu Linux, you would have to go through the following steps:

- Install Apache (alternatively nginx): `sudo apt install apache2`
- Install MariaDB: `sudo apt install mariadb`
- Install PHP: `sudo apt install php libapache2-mod-php php-mysql`
- install git: `sudo apt install git`
- Check out the Stud.IP repository: `git clone git@gitlab.studip.de:studip/studip.git`
- Run `make` in the Stud.IP directory.
- In the folder `./config` copy the file `config.inc.php.dist` to `config.inc.php`.
- In the folder `./config` copy the file `config_local.inc.php.dist` to `config_local.inc.php` and then edit this file. At least the variables `$DB_STUDIP_USER` and `$DB_STUDIP_PASSWORD` must be set so that they contain the access data for MariaDB or MySQL.
- Copy the file `./config/.htaccess.dist` to `./public/.htaccess` (or alternatively change the Apache configuration or php.ini).
- Create a new database `studip` in MariaDB or MySQL: `CREATE DATABASE studip CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;`
- And then import the files `studip.sql`, `studip_root_user.sql`, `studip_default_data.sql`, `studip_demo_data.sql`, `studip_mvv_demo_data.sql`, `studip_resources_default_data.sql` and `studip_resources_demo_data.sql` from the folder `./db` one after the other in this order.
- If the `DocumentRoot` of Apache points to the folder `public` of Stud.IP, everything should work.

### Windows

This installation guide explains the installation of a Stud.IP test system
in version 4.6 on Windows. Instructions for Unix operating systems can be found
[here](https://hilfe.studip.de/admin/Admins/Installationsanleitung).

The following applications are required for the installation
- Apache web server
- MySQL/MariaDB database server: At least MySQL 5.7 or MariaDB 10.2.3
- PHP: at least PHP 7.2, at most PHP 8.1

The Apache distribution XAMPP version 7.3.28 is used demonstratively in these instructions.
If you choose a different version, please make sure that the PHP version is compatible.
In addition, only a basic installation is created,
This is the simplest possible variant for putting a test system into operation,
without going into too much detail about the individual aspects.
It is therefore more about simply setting up a test system,
which can be used to develop plugins, for example.
For further and detailed explanations, please refer to the Unix installation instructions linked above.

The Stud.IP files can be loaded using git at the following repository address:
`git clone git@gitlab.studip.de:studip/studip.git`


## Apache configuration

The "document-root" from which XAMPP loads the PHP files to be displayed,
is located under `C:/xampp2/htdocs` by default.
This means that all files to be displayed (e.g. the Stud.IP directory) must be located in this directory,
so that they can be opened via `localhost`.
Alternatively, the document-root of Apache can also be adapted as required.
To do this, in the file http.conf (xampp-directory\apache\conf\http.conf)
the setting `DocumentRoot` and `Directory` (~ line 252f) must be changed to the new directory.
It should be borne in mind that with every change to config files (http.conf, php.ini etc.)
the affected applications (Apache, MySQL etc.) must be restarted so that the changes are applied.
To set the document-root in the directory `C:\Users\MaxMustermann\PhpstormProjects`,
the http.conf could look like this at the relevant point, for example:

```
\[...\]
DocumentRoot "C:\Users\MaxMustermann\PhpstormProjects"
<Directory "C:\Users\MaxMustermann\PhpstormProjects">
\#
\[...\]
```

## PHP configuration

The following configurations must be made in php.ini (xampp-verzeichnis\php\php.ini).
The line is specified after the respective setting,
if XAMPP has been reinstalled in the same version,
otherwise the ini file can usually be easily searched with Ctrl+F.
It should also be noted that lines beginning with a semicolon (`;`) are commented out.
So you should either search for another entry to overwrite the setting (e.g. `error_reporting` ),
or if only this entry exists, the semicolon should be removed (e.g. `mbstring.internal_encoding`).

- short_open_tag = On (line 192)
- max_execution_time = 300 (line: 380)
- max_input_vars = 10000 (line: 397)
- memory_limit = 1024M (line: 401)
- error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE (line: 457)
- post_max_size = 514M (line: 687)
- default_charset = "UTF-8" (line: 706)
- upload_max_filesize = 512M (line: 839)
- allow_url_fopen = On (line: 850)
- mbstring.internal_encoding = "UTF-8" (line: 1670)

## Stud.IP configuration

### Basic configuration

If the web server and PHP have been configured correctly, Stud.IP can be configured.
Since Stud.IP version 4.5 there is an installation wizard which is automatically called up the first time the start page is called up.
Stud.IP is available after configuration in a web browser under `localhost` (or the IP address of the computer on which XAMPP is running).
To open the start page, navigate to the folder `public`.
Example: If the complete Stud.IP directory is located in `DocumentRoot` and is named `4.6`,
the start page can be opened with the URL `localhost/4.6/public` in the web browser.
If Stud.IP has not yet been configured, the installation wizard will open.

All steps of the installation wizard are described below, essential steps are marked in bold.

Step 1:
Click on Start wizard
**Step 2**:
This shows whether PHP has been configured correctly. If parts have not been configured correctly, this will be indicated here.
If the correct XAMPP version has been installed and the PHP configuration has been carried out correctly,
all settings should be "ok" here. If changes are still made, you should remember to
restart the Apache server.
**Step 3**:
Here the database is initially created, the recommended host is `localhost` and the recommended user is `root`.
If the MySQL/MariaDB server is started (in XAMPP the module under Apache),
the connection check should be successful.
Step 4:
This shows whether MariaDB is configured correctly.
With the recommended XAMPP version, everything should already be correct without having to make any further settings.
Step 5:
This step checks whether selected data directories are writable.
Under Windows, no further settings should be necessary by default and the directories should be writable.
Step 6:
The standard data for the database can be created here. The installation of optional demo data is recommended here,
if no other data is available.
The remaining settings to be made are only relevant for production systems and can therefore be filled as required for this test system.
**Step 7**:
Here it is possible to create a root account for the login to the Stud.IP system.
This root account should be remembered, as only root users have access to functions such as installing plugins.
Step 8:
All you have to do here is wait for the installation to be carried out.
Step 9:
Confirmation that the installation was (hopefully) successful.

### Further configuration
Further configuration steps must and can be carried out under Windows.
All of these should be included in the local configuration file `config_local.inc.php` (`<studip-directory>/config/config.local.inc.php`)
within the second `namespace`. The setting for the connection to the database can also be found here,
if you wish to customize it.
A config.local.inc.php file containing all the following settings is otherwise available here separately. However, the database configuration may need to be adjusted within this file.
[config_local.inc.php](../assets/3310a5850c6c4ed2d0b55a1884e5a39b/config_local.inc.php)

A `tmp` directory/folder is required to store temporary files.
It is recommended to create the directory in the Stud.IP directory (on the same level as the directories `app`, `lib`, `config`, `public` etc.).
In the configuration file mentioned above, the path of the tmp directory must be assigned to the variable `$TMP_PATH`.
If the `tmp` directory was created in the Stud.IP directory, the following line can simply be transferred to the configuration file.
`$TMP_PATH = $STUDIP_BASE_PATH . "/tmp";`

To prevent possible error messages, caching should be switched off.
The following line can be added to the configuration file for this purpose.
`$CACHING_ENABLE = false;`

As this is a test system, a mail server is probably not required to send e-mails,
However, in order to avoid error messages, the sending of mails should be prevented.
The following line can be added to the configuration file for this purpose.
`$MAIL_TRANSPORT = 'debug';`

In some Stud.IP versions it is also recommended to add the following line to the configuration file,
to prevent PHP warnings:
`define("LC_MESSAGES", 5);`

Stud.IP at the University of Oldenburg offers the possibility to display texts in English.
To take this into account during development, English should be added as a language.
The following line can be added to the configuration file for this purpose.
`$CONTENT_LANGUAGES['en_GB'] = array('picture' => 'lang_en.gif', 'name' => 'English');`

If all necessary and desired settings have been made, the Stud.IP test environment should now be usable.


### Mac OS

// TODO

## Get the latest version

The Stud.IP developers use SVN for version management, all official versions are anonymous and publicly readable.
Gitlab offers a simple insight at https://gitlab.studip.de

**Important:**

There is always exactly ONE Stud.IP version that is being actively developed.
This is located in the Git repository at [main](https://gitlab.studip.de/studip/studip/-/tree/main).
A release is compiled from the current repository every 6 months. A
lte releases are sometimes provided with bug fixes, otherwise all developers always work in the main.

Anyone can *read*: You can check out the complete code with your Git client at https://gitlab.studip.de/studip/studip.git.
There is a whole range of different branches, some of which are very specific. You need the following information in particular:


git command to check out the current developer version:
```shell
git clone https://gitlab.studip.de/studip/studip.git
```

However, not everyone is allowed to *write*.
Although we are happy to welcome anyone who would like to contribute their own developments, bug fixes and improvements to Stud.IP, no code should be included in the current version without careful quality assurance.
That's why only selected developers are allowed to check code into the repository.
As long as you are not yet one of them, your path leads via the developer board.

## Installation and configuration

All important information about the Stud.IP installation is listed in the [Admins/Installation Guide](Admins/Installation Guide).
Since you are not installing a release version, but an SVN version, you must note the following differences:


## Development environment

### Simple text editor

The minimum requirement is a simple text editor, e.g. vi or nano.

### Advanced text editor

Advanced text editors such as Kate offer considerably more convenience than vi or nano, as they have syntax highlighting, split views (several files in one window) and automatic text insertion.

### IDE (PHPSTORM)
// TODO

## Check in changes

*As already mentioned, not everyone is allowed to write in SVN. We are happy about everyone who has their own developments,
bugfixes and improvements to Stud.IP, but of course no code should be included in the current version without careful quality assurance. That's why only selected developers are allowed to check code into the repository. If you are not yet one of them, your path leads via the developer board.
