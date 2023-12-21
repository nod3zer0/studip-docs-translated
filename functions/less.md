---
id: less
title: Stylesheets in LESS
sidebar_label: Less
---


As of version 2.3 ([#2602](https://develop.studip.de/trac/ticket/2602)) of Stud.IP, stylesheets are written in LESSCss (http://lesscss.org) and compiled into normal CSS.

Compiling the .less files is now part of the build process or can also be triggered separately using `make` on the command line.

## Special variables and mixins for Stud.IP

### Paths

| Variable | Description |
| ---- | ---- |
| `@image-path` | path to the images of Stud.IP, corresponds to `public/assets/images`| by default

### Images

| variable | description |
| ---- | ---- |
| `.retina-background-image(@normal, @retina)` | Includes a background image for Retina displays in two sizes. The images are expected in the image path of Stud.IP. |
The following two functions are available for Retina images:


### Icons

From v3.4 Stud.IP icons can be integrated in LESS/CSS via the following mixins:

| **purpose** | **signature** | **example**|
| ---- | ---- | ---- |
|**Icon in the background** |`.background-icon(shape, role);` |`.background-icon('seminar', 'clickable');` |
|**Button with icon incl. hover effect** |`.button-with-icon(shape, role, role_hover)` |`.button-with-icon("accept", "clickable", "info_alt")` |
|**Icon as BG in ::before** |`.icon("before", shape, role)` |`.icon("before", "arr_1right", "clickable")`|
|**Icon as BG in ::after** |`.icon("after", shape, role)` |`.icon("after", "arr_1right", "clickable")` |

### Colors

// TODO: Show actual color scheme as screenshot
// TODO: Describe namespaces better

Since version 3.0, Stud.IP has its own color scheme, which should always be used as far as possible.

| color | description |
| ---- | ---- |
| `@red` | A shade of red |
| `@orange` | A shade of orange |
| `@activity-color` | Indicates activity options |
| `@dark-gray-color` | Dark gray |
| `@light-gray-color` | Light gray |
| `@content-color` | Color for content |
| `@base-color` | Base color |


All colors are available in four further shades, in which 80%, 60%, 40% and 20% of the original color have been mixed. These colors are spoken by appending the respective percentage to the color variable separated by a -, e.g. `red-60`.

## Deprecated: LESS in plugins

The following suggestions will change in the future (probably Stud.IP v5) and are therefore not future-proof.

The class `StudipPlugin` provides the method `addStylesheet()`, via which LESS can also be used in plugins. To do this, the name of the LESS file **relative** to the plugin path must be specified to this function. This compiles the LESS file and also makes it available in the page. All mixins that are available in the core are also available to the plugin.

In addition, since Stud.IP 3.4 the variable `@plugin-path` is available to the plugins in LESS to reference files within the plugin directory.

#### Deprecated: Own implementations for saving the compiled files

If the storage of the compiled files is to be changed, the `Assets\Storage` can be passed an instance of its own implementation of `Assets\AssetFactory` via the `setFactory()` method, which creates specialized `Assets\Asset` objects that can manage the storage differently. The download path for accessing the files can also be changed accordingly. For more information, please refer to the interfaces [AssetFactory](https://develop.studip.de/trac/browser/trunk/lib/classes/assets/AssetFactory.php) and [Asset](https://develop.studip.de/trac/browser/trunk/lib/classes/assets/Asset.php) or their specific core implementations [PluginAssetFactory](https://develop.studip.de/trac/browser/trunk/lib/classes/assets/PluginAssetFactory.php) and [PluginAsset](https://develop.studip.de/trac/browser/trunk/lib/classes/assets/PluginAsset.php).

Such a change can be imported via a SystemPlugin, provided that this is loaded as the first plugin (smallest "position" in the plugin administration of the system).
