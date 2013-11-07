<?php
		//	prevent direct access
		if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
			die( "Direct access to this script is forbidden!" );

		global	$current_screen;

		$sidebar =	'<p style="font-size: larger">' . __( 'More information', 'fpw-category-thumbnails' ) . '</p>' .
					'<blockquote><a href="http://fw2s.com/themes-and-fpw-category-thumbnails/" target="_blank">' . __( "Plugin's site", "fpw-category-thumbnails" ) . '</a></blockquote>' .
					'<p style="font-size: larger">' . __( 'Support', 'fpw-category-thumbnails' ) . '</p>' .
					'<blockquote><a href="http://wordpress.org/support/plugin/fpw-post-thumbnails" target="_blank">WordPress</a><br />' . 
					'<a href="http://fw2s.com/support/fpw-post-thumbnails-support/" target="_blank">FWSS</a></blockquote>'; 

		$current_screen->set_help_sidebar( $sidebar );

		$intro =	'<p style="font-size: larger">' . __( 'Introduction', 'fpw-category-thumbnails' ) . '</p>' . '<blockquote>' . '<p style="text-align: justify;">' .
					__( 'There are many nice themes not providing any support for', 'fpw-category-thumbnails' ) . ' <em>' .
					__( 'post thumbnails', 'fpw-category-thumbnails' ) . '</em> ( ' .
					__( 'now called', 'fpw-category-thumbnails' ) . ' <em>' .
					__( 'featured images', 'fpw-category-thumbnails' ) . '</em> ). ' .
					__( 'Some themes provide such support but do not display them.', 'fpw-category-thumbnails' ) . ' ' .
					__( 'Then we have three choices.', 'fpw-category-thumbnails' ) . ' ' .
					__( "First is to find another theme supporting and displaying " . 
						"thumbnails, second - forget about thumbnails, and the third " . 
						"is to get our hands dirty. The last one requires modifications " . 
						"to the current theme's files ( not very elegant and practical " . 
						"as the next theme's upgrade will wipe out those modifications " . 
						") or at least creating a child theme.", "fpw-category-thumbnails" ) . ' <strong>' .
					__( 'FPW Post Thumbnails', 'fpw-category-thumbnails' ) . '</strong> ' .
					__( 'plugin makes these choices obsolete. It will add support for ' . 
						'thumnails, display them, and give you more control over their appearance.', 'fpw-category-thumbnails' ) . ' ' .
					__( "And what's most important it will not modify the current theme in any way.", "fpw-category-thumbnails" ) .
					'</p></blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Introduction', 'fpw-category-thumbnails' ),
    		'id'      => 'fpw-fpt-help-introduction',
   			'content' => $intro,
		) );

		$opts =		'<p style="font-size: larger">' . __( 'Available Options', 'fpw-category-thumbnails' ) . '</p><blockquote>' .
					'<p style="text-align: justify;"><strong>' . 
					__( "Remove plugin's data from database on uninstall", 'fpw-category-thumbnails' ) . '</strong> ' .
					'( ' . __( 'checked', 'fpw-category-thumbnails' ) . ' ) - ' .
					__( "during uninstall procedure all plugin's information will be removed from the database", "fpw-category-thumbnails" ) . '<br /><strong>' .
					__( 'Add this plugin to the Admin Bar', 'fpw-category-thumbnails' ) . '</strong> ' .
					'( ' . __( 'checked', 'fpw-category-thumbnails' ) . ' ) - ' .
					__( "plugin's link to settings page will be added to the Admin Bar", "fpw-category-thumbnails" ) .
					'</p></blockquote>' . 
					'<p style="font-size: larger">' . __( 'Action Buttons', 'fpw-category-thumbnails' ) . '</p><blockquote>' .
					'<table style="width: 100%;"><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
					__( 'Update', 'fpw-category-thumbnails' ) . '" /></td><td>' . __( 'saves modified data to the database', 'fpw-category-thumbnails' ) .
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
					__( 'Copy', 'fpw-category-thumbnails' ) . ' &raquo;' . '" />' . '</td><td style="text-align: justify; vertical-align: middle;">' .
					__( "copies all data from the left to the right panel", "fpw-category-thumbnails" ) .
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="&laquo; ' .
					__( 'Copy', 'fpw-category-thumbnails' ) . '" />' . '</td><td style="text-align: justify; vertical-align: middle;">' .
					__( "copies all data from the right to the left panel", "fpw-category-thumbnails" ) .
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' .  
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-category-thumbnails' ) . '" value="' .
					__( 'Preview', 'fpw-category-thumbnails' ) . '" /></td><td style="text-align: justify; vertical-align: middle;">' .
					__( "shows preview of the thumbnail and its layout ( hidden if JavaScript is turned off )", "fpw-category-thumbnails" ) .
					'</td></tr></table></blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Options & Actions', 'fpw-category-thumbnails' ),
    		'id'      => 'fpw-fpt-help-options',
	   		'content' => $opts,
		) );

		$panels =	'<p style="font-size: larger">' . __( 'Panels', 'fpw-category-thumbnails' ) . '</p><blockquote>' .
					'<p style="text-align: justify;"><strong>' . 
					__( 'Thumbnails for Content enabled:', 'fpw-category-thumbnails' ) . '</strong> ' .
					'( ' . __( 'checked', 'fpw-category-thumbnails' ) . ' ) - ' .
					__( 'enables thumbnails for contents', 'fpw-category-thumbnails' ) . '<br /><strong>' .
					__( 'Thumbnails for Excerpt enabled:', 'fpw-category-thumbnails' ) . '</strong> ' .
					'( ' . __( 'checked', 'fpw-category-thumbnails' ) . ' ) - ' .
					__( 'enables thumbnails for excerpts', 'fpw-category-thumbnails' ) . '</p>' .
					'<p style="text-align: justify;"><strong>width</strong> ' . __( 'and', 'fpw-category-thumbnails' ) . ' <strong>height</strong> - ' .
					__( 'width and height of thumbnails', 'fpw-category-thumbnails' ) . '<br /><strong>scaling base</strong> - ' .
					__( 'base dimension for scaling', 'fpw-category-thumbnails' ) . '<br /><strong>float</strong> - ' .
					__( 'position of thumbnails relative to the content ( excerpt )', 'fpw-category-thumbnails' ) .
					'</p><p style="text-align: justify;"><strong>border</strong> ' . 
					'( ' . __( 'checked', 'fpw-category-thumbnails' ) . ' ) - ' .
					__( 'thumbnails will have a border and next four parameters will be applied', 'fpw-category-thumbnails') .
					'<br /><strong>border-radius</strong> - ' . 
					__( 'if > 0 then the border will have rounded corners with the radius ' . 
						'of corners in pixels equal to the specified value', 'fpw-category-thumbnails' ) .
					'<br /><strong>border-width</strong> - ' . __( 'thickness of the border in pixels', 'fpw-category-thumbnails' ).
					'<br /><strong>border-color</strong> - ' . __( 'color of the border', 'fpw-category-thumbnails' ) . ' ( ' .
					__( 'selection with JavaScript Color Wheel', 'fpw-category-thumbnails' ) . ' )' .
					'<br /><strong>background-color</strong> - ' . __( "thumbnails' background color", "fpw-category-thumbnails" ) . ' ( ' .
					__( 'selection with JavaScript Color Wheel', 'fpw-category-thumbnails' ) . ' )' .
					'</p><p style="text-align: justify;"><strong>padding-xxx and margin-xxx</strong> - ' . 
					__( 'these are standard padding and margin parameters', 'fpw-category-thumbnails' ) . '</p></blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Panels', 'fpw-category-thumbnails' ),
    		'id'      => 'fpw-fpt-help-panels',
	   		'content' => $panels,
		) );

		$faq =		'<p style="font-size: larger">' . __( 'Frequently Asked Questions', 'fpw-category-thumbnails' ) . '</p><blockquote style="text-align: justify"><strong>' .
					__( 'Question:', 'fpw-category-thumbnails' ) . '</strong> ' .
					__( 'Can I use this plugin if my theme supports and displays thumbnails?', 'fpw-category-thumbnails' ) . '<br /><strong>' .
					__( 'Answer:', 'fpw-category-thumbnails' ) . '</strong> ' .
					__( "If the theme displays thumbnails for both the content and excerpts I would not recommended it as you would get two thumbnails displayed. However I can imagine one exception. The theme displays thumbnails for full content but not for excerpts or the other way around. The plugin will display thumbnails for the part not being displayed by the theme not adding thumbnails to the other part.", "fpw-category-thumbnails" ) . '<br /><br /><strong>' .
					__( 'Question:', 'fpw-category-thumbnails' ) . '</strong> ' .
					__( 'Will this plugin work with JavaScript turned off?', 'fpw-category-thumbnails' ) . '<br /><strong>' .
					__( 'Answer:', 'fpw-category-thumbnails' ) . '</strong> ' .
					__( 'Yes. This plugin is functional with JavaScript turned off to comply with accessibility regulations.', 'fpw-category-thumbnails' ) .
					'</blockqoute>'; 

		$current_screen->add_help_tab( array(
   			'title'   => __( 'FAQ', 'fpw-category-thumbnails' ),
    		'id'      => 'fpw-fpt-help-faq',
	   		'content' => $faq,
		) );
?>