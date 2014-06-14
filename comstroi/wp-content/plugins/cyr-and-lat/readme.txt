=== Cyr-And-Lat ===
Contributors: necrowolf
Tags: cyrillic, latin, l10n, russian, rustolat, slugs, translations, transliteration, media, georgian, european, diacritics, muiltilanguage
Requires at least: 2.3
Tested up to: 3.4.1
Stable tag: 1.0.2

Allows to use both "old" cyrillic and "new" latin slugs at the same time. Now it works for terms (tags and categories slugs) too.

== Description ==

Converts Cyrillic characters in post, page and term slugs to Latin characters. Useful for creating human-readable URLs. After the activation changes every slug to Latin characters, but old slugs will work too.

= Features =
* Automatically converts existing post, page and term slugs on activation.
* Saves access to posts by old cyrillic slugs (prevents broken links).
* Saves existing post and page permalinks integrity.
* Performs transliteration of attachment file names.
* Includes Russian, Belarusian, Ukrainian, Bulgarian and Macedonian characters
* Transliteration table can be customized without editing the plugin itself

Base on the Cyr-To-Lat plugin by Sergey Biryukov, which based on the original Rus-To-Lat plugin by Anton Skorobogatov.

== Installation ==

1. Upload `cyrandlat` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Make sure your system has iconv set up right, or iconv is not installed at all. If you have any problems (trimmed slugs, strange characters, question marks) - please ask for support. 

== Frequently Asked Questions ==

= How can I define my own substitutions? =

Add this code to your theme's `functions.php` file:
`
function my_cyr_and_lat_table($cal_table) {
   $cal_table['Ъ'] = 'U';
   $cal_table['ъ'] = 'u';
   return $cal_table;
}
add_filter('cal_table', 'my_cyr_and_lat_table');
`

== Changelog ==

= 1.0.2 = 
* Backward сompatibility with "old" russian slugs works in terms (tags and categories) too.

= 1.0.1 =
* Fixed minor bug

= 1.0 =
* Initial release