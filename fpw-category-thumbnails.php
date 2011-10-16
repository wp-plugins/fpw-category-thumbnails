<?php
/*
Plugin Name: FPW Category Thumbnails
Description: Sets post/page thumbnail based on category.
Plugin URI: http://fw2s.com/2010/10/14/fpw-category-thumbnails-plugin/
Version: 1.3.7
Author: Frank P. Walentynowicz
Author URI: http://fw2s.com/

Copyright 2011 Frank P. Walentynowicz (email : frankpw@fw2s.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global	$fpw_fct_version, $wp_version,
		$fpw_fct_options;

$fpw_fct_version = '1.3.7';

//	Get plugin's options
function fpw_fct_get_options() {
	global	$wp_version;
	
	$needs_update = FALSE;
	$opt = get_option( 'fpw_category_thumb_opt' );
	
	if ( !is_array( $opt ) ) {
		$needs_update = TRUE;
		if ( '3.1' <= $wp_version ) {
			$opt = array( 
				'clean'		=> FALSE,
				'donotover' => FALSE,
				'dash'		=> FALSE,
				'abar'		=> FALSE );
		} else {
			$opt = array( 
				'clean'		=> FALSE,
				'donotover' => FALSE,
				'dash'		=> FALSE );
		}
	} else {
		if ( !array_key_exists( 'clean', $opt ) || !is_bool( $opt[ 'clean' ] ) ) { 
			$needs_update = TRUE;
			$opt[ 'clean' ] = FALSE;
		}
		if ( !array_key_exists( 'donotover', $opt ) || !is_bool( $opt[ 'donotover' ] ) ) { 
			$needs_update = TRUE;
			$opt[ 'donotover' ] = FALSE;
		}
		if ( !array_key_exists( 'dash', $opt ) || !is_bool( $opt[ 'dash' ] ) ) { 
			$needs_update = TRUE;
			$opt[ 'dash' ] = FALSE;
		}
		if ( '3.1' <= $wp_version ) 
			if ( !array_key_exists( 'abar', $opt ) || !is_bool( $opt[ 'abar' ] ) ) { 
				$needs_update = TRUE;
				$opt[ 'abar' ] = FALSE;
			}
		if ( $needs_update ) 
			update_option( 'fpw_category_thumb_opt', $opt );
	}
	return $opt;
}

//	Load text domain for translation
function fpw_fct_init(){
	load_plugin_textdomain( 'fpw-fct', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}	
add_action( 'init', 'fpw_fct_init', 1 );

//	Read plugin's options
$fpw_fct_options = fpw_fct_get_options();

//	include Get Image ID logic
include 'fpw-file-select.php';

//	Register plugin's menu in Settings
function fpw_fct_settings_menu() {
	global 	$fpw_fct_hook,
			$fpw_fct_version;
	$page_title = __( 'FPW Category Thumbnails', 'fpw-fct' ) . ' (' . $fpw_fct_version . ')';
	$menu_title = __( 'FPW Category Thumbnails', 'fpw-fct' );
	$fpw_fct_hook = add_options_page( $page_title, $menu_title, 'manage_options', 'fpw-category-thumbnails', 'fpw_fct_settings');
	add_action( 'admin_enqueue_scripts', 'fpw_fct_enqueue_scripts' );
}
add_action( 'admin_menu', 'fpw_fct_settings_menu' );

//	Register plugin's menu in admin bar for WP 3.1+
if ( '3.1' <= $wp_version ) {
	function fpw_fct_in_admin_bar() {
		if ( current_user_can( 'edit_plugins' ) && is_admin() ) {
			global $wp_admin_bar;

			$main = array(
				'id' => 'fpw_plugins',
				'title' => __( 'FPW Plugins', 'fpw-fct' ),
				'href' => '#' );

			$subm = array(
				'id' => 'fpw_bar_category_thumbnails',
				'parent' => 'fpw_plugins',
				'title' => __( 'FPW Category Thumbnails', 'fpw-fct' ),
				'href' => get_admin_url() . 'options-general.php?page=fpw-category-thumbnails' );

			$addmain = ( is_array($wp_admin_bar->menu->fpw_plugins) ) ? false : true; 

			if ( $addmain )
				$wp_admin_bar->add_menu( $main );
			$wp_admin_bar->add_menu( $subm );
		}
	}
	if ( $_POST[ 'buttonPressed' ] ) 
		$fpw_fct_options[ 'abar' ] = ( $_POST[ 'abar' ] == 'yes' ); 
	if ( $fpw_fct_options[ 'abar' ] ) 
		add_action( 'admin_bar_menu', 'fpw_fct_in_admin_bar', 1010 );
}

//	Register plugin's Dashboard widget
function fpw_fct_add_dashboard_widgets() {
	$widget_title = __( 'FPW Category Thumbnails', 'fpw-fct' );
	wp_add_dashboard_widget( 'fpw_fct_dashboard_widget', $widget_title, 'fpw_fct_dashboard_widget_function' );
}
if ( $fpw_fct_options[ 'dash' ] ) 
	add_action( 'wp_dashboard_setup', 'fpw_fct_add_dashboard_widgets' );

//	Display plugin Dashboard Widget's content
function fpw_fct_dashboard_widget_function() {
	global	$fpw_fct_options, 
			$wp_version;
	
	if ( !current_theme_supports( 'post-thumbnails') )
		echo '<p style="font-family:arial;font-size:.9em;color:red;"><strong>' .__( 'WARNING: Your theme has no support for <em>post thumbnails</em>!', 'fpw-fct' ) . '</strong></p>' . PHP_EOL; 
	
	if ( $fpw_fct_options[ 'donotover' ] ) {
		$dont = 'On';
	} else {
		$dont = 'Off';
	}
	
	if ( $fpw_fct_options[ 'clean' ] ) {
		$clean = 'On';
	} else {
		$clean = 'Off';
	}
	
	if ( '3.1' <= $wp_version ) { 
		if ( $fpw_fct_options[ 'abar' ] ) {
			$abar = 'On';
		} else {
			$abar = 'Off';
		}
	}
	
	echo '<p style="font-family:arial;font-size:.9em;">' . __( "Do not overwrite if post/page has thumbnail assigned already", 'fpw-fct' ) . ' ( <strong>' . $dont . '</strong> )<br />' . PHP_EOL;
	
	if ( '3.1' <= $wp_version ) 
		echo __( "Add this plugin to the Admin Bar", 'fpw-fct' ) . ' ( <strong>' . $abar . '</strong> )<br />' . PHP_EOL;
	
	echo __( "Remove plugin's data from database on uninstall", 'fpw-fct' ) . ' ( <strong>' . $clean . '</strong> )</p>' . PHP_EOL;
} 

//	Register plugin's filters and actions
function fpw_fct_activate() {
	global	$fpw_fct_options;

	//	base name for uninstall file
	$uninstall_file_base = ABSPATH . PLUGINDIR . '/' . dirname( plugin_basename ( __FILE__ ) ) . '/uninstall';

	//	if cleanup requested make uninstall.php otherwise make uninstall.txt
	if ( $fpw_fct_options[ 'clean' ] ) {
		if ( file_exists( $uninstall_file_base . '.txt' ) ) 
			rename( $uninstall_file_base . '.txt', $uninstall_file_base . '.php' );
	} else {
		if ( file_exists( $uninstall_file_base . '.php' ) ) 
			rename( $uninstall_file_base . '.php', $uninstall_file_base . '.txt' );
	}
}	
register_activation_hook( __FILE__, 'fpw_fct_activate' );

//	Add link to Settings on Plugins page
function fpw_fct_plugin_links( $links, $file ) {
   	$settings_link = '<a href="' . site_url( '/wp-admin/' ) . 'options-general.php?page=fpw-category-thumbnails">' . __( "Settings", "fpw-fct" ) . '</a>';
	array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_fpw-category-thumbnails/fpw-category-thumbnails.php', 'fpw_fct_plugin_links', 10, 2);

//	Add link to Donation to plugins meta
function fpw_fct_plugin_meta_links( $links, $file ) {
	if ( 'fpw-category-thumbnails/fpw-category-thumbnails.php' == $file ) {
		$links[] = '<a href="http://fw2s.com/payments-and-donations/" target="_blank">' . __( "Donate", "fpw-fct" ) . '</a>';
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'fpw_fct_plugin_meta_links', 10, 2 );

//	Add update information after plugin mete
function fpw_fct_add_after_plugin_meta( $file, $plugin_data ) {
	$current = get_site_transient( 'update_plugins' );
	if ( !isset( $current -> response[ $file ] ) ) 
		return false;
	$url = "http://fw2s.com/fpwcatthumbsupdate.txt";
	$update = wp_remote_fopen( $url );
	echo '<tr class="plugin-update-tr"><td></td><td></td><td class="plugin-update"><div class="update-message">' . $update . '</div></td></tr>';
}
add_action( 'after_plugin_row_fpw-category-thumbnails/fpw-category-thumbnails.php', 'fpw_fct_add_after_plugin_meta', 10, 2 );

//	Add plugin's contextual help
if ( '3.3' <= $wp_version ) {
	function fpw_fct_help33() {
		global	$current_screen, $fpw_fct_hook;
		
		if ( $current_screen->id == $fpw_fct_hook ) {
		
			$sidebar =	'<p><strong>For more information:</strong><br />' . 
						'&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://fw2s.com/2010/10/14/fpw-category-thumbnails-plugin/" target="_blank">Visit plugin site</a></p>' . 
						'<p><strong>Support on:</strong><br />' . 
						'&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://wordpress.org/tags/fpw-category-thumbnails?forum_id=10" target="_blank">wordpress.org</a><br />' . 
						'&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://fw2s.com/forums/topic/fpw-category-thumbnail-plugin-support/" target="_blank">fw2s.com</a></p>'; 
			
			$current_screen->add_help_sidebar( $sidebar );
			
			$intro =	'<p style="text-align: justify">' . __( 'Setting featured images for posts / pages could be very time consuming, ', 'fpw-category-thumbnails' ) . 
						__( 'especially when your media library holds hundreds of pictures. ', 'fpw-category-thumbnails' ) . 
						__( 'Very often we select the same thumbnail for posts in particular category. ', 'fpw-category-thumbnails' ) . 
						__( 'This plugin automates the process by inserting a thumbnail based on category / thumbnail mapping while post / page is being created or updated.', 'fpw-category-thumbnails' ) . '<br /><br />' . 
						'<strong>' . __( 'Note', 'fpw-category-thumbnails' ) . '</strong>: ' . __( 'please remember that your theme must support post thumbnails.', 'fpw-category-thumbnails' ) . 
						'</p><p>&nbsp;</p>';

			$current_screen->add_help_tab( array(
	    		'title'   => __('Introduction', 'fpw-category-thumbnails'),
		    	'id'      => 'fpw-category-thumbnails-help-introduction',
	    		'content' => $intro,
			) );

			$current_screen->add_help_tab( array(
	    		'title'   => __('Options', 'fpw-category-thumbnails'),
		    	'id'      => 'fpw-category-thumbnails-help-options',
	    		'content' => "<p>Help text for options...</p>",
			) );

			$current_screen->add_help_tab( array(
	    		'title'   => __('FAQ', 'fpw-category-thumbnails'),
		    	'id'      => 'fpw-category-thumbnails-help-faq',
	    		'content' => "<p>Help text for faq...</p>",
			) );
			
		}
	}
	add_action( 'admin_print_scripts', 'fpw_fct_help33' );
} else {
	function fpw_fct_help( $contextual_help, $screen_id, $screen ) {
		global $fpw_fct_hook;

		if ( $screen_id == $fpw_fct_hook ) {
			//	display description block
			$my_help  = '<h3>' . __( 'Description', 'fpw-category-thumbnails' ) . '</h3>' . PHP_EOL;
			$my_help .= '<p>' . __( 'This plugin inserts a thumbnail based on category / thumbnail mapping while post / page is being created or updated.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
			$my_help .= '<strong>' . __( 'Note', 'fpw-category-thumbnails' ) . '</strong>: ' . __( 'please remember that your theme must support post thumbnails.', 'fpw-category-thumbnails' ) . '</p>' . PHP_EOL;

			//	display instructions block
			$my_help .= '<h3>' . __( 'Instructions', 'fpw-category-thumbnails' ) . '</h3>' . PHP_EOL;
			$my_help .= '<p>' . __( 'Enter', 'fpw-category-thumbnails' ) . ' <strong>' . __( 'IDs', 'fpw-category-thumbnails' ) . '</strong> ' . __( 'of thumbnail images for corresponding categories.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
			$my_help .= __( 'Enter', 'fpw-category-thumbnails' ) . ' <strong>0</strong> ' . __( 'for categories without assignment.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
			$my_help .= __( 'Click on', 'fpw-category-thumbnails' ) . ' <strong>' . __( 'Apply to all existing posts/pages', 'fpw-category-thumbnails' ) . '</strong> ' . __( 'to immediately apply mappings to existing posts/pages.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
			$my_help .= __( 'Click on', 'fpw-category-thumbnails' ) . ' <strong>' . __( 'Remove all thumbnails from existing posts/pages', 'fpw-category-thumbnails' ) . '</strong> ' . __( 'to immediately remove thumbnails from existing posts/pages.', 'fpw-category-thumbnails' ) . '</p>' . PHP_EOL;

			//	WordPress default help
			$my_help .= '<h3>WordPress</h3>' . PHP_EOL;
			$my_help .= '<p>' . PHP_EOL;
			$my_help .= $contextual_help;
			$my_help .= '</p>' . PHP_EOL;

			$contextual_help = $my_help;
		}
		return $contextual_help; 
	}
	add_filter( 'contextual_help', 'fpw_fct_help', 10, 3 );
}

/*	----------------------
	Plugin's settings page
	------------------- */
