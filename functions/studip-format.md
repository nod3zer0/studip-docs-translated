---
id: studip-format
title: StudipFormat
sidebar_label: StudipFormat
---

:::danger
The function is obsolete and should no longer be used
:::

In Stud.IP version 2.3 the formatting engine was changed to its own parser, which is hidden in the classes `StudipFormat` and `TextFormat`. TextFormat is the basic parser and StudipFormat a special one, which is enriched with the typical Studip syntax.

It is basically easy to use. You instantiate the parser and send the text to be formatted through the `format` method. Something like this:

```php
$markup = new StudipFormat();
echo $markup->format("My %%cooler%% formatted text is **cool**.");
```


You can also define your own parser with the class `TextFormat`. However, a naked object of this does nothing at first because `TextFormat` does not come with any rules of its own. A rule is defined via the constructor or then via the `addMarkup` method. Something like this:

```php
function bbbold($text) {
    return "<b>".$text."</b>";
}
function bbitalic($text) {
    return "<i>".$text."</i>";
}
$bbmarkup = new TextFormat(array(
    'bold-text' => array(
         'start' => "\[b\]",
         'end' => "\[\/b\]",
         'callback' => "bbbold"
     )
));
$bbmarkup->addMarkup(
    'italic-text',
    "\[i\]",
    "\[\/i\]",
    "bbitalic"
);
```


Within Stud.IP, the functions `formatReady` `linkReady` and `wikiReady` are always used to format text. With the new parser you can now also build plugins that extend or change this markup. Internally, `htmlReady` simply uses `$markup->format($text)` to format the text, which in turn has a number of formatting rules preset. These formatting rules are stored in the private variable `StudipFormat->studip_rules`, which can be manipulated with the static methods `getStudipMarkup($name)`, `addStudipMarkup($name, $start, $end, $callback, $before = null)` and `removeStudipMarkup($name)`. The most interesting method is certainly `addStudipMarkup`.

For example, you could build a plugin that extends the markup for Stud.IP. I will present a small example plugin that integrates OpenstreetMaps wherever the user writes something like `[osmap]latitude;longitude;zoomfactor[/osmap]` This is simple and can always be put to good use.

```php
class OpenstreetmapEmbedder extends StudIPPlugin implements SystemPlugin {

    public function __construct()
    {
        parent::__construct();
        StudipFormat::addStudipMarkup("osmap", "\[osmap\]", "\[\/osmap\]", "OpenstreetmapEmbedder::createMap");
        PageLayout::addHeadElement('script', array('type' => "text/javascript", 'src' => $this->getPluginURL()."/assets/khtml_all.js"), *);
    }

    public static function createMap($markup, $matches, $adress)
    {
        $id = "map_".uniqid();
        list($latitude, $longitude, $zoom) = explode(";", $adress);
        $output = sprintf(
                '<style>#%s > div:not(:first-child) {display: none;} </style>'.
                '<div id="%s" style="width: 400px; height: 300px; border: 1px solid black;"></div>' .
                '<script>
                jQuery(function () {
                    map=new khtml.maplib.base.Map(document.getElementById("%s"));
                    map.centerAndZoom(new khtml.maplib.LatLng(%s,%s),%s);
                    var zoombar=new khtml.maplib.ui.Zoombar();
                    map.addOverlay(zoombar);
                });
                </script>',
            $id,
            $id,
            $id,
            (double) $latitude,
            (double) $longitude,
            (int) $zoom
        );
        return str_replace("\n", "", $output);
    }
}

```

Apart from the peculiarities of the underlying Javascript engine for displaying the map, the plugin is very simple: In the constructor, the markup is specified to StudipFormat via the method `StudipFormat::addStudipMarkup`. This means that the markup already exists. The first parameter is simply a name that can be freely chosen. The second parameter is the start expression as a regular expression. This could also contain named expressions such as `(.*?)`, which are subsequently passed to the executing markup function via `$matches` variables. But for our example it doesn't matter, as we only need one parameter.
The third argument is the end expression. Everything between the start expression and the end expression is passed to the function later as `$contents`.
The fourth argument is the name of the function that will later execute the markup. Static functions are best suited for this.
There could be a fifth argument called `$before` which specifies before which other directives this markup is executed. It could be that two markups cause ambiguities. And the markup that is further ahead in the sequence always 'wins'. If you specify `$before = "left"`, your own markup is always preferred to the left syntax.

In the method `OpenstreetmapEmbedder::createMap`, the method that executes the markup, we receive three parameters, the third of which only exists if a final expression has been defined. The first parameter is always a `TextFormat` object that coordinates the markup. In most cases, this object does not need to be considered. `$matches` is an array with all the parameters obtained from the regular expression. `$matches[0]` is always the whole string, `$matches[1]` would be the first named parameter in the regular expression like `(.*?)`. `$contents`, the third argument and called `$address` in our example, specifies everything between the start expression and the end expression.
The `createMap` method now only has to return something that is practically used for `$matches[0]`, i.e. for the string concerned. The string touched here will never be affected by other markup.

**WARNING** for plugin developers: Be aware that you can quickly build a plugin that people will probably understand quickly. But the syntax of the formatting you choose is fixed from the beginning for all time, because the forum posts that use your new syntax will keep the syntax you used in the beginning for eternity. Therefore, think carefully about the syntax, be sparing with formatting options that are likely to become obsolete one day (such as SWF file integration), or where data protection is at least controversial (such as Google Maps integration).
