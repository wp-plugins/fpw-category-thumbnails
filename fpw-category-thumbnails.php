<?php
/*
Plugin Name: FPW Category Thumbnails
Description: Sets post/page thumbnail based on category. Built-in FPW Post Thumbnails.
Plugin URI: http://fw2s.com/fpw-category-thumbnails-plugin/
Version: 1.5.1
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

//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
	die( "Direct access to this script is forbidden!" );

//	load FPW library
if ( !function_exists( 'fpw_is_wp_version' ) )
	require_once dirname(__FILE__) . '/lib/fpwlib.php';

//	quit if not wp 3.3 or higher
if ( ! fpw_is_wp_version( '3.3' ) ) 
	wp_die( '<center>Cannot activate! <strong>FPW Category Thumbnails</strong> plugin ' . 
			'requires WordPress version <strong>3.3 or higher</strong>!<br />&nbsp;<br />' . 
			'<a style="border: solid 1px #000; padding: 5px 20px ' . 
			'5px 20px; border-radius: 10px; -moz-border-radius: 10px; ' . 
			'-webkit-border-radius: 10px; text-decoration: none; color: black; ' . 
			'background-color: cyan;" href="/wp-admin/plugins.php" title="Go back to Installed plugins">' . 
			'Back</a></center>' );

global $fpw_CT, $fpw_PT;

$needFPWPostThumbnailsClass = false;

if ( is_admin() ) {
	//	back end
	require_once dirname(__FILE__) . '/classes/fpw-category-thumbnails-class.php';
	
	$needFPWPostThumbnailsClass = true;
	require_once dirname(__FILE__) . '/classes/fpw-post-thumbnails-class.php';
} else {
	//	front end
	require_once dirname(__FILE__) . '/classes/fpw-category-thumbnails-front-class.php';

	$o = get_option( 'fpw_post_thumbnails_options' );
	if ( is_array( $o ) && ( $o[ 'content' ][ 'enabled' ] || $o[ 'excerpt' ][ 'enabled' ] ) ) {
		$needFPWPostThumbnailsClass = true;
		require_once dirname(__FILE__) . '/classes/fpw-post-thumbnails-front-class.php';
	}
}
$fpw_CT = new fpwCategoryThumbnails( dirname(__FILE__), '1.5.1' );

if ( $needFPWPostThumbnailsClass ) {
	$fpw_PT = new fpwPostThumbnails( dirname(__FILE__), '1.5.1' );
}
?>