function fpw_fct_settings() {
	global	$fpw_fct_version, 
			$fpw_fct_options,
			$wp_version;

	//	get all categories
	$categories = array();
	$cats0 = get_categories('hide_empty=0&orderby=name&parent=0');

	foreach ( $cats0 as $cats00 ) {
    	array_push( $categories, array(0,$cats00) );
    	$cats1 = get_categories('hide_empty=0&orderby=name&parent='.$cats00->cat_ID);

    	foreach ( $cats1 as $cats10 ) {
        	array_push( $categories, array(1,$cats10) );
        	$cats2 = get_categories('hide_empty=0&orderby=name&parent='.$cats10->cat_ID);

        	foreach ( $cats2 as $cats20 ) {
            	array_push( $categories, array(2,$cats20) );
            	$cats3 = get_categories('hide_empty=0&orderby=name&parent='.$cats20->cat_ID);

            	foreach ( $cats3 as $cats30 ) {
                	array_push( $categories, array(3,$cats30) );
                	$cats4 = get_categories('hide_empty=0&orderby=name&parent='.$cats30->cat_ID);

                	foreach ( $cats4 as $cats40 ) {
                    	array_push( $categories, array(4,$cats40) );
                    	$cats5 = get_categories('hide_empty=0&orderby=name&parent='.$cats40->cat_ID);

                    	foreach ( $cats5 as $cats50 ) {
                        	array_push( $categories, array(5,$cats50) );
                    	}
                	}
            	}
        	}
    	}
	}

	//	build initial associative array(category_id => thumbnail_id)
	//	where all values are 0
	$assignments = array();

	foreach ( $categories as $category ) {
		$assignments[ $category[1] -> cat_ID ] = 0;
	}

	//	create a copy of above array which will be used to strip
	//	all elements with 0 values from the array passed to
	//	update_option function
	$azeroes = $assignments;

	//	initialize update flags
	$update_options_ok = FALSE;
	$update_mapping_ok = FALSE;
	
	//	check nonce if any of buttons was pressed
	if ( $_POST[ 'buttonPressed' ] ) {
		if ( !isset( $_POST[ 'fpw-fct-nonce' ] ) ) 
			die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red"><strong>' . __( 'You did not send any credentials!', 'fpw-fct' ) . '</strong></p>' );
		if ( !wp_verify_nonce( $_POST[ 'fpw-fct-nonce' ], 'fpw-fct-nonce' ) ) 
			die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red;"><strong>' . __( 'You did not send the right credentials!', 'fpw-fct' ) . '</strong></p>' );

		//	check ok - update options
		$fpw_fct_options[ 'clean' ] = ( $_POST[ 'cleanup' ] == 'yes' );
		$fpw_fct_options[ 'donotover' ] = ( $_POST[ 'donotover' ] == 'yes' );
		$fpw_fct_options[ 'dash' ] = ( $_POST[ 'dash' ] == 'yes' );
		if ( '3.1' <= $wp_version ) 
			$fpw_fct_options[ 'abar' ] = ( $_POST[ 'abar' ] == 'yes' );
		
		$update_options_ok = ( update_option( 'fpw_category_thumb_opt', $fpw_fct_options ) );
		
		// 	if any changes to options then check uninstall file's extension
		if ( $update_options_ok ) 
			fpw_fct_activate();

		//	update mappings
		//	inserting posted values into $assignments array 
        reset( $assignments );
		while ( strlen( key( $assignments ) ) ) {
			//	validation
			$v = (string) $_POST[ 'val-for-id-' . key( $assignments ) . '-field' ];
			if ( strlen( $v ) > 0 ) {
				if ( ctype_digit( $v ) ) {
					if ( strlen( $v ) > 1 ) $v = ltrim( $v, '0' );
				} else {
					if ( 'ngg-' == substr( $v, 0, 4 ) ) {
						$v = 'ngg-' . ltrim( substr( $v, 4 ), '0' );
					} else {
						$v = '0';
					}
				}
			} else {
				$v = '0';
			}
        	$assignments[ key( $assignments ) ] = $v;
			next($assignments);
		}

		//	create array with all 0 valued elements removed
		$option = array_diff_assoc( $assignments, $azeroes );

		//	database update
		$update_mapping_ok = ( update_option( 'fpw_category_thumb_map', $option ) );
	}

	//	check if remove button was pressed
	if ( 'Remove' == $_POST[ 'buttonPressed' ] ) {
		reset( $assignments );
		
		while ( strlen( key( $assignments ) ) ) {
			$catid = key( $assignments );
			$parg = array(
				numberofposts => -1,
				nopaging => true,
				category => $catid,
				post_type => 'any' );
			$posts = get_posts( $parg );
			foreach ( $posts as $post ) {
				$post_id = $post -> ID;
				//	make sure this is not a revision
				if ( 'revision' != $post -> post_type )
					delete_post_meta( $post_id, '_thumbnail_id' );
			}
			next( $assignments );
		}
	}

	//	check if apply button was pressed
	if ( 'Apply' == $_POST[ 'buttonPressed' ] ) {
		$map = get_option( 'fpw_category_thumb_map' );
		if ( $map )
			while ( strlen( key( $map ) ) ) {
				$catid = key($map);
				$parg = array(
					numberofposts => -1,
					nopaging => true,
					category => $catid,
					post_type => 'any' );
				$posts = get_posts( $parg );
				foreach ( $posts as $post ) {
					$post_id = $post -> ID;
					//	make sure this is not a revision
					if ( 'revision' != $post -> post_type )
						fpw_fct_update_id( $post_id, $post );
				}
				next($map);
			}
	}
	
	//	get assignments from database
	$opt = get_option( 'fpw_category_thumb_map' );

	// update $assignments array with values from database
	if ( $opt ) {
	    reset( $assignments );
		while ( strlen( key( $assignments ) ) ) {
			if ( array_key_exists( key( $assignments ), $opt ) ) {
				$assignments[ key( $assignments ) ] = $opt[ key( $assignments ) ];	
			}
			next( $assignments );
		}
	}

/*	------------------------------
	Settings page HTML starts here
	--------------------------- */

	echo '<div class="wrap">' . PHP_EOL;
	echo '<div id="icon-options-general" class="icon32"></div><h2>' . __( 'FPW Category Thumbnails', 'fpw-fct' ) . ' (' . $fpw_fct_version . ')</h2>' . PHP_EOL;

    //	display warning if current theme doesn't support post thumbnails
    if ( !current_theme_supports( 'post-thumbnails') ) {
    	echo '	<div id="message" class="error fade" style="background-color: #CCFFFF; color: red;"><p><strong>';
		echo __( 'WARNING: Your theme has no support for <em>post thumbnails</em>!', 'fpw-fct' ) . ' '; 
		echo __( 'You can continue with <em>Settings</em> but until you add <code>add_theme_support( \'post-thumbnails\' );</code> to the theme\'s functions.php you will not be able to display thumbnails.', 'fpw-fct' ); 
		echo '</strong></p></div>' . PHP_EOL;
	}

	//	display message about update status
	if ( 'Update' == $_POST[ 'buttonPressed' ] )
		if ( $update_options_ok || $update_mapping_ok ) {
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'Updated successfully.', 'fpw-fct' ) . '</strong></p></div>' . PHP_EOL;
		} else {
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'No changes detected. Nothing to update.', 'fpw-fct' ) . '</strong></p></div>' . PHP_EOL;
		}

	//	display message about apply status
	if ( 'Apply' == $_POST[ 'buttonPressed' ] )
		echo '<div id="message" class="updated fade"><p><strong>' . __( 'Applied thumbnails to existing posts / pages successfully.', 'fpw-fct' ) . '</strong></p></div>' . PHP_EOL;

	//	display message about remove status
	if ( 'Remove' == $_POST[ 'buttonPressed' ] )
		echo '<div id="message" class="updated fade"><p><strong>' . __( 'All thumbnails removed successfully.', 'fpw-fct' ) . '</strong></p></div>' . PHP_EOL;

	//	About Help message
	echo '<p class="alignright">' . __( 'For additional information click on', 'fpw-fct' ) . ' <strong>' . __( 'Help', 'fpw-fct' ) . '</strong> ';
	if ( '3.3' <= $wp_version ) {
		echo __( 'on the Admin Bar', 'fpw-fct' );
	} else {
		echo __( 'above', 'fpw-category-thumbnails' );
	}
	echo '.</p>' . PHP_EOL;

	//	the form starts here
	echo '<p>' . PHP_EOL;
	echo '<form name="fpw_cat_thmb_form" action="';
	print '?page=' . basename( __FILE__, '.php' );
	echo '" method="post">' . PHP_EOL;

	//	protect this form with nonce
	echo '<input name="fpw-fct-nonce" type="hidden" value="' . wp_create_nonce( 'fpw-fct-nonce' ) . '" />' . PHP_EOL;

	//	do not overwrite checkbox
	echo '<input type="checkbox" name="donotover" value="yes"';
	if ( $fpw_fct_options[ 'donotover' ] ) 
		echo ' checked';
	echo "> " . __( "Do not overwrite if post/page has thumbnail assigned already", 'fpw-fct' ) . "<br />" . PHP_EOL;

	//	cleanup checkbox
	echo '<input type="checkbox" name="cleanup" value="yes"';
	if ( $fpw_fct_options[ 'clean' ] ) 
		echo ' checked';
	echo "> " . __( "Remove plugin's data from database on uninstall", 'fpw-fct' ) . "<br />" . PHP_EOL;

	//	dashboard widget checkbox
	echo '<input type="checkbox" name="dash" value="yes"';
	if ( $fpw_fct_options[ 'dash' ] ) 
		echo ' checked';
	echo "> " . __( "Show plugin info widget on the Dashboard", 'fpw-fct' ) . "<br />" . PHP_EOL;

	//	add plugin to admin bar checkbox
	if ( '3.1' <= $wp_version ) {
		echo '<input type="checkbox" name="abar" value="yes"';
		if ( $fpw_fct_options[ 'abar' ] ) 
			echo ' checked';
		echo "> " . __( "Add this plugin to the Admin Bar", 'fpw-fct' ) . "<br /><br />" . PHP_EOL;
	}

	// start of the table
	echo '<table class="widefat">' . PHP_EOL;
	echo '<thead>' . PHP_EOL;
	echo '<tr>' . PHP_EOL;
	echo '<th width="25%" style="text-align: left;">' . __( 'Category (ID)', 'fpw-fct' ) . '</th>' . PHP_EOL;
	echo '<th width="250px" style="text-align: left;">' . __( 'Image ID', 'fpw-fct' ) . '</th>' . PHP_EOL;
	echo '<th style="text-align: left;">' . __( 'Preview', 'fpw-fct' ) . '</th>' . PHP_EOL;
	echo '</tr>' . PHP_EOL;
	echo '</thead>' . PHP_EOL;
	echo '<tfoot>' . PHP_EOL;
	echo '<tr>' . PHP_EOL;
	echo '<th width="25%" style="text-align: left;">' . __( 'Category (ID)', 'fpw-fct' ) . '</th>' . PHP_EOL;
	echo '<th width="250px" style="text-align: left;">' . __( 'Image ID', 'fpw-fct' ) . '</th>' . PHP_EOL;
	echo '<th style="text-align: left;">' . __( 'Preview', 'fpw-fct' ) . '</th>' . PHP_EOL;
	echo '</tr>' . PHP_EOL;
	echo '</tfoot>' . PHP_EOL;
	echo '<tbody>' . PHP_EOL;

	//	build form's input fields and buttons
	reset( $assignments );
	reset( $categories );
	$i = 0;
	while ( strlen( key( $assignments ) ) ) {
		echo '<tr>' . PHP_EOL;
		echo '<td>'; 
		$indent = str_repeat( '&nbsp;', $categories[ key( $categories )][ 0 ] * 4);
		echo $indent . $categories[ key( $categories ) ][ 1 ] -> cat_name . ' (' . $categories[ key( $categories )][ 1 ] -> cat_ID . ')'; 
		echo '</td>' . PHP_EOL;
		fpw_fs_button( 'val-for-id-' . key( $assignments ) . '-field', $assignments[ key( $assignments ) ], key( $assignments ), $label = 'Get ID' );
		echo '</tr>' . PHP_EOL;
		$i++;
		next( $assignments );
		next( $categories );
	}

	//	end of the table
	echo '</tbody>' . PHP_EOL;
	echo '</table>' . PHP_EOL;

	//	submit buttons
	echo '<br /><div class="inputbutton"><input onclick="confirmUpdate();" id="update" class="button-primary fpw-submit" type="button" name="fpw_cat_thmb_submit" value="' . __( 'Update', 'fpw-fct' ) . '" /> ';
	echo '<input onclick="confirmApply();" id="apply" class="button-primary fpw-submit" type="button" name="fpw_cat_thmb_submit_apply" value="' . __( 'Apply to all posts/pages', 'fpw-fct' ) . '" /> ';
	echo '<input onclick="confirmRemove();" id="remove" class="button-primary fpw-submit" type="button" name="fpw_cat_thmb_submit_remove" value="' . __( 'Remove all thumbnails from posts/pages', 'fpw-fct' ) . '" />';
	echo '<input id="buttonPressed" type="hidden" value="" name="buttonPressed" /></div>' . PHP_EOL;

	//	end of form
	echo '</form>' . PHP_EOL;
	echo '</p>' . PHP_EOL;
	echo '</div>' . PHP_EOL;
}

