---
title: Automatic plugin updates
slug: /plugins/automatic-updates
sidebar_label: Automatic updates
---

### Installation

As of version 3.2, you can install plugins in Stud.IP directly from github or other repositories such as gitlab. To do this, go to the plugin administration as root and click on "Install plugin from URL" in the sidebar.

A dialog box opens in which you enter the URL. This URL must lead to the ZIP download on github. It is therefore not enough to enter the basic URL of the repository like https://github.com/studip/PluginMarket, but you have to enter the URL to the ZIP file like https://github.com/studip/PluginMarket/archive/master.zip . Most systems such as gitlab, github or bitbucket should have such a URL.

Now you can click on Save and the plugin will be installed.

### Setup

What works once can also work more often. So why shouldn't Stud.IP automatically install the plugin installed from the web more often and thus always stay up to date? Exactly, there is hardly anything against it. However, Stud.IP will only do this when github contacts Stud.IP and informs them that the plugin has been updated.

The process will then look like this:
* A change to the plugin is checked into the repository.
* The repository reports to Stud.IP via webhook with the message "something has changed with plugin xyz".
* Stud.IP checks this webhook request, because anyone could come here. Stud.IP will only take it seriously if the correct repository is really calling.
* Stud.IP will then automatically call up the URL of the ZIP download and install the modified plugin.

For this to work, both Stud.IP and the repository must be specially set up.

Stud.IP needs the URL of the ZIP download and information on whether the webhook should be secured via a security token. The security token currently only works with github.

The repository must know the exact URL to be called by the webhook. A URL looks something like this:
http://www.superstudip.de/studip/dispatch.php/plugins/trigger_automaticupdate/OnlineList?s=8d1e6b52927a7f5f567f7aedeb8b17b0
This URL already contains a security token; only those who know the token, i.e. the exact URL, can call up the request at all. It must be said that tokens in URLs are not particularly secure. But they are better than nothing. And with gitlab or other systems, this is currently the only possible safeguard.
If desired, the webhook in github can still be secured using a security token. This does NOT mean the token from the URL, but the separate token that is displayed in Stud.IP under the URL.

If Stud.IP and the repository are set up in the same way, everything is actually done.

### Important

We do not recommend using these automatic updates for productive operation. But they can be worth their weight in gold for test systems. Especially with extensive and complicated plugins, you may always want to upload all the plugins via the Stud.IP interface after every change. Remember, you have to be logged in as root, go to the plugin administration, click in the sidebar, scroll down, click on the file upload, realize that you forgot to zip the plugin, then zip the plugin, select the file again. And then, depending on the size of the plugin, it takes an agonizingly long time for the plugin to be uploaded. And let's be honest: who has never uploaded a fully zipped plugin to the repository during the whole process?

Automatic updates therefore simplify testing with test servers immensely. You only have to push the progress of the plugin into the repository, which you have to do anyway, and the connected test systems all update themselves at the same time. If you have several test systems (this is absolutely necessary when developing the CampusConnect plugin, for example), the automatic update also prevents you from forgetting an update somewhere and then chasing errors that don't even exist in the code.

The automatic update also has the advantage that the developer of the plugin does not necessarily have to have root access to the test system in order to be able to install updates.
