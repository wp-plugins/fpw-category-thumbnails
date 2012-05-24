<?php
		global	$current_screen;
		
		$sidebar =	'<p style="font-size: larger">' . __( 'More information', 'fpw-fct' ) . '</p>' . 
					'<blockquote><a href="http://fw2s.com/fpw-category-thumbnails-plugin/" target="_blank">' . __( "Plugin's site", "fpw-fct" ) . '</a></blockquote>' . 
					'<p style="font-size: larger">' . __( 'Support', 'fpw-fct' ) . '</p>' . 
					'<blockquote><a href="http://wordpress.org/tags/fpw-category-thumbnails?forum_id=10" target="_blank">WordPress</a><br />' . 
					'<a href="http://fw2s.com/support/fpw-category-thumbnails-support/" target="_blank">FWSS</a></blockquote>'; 
			
		$current_screen->set_help_sidebar( $sidebar );
			
		$intro =	'<p style="font-size: larger">' . __( 'Introduction', 'fpw-fct' ) . '</p>' . 
					'<blockquote style="text-align: justify">' . __( 'Setting featured images for posts / pages could be very time consuming,', 'fpw-fct' ) . 
					' ' . __( 'especially when your media library holds hundreds of pictures.', 'fpw-fct' ) . ' ' . 
					__( 'Very often we select the same thumbnail for posts in particular category.', 'fpw-fct' ) . ' ' . 
					__( 'This plugin automates the process by inserting a thumbnail based on category / thumbnail mapping while post / page is being created or updated.', 'fpw-fct' ) . '</blockquote></p>' . 
					'<p style="font-size: larger">' . __( 'Note', 'fpw-fct' ) . '</p>' . 
					'<blockquote style="text-align: justify">' . __( 'Please remember that the active theme must support post thumbnails.', 'fpw-fct' ) . 
					'</blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Introduction', 'fpw-fct' ),
    		'id'      => 'fpw-fct-help-introduction',
   			'content' => $intro,
		) );
			
		$opts =		'<p style="font-size: larger">' . __( 'Available Options', 'fpw-fct' ) . '</p>' . 
					'<blockquote style="text-align: justify"><strong>' . __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) . '</strong> ' . 
					'( ' . __( 'checked', 'fpw-fct' ) . ' ) - ' . __( 'while the post is being saved the originally set thumbnail will be preserved', 'fpw-fct' ) . '<br />' . 
					'<strong>' . __( "Remove plugin's data from database on uninstall", 'fpw-fct' ) . '</strong> ' . 
					'( ' . __( 'checked', 'fpw-fct' ) . ' ) - ' . __( "during uninstall procedure all plugin's information ( options, mappings ) will be removed from the database", "fpw-fct" ) . '<br />' . 
					'<strong>' . __( 'Add this plugin to the Admin Bar', 'fpw-fct' ) . '</strong> ' . 
					'( ' . __( 'checked', 'fpw-fct' ) . ' ) - ' . __( "the plugin's link to its settings page will be added to the Admin Bar", "fpw-fct" ) . '</blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Options', 'fpw-fct' ),
    		'id'      => 'fpw-fct-help-options',
	   		'content' => $opts,
		) );

		$mapping =	'<p style="font-size: larger">' . __( 'Mapping', 'fpw-fct' ) . '</p><blockquote style="text-align: justify">' . 
					__( 'Each row of the mapping table represents a category and a thumbnail image ID assigned to it.', 'fpw-fct' ) . ' ' . 
					__( 'First column holds a category name and its ID.', 'fpw-fct' ) . ' ' . 
					__( "Second column consists of four elements: Image ID - an input field which holds thumbnail's image ID,", "fpw-fct" ) . ' ' . 
					__( "Get ID, Clear, and Refresh buttons. Third column holds thumbnail's preview.", "fpw-fct" ) . ' ' . 
					__( "Image ID can be entered manually ( if you remember it ) or by clicking on 'Get ID' button which will call 'media upload' overlay.", "fpw-fct" ) . 
					'</blockquote><p style="font-size: larger">' . __( 'Action Buttons', 'fpw-fct' ) . '</p><blockquote>' . 
					'<table style="width: 100%;"><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Get ID', 'fpw-fct' ) . '" />' . '</td><td style="text-align: justify; vertical-align: middle;">' .  
					__( "will call 'media upload' overlay and on return will populate 'Image ID' input box and 'Preview' area ( AJAX - without reloading screen )", "fpw-fct" ) . 
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' .
					__( 'Author picture', 'fpw-fct' ) . '" />' . '</td><td style="text-align: justify; vertical-align: middle;">' .
					__( "will assign author's picture as a thumbnail for the category", "fpw-fct" ) .
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' .  
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Clear', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle;">' . 
					__( "will enter '0' as image ID and clear 'Preview' area ( AJAX - without reloading screen )", "fpw-fct" ) . 
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Refresh', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle;">' . 
					__( "when clicked after entering of an image ID manually it will populate 'Preview' area ( AJAX - without reloading screen )", "fpw-fct" ) . 
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Update', 'fpw-fct' ) . '" /></td><td>' . __( 'saves modified options and mapping to the database', 'fpw-fct' ) .  
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Apply Mapping', 'fpw-fct' ) . '" /></td><td>' . __( 'adds thumbnails to existing posts / pages based on category mapping', 'fpw-fct' ) . 
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Remove Thumbnails', 'fpw-fct' ) . '" /></td><td>' . __( 'removes thumbnails from all posts /pages regardless of the category', 'fpw-fct' ) . 
					'</td></tr></table></blockquote>';
			
		$current_screen->add_help_tab( array(
   			'title'   => __( 'Mapping & Actions', 'fpw-fct' ),
    		'id'      => 'fpw-fct-help-mapping',
	   		'content' => $mapping,
		) );
			
		$faq =		'<p style="font-size: larger">' . __( 'Frequently Asked Questions', 'fpw-fct' ) . '</p><blockquote style="text-align: justify"><strong>' . 
					__( 'Question:', 'fpw-fct' ) . '</strong> ' .
					__( 'I got an ID for the image and assigned it to the category, and the plugin does not display it in posts.', 'fpw-fct' ) . '<br /><strong>' . 
					__( 'Answer:', 'fpw-fct' ) . '</strong> ' . __( "The plugin does not display thumbnails by itself. This is your theme's role.", "fpw-fct" ) . ' ' . 
					__( 'Read this article', 'fpw-fct' ) . ' ' . 
					'<a href="http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/" target="_blank" rel="nofollow">' . 
					'New in WordPress 2.9 post thumbnail images</a> ' . 
					__( 'by', 'fpw-fct' ) . ' Mark Jaquith ' . __( "about enabling theme's support for post thumbnails.", "fpw-fct" ) . '<br /><br /><strong>' . 
					__( 'Question:', 'fpw-fct' ) . '</strong> ' . 
					__( "I've entered ID of a picture from NextGen Gallery and thumbnail doesn't show.", "fpw-fct" ) . '<br><strong>' . 
					__( 'Answer:', 'fpw-fct' ) . '</strong> ' . 
					__( 'IDs from NextGen Gallery must be entered with ngg- prefix, so ID 230 should be entered as ngg-230.', 'fpw-fct' ) . '<br /><br /><strong>' .
					__( 'Question:', 'fpw-fct' ) . '</strong> ' . 
					__( "What is required to use authors' pictures as thumbnails?", "fpw-fct" ) . '<br><strong>' . 
					__( 'Answer:', 'fpw-fct' ) . '</strong> ' . 
					__( "Upload authors' pictures into media library or NextGen gallery.", "fpw-fct" ) . ' ' . 
					__( "File names of authors' pictures in media library must follow this naming convention: 'autor_id.jpg' where 'id' is author's user id.", "fpw-fct" ) . ' ' . 
					__( "File names of authors' pictures in NextGen gallery must follow this naming convention: 'id.jpg' where 'id' is author's user id.", "fpw-fct" ) . ' ' .
					__( "The name of NextGen gallery must be 'authors'.", "fpw-fct" ) . '<br /><br /><strong>' . 
					__( 'Question:', 'fpw-fct' ) . '</strong> ' . 
					__( "How to use 'fpwCategoryThumbnails::addThumbnailToPost' method?", "fpw-fct" ) . '<br><strong>' . 
					__( 'Answer:', 'fpw-fct' ) . '</strong> ' . 
					__( 'Look into', 'fpw-fct' ) . 
					' <a href="http://fw2s.com/support/fpw-category-thumbnails-documentation/public-method-fpwcategorythumbnailsaddthumbnailtopost/" target="_blank">Public method fpwCategoryThumbnails::addThumbnailToPost</a> ' . 
					__( 'topic of Documentation.', 'fpw-fct' ) . '</blockquote>'; 
			
		$current_screen->add_help_tab( array(
   			'title'   => __( 'FAQ', 'fpw-fct' ),
    		'id'      => 'fpw-fct-help-faq',
	   		'content' => $faq,
		) );
?>