/*	----------------------------------------------------------------------
	Main action - sets the value of post's _thumbnail_id based on category
	assignments
	------------------------------------------------------------------- */
function fpw_fct_update_id( $post_id, $post ) {
	//	we don't want to apply changes to post's revision
	if ( 'revision' == $post -> post_type )
		return;
	//	this is actual post
	$thumb_id = get_post_meta( $post_id, '_thumbnail_id', TRUE );
	$do_notover = get_option( 'fpw_category_thumb_opt' );
	if ( $do_notover )
		$do_notover = $do_notover[ 'donotover' ]; 
	$map = get_option( 'fpw_category_thumb_map' );
	if ( $map ) {
		$cat = get_the_category( $post_id );
		foreach ( $cat as $c ) {
			if ( $post->post_date === $post -> post_modified ) {
				//	in case of a new post we have to ignore setting of $do_notover flag
				//	as the thumbnail of default category will be there already
				if ( array_key_exists( $c -> cat_ID, $map ) )
					update_post_meta( $post_id, '_thumbnail_id', $map[ $c -> cat_ID ] );
			} else {
				//	modified post - observe $do_notover flag
				if ( ( array_key_exists( $c -> cat_ID, $map ) ) && ( ( '' == $thumb_id ) || !( $do_notover ) ) )
					update_post_meta( $post_id, '_thumbnail_id', $map[ $c -> cat_ID ] );
			}
  		}
	}
}	
add_action( 'save_post', 'fpw_fct_update_id', 10, 2 );
