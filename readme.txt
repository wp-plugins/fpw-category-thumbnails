=== FPW Category Thumbnails ===
Contributors: frankpw
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: http://fw2s.com/payments-and-donations/
Tags: category, thumbnail, plugin
Requires at least: 3.3
Tested up to: 4.0
Stable tag: 1.6.2

Assigns a thumbnail based on categoryid/thumbnail mapping to a post / 
page when the post is created or updated. Built-in FPW Post Thumbnails.

== Description ==
Setting featured images for posts / pages could be very time 
consuming, especially when your media library holds hundreds of 
pictures. Very often we select the same thumbnail for posts in 
particular category. This plugin automates the process by inserting a 
thumbnail based on category / thumbnail mapping while post / page is
being created or updated. Now bundled with FPW Post Thumbnails.

Note: please remember that your theme must support post thumbnails.

== Installation ==

1. Upload `fpw-category-thumbnails` folder to the `/wp-content/plugins/` directory
1. If standalone plugin FPW Post Thumbnails is installed deactivate and remove it.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Select Dashboard's Appearance -> FPW Category Thumbnails and build category/thumbnail mapping

== Frequently Asked Questions ==

= I got an ID for the image and assigned it to the category, and the plugin does not display it in posts. =
The plugin does not display thumbnails by itself. This is your theme's role.

= I've entered ID of a picture from NextGen Gallery and thumbnail doesn't show. =
IDs from NextGen Gallery must be entered with ngg- prefix, so ID 230 should be entered as ngg-230.

= What is required to use authors' pictures as thumbnails? = 
Upload authors' pictures into media library or NextGen gallery. 
File names of authors' pictures in media library must follow this naming convention: 'autor_id.jpg' where 'id' is author's user id. 
File names of authors' pictures in NextGen gallery must follow this naming convention: 'id.jpg' where 'id' is author's user id.
The name of NextGen gallery must be 'authors'.

