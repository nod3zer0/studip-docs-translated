---
id: assets
title: Assets
sidebar_label: Assets
---

### Introduction

All images, JavaScript and stylesheet files of Stud.IP are located in a common directory `public/assets` (see for example: https://gitlab.studip.de/studip/studip/-/blob/main/public/assets)

In addition, the configuration variable `$GLOBALS['ASSETS_URL']` exists, which contains a URI to this directory. By default, this refers to the `assets` directory located relative to the respective Stud.IP URI.

Example: A Stud.IP is located at `https://www.meinstudip.de`, then the assets directory is located at `https://www.meinstudip.de/assets` by default.

The purpose of this exercise is to be able to deliver static content from other servers in order to relieve the actual web servers. A specially set up assets web server is much more efficient in delivering static content than the normal web server could be.

### Setting up a special assets web server

The prerequisite is an existing web server that can serve as an assets web server. In this respect, [lighttpd](http://lighttpd.net) is highly recommended. Now simply copy the complete `assets` directory into your web area and make a note of the URI for this directory. In your Stud.IP installation, open the configuration file `config/config_local.inc.php` and search for the text `$ASSETS_URL = $ABSOLUTE_URI_STUDIP . 'assets/';`, which you must then change to the URI noted above. **Please note that the `$ASSETS_URL` must end with a slash.

### Using the Assets class

To be able to address images, JavaScripts etc. located in the assets directory in the HTML markup, it would of course be possible to use the global variable `$ASSETS_URL` directly. However, it is easier with the class `Assets`, and the class also offers some advantages for the dynamic delivery of graphic assets, e.g. for Retina displays.

**Since Stud.IP v3.4 the class `Assets` is no longer used for the integration of icons, but the separate Icon API.

The use of the class is briefly described here.

`echo Assets::img('blank.gif');` outputs a complete image tag:

```php
<img alt="Blank" src="assets/images/blank.gif" />
```

If you want to change the `alt` attribute or add further attributes, you can simply add an array of attribute => attribute values as the second parameter:

```php
echo Assets::img('blank.gif', array('alt' => 'nothing here', 'class' => 'some_class'));
<img alt="nothing here" class="some_class" src="assets/images/blank.gif" />
```

## Retina resolutions

The Retina class automatically takes care of the insertion of graphics in Retina resolution if the user works with a corresponding resolution when logging on to a system (this behavior is fully implemented from version 2.5). For all graphics, a parameter must be used to indicate that the corresponding graphic is also available in a Retina version.

If you now want to provide graphics in Retina resolution and have the assets class automatically select the correct size or graphics file, the following requirements must be met:

- First, a graphic must be created in double resolution (or, in other words, in double size in X and Y dimensions).
- The graphic must be stored in the same directory as the original file with the addition "@2x" (before the file extension and the dot). For example, `header_logo@2x.png` is added to `header_logo.png`.
- The parameter "@2x" must be set when calling. Only then does the assets class search for a correspondingly larger graphics file (there is no automatic search for a Retina file).

*A little background*: Retina graphics are used on many smartphones or tablets with high-resolution displays. The first notebooks with double resolution are also available. Since version 2.4, Stud.IP checks the pixel ratio of the output device when logging in and stores this in the session (the value is called "'devicePixelRatio"). If this value is 2, Stud.IP assumes a retina resolution for the duration of the session. If "@2x" is set, the graphics are loaded in double the resolution, but are still displayed with a pixel ratio of 1. A graphic with 44*44 is therefore loaded from an 88*88 file, but is given the size 44*44. The current browsers then decide how to deal with the larger number of pixels. However, to ensure that these graphics do not have to be loaded and scaled on non-retina displays, the above conditions must be met. On screens with a lower resolution, the graphic will still be scaled down if Retina was assumed by mistake.
