<?php
/*
Plugin Name: FPW Category Thumbnails
Description: Sets post/page thumbnail based on category. Built-in FPW Post Thumbnails.
Plugin URI: http://fw2s.com/fpw-category-thumbnails-plugin/
Version: 1.6.9
Author: Frank P. Walentynowicz
Author URI: http://fw2s.com/
Text Domain: fpw-category-thumbnails
Domain Path: /languages
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
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

//	quit if not wp 3.3 or higher
global $wp_version;

if ( version_compare( $wp_version, '3.3', '<' ) ) 
	wp_die( '<p style="text-align:center">Cannot activate! FPW Category Thumbnails plugin ' . 
			'requires WordPress version 3.3 or higher!</p><p style="text-align:center">' . 
			'<a href="/wp-admin/plugins.php" title="Go back to Installed Plugins">' . 
			'Go back to Installed Plugins</a></p>' );

//	quit if standalone FPW Post Thumbnails plugin is active
if ( class_exists( 'fpwPostThumbnails' ) ) {
	$ver = ( defined( 'FPW_POST_THUMBNAILS_VERSION' ) ) ? ' ' . FPW_POST_THUMBNAILS_VERSION : '';
	wp_die( '<p style="text-align">Cannot activate! Standalone ' . 
			'FPW Post Thumbnails' . $ver . 
			' plugin is installed and active. Deactivate and remove ' . 
			'it before trying to activate FPW Category Thumbnails</p><p style="text-align:center">' . 
			'<a href="/wp-admin/plugins.php" title="Go back to Installed Plugins">' . 
			'Go back to Installed Plugins</a></p>' );
}

global $fpw_CT, $fpw_PT;

if ( is_admin() ) {
	//	back end
	require_once dirname( __FILE__ ) . '/classes/fpw-category-thumbnails-class.php';
	$fpw_CT = new fpwCategoryThumbnails( dirname( __FILE__ ), '1.6.9' );
	$o = get_option( 'fpw_category_thumb_opt' );
	if ( is_array( $o ) && $o[ 'fpt' ] ) {
		require_once dirname( __FILE__ ) . '/classes/fpw-post-thumbnails-class.php';
		$fpw_PT = new fpwPostThumbnails( dirname( __FILE__ ), '1.6.9' );
	}
} else {
	//	front end
	require_once dirname( __FILE__ ) . '/classes/fpw-category-thumbnails-front-class.php';
	$oFPT = get_option( 'fpw_post_thumbnails_options' );
	$hide = ( is_array( $oFPT ) && $oFPT[ 'nothepostthumbnail' ] );
	$fpw_CT = new fpwCategoryThumbnails( dirname( __FILE__ ), '1.6.9', $hide );
	if ( is_array( $oFPT ) && ( $oFPT[ 'content' ][ 'enabled' ] || $oFPT[ 'excerpt' ][ 'enabled' ] ) ) {
		require_once dirname( __FILE__ ) . '/classes/fpw-post-thumbnails-front-class.php';
		$fpw_PT = new fpwPostThumbnails( dirname( __FILE__ ), '1.6.9' );
	}
}
