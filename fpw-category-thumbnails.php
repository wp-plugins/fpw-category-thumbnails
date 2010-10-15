<?php
/*
Plugin Name: FPW Category Thumbnails
Description: Sets post/page thumbnail based on category.
Plugin URI: http://fw2s.com/2010/10/14/fpw-category-thumbnails-plugin/
Version: 1.0
Author: Frank P. Walentynowicz
Author URI: http://fw2s.com/

Copyright 2010 Frank P. Walentynowicz (email : frankpw@fw2s.com)

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

/*

	Administration - load text domain for translation
	
*/
add_action('init', 'fpw_category_thumbnails_init', 1);

function fpw_category_thumbnails_init(){
	load_plugin_textdomain( 'fpw-category-thumbnails', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}	
/*	
	--------------------------------------------------
	Adimistration - register plugin's menu in Settings
	--------------------------------------------------
*/

add_action('admin_menu', 'fpw_cat_thumbs_settings_menu');

function fpw_cat_thumbs_settings_menu() {
	$page_title = __('FPW Category Thumbnails - Settings', 'fpw-category-thumbnails');
	$menu_title = __('FPW Category Thumbnails', 'fpw-category-thumbnails');
	add_options_page( $page_title, $menu_title, 'administrator', 'fpw-category-thumbnails', 'fpw_cat_thumbs_options');
}

/*
	---------------------------------------
	Administration - plugin's settings page
	---------------------------------------	
*/

function fpw_cat_thumbs_options() {
	/* this set of arguments will give you a list of all post categories */
	$arg = array('hide_empty' => 0,'pad_counts' => 0,'type' => 'post');
	
	/* get all categories */
	$cats = get_categories($arg);
	
	/*	build initial associative array(category_name => thumbnail_id)
		where all values are 0 */
	$assignments = array();
	foreach ( $cats as $category ) {
		$assignments[$category->name] = 0;
	}
	
	/*	create a copy of above array which will be used to strip
		all elements with 0 values from the array passed to
		update_option function */
	$azeroes = $assignments;
	
	/*	check if changes were submitted */
	if ($_POST['fpw_cat_thmb_submit']) {    
		$i = 0;
		
		/*	inserting posted values into $assignments array */ 
        while ( strlen( key( $assignments ) ) ) {
        	$assignments[key( $assignments )] = $_POST['val'.$i];
        	$i++;
			next($assignments);
		}
		
		/*	create array with all 0 valued elements removed */
		$option = array_diff_assoc($assignments, $azeroes);
		
		/*	check nonce before updating database */
		check_admin_referer('fpw_cat_thumbs_options_', 'updates');
		
		/*	database update */
		$updateok = update_option( 'fpw_category_thumb_ids', $option );
	}
	
	/*	get assignments from database */
	$opt = get_option( 'fpw_category_thumb_ids' );
	
	/* update $assignments array with values from database */
	if ( $opt ) {
		while ( strlen( key( $assignments ) ) ) {
			if ( array_key_exists( key( $assignments ), $opt ) ) {
				$assignments[key( $assignments )] = $opt[key( $assignments )];	
			}
			next( $assignments );
		}
	}

/*
	---------------------------------
	Administration - assignments page
	---------------------------------
*/
	echo '<div class="wrap">' . PHP_EOL;
	echo '	<h2>' . __( 'FPW Category Thumbnails - Settings', 'fpw-category-thumbnails' ) . '</h2>' . PHP_EOL;

    /*	display message about update status */
	if ( $_POST['fpw_cat_thmb_submit'] )
		if ( $updateok ) {
			echo '	<div id="message" class="updated fade">' . __( 'Settings updated successfully.', 'fpw-category-thumbnails' ) . '</div>' . PHP_EOL;
		} else {
			echo '	<div id="message" class="updated fade">' . __( 'No changes detected. Nothing to update.', 'fpw-category-thumbnails' ) . '</div>' . PHP_EOL;
		}
	
	/*	display description block */
	echo '	<h3>' . __( 'Description', 'fpw-category-thumbnails' ) . '</h3>' . PHP_EOL;
	echo '	<p>' . __( 'This plugin inserts a thumbnail based on category / thumbnail mapping while post / page is being created or updated.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
	echo '	<strong>' . __( 'Note', 'fpw-category-thumbnails' ) . '</strong>: ' . __( 'please remember that your theme must support post thumbnails.', 'fpw-category-thumbnails' ) . '</p>' . PHP_EOL;
	
	/*	display instructions block */
	echo '	<h3>' . __( 'Instructions', 'fpw-category-thumbnails' ) . '</h3>' . PHP_EOL;
	echo '	<p>' . __( 'Enter <strong>IDs</strong> of thumbnail images for corresponding categories.', 'fpw-category-thumbnails' ) . '<br />' . PHP_EOL;
	echo '	' . __( 'Enter <strong>0</strong> for categories without assignment.', 'fpw-category-thumbnails' ) . '</p>' . PHP_EOL;
	
	/*	the form starts here */
	echo '	<p>' . PHP_EOL;
	echo '		<form name="fpw_cat_thmb_form" action="';
	print '?page=' . basename( __FILE__, '.php' );
	echo '" method="post">' . PHP_EOL;
	
	/*	protect this form with nonce */
	if ( function_exists('wp_nonce_field') ) 
		wp_nonce_field('fpw_cat_thumbs_options_', 'updates'); 

	echo '			<table class="widefat">' . PHP_EOL;
	echo '				<tr>' . PHP_EOL;
	echo '					<th width="25%" style="text-align: left;">' . __( 'Category', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
	echo '					<th style="text-align: left;">' . __( 'Image ID', 'fpw-category-thumbnails' ) . '</th>' . PHP_EOL;
	echo '				</tr>' . PHP_EOL;

	/*	build form's input fields */
	reset( $assignments );
	$i = 0;
	while ( strlen( key( $assignments ) ) ) {
		echo '				<tr>' . PHP_EOL;
		echo '					<td>'; 
		echo key($assignments); 
		echo '</td>' . PHP_EOL;
		echo '					<td><input type="text" size="10" maxlength="10" name="val';
		echo $i; 
		echo '" value="';
		echo $assignments[key($assignments)]; 
		echo '" /></td>' . PHP_EOL;
		echo '				</tr>' . PHP_EOL;
		$i++;
		next($assignments);
	}
	
	/*	end of table */
	echo '			</table>' . PHP_EOL;
	
	/*	submit button */
	echo '			<p class="submit"><input type="submit" name="fpw_cat_thmb_submit" value="' . __( 'Update', 'fpw-category-thumbnails' ) . '" /></p>' . PHP_EOL;
	
	/*	end of form */
	echo '		</form>' . PHP_EOL;
	echo '	</p>' . PHP_EOL;
	echo '</div>' . PHP_EOL;
}

/*
	----------------------------------------------------------------------
	Main action - sets the value of post's _thumbnail_id based on category
	assignments
	----------------------------------------------------------------------
*/
	
function fpw_update_category_thumbnail_id($post_id, $post) {
	$opt = get_option( 'fpw_category_thumb_ids' );
	if ( $opt ) {
		$cat = get_the_category( $post_id );
		foreach ( $cat as $c ) {
			if ( array_key_exists( $c->name, $opt ) ) {
				update_post_meta( $post_id, '_thumbnail_id', $opt[$c->name] );
			}
  		}
	}
}	
add_action( 'save_post', 'fpw_update_category_thumbnail_id', 10, 2 );
?>
