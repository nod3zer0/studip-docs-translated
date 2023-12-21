---
title: Asset Bundling
---


## Asset Bundling
Asset bundling is the assembly and, if necessary, compilation of modules. Modules can contain JavaScript, CSS, LESS-CSS, etc. Modules have dependencies among each other. Asset Bundling resolves these dependencies, compiles modules if necessary and generates ready-to-use JS and CSS files, for example.


Stud.IP version 4.2 and higher uses the open source asset bundler "webpack".


The configuration files are located in the project directory and are called "webpack.dev.js", "webpack.dev-server.js" and "webpack.prod.js".


This means that three modes are available for asset bundling:


* `make webpack-dev`: Fast. Compiled and bundled in developer mode. The bundles are not optimized and easy to debug.
* `make webpack-prod`: Slow. Compiled and bundled in production mode. The bundles are optimized and can be debugged with source maps.
* `make wds`: Very fast. Starts the webpack-dev-server. The $ASSETS_URL must be changed: `$ASSETS_URL = "http://localhost:8123/";` (as of Stud.IP 4.4 the adjustment of the `$ASSETS_URL` is done automatically when the webpack-dev-server is running).


The npm packages must be installed **once** before the first `make` call:


`npm install`


If you have then modified JavaScript or CSS files, call `make webpack-dev` or `make webpack-prod` so that the changes are compiled into the output files.


### make webpack-dev vs. make webpack-prod


Since `webpack-dev` is much faster (but not optimized), `make webpack-dev` is suitable for local development.


As soon as you want to check in changes in SVN, you should urgently call `make webpack-prod`.
Since the developer board takes the files directly from the `trunk`, only optimized files should be stored there, otherwise all developer board users will have longer loading times.


With `make webpack-prod`, the debug information is stored in source map files, with which debugging should be similarly convenient.


On a test system, `make webpack-dev` runs in ~6 seconds. The `make webpack-prod` variant requires ~17 seconds on the system.
The fastest, however, is `make wds`, which takes less than 2 seconds from the time the change is **saved**




**When should you call `make webpack-dev`?


Whenever you are just developing locally without checking anything in.


**When should you call `make webpack-prod`?


Always before you check in JS or CSS changes to SVN. But before that you should always call `npm install` to be sure that you have the latest versions of the libraries used.




### make wds


With `make webpack-prod` and `make webpack-dev` the assets are assembled on the command line. The webpack-dev-server, on the other hand, monitors the asset files; if one of them changes, it triggers an incremental build and reloads the page in the browser.


If you want to use the webpack-dev-server:


* first change the ASSETS_URL in the config_local.inc.php file to: `$ASSETS_URL = "http://localhost:8123/";`
* then call `make wds` on the command line.


The assets are then assembled incrementally and delivered via `http://localhost:8123/`.


Via the magic URL: `http://localhost:8123/webpack-dev-server` you can see what the webpack-dev-server delivers




### Cryptic names of bundled JavaScript files


In order to avoid caching problems for the user after a version change of a Stud.IP installation, it was introduced a few years ago that the JavaScript files are integrated via PHP with a special version-dependent URL parameter, which thus solves the caching problem.


If JavaScript files are now loaded dynamically (see [JavaScript in Stud.IP](HowToJavascript)), the files to be reloaded are integrated directly via JS without taking the detour via PHP. This means that the existing PHP mechanism can no longer be used. The problem is reported in ticket:9114. The JS files to be reloaded are referred to as "chunks".


Fortunately, webpack offers the configuration option for this purpose [to adapt the file name of the JS files ("chunks") to be reloaded](https://webpack.js.org/configuration/output/#output-chunkfilename):


```shell
output: {
  chunkFilename: "javascripts/[name]-[chunkhash].chunk.js",
}
```


In asset bundling, the "chunks" are given a name that contains a hash value for the content of the chunk.
If the content of the chunk changes, it is given a new name. At the points in the JS code where this "chunk" is referenced, webpack automatically inserts this content-dependent name. The whole thing works automatically, of course, so that you as a developer have no trouble with it.


**BUT:** If you change the "chunk" or update (automatically) 3rd-party libraries that are used in the "chunk", the name of the bundled "chunk" file also changes. If you call "make" or "webpack [ ]" after such a change, new "*.chunk.js" files are created. If you want to check these files into a version management system (svn or git), you must first remove the old "chunk" files and add the new "chunk" files. **However, this is only necessary if you make changes to these files.


This means that changes to the tablesorter may not reach the user immediately due to caching. I reported this problem in ticket:9114.
