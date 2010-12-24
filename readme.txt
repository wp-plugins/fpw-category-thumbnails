=== FPW Category Thumbnails ===
Contributors: frankpw
Donate link: 
Tags: category, thumbnail
Requires at least: 2.9.0
Tested up to: 3.0.1
Stable tag: 1.1.0

Assigns a thumbnail based on categoryid/thumbnail mapping to a post/page when
the post is created or updated.

== Description ==

**FPW Category Thumbnails** allows assigning thumbnails to post categories.
When configured it will check on create/update of the post/page if selected
category has thumbnail mapped to it and will add that thumbnail to the 
post/page.

== Installation ==

1. Upload `fpw-category-thumbnails` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Select Dashboard's Settings -> FPW Category Thumbnails and build category/thumbnail mapping
1. **WARNING:** please write down your current assignments! This update will destroy previous assignments 

== Frequently Asked Questions ==

= I've entered ID of a picture from NextGen Gallery and thumbnail doesn't show. =

IDs from NextGen Gallery must be entered with ngg- prefix, so ID 230 should be entered as ngg-230.

== Changelog ==

= 1.1.0 =
* Changed: changed thumbnails to category names mapping to thumbnails to category ids
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