<?php
		//	prevent direct access
		if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
			die( "Direct access to this script is forbidden!" );

		global	$current_screen;

		$sidebar =	'<p style="font-size: larger">' . __( 'More information', 'fpw-fct' ) . '</p>' . 
					'<blockquote><a href="http://fw2s.com/themes-and-fpw-category-thumbnails/" target="_blank">' . __( "Plugin's site", "fpw-fct" ) . '</a></blockquote>' . 
					'<p style="font-size: larger">' . __( 'Support', 'fpw-fct' ) . '</p>' . 
					'<blockquote><a href="http://wordpress.org/support/plugin/fpw-post-thumbnails" target="_blank">WordPress</a><br />' . 
					'<a href="http://fw2s.com/support/fpw-post-thumbnails-support/" target="_blank">FWSS</a></blockquote>'; 

		$current_screen->set_help_sidebar( $sidebar );

		$intro =	'<p style="font-size: larger">' . __( 'Introduction', 'fpw-fct' ) . '</p>' . '<blockquote>' . '<p style="text-align: justify;">' .
					__( 'There are many nice themes not providing any support for', 'fpw-fct' ) . ' <em>' .  
					__( 'post thumbnails', 'fpw-fct' ) . '</em> ( ' . 
					__( 'now called', 'fpw-fct' ) . ' <em>' . 
					__( 'featured images', 'fpw-fct' ) . '</em> ). ' . 
					__( 'Some themes provide such support but do not display them.', 'fpw-fct' ) . ' ' . 
					__( 'Then we have three choices.', 'fpw-fct' ) . ' ' . 
					__( "First is to find another theme supporting and displaying " . 
						"thumbnails, second - forget about thumbnails, and the third " . 
						"is to get our hands dirty. The last one requires modifications " . 
						"to the current theme's files ( not very elegant and practical " . 
						"as the next theme's upgrade will wipe out those modifications " . 
						") or at least creating a child theme.", "fpw-fct" ) . ' <strong>' . 
					__( 'FPW Post Thumbnails', 'fpw-fct' ) . '</strong> ' . 
					__( 'plugin makes these choices obsolete. It will add support for ' . 
						'thumnails, display them, and give you more control over their appearance.', 'fpw-fct' ) . ' ' . 
					__( "And what's most important it will not modify the current theme in any way.", "fpw-fct" ) . 
					'</p></blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Introduction', 'fpw-fct' ),
    		'id'      => 'fpw-fpt-help-introduction',
   			'content' => $intro,
		) );

		$opts =		'<p style="font-size: larger">' . __( 'Available Options', 'fpw-fct' ) . '</p><blockquote>' . 
					'<p style="text-align: justify;"><strong>' . 
					__( "Remove plugin's data from database on uninstall", 'fpw-fct' ) . '</strong> ' . 
					'( ' . __( 'checked', 'fpw-fct' ) . ' ) - ' . 
					__( "during uninstall procedure all plugin's information will be removed from the database", "fpw-fct" ) . '<br /><strong>' . 
					__( 'Add this plugin to the Admin Bar', 'fpw-fct' ) . '</strong> ' . 
					'( ' . __( 'checked', 'fpw-fct' ) . ' ) - ' .
					__( "plugin's link to settings page will be added to the Admin Bar", "fpw-fct" ) . 
					'</p></blockquote>' . 
					'<p style="font-size: larger">' . __( 'Action Buttons', 'fpw-fct' ) . '</p><blockquote>' . 
					'<table style="width: 100%;"><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Update', 'fpw-fct' ) . '" /></td><td>' . __( 'saves modified data to the database', 'fpw-fct' ) .  
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Copy', 'fpw-fct' ) . ' &raquo;' . '" />' . '</td><td style="text-align: justify; vertical-align: middle;">' .  
					__( "copies all data from the left to the right panel", "fpw-fct" ) . 
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="&laquo; ' . 
					__( 'Copy', 'fpw-fct' ) . '" />' . '</td><td style="text-align: justify; vertical-align: middle;">' .
					__( "copies all data from the right to the left panel", "fpw-fct" ) .
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' .  
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Preview', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle;">' . 
					__( "shows preview of the thumbnail and its layout ( hidden if JavaScript is turned off )", "fpw-fct" ) . 
					'</td></tr></table></blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Options & Actions', 'fpw-fct' ),
    		'id'      => 'fpw-fpt-help-options',
	   		'content' => $opts,
		) );

		$panels =	'<p style="font-size: larger">' . __( 'Panels', 'fpw-fct' ) . '</p><blockquote>' . 
					'<p style="text-align: justify;"><strong>' . 
					__( 'Thumbnails for Content enabled:', 'fpw-fct' ) . '</strong> ' . 
					'( ' . __( 'checked', 'fpw-fct' ) . ' ) - ' . 
					__( 'enables thumbnails for contents', 'fpw-fct' ) . '<br /><strong>' . 
					__( 'Thumbnails for Excerpt enabled:', 'fpw-fct' ) . '</strong> ' . 
					'( ' . __( 'checked', 'fpw-fct' ) . ' ) - ' . 				
					__( 'enables thumbnails for excerpts', 'fpw-fct' ) . '</p>' . 
					'<p style="text-align: justify;"><strong>width</strong> ' . __( 'and', 'fpw-fct' ) . ' <strong>height</strong> - ' . 
					__( 'width and height of thumbnails', 'fpw-fct' ) . '<br /><strong>scaling base</strong> - ' . 
					__( 'base dimension for scaling', 'fpw-fct' ) . '<br /><strong>float</strong> - ' . 
					__( 'position of thumbnails relative to the content ( excerpt )', 'fpw-fct' ) . 
					'</p><p style="text-align: justify;"><strong>border</strong> ' . 
					'( ' . __( 'checked', 'fpw-fct' ) . ' ) - ' . 
					__( 'thumbnails will have a border and next four parameters will be applied', 'fpw-fct') . 
					'<br /><strong>border-radius</strong> - ' . 
					__( 'if > 0 then the border will have rounded corners with the radius ' . 
						'of corners in pixels equal to the specified value', 'fpw-fct' ) . 
					'<br /><strong>border-width</strong> - ' . __( 'thickness of the border in pixels', 'fpw-fct' ). 
					'<br /><strong>border-color</strong> - ' . __( 'color of the border', 'fpw-fct' ) . ' ( ' . 
					__( 'selection with JavaScript Color Wheel', 'fpw-fct' ) . ' )' . 
					'<br /><strong>background-color</strong> - ' . __( "thumbnails' background color", "fpw-fct" ) . ' ( ' . 
					__( 'selection with JavaScript Color Wheel', 'fpw-fct' ) . ' )' .
					'</p><p style="text-align: justify;"><strong>padding-xxx and margin-xxx</strong> - ' . 
					__( 'these are standard padding and margin parameters', 'fpw-fct' ) . '</p></blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Panels', 'fpw-fct' ),
    		'id'      => 'fpw-fpt-help-panels',
	   		'content' => $panels,
		) );

		$faq =		'<p style="font-size: larger">' . __( 'Frequently Asked Questions', 'fpw-fct' ) . '</p><blockquote style="text-align: justify"><strong>' . 
					__( 'Question:', 'fpw-fct' ) . '</strong> ' .
					__( 'Can I use this plugin if my theme supports and displays thumbnails?', 'fpw-fct' ) . '<br /><strong>' . 
					__( 'Answer:', 'fpw-fct' ) . '</strong> ' . 
					__( "If the theme displays thumbnails for both the content and excerpts I would not recommended it as you would get two thumbnails displayed. However I can imagine one exception. The theme displays thumbnails for full content but not for excerpts or the other way around. The plugin will display thumbnails for the part not being displayed by the theme not adding thumbnails to the other part.", "fpw-fct" ) . '<br /><br /><strong>' . 
					__( 'Question:', 'fpw-fct' ) . '</strong> ' . 
					__( 'Will this plugin work with JavaScript turned off?', 'fpw-fct' ) . '<br /><strong>' .
					__( 'Answer:', 'fpw-fct' ) . '</strong> ' . 
					__( 'Yes. This plugin is functional with JavaScript turned off to comply with accessibility regulations.', 'fpw-fct' ) .  
					'</blockqoute>'; 

		$current_screen->add_help_tab( array(
   			'title'   => __( 'FAQ', 'fpw-fct' ),
    		'id'      => 'fpw-fpt-help-faq',
	   		'content' => $faq,
		) );
?>