= How to use 'fpwCategoryThumbnails::addThumbnailToPost' method? =
Look into [Public method fpwCategoryThumbnails::addThumbnailToPost](http://fw2s.com/support/fpw-category-thumbnails-documentation/public-method-fpwcategorythumbnailsaddthumbnailtopost/) topic of Documentation. 

= Will both plugins work with JavaScript turned off? =
Yes. Both plugins are functional with JavaScript turned off to comply with accessibility regulations.

== Screenshots ==

1. FPW Category Thumbnails - Settings - JavaScript on
1. FPW Category Thumbnails - Settings - JavaScript off
1. FPW Post Thumbnails - Settings - JavaScript on
1. FPW Post Thumbnails - Settings - JavaScript off

== Changelog ==

= 1.6.3 =
* CSS improvements

= 1.6.2 =
* Fixed translation plurals
* Added plugin icon 

= 1.6.1 =
* FPW Category Thumbnails and FPW Post Thumbnails: fixed version check not working when major versions changes 

= 1.6.0 =
* FPW Category Thumbnails: fixed Get Image ID malformed dialog since WordPress 3.8 for all flavours of NextGen Gallery

= 1.5.9 =
* FPW Category Thumbnails: images compatible with Media Library, NextGEN Gallery up to 1.9.13, NextCellent Gallery, and NextGEN Gallery by Photocrati
* FPW Category Thumbnails: optimized images display to rescale only when necessary
* FPW Post Thumbnails: optimized images display to rescale only when necessary
* FPW Post Thumbnails: standarized 'alt' and 'title' attributes of thumbnails
* FPW Post Thumbnails: changed contextual help
* text domain changed for compatibility with language packs and automated updates

= 1.5.8 =
* FPW Category Thumbnails: modified contextual help
* FPW Post Thumbnails: removed 'hide-if-no-js' class from both 'Copy' buttons
* FPW Post Thumbnails: modified contextual help

= 1.5.7 =
* FPW Category Thumbnails: added "Thumbnail" column to Posts -> Categories admin screen
* FPW Category Thumbnails: fixed bug in JavaScript detection handler
* FPW Post Thumbnails: added JavaScript detection handler
* Removing plugins' data from database on uninstallation handled independently from each other

= 1.5.6 =
* Removed built-in front end stylesheet and added dynamic CSS instead ( FPW Post Thumbnails )

= 1.5.5 =
* Added choice of base dimension for image scaling ( FPW Post Thumbnails )
* Removed built-in stylesheet for preview and added dynamic CSS instead ( FPW Post Thumbnails )

= 1.5.4 =
* Fixed mapping not being applied when post/page is created/updated in user context ( eg. xmlrpc )

= 1.5.3 =
* Added option to enable / disable built-in FPW Post Thumbnails

= 1.5.2 =
* Added check to prevent activation if standalone version of FPW Post Thumbnails plugin is installed and active

= 1.5.1 =
* Dropped support for WordPress versions lower than 3.3
* Bundled with FPW Post Thumbnails plugin
* Moved to Appearance menu of the Dashboard
* Loading JavaScript into the footer

= 1.5.0 =
* Major release
* Dropped support for WordPress versions lower than 3.1
* Uses WP_List_Table descendant to display category / thumbnail mapping
* Full AJAX implementation of all operations
* Ensured proper operation when JavaScript is disabled
* Support for downloading of translation files from plugin's repository

= 1.4.9 =
* Last version supporting WordPress versions from 2.9 and lower than 3.1
* Added code to prevent plugin being activated when WordPress version is lower than 2.9
* Exposed method 'fpwCategoryThumbnails::addThumbnailToPost' for both back and front end
* Minor code modifications

= 1.4.8 =
* Added support for pointers ( WP 3.3+ )
* Minor bugs fixes

= 1.4.7 =
* Fixed issues with PHP notices about uninitialized $_POST index
* Fixed external links to plugin's website ( changed permalinks at fw2s.com ) 

= 1.4.6 =
* New feature: authors' pictures as thumbnails
* Updated .pot file for translations

= 1.4.5 =
* Added missing strings for translations
* Fixed loading of text domain for translations
* Included Polish translation

= 1.4.4 =
* Change to make it 100% predictable which thumbnail will be used in case of multiple categories selection

= 1.4.3 =
* Optimized code to use less resources

= 1.4.2 =
* Changed support links to reflect changes to FWSS site

= 1.4.1 =
* Prevents adding thumbnails to drafts
* Adding actions and filters to back end only 

= 1.4.0 =
* Maintenance release
* Recoded using classes to prevent naming conflicts

= 1.3.9 =
* Optimized for WordPress 3.3
* Code cleanup

= 1.3.8 =
* New option ( width of 'Image ID' column )
* New ability to AJAX-ed refresh of 'Preview' column after manual insertion of ID
* Added missing text for Help screens
* Various code modifications

= 1.3.7 =
* Code improvements
* Full localization of javascripts
* WordPress 3.3 ready

= 1.3.6 =
* Minified javascripts
* Fixed problem of loading scripts on all admin pages

= 1.3.5 =
* Added confirmation dialogs for bulk actions

= 1.3.4 =
* Improved code for bulk actions to reduce memory use
* Modified media-upload to simplify image ID extraction
* Added option to show / hide dashboard widget
* Added AJAX preview of thumbnails on settings screen

= 1.3.3 =
* Added FPW Plugins to admin bar for WordPress 3.1+

= 1.3.2 =
* Fixed problem with extraction of image id from NextGEN library

= 1.3.1 =
* Fixed problem with javascripts conflicts
* Added a button to clear assigned image id

= 1.3.0 =
* Major release
* Added support for media upload
* Code cleanup

= 1.2.3 =
* Changed code to properly recognize location of installation core files
* Security related changes
* Visual enhancements to Settings page

= 1.2.2 =
* Minor code improvements. Extensive tests on WP 3.1.1

= 1.2.1 =
* Fixed a bug ( new posts only ) causing default category thumbnail being set instead of the one of selected category when do not overwrite flag is on

= 1.2.0 =
* Added checks to avoid operations on post revisions

= 1.1.9 =
* Added plugin's dashboard info widget

= 1.1.8 =
* Changed minimum role required to manage settings
* Moved table of available images to contextual help

= 1.1.7 =
* Combined default contextual help content and plugin's contextual help content
* Updated translations

= 1.1.6 =
* Moved Description and Instructions blocks to contextual help

= 1.1.5 =
* Added table of available images

= 1.1.4 =
* Added plugin activation action to apply proper extension to uninstall( .txt/ .php) file based on option setting in database

= 1.1.3 =
* Plugin code optimization
* Minor fixes

= 1.1.2 =
* Added: update information line to plugin's meta block which shows only when update is detected

= 1.1.1 =
* Added: immediate action to apply all mappings to existing posts/pages
* Added: immediate action to unconditionally remove thumbnails from existing posts/pages

= 1.1.0 =
* Changed: changed from thumbnails to category names mapping to thumbnails to category ids mapping
* Changed: category listing shows category names and ids reflecting hierarchy of categories 

= 1.0.4 =
* Added: change name of uninstall file based on cleanup flag

= 1.0.3 =
* Added: option to prevent overwriting if post/page has thumbnail allready
* Updated: translations

= 1.0.2 =
* Added: link to Settings into plugin's action links
* Added: database cleanup on uninstall
* Updated: translations

= 1.0.1 =
* Added: check if current theme supports post thumbnails
* Updated: translations

= 1.0 =
* Initial release.