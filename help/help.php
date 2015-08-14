<?php
//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

global	$current_screen;

$sidebar =	'<p style="font-size: larger">' . __( 'More information', 'fpw-category-thumbnails' ) . '</p>' .
			'<blockquote><a href="http://fw2s.com/fpw-category-thumbnails-plugin/" target="_blank">' . __( "Plugin's site", "fpw-category-thumbnails" ) . '</a></blockquote>' .
			'<p style="font-size: larger">' . __( 'Support', 'fpw-category-thumbnails' ) . '</p>' .
			'<blockquote><a href="http://wordpress.org/tags/fpw-category-thumbnails?forum_id=10" target="_blank">WordPress</a><br />' . 
			'<a href="http://fw2s.com/support/fpw-category-thumbnails-support/" target="_blank">FWSS</a></blockquote>'; 

$current_screen->set_help_sidebar( $sidebar );

$intro =	'<p style="font-size: larger">' . __( 'Introduction', 'fpw-category-thumbnails' ) . '</p>' .
			'<blockquote style="text-align: justify">' . __( 'Setting featured images for posts / pages could be very time consuming,', 'fpw-category-thumbnails' ) .
			' ' . __( 'especially when your media library holds hundreds of pictures.', 'fpw-category-thumbnails' ) . ' ' .
			__( 'Very often we select the same thumbnail for posts in particular category.', 'fpw-category-thumbnails' ) . ' ' .
			__( 'This plugin automates the process by inserting a thumbnail based on category / thumbnail mapping while post / page is being created or updated.', 'fpw-category-thumbnails' ) . '</blockquote></p>';

$current_screen->add_help_tab( array(
	'title'   => __( 'Introduction', 'fpw-category-thumbnails' ),
    'id'      => 'fpw-fct-help-introduction',
   	'content' => $intro,
	) );

$opts =		'<p style="font-size: larger">' . __( 'Available Options', 'fpw-category-thumbnails' ) . '</p>' .
			'<blockquote style="text-align: justify"><strong>' . __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-category-thumbnails' ) . '</strong> ' .
			'( ' . __( 'checked', 'fpw-category-thumbnails' ) . ' ) - ' . __( 'while the post is being saved the originally set thumbnail will be preserved', 'fpw-category-thumbnails' ) . '<br />' .
			'<strong>' . __( "Remove plugin's data from database on uninstall", 'fpw-category-thumbnails' ) . '</strong> ' .
			'( ' . __( 'checked', 'fpw-category-thumbnails' ) . ' ) - ' . __( "during uninstall procedure all plugin's information ( options, mappings ) will be removed from the database", "fpw-category-thumbnails" ) . '<br />' .
			'<strong>' . __( "Enable FPW Post Thumbnails", 'fpw-category-thumbnails' ) . '</strong> ' .
			'( ' . __( 'checked', 'fpw-category-thumbnails' ) . ' ) - ' . __( "activates FPW Post Thumbnails plugin's functionality", "fpw-category-thumbnails" ) . '</blockquote>';

$current_screen->add_help_tab( array(
	'title'   => __( 'Options', 'fpw-category-thumbnails' ),
	'id'      => 'fpw-fct-help-options',
	'content' => $opts,
	) );

$mapping =	'<p style="font-size: larger">' . __( 'Mapping', 'fpw-category-thumbnails' ) . '</p><blockquote style="text-align: justify">' .
			__( 'Each row of the mapping table represents a category and a thumbnail image ID assigned to it.', 'fpw-category-thumbnails' ) . ' ' .
			__( 'First column holds a category name and its ID.', 'fpw-category-thumbnails' ) . ' ' .
			__( "Second column consists of four elements: Image ID - an input field which holds thumbnail's image ID,", "fpw-category-thumbnails" ) . ' ' .
			__( "Get ID, Clear, and Refresh buttons. Third column holds thumbnail's preview.", "fpw-category-thumbnails" ) . ' ' .
			__( "Image ID can be entered manually ( if you remember it ) or by clicking on 'Get ID' button which will call 'media upload' overlay.", "fpw-category-thumbnails" ) .
			'</blockquote><p style="font-size: larger">' . __( 'Action Buttons', 'fpw-category-thumbnails' ) . '</p><blockquote>' .
			'<table style="width: 100%;"><tr><td style="text-align: left; vertical-align: middle;">' . 
			'<tr><td style="text-align: left; vertical-align: middle;">' .
			'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
			__( 'Update', 'fpw-category-thumbnails' ) . '" /></td><td>' . __( 'saves modified options and mapping to the database', 'fpw-category-thumbnails' ) .
			'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
			'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
			__( 'Apply Mapping', 'fpw-category-thumbnails' ) . '" /></td><td>' . __( 'adds thumbnails to existing posts / pages based on category mapping', 'fpw-category-thumbnails' ) .
			'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
			'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
			__( 'Remove Thumbnails', 'fpw-category-thumbnails' ) . '" /></td><td>' . __( 'removes thumbnails from all posts /pages regardless of the category', 'fpw-category-thumbnails' ) .
			'<tr><td style="text-align: left; vertical-align: middle;">' .
			'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
			__( 'Restore Thumbnails', 'fpw-category-thumbnails' ) . '" /></td><td>' . __( 'restores thumbnails backed up by the recent Remove Thumbnails action', 'fpw-category-thumbnails' ) .
			'<tr><td style="text-align: left; vertical-align: middle;">' .
			'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
			__( 'Get ID', 'fpw-category-thumbnails' ) . '" />' . '</td><td style="text-align: justify; vertical-align: middle;">' .
			__( "will call 'media upload' overlay and on return will populate 'Image ID' input box and 'Preview' area ( AJAX - without reloading screen )", "fpw-category-thumbnails" ) .
			'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
			'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
			__( 'Author', 'fpw-category-thumbnails' ) . '" />' . '</td><td style="text-align: justify; vertical-align: middle;">' .
			__( "will assign author's picture as a thumbnail for the category", "fpw-category-thumbnails" ) .
			'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' .  
			'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
			__( 'Clear', 'fpw-category-thumbnails' ) . '" /></td><td style="text-align: justify; vertical-align: middle;">' .
			__( "will enter '0' as image ID and clear 'Preview' area ( AJAX - without reloading screen )", "fpw-category-thumbnails" ) .
			'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
			'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
			__( 'Refresh', 'fpw-category-thumbnails' ) . '" /></td><td style="text-align: justify; vertical-align: middle;">' .
			__( "when clicked after entering of an image ID manually it will populate 'Preview' area ( AJAX - without reloading screen )", "fpw-category-thumbnails" ) .
			'</td></tr></table></blockquote>';

