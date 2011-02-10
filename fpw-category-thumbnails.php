<?php
/*
Plugin Name: FPW Category Thumbnails
Description: Sets post/page thumbnail based on category.
Plugin URI: http://fw2s.com/2010/10/14/fpw-category-thumbnails-plugin/
Version: 1.2.0
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

global	$fpw_category_thumbnails_version;
$fpw_category_thumbnails_version = '1.1.9';

/*	--------------------------------
	Load text domain for translation
	----------------------------- */

add_action('init', 'fpw_category_thumbnails_init', 1);

function fpw_category_thumbnails_init(){
	load_plugin_textdomain( 'fpw-category-thumbnails', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}	

/*	----------------------------------
	Register plugin's menu in Settings
	------------------------------- */

//	Add plugin's options page
add_action('admin_menu', 'fpw_cat_thumbs_settings_menu');

function fpw_cat_thumbs_settings_menu() {
	global 	$fpw_cat_thumbs_hook,
			$fpw_category_thumbnails_version;
	$page_title = __('FPW Category Thumbnails - Settings', 'fpw-category-thumbnails') . ' (' . $fpw_category_thumbnails_version . ')';
	$menu_title = __('FPW Category Thumbnails', 'fpw-category-thumbnails');
	$fpw_cat_thumbs_hook = add_options_page( $page_title, $menu_title, 'manage_options', 'fpw-category-thumbnails', 'fpw_cat_thumbs_settings');
}

//	Register plugin's Dashboard widget
add_action('wp_dashboard_setup', 'fpw_cat_thumbs_add_dashboard_widgets' );

function fpw_cat_thumbs_add_dashboard_widgets() {
	$widget_title = __('FPW Category Thumbnails', 'fpw-category-thumbnails');
	wp_add_dashboard_widget('fpw_dashboard_widget', $widget_title, 'fpw_cat_thumbs_dashboard_widget_function');	
} 

//	Display plugin Dashboard Widget's content
function fpw_cat_thumbs_dashboard_widget_function() {

	if ( !current_theme_supports( 'post-thumbnails') )
		echo '<p style="font-family:arial;font-size:.9em;color:red;"><strong>' .__( 'WARNING: Your theme has no support for <em>post thumbnails</em>!', 'fpw-category-thumbnails' ) . '</strong></p>' . PHP_EOL; 

	$fpw_options = get_option( 'fpw_category_thumb_opt' );
	if ( is_array( $fpw_options ) ) {
		if ( $fpw_options[ 'donotover' ] ) {
			$dont = 'On';
		} else {
			$dont = 'Off';
		}
		if ( $fpw_options[ 'clean' ] ) {
			$clean = 'On';
		} else {
			$clean = 'Off';
		}
	} else {
		$dont = 'Off';
		$clean = 'Off';
	}

	echo '<p style="font-family:arial;font-size:.9em;">' . __( "Do not overwrite if post/page has thumbnail assigned already", 'fpw-category-thumbnails' ) . ' ( <strong>' . $dont . '</strong> )<br />' . PHP_EOL;
	echo __( "Remove plugin's data from database on uninstall", 'fpw-category-thumbnails' ) . ' ( <strong>' . $clean . '</strong> )</p>' . PHP_EOL;
} 

/*	-------------------------------------
	Register plugin's filters and actions
	---------------------------------- */

register_activation_hook( __FILE__, 'fpw_category_thumbnails_activate' );

function fpw_category_thumbnails_activate() {
	/*	base name for uninstall file */
	$uninstall = ABSPATH . PLUGINDIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/uninstall.';
	
	/*	get options array */
	$fpw_options = get_option( 'fpw_category_thumb_opt' );
	if ( is_array( $fpw_options ) ) {

		/* if cleanup requested make uninstall.php otherwise make uninstall.txt */
		if ( $fpw_options[ 'clean' ] ) {
			if ( file_exists( $uninstall . 'txt' ) )
				rename( $uninstall . 'txt', $uninstall . 'php' );
		} else {
			if ( file_exists( $uninstall . 'php' ) )
				rename( $uninstall . 'php', $uninstall . 'txt' );
		}
	}
}	

add_filter('plugin_action_links_fpw-category-thumbnails/fpw-category-thumbnails.php', 'fpw_cat_thumbs_plugin_links', 10, 2);

function fpw_cat_thumbs_plugin_links($links, $file) {
   	$settings_link = '<a href="/wp-admin/options-general.php?page=fpw-category-thumbnails">'.__("Settings", "fpw-category-thumbnails").'</a>';
	array_unshift($links, $settings_link);
    return $links;
}

add_action('after_plugin_row_fpw-category-thumbnails/fpw-category-thumbnails.php', 'fpw_add_after_plugin_meta', 10, 2);

function fpw_add_after_plugin_meta($file,$plugin_data) {
	$current = get_site_transient('update_plugins');
	if (!isset($current->response[$file])) return false;
	$url = "http://fw2s.com/fpwcatthumbsupdate.txt";
	$update = wp_remote_fopen($url);
	echo '<tr class="plugin-update-tr"><td></td><td></td><td class="plugin-update"><div class="update-message">'.$update.'</div></td></tr>';
}

add_filter('contextual_help', 'fpw_cat_thumbs_help', 10, 3);

function fpw_cat_thumbs_help($contextual_help, $screen_id, $screen) {
	global $fpw_cat_thumbs_hook;
	
	if ($screen_id == $fpw_cat_thumbs_hook) {
		
		/*	display description block */
		$my_help  = '<h3>' . __( 'Description', 'fpw-category-thumbnails' ) . '</h3>' . PHP_EOL;
		$my_help .= '<p>' . __( 'This plugin inserts a thumbnail based on category / thumbnail mapping while post / page is being created or updated.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
		$my_help .= '<strong>' . __( 'Note', 'fpw-category-thumbnails' ) . '</strong>: ' . __( 'please remember that your theme must support post thumbnails.', 'fpw-category-thumbnails' ) . '</p>' . PHP_EOL;

		/*	display instructions block */
		$my_help .= '<h3>' . __( 'Instructions', 'fpw-category-thumbnails' ) . '</h3>' . PHP_EOL;
		$my_help .= '<p>' . __( 'Enter', 'fpw-category-thumbnails' ) . ' <strong>' . __( 'IDs', 'fpw-category-thumbnails' ) . '</strong> ' . __( 'of thumbnail images for corresponding categories.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
		$my_help .= __( 'Enter', 'fpw-category-thumbnails' ) . ' <strong>0</strong> ' . __( 'for categories without assignment.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
		$my_help .= __( 'Click on', 'fpw-category-thumbnails' ) . ' <strong>' . __( 'Apply to all existing posts/pages', 'fpw-category-thumbnails' ) . '</strong> ' . __( 'to immediately apply mappings to existing posts/pages.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
		$my_help .= __( 'Click on', 'fpw-category-thumbnails' ) . ' <strong>' . __( 'Remove all thumbnails from existing posts/pages', 'fpw-category-thumbnails' ) . '</strong> ' . __( 'to immediately remove thumbnails from existing posts/pages.', 'fpw-category-thumbnails' ) . '</p>' . PHP_EOL;
		
		/*	WordPress default help */
		$my_help .= '<h3>WordPress</h3>' . PHP_EOL;
		$my_help .= $contextual_help;
		
		/*	display table of images */
		$my_help .= '<h3>' . __( 'Available images', 'fpw-category-thumbnails' ) . '</h3>' . PHP_EOL;
		$my_help .= '<p>' . PHP_EOL;

		/*	start of images table */
		$my_help .= '<table width="100%">' . PHP_EOL;
		$my_help .= '<theader>' . PHP_EOL;
		$my_help .= '	<tr>' . PHP_EOL;
		$my_help .= '		<th style="text-align: left;">' . __( 'Image', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
		$my_help .= '		<th style="text-align: left;">' . __( 'Image ID', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
		$my_help .= '		<th style="text-align: left;">' . __( 'Image', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
		$my_help .= '		<th style="text-align: left;">' . __( 'Image ID', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
		$my_help .= '		<th style="text-align: left;">' . __( 'Image', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
		$my_help .= '		<th style="text-align: left;">' . __( 'Image ID', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
		$my_help .= '		<th style="text-align: left;">' . __( 'Image', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
		$my_help .= '		<th style="text-align: left;">' . __( 'Image ID', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
		$my_help .= '	</tr>' . PHP_EOL;
		$my_help .= '</theader>' . PHP_EOL;
		$my_help .= '<tbody>' . PHP_EOL;
		
		$i = 1;

		/*	get available images from media library */
		$args = array(
			'post_type' => 'attachment',
			'post_mime_type' => 'image/jpeg,image/png,image/gif',
			'numberposts' => -1,
			'post_status' => null,
			'orderby' => 'ID',
			'post_parent' => $post->ID
		);
	
		$attachments = get_posts($args);
	
		if ($attachments) {
			foreach ($attachments as $attachment) {
				if ( 1 == $i ) {
					$my_help .= '	<tr>' . PHP_EOL;
					$my_help .= '		<td><img src="' . wp_get_attachment_url($attachment->ID) . '" width="64" /></td>' . PHP_EOL;
					$my_help .= '		<td>' . $attachment->ID . '</td>' . PHP_EOL;
					$i = 2;
				} else if ( 2 == $i ) {
					$my_help .= '		<td><img src="' . wp_get_attachment_url($attachment->ID) . '" width="64" /></td>' . PHP_EOL;
					$my_help .= '		<td>' . $attachment->ID . '</td>' . PHP_EOL;
					$i = 3;
				} else if ( 3 == $i ) {
					$my_help .= '		<td><img src="' . wp_get_attachment_url($attachment->ID) . '" width="64" /></td>' . PHP_EOL;
					$my_help .= '		<td>' . $attachment->ID . '</td>' . PHP_EOL;
					$i = 4;
				} else {
					$my_help .= '		<td><img src="' . wp_get_attachment_url($attachment->ID) . '" width="64" /></td>' . PHP_EOL;
					$my_help .= '		<td>' . $attachment->ID . '</td>' . PHP_EOL;
					$my_help .= '	</tr>' . PHP_EOL;
					$i = 1;
				}
			}
		}
	
		/*	end of images table */
		$my_help .= '</tbody>' . PHP_EOL;
		$my_help .= '</table>' . PHP_EOL;
	
		$my_help .= '</p>' . PHP_EOL;
		$contextual_help = $my_help;
	}
	return $contextual_help; 
}

/*	----------------------
	Plugin's settings page
	------------------- */

function fpw_cat_thumbs_settings() {
	global	$fpw_category_thumbnails_version;
	
	/* base name for uninstall file */
	$uninstall = ABSPATH . PLUGINDIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/uninstall.';
	
	/* initialize options array */
	$fpw_options = get_option( 'fpw_category_thumb_opt' );
	if ( !is_array( $fpw_options ) )
		$fpw_options = array( 'clean' => FALSE, 'donotover' => FALSE );
	
	/* get all categories */
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
	
	/*	build initial associative array(category_id => thumbnail_id)
		where all values are 0 */
	$assignments = array();
	foreach ( $categories as $category ) {
		$assignments[$category[1]->cat_ID] = 0;
	}
	
	/*	create a copy of above array which will be used to strip
		all elements with 0 values from the array passed to
		update_option function */
	$azeroes = $assignments;
	
	/*	read cleanup flag */
	$do_cleanup = get_option( 'fpw_category_thumb_opt' );
	if ( is_array( $do_cleanup ) ) {
		$do_cleanup = $do_cleanup[ 'clean' ];
	} else {
		$do_cleanup = false;
	}
	
	/*	check if apply or remove button was pressed */
	if ( ( $_POST['fpw_cat_thmb_submit_apply'] ) || ( $_POST['fpw_cat_thmb_submit_remove'] ) ) {
		$parg = array(
			numberofposts=>-1,
			nopaging=>true,
			orderby=>'category',
			post_type=>'any');
		$posts = get_posts($parg);
		foreach ( $posts as $post ) {
			$post_id = $post->ID;
			if ( $_POST['fpw_cat_thmb_submit_remove'] ) {
				/*	make sure this is not a revision */
				if ( 'revision' != $post->post_type )
					delete_post_meta($post_id,'_thumbnail_id');
			} else {
				/*	make sure this is not a revision */
				if ( 'revision' != $post->post_type )
					fpw_update_category_thumbnail_id($post_id, $post);
			}
		}
	}
	
	/*	check if changes were submitted */
	if ( $_POST['fpw_cat_thmb_submit'] ) {    
		$do_cleanup = ( $_POST[ 'cleanup' ] == 'yes' );
		$do_notover = ( $_POST[ 'donotover' ] == 'yes' );
		/*	inserting posted values into $assignments array */ 
        reset($assignments);
		while ( strlen( key( $assignments ) ) ) {
        	$assignments[key( $assignments )] = $_POST['val'.key( $assignments )];
			next($assignments);
		}
		
		/*	create array with all 0 valued elements removed */
		$option = array_diff_assoc($assignments, $azeroes);
		
		/*	check nonce before updating database */
		check_admin_referer('fpw_cat_thumbs_options_', 'updates');
		
		/*	database update */
		$fpw_options[ 'clean' ] = $do_cleanup;
		$fpw_options[ 'donotover' ] = $do_notover;
		$updateok = ( update_option( 'fpw_category_thumb_map', $option ) ) || ( update_option( 'fpw_category_thumb_opt', $fpw_options ) );
		
		/* if cleanup requested make uninstall.php otherwise make uninstall.txt */
		if ( $updateok ) fpw_category_thumbnails_activate();
	}
	
	/*	get assignments from database */
	$opt = get_option( 'fpw_category_thumb_map' );
	
	/* update $assignments array with values from database */
	if ( $opt ) {
	    reset($assignments);
		while ( strlen( key( $assignments ) ) ) {
			if ( array_key_exists( key( $assignments ), $opt ) ) {
				$assignments[key( $assignments )] = $opt[key( $assignments )];	
			}
			next( $assignments );
		}
	}

/*	-------------------------
	Settings page starts here
	---------------------- */
	
	echo '<div class="wrap">' . PHP_EOL;
	echo '	<h2>' . __( 'FPW Category Thumbnails - Settings', 'fpw-category-thumbnails' ) . ' (' . $fpw_category_thumbnails_version . ')</h2>' . PHP_EOL;

    /*	display warning if current theme doesn't support post thumbnails */
    if ( !current_theme_supports( 'post-thumbnails') ) {
    	echo '	<div id="message" class="error fade" style="background-color: #CCFFFF; color: red;"><p><strong>';
		echo __( 'WARNING: Your theme has no support for <em>post thumbnails</em>!', 'fpw-category-thumbnails' ) . ' '; 
		echo __( 'You can continue with <em>Settings</em> but until you add <code>add_theme_support( \'post-thumbnails\' );</code> to the theme\'s functions.php you will not be able to display thumbnails.', 'fpw-category-thumbnails' ); 
		echo '</strong></p></div>' . PHP_EOL;
	}
	
	/*	display message about update status */
	if ( $_POST['fpw_cat_thmb_submit'] )
		if ( $updateok ) {
			echo '	<div id="message" class="updated fade"><p><strong>' . __( 'Settings updated successfully.', 'fpw-category-thumbnails' ) . '</strong></p></div>' . PHP_EOL;
		} else {
			echo '	<div id="message" class="updated fade"><p><strong>' . __( 'No changes detected. Nothing to update.', 'fpw-category-thumbnails' ) . '</strong></p></div>' . PHP_EOL;
		}
	
	/*	display message about apply status */
	if ( $_POST['fpw_cat_thmb_submit_apply'] )
		echo '	<div id="message" class="updated fade"><p><strong>' . __( 'Applied to existing posts/pages successfully.', 'fpw-category-thumbnails' ) . '</strong></p></div>' . PHP_EOL;
		
	/*	display message about remove status */
	if ( $_POST['fpw_cat_thmb_submit_remove'] )
		echo '	<div id="message" class="updated fade"><p><strong>' . __( 'All thumbnails removed successfully.', 'fpw-category-thumbnails' ) . '</strong></p></div>' . PHP_EOL;

	/*	about instructions */
	echo	'	<p class="alignright">' . __( 'For instructions click on', 'fpw-category-thumbnails' ) . ' <strong>' . __( 'Help', 'fpw-category-thumbnails' ) . '</strong> ' . __( 'above', 'fpw-category-thumbnails' ) . '.</p>' . PHP_EOL;

	/*	the form starts here */
	echo '	<p>' . PHP_EOL;
	echo '		<form name="fpw_cat_thmb_form" action="';
	print '?page=' . basename( __FILE__, '.php' );
	echo '" method="post">' . PHP_EOL;
	
	/*	protect this form with nonce */
	if ( function_exists('wp_nonce_field') ) 
		wp_nonce_field('fpw_cat_thumbs_options_', 'updates'); 

	/*	do not overwrite checkbox */
	echo '			<input type="checkbox" name="donotover" value="yes"';
	if ( $fpw_options[ 'donotover' ] ) echo ' checked';
	echo "> " . __( "Do not overwrite if post/page has thumbnail assigned already", 'fpw-category-thumbnails' ) . "<br />" . PHP_EOL;

	/*	cleanup checkbox */
	echo '			<input type="checkbox" name="cleanup" value="yes"';
	if ( $do_cleanup ) echo ' checked';
	echo "> " . __( "Remove plugin's data from database on uninstall", 'fpw-category-thumbnails' ) . "<br /><br />" . PHP_EOL;

	/* start of the table */
	echo '			<table class="widefat">' . PHP_EOL;
	echo '				<tr>' . PHP_EOL;
	echo '					<th width="25%" style="text-align: left;">' . __( 'Category (ID)', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
	echo '					<th style="text-align: left;">' . __( 'Image ID', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
	echo '				</tr>' . PHP_EOL;

	/*	build form's input fields */
	reset( $assignments );
	reset($categories);
	$i = 0;
	while ( strlen( key( $assignments ) ) ) {
		echo '				<tr>' . PHP_EOL;
		echo '					<td>'; 
		$indent = str_repeat('&nbsp;', $categories[key($categories)][0] * 4);
		echo $indent.$categories[key($categories)][1]->cat_name.' ('.$categories[key($categories)][1]->cat_ID.')'; 
		echo '</td>' . PHP_EOL;
		echo '					<td><input type="text" size="10" maxlength="10" name="val';
		echo key($assignments); 
		echo '" value="';
		echo $assignments[key($assignments)]; 
		echo '" /></td>' . PHP_EOL;
		echo '				</tr>' . PHP_EOL;
		$i++;
		next($assignments);
		next($categories);
	}
	
	/*	end of the table */
	echo '			</table>' . PHP_EOL;
	
	/*	submit button */
	echo '			<div class="inputbutton"><input type="submit" name="fpw_cat_thmb_submit" value="' . __( 'Update Settings', 'fpw-category-thumbnails' ) . '" /> ';
	echo '<input type="submit" name="fpw_cat_thmb_submit_apply" value="' . __( 'Apply to all existing posts/pages', 'fpw-category-thumbnails' ) . '" /> ';
	echo '<input type="submit" name="fpw_cat_thmb_submit_remove" value="' . __( 'Remove all thumbnails from existing posts/pages', 'fpw-category-thumbnails' ) . '" /></div>' . PHP_EOL;
	
	/*	end of form */
	echo '		</form>' . PHP_EOL;
	echo '	</p>' . PHP_EOL;
	echo '</div>' . PHP_EOL;
}

/*	----------------------------------------------------------------------
	Main action - sets the value of post's _thumbnail_id based on category
	assignments
	------------------------------------------------------------------- */

add_action( 'save_post', 'fpw_update_category_thumbnail_id', 10, 2 );
	
function fpw_update_category_thumbnail_id($post_id, $post) {
	/*	we don't want to apply changes to post's revision */
	if ( 'revision' == $post->post_type )
		return;
	/*	this is actual post */
	$thumb_id = get_post_meta( $post_id, '_thumbnail_id', TRUE );
	$do_notover = get_option( 'fpw_category_thumb_opt' );
	if ( $do_notover )
		$do_notover = $do_notover[ 'donotover' ]; 
	$map = get_option( 'fpw_category_thumb_map' );
	if ( $map ) {
		$cat = get_the_category( $post_id );
		foreach ( $cat as $c ) {
			if ( ( array_key_exists( $c->cat_ID, $map ) ) && ( ( '' == $thumb_id ) || !( $do_notover ) ) )
				update_post_meta( $post_id, '_thumbnail_id', $map[$c->cat_ID] );
  		}
	}
}	
?>