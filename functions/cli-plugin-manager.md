---
id: cli-plugin-manager
title: Plugin manager on the command line
sidebar_label: Plugin manager CLI
---

# Plugin manager on the command line

| 📌 **Attention** |
|--|
| The command line tool `cli/plugin_manager` is used up to **Stud.IP v5.0** and is then replaced by the more general tool [`cli/studip`](CLI). |

If you don't have the possibility to manage the plugins via the user interface or simply want to add a plugin to Stud.IP that is already in the right place, then this tool is just what you need. It is located in the `cli` folder and can be called from any location under the name `plugin_manager`.

It offers the following commands:

## Install a plugin file

```shell
./cli/plugin_manager install path/to/plugin.zip
```

Installs the plugin in the specified ZIP file. This action is analogous to installing a plugin via the user interface.

## Registering a plugin

```shell
./cli/plugin_manager register public/plugins_packages/origin/plugin
```

If the plugin is already in the correct place in the Stud.IP directory tree, you can use this to register it in the system. Any installation SQL files are executed and missing migrations are carried out

## Removing the registration of a plugin

```shell
./cli/plugin_manager unregister PluginName
```

Removes the plugin with the specified name from the system and executes all "down" migrations of the plugin.

## Execute plugin migrations

```shell
./cli/plugin_manager migrate PluginName [-l] [-v] [-t *number]

-l Only lists what should be done, but does not migrate.
-v Activates the "verbose" mode. Additional information about the migrations performed is displayed.
-t number - Allows the target migration. A 0 causes all migrations to be canceled, otherwise the system migrates to the corresponding migration (up or down). If this parameter is not specified, the system automatically migrates up to the most recent migration.
```

Allows plugin migrations to be carried out. Behaves analogously to the [Stud.IP-CLI-Migrator](Migrations#toc7).

## Activate plugin

```shell
./cli/plugin_manager activate PluginName
```

Activates the specified plugin

## Disable plugin

```shell
./cli/plugin_manager deactivate PluginName
```

Switches the specified plugin off

## Show plugin info

```shell
./cli/plugin_manager info PluginName
```

Displays information about a single or all installed plugins. The information is read from the database, if the plugin was not found in the file system, \[class_exists\] => 0 is set.

```shell
[id] => 142
[name] => Forum
[class] => CoreForum
[path] => core/Forum
[type] => ForumModule,StandardPlugin,StudipModule
[enabled] => 1
[position] => 50
[depends] => 0
[core] => 1
[class_exists] => 1
```

## List all available plugins

```shell
./cli/plugin_manager scan
```

Lists all plugins that exist in the file system but are not registered in the database.

```shell
[pluginclassname] => Achievements
[pluginname] => Achievements
[origin] => tgloeggl
[version] => 0.1.2
[path] => /path/to/trunk/public/plugins_packages/tgloeggl/Achievements
```