$current_screen->add_help_tab( array(
	'title'   => __( 'Mapping & Actions', 'fpw-category-thumbnails' ),
	'id'      => 'fpw-fct-help-mapping',
	'content' => $mapping,
	) );

$faq =		'<p style="font-size: larger">' . __( 'Frequently Asked Questions', 'fpw-category-thumbnails' ) . '</p><blockquote style="text-align: justify"><strong>' .
			__( 'Question:', 'fpw-category-thumbnails' ) . '</strong> ' .
			__( 'I got an ID for the image and assigned it to the category, and the plugin does not display it in posts.', 'fpw-category-thumbnails' ) . '<br /><strong>' .
			__( 'Answer:', 'fpw-category-thumbnails' ) . '</strong> ' . __( "The plugin does not display thumbnails by itself. This is your theme's role.", "fpw-category-thumbnails" ) . ' ' .
			__( 'Read this article', 'fpw-category-thumbnails' ) . ' ' .
			'<a href="http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/" target="_blank" rel="nofollow">' . 
			'New in WordPress 2.9 post thumbnail images</a> ' . 
			__( 'by', 'fpw-category-thumbnails' ) . ' Mark Jaquith ' . __( "about enabling theme's support for post thumbnails.", "fpw-category-thumbnails" ) . '<br /><br /><strong>' .
			__( 'Question:', 'fpw-category-thumbnails' ) . '</strong> ' .
			__( "I've entered ID of a picture from NextGen Gallery and thumbnail doesn't show.", "fpw-category-thumbnails" ) . '<br><strong>' .
			__( 'Answer:', 'fpw-category-thumbnails' ) . '</strong> ' .
			__( 'IDs from NextGen Gallery must be entered with ngg- prefix, so ID 230 should be entered as ngg-230.', 'fpw-category-thumbnails' ) . '<br /><br /><strong>' .
			__( 'Question:', 'fpw-category-thumbnails' ) . '</strong> ' .
			__( "What is required to use authors' pictures as thumbnails?", "fpw-category-thumbnails" ) . '<br><strong>' .
			__( 'Answer:', 'fpw-category-thumbnails' ) . '</strong> ' .
			__( "Upload authors' pictures into media library or NextGen gallery.", "fpw-category-thumbnails" ) . ' ' .
			__( "File names of authors' pictures in media library must follow this naming convention: 'autor_id.jpg' where 'id' is author's user id.", "fpw-category-thumbnails" ) . ' ' .
			__( "File names of authors' pictures in NextGen gallery must follow this naming convention: 'id.jpg' where 'id' is author's user id.", "fpw-category-thumbnails" ) . ' ' .
			__( "The name of NextGen gallery must be 'authors'.", "fpw-category-thumbnails" ) . '<br /><br /><strong>' .
			__( 'Question:', 'fpw-category-thumbnails' ) . '</strong> ' .
			__( "How to use 'fpwCategoryThumbnails::addThumbnailToPost' method?", "fpw-category-thumbnails" ) . '<br><strong>' .
			__( 'Answer:', 'fpw-category-thumbnails' ) . '</strong> ' .
			__( 'Look into', 'fpw-category-thumbnails' ) .
			' <a href="http://fw2s.com/support/fpw-category-thumbnails-documentation/public-method-fpwcategorythumbnailsaddthumbnailtopost/" target="_blank">Public method fpwCategoryThumbnails::addThumbnailToPost</a> ' . 
			__( 'topic of Documentation.', 'fpw-category-thumbnails' ) . '<br /><br /><strong>' .
			__( 'Question:', 'fpw-category-thumbnails' ) . '</strong> ' .
			__( 'Will this plugin work with JavaScript turned off?', 'fpw-category-thumbnails' ) . '<br /><strong>' .
			__( 'Answer:', 'fpw-category-thumbnails' ) . '</strong> ' .
			__( 'Yes. This plugin is functional with JavaScript turned off to comply with accessibility regulations.', 'fpw-category-thumbnails' ) . '</blockquote>';

$current_screen->add_help_tab( array(
	'title'   => __( 'FAQ', 'fpw-category-thumbnails' ),
	'id'      => 'fpw-fct-help-faq',
	'content' => $faq,
	) );
