for Drupal 7
Getlocations will provide a Google maps API version 3 enabled map on which to
display markers of locations found in location-enabled content-types.

INSTALL
Before installing getlocations please ensure that you have the libraries module installed

You can fetch markers from dropbox:
As tarballs:
http://dl.dropbox.com/u/41489105/Drupal/getlocations/getlocations-markers.tar.gz (required)
http://dl.dropbox.com/u/41489105/Drupal/getlocations/getlocations-markers-extra.tar.gz

As zipfles:
http://dl.dropbox.com/u/41489105/Drupal/getlocations/getlocations-markers.zip (required)
http://dl.dropbox.com/u/41489105/Drupal/getlocations/getlocations-markers-extra.zip

Download the file(s) and place them into your libraries folder so you have
a path something like this:
sites/all/libraries/getlocations/markers

The 'extra' files contain numbered and letter markers.
You can optionally add these if you need them.

CONFIGURE
You should configure Getlocations by visiting admin/config/services/getlocations.

USAGE
Getlocations maps can be displayed per node, eg "/getlocations/node/xxx"
will display all the locations associated with that node.

They can also be displayed per content-type, so if your content-type
has a machine name 'venue' you can show them all with
"/getlocations/type/venue".

With the above path you can add another two parameters which must be a
location key/value pair, so "/getlocations/type/venue/city/london" will
give you all the locations in London. The keys might typically be

lid
name
street
additional
city
province
postal_code
country
latitude
longitude
province_name
country_name

If you need more complex things use Views.

TODO
add location-enabled user ids once location handles users properly

You can display a list of location ids with something like
"getlocations/lids/1,2,3,4"
and a list of nodes with
"getlocations/nids/1,2,3,4"

There are some Views, disabled by default.
The getlocations View will provide a block that will appear when a location
enabled node is being shown. The block contains a link to a map.


Automatic Panning
This setting has 4 possibilities:
"None" is No panning.
This uses the default zoom and map center.

"Pan" keeps the markers in the Viewport.
This will try to fit the markers in by panning to them but uses
the default zoom.

"Pan and zoom" fits the markers to the Viewport.
This zooms in as far as it can and will fit all the markers onto the map.
This setting should only be used if you have less than 30 - 50 markers.

"Set Center" places the markers in the middle of the map.
This is similar to "Pan" but uses averaging to define the map center.


Which of these settings is best for your usecase depends on how many markers
you have and their 'spread', eg are they all in one region or spread out all
over the world.

Showing more than 30 -50 markers could lead to browser crash, remember that
it is the client browser not the server that is doing the work so you need to
test on slow machines and basic handheld devices to determine the best
settings for your site.

If you have hundreds of markers make sure that the markermanager is enabled and
that the markers are not all in the viewport at once, at least not on the map
as it is initially set up.
This applies especially to the
"/getlocations/type/zzz"
map which shows all the markers (of a given content-type)

Alternately you can use the MarkerClusterer feature, useful if you have many markers
near each other.

If you have the Colorbox module installed and enabled in Get Locations
you can place any of the above paths in a colorbox iframe by replacing
'getlocations' with 'getlocations_box'.
To enable this for a link you can use the 'colorbox-load' method,
make sure that this feature has been enabled in colorbox
and use a url like this:
<a href="/getlocations_box/node/xxx?width=700&height=600&iframe=true" class="colorbox-load">See map</a>

or (advanced use) by adding rel="getlocationsbox" to the url, eg
<a href="/getlocations_box/node/xxx" rel="getlocationsbox">See map</a>

The last method uses the settings in admin/config/services/getlocations for
colorbox and uses its own colorbox event handler, see getlocations_colorbox.js.
You can define your own event handlers in your theme's javascript.
'getlocations_box' has it's own template, getlocations_box.tpl.php which can be
copied over to your theme's folder and tweaked there.

The InfoBubble javascript library is included and can be configured by copying
js/infobubble_options.txt to js/infobubble_options.js and editing that.

Getlocations now has hook_getlocations_markerdir()
from jhm http://drupal.org/user/15946
This hook allows other modules to add their own marker collections.
example:

function mymodule_getlocations_markerdir() {
  $markers = drupal_get_path('module', 'mymodule') . '/mymarkers';
  return $markers;
}

The above example requires the module mymodule to have a folder
mymodule/mymarkers which contains the bespoke markersets.
These will be added to the available markers when the
Marker Cache is regenerated.

In a Getlocations View, in Format: Getlocations | Settings,
if you have selected InfoBubble or InfoWindow in the Marker action dropdown
you will see a checkbox 'Replace default content' which when checked will
provide a list of available fields to use instead of the default content.

Geofield and Geolocation support
Getlocations provides some default Views to work with the
Geofield and Geolocation modules but if you want maps you will have to do
some tweaking.

Geofield:
In the Getlocations view, add a block and in 'Fields'
add Content: Location (or whatever you called it),
Select Formatter: Latitude only
Select Data options: Use full geometry
Select Format: decimal degrees
Under 'More' give it an Administrative title of 'latitude'

Repeat for longitude

Then add Content: Type
You should now have the following in Fields:
Content: Title
Content: Nid (make sure this has 'Rewrite the output of this field' and 'Output this field as a link' switched off)
latitude
longitude
Content: Type

They should be in that order.
exclude all from display except Content: Nid

Then add to Filter criteria
Content: Type and select the content type(s) you want

Under Format: select Getlocations and set up the map
Edit the Block title to whatever suits you
Save the view, enable the block and test.

You can do much the same in 'Getlocations links' View

Geolocation:
In the Getlocations view, add a block and in 'Fields'
add Content: Location (or whatever you called it),
Select Formatter: Latitude text-based formatter
Under 'More' give it an Administrative title of 'latitude'

Repeat for longitude

Then add Content: Type
You should now have the following in Fields:
Content: Title
Content: Nid (make sure this has 'Rewrite the output of this field' and 'Output this field as a link' switched off)
latitude
longitude
Content: Type

They should be in that order.
exclude all from display except Content: Nid

Then add to Filter criteria
Content: Type and select the content type(s) you want

Under Format: select Getlocations and set up the map
Edit the Block title to whatever suits you
Save the view, enable the block and test.

You can do much the same in 'Getlocations links' View

Addressfield support.
If the Addressfield module is supplying an address in conjunction with Geofield or Geolocation
the address will be used to populate the InfoWindow or InfoBubble.

Theming.
Getlocations pages can be themed by copying the relevant function to your theme's template.php,
renaming it in the usual manner.
eg
theme_getlocations_info() becomes MYTHEME_getlocations_info() where MYTHEME is the name of your theme.
You can edit it there to suit your needs.

These functions can be found in the file getlocations.theme.inc

Theming the content of InfoWindow or InfoBubble.
This is done with function theme_getlocations_info()

Theming the map display.
This is done with function theme_getlocations_show()

Theming the Getlocations settings form.
This is done with function theme_getlocations_settings_form()

Theming the Getlocations options form in Views.
This is done with function theme_getlocations_plugin_style_map_options_form()

function template_preprocess_getlocations_box() and
function template_preprocess_getlocations_marker_box() are for use in conjunction
with the colorbox module and use the files getlocations_box.tpl.php and
getlocations_marker_box.tpl.php respectively.

More information on theming can be found on http://drupal.org/documentation/theme

