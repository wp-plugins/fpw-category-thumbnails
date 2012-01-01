<?php
/*
Plugin Name: FPW Category Thumbnails
Description: Sets post/page thumbnail based on category.
Plugin URI: http://fw2s.com/2010/10/14/fpw-category-thumbnails-plugin/
Version: 1.4.1
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

//	Plugin class
class fpwCategoryThumbnails {
	public	$pluginOptions;
	public	$pluginPath;
	public	$pluginUrl;
	public	$pluginVersion;
	public	$pluginPage;
	
	//	constructor
	public	function __construct() {
		global $wp_version;

		//	set plugin's path
		$this->pluginPath = dirname(__FILE__);
		
		//	set plugin's url
		$this->pluginUrl = WP_PLUGIN_URL . '/fpw-category-thumbnails';
		
		//	set version
		$this->pluginVersion = '1.4.0';
		
		//	actions and filters
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'admin_menu', array( &$this, 'adminMenu' ) );
		add_action( 'admin_init', array( &$this, 'disableFlashUploader' ) );
		add_action( 'wp_ajax_fpw_fs_get_file', array( &$this, 'fpw_fs_get_file_ajax' ) );
		add_action( 'save_post', array( &$this, 'updateID' ), 10, 2 );
		add_action( 'after_plugin_row_fpw-category-thumbnails/fpw-category-thumbnails.php', array( &$this, 'afterPluginMeta' ), 10, 2 );

		add_filter( 'plugin_action_links_fpw-category-thumbnails/fpw-category-thumbnails.php', array( &$this, 'pluginLinks' ), 10, 2);
		add_filter( 'plugin_row_meta', array( &$this, 'pluginMetaLinks'), 10, 2 );

		register_activation_hook( __FILE__, array( &$this, 'pluginActivate' ) );
		
		//	Read plugin's options
		$this->pluginOptions = $this->getOptions();

		if ( '3.1' <= $wp_version ) {
			if ( $_POST[ 'buttonPressed' ] ) 
				$this->pluginOptions[ 'abar' ] = ( $_POST[ 'abar' ] == 'yes' ); 
			if ( $this->pluginOptions[ 'abar' ] ) 
				add_action( 'admin_bar_menu', array( &$this, 'pluginToAdminBar' ), 1010 );
		}
		
		if ( $this->pluginOptions[ 'dash' ] ) 
			add_action( 'wp_dashboard_setup', array( &$this, 'addDashboardWidget' ) );
	}

	//	Register plugin's textdomain
	public function init() {
		load_plugin_textdomain( 'fpw-fct', false, $this->pluginPath . '/languages' );
	} 

	//	Register admin menu
	public function adminMenu() {
		global 	$wp_version;
			
		$page_title = __( 'FPW Category Thumbnails', 'fpw-fct' ) . ' (' . $this->pluginVersion . ')';
		$menu_title = __( 'FPW Category Thumbnails', 'fpw-fct' );
		$this->pluginPage = add_options_page( $page_title, $menu_title, 'manage_options', 'fpw-category-thumbnails', array( &$this, 'pluginSettings' ) );
		
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueueScripts' ) );
	
		if ( '3.3' <= $wp_version ) {
			add_action( 'load-' . $this->pluginPage, array( &$this, 'help33' ) );
		} else {
			add_filter( 'contextual_help', array( &$this, 'help'), 10, 3 );
		}
	}

	//	Register styles, scripts, and localize javascript
	public function enqueueScripts( $hook ) {
		if ( ( 'settings_page_fpw-category-thumbnails' == $hook ) || ( 'media-upload-popup' == $hook ) ) {
			wp_register_style( 'fpw-fs-alerts', plugins_url( '/fpw-category-thumbnails/js/css/jquery.alerts.css' ) );
			wp_register_script( 'fpw-fs-alerts', plugins_url( '/fpw-category-thumbnails/js/jquery.alerts.js' ), array( 'jquery' ) );
			wp_register_script( 'fpw-file-select', plugins_url( '/fpw-category-thumbnails/js/fpw-file-select.js' ), array( 'jquery', 'fpw-fs-alerts', 'media-upload', 'thickbox' ) );
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'fpw-fs-alerts');
			wp_enqueue_script( 'fpw-fs-alerts' );
			wp_enqueue_script( 'fpw-file-select' );
			$protocol = isset( $_SERVER[ 'HTTPS' ] ) ? 'https://' : 'http://';
			wp_localize_script( 'fpw-file-select', 'fpw_file_select', array(
				'ajaxurl'			=> admin_url( 'admin-ajax.php', $protocol ),
				'text_select_file'	=> esc_html( __( 'Get ID', 'fpw-fct' ) ),
				'apply_line_1_1'	=> esc_html( __( 'This action will add thumbnails based on current settings to', 'fpw-fct' ) ),
				'apply_line_1_2'	=> esc_html( __( 'ALL', 'fpw-fct' ) ),
				'apply_line_1_3'	=> esc_html( __( 'existing posts / pages.', 'fpw-fct' ) ),
				'apply_line_1_4'	=> esc_html( __( 'Option', 'fpw-fct' ) ),
				'apply_line_1_5'	=> esc_html( __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) ),
				'apply_line_1_6'	=> esc_html( __( 'will be respected.', 'fpw-fct' ) ),
				'apply_line_2'		=> esc_html( __( 'Are you sure you want to proceed?', 'fpw-fct' ) ),
				'remove_line_1_1'	=> esc_html( __( 'This action', 'fpw-fct' ) ),
				'remove_line_1_2'	=> esc_html( __( 'WILL REMOVE', 'fpw-fct' ) ),
				'remove_line_1_3'	=> esc_html( __( 'thumbnails from', 'fpw-fct' ) ),
				'remove_line_1_4'	=> esc_html( __( 'ALL', 'fpw-fct' ) ),
				'remove_line_1_5'	=> esc_html( __( 'existing posts / pages.', 'fpw-fct' ) ),
				'remove_line_1_6'	=> esc_html( __( 'Option', 'fpw-fct' ) ),
				'remove_line_1_7'	=> esc_html( __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) ),
				'remove_line_1_8'	=> esc_html( __( 'WILL NOT', 'fpw-fct' ) ),
				'remove_line_1_9'	=> esc_html( __( 'be respected!', 'fpw-fct' ) ),
				'clear_line_1'		=> esc_html( __( 'Are you sure you want to clear this ID?', 'fpw-fct' ) ),
				'tb_show_title'		=> esc_html( __( 'Get Image ID', 'fpw-fct' ) ),
				'confirm_header'	=> esc_html( __( 'Please confirm', 'fpw-fct' ) ),
				'help_link_text'	=> esc_html( __( 'Help for FPW Category Thumbnails', 'fpw-fct' ) )
				));
			wp_localize_script( 'fpw-fs-alerts', 'fpw_fs_alerts', array (
				'text_ok'			=> esc_html( __( 'OK', 'fpw-fct' ) ),
				'text_cancel'		=> esc_html( __( 'Cancel', 'fpw-fct' ) )
			));
		}
	}

	//	Contextual help for WordPress 3.3+
	public function help33() {
		global	$current_screen;
		
		$sidebar =	'<p style="font-size: larger">' . __( 'More information', 'fpw-fct' ) . '</p>' . 
					'<blockquote><a href="http://fw2s.com/2010/10/14/fpw-category-thumbnails-plugin/" target="_blank">' . __( 'Plugin\' site', 'fpw-fct' ) . '</a></blockquote>' . 
					'<p style="font-size: larger">' . __( 'Support', 'fpw-fct' ) . '</p>' . 
					'<blockquote><a href="http://wordpress.org/tags/fpw-category-thumbnails?forum_id=10" target="_blank">WordPress</a><br />' . 
					'<a href="http://fw2s.com/forums/topic/fpw-category-thumbnail-plugin-support/" target="_blank">FWSS</a></blockquote>'; 
			
		$current_screen->set_help_sidebar( $sidebar );
			
		$intro =	'<p style="font-size: larger">' . __( 'Introduction', 'fpw-fct' ) . '</p>' . 
					'<blockquote style="text-align: justify">' . __( 'Setting featured images for posts / pages could be very time consuming, ', 'fpw-fct' ) . 
					__( 'especially when your media library holds hundreds of pictures. ', 'fpw-fct' ) . 
					__( 'Very often we select the same thumbnail for posts in particular category. ', 'fpw-fct' ) . 
					__( 'This plugin automates the process by inserting a thumbnail based on category / thumbnail mapping while post / page is being created or updated.', 'fpw-fct' ) . '</blockquote></p>' . 
					'<p style="font-size: larger">' . __( 'Note', 'fpw-fct' ) . '</p>' . 
					'<blockquote style="text-align: justify">' . __( 'Please remember that your theme must support post thumbnails.', 'fpw-fct' ) . 
					'</blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Introduction', 'fpw-fct' ),
    		'id'      => 'fpw-fct-help-introduction',
   			'content' => $intro,
		) );
			
		$opts =		'<p style="font-size: larger">' . __( 'Available Options', 'fpw-fct' ) . '</p>' . 
					'<blockquote style="text-align: justify"><strong>Do not overwrite if post / page has thumbnail assigned already</strong> ' . 
					'( checked ) - while the post is being saved the originally set thumbnail will be preserved<br />' . 
					'<strong>Remove plugin\'s data from database on uninstall</strong> ' . 
					'( checked ) - during uninstall procedure all plugin\'s information ( options, mappings ) will be removed from the database<br />' . 
					'<strong>Show plugin\'s info widget on the Dashboard</strong> ' . 
					'( checked ) - a new metabox, showing the state of plugin\'s options, will be added to the Dashboard page<br />' . 
					'<strong>Add this plugin to the Admin Bar</strong> ' . 
					'( checked ) - the plugin\'s link to its settings page will be added to the Admin Bar<br />' . 
					'<strong>width of Image ID column in pixels</strong> - ' . 
					'this value may need to be adjusted for non-English translations of the plugin as widths of buttons could be different</blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Options', 'fpw-fct' ),
    		'id'      => 'fpw-fct-help-options',
	   		'content' => $opts,
		) );

		$mapping =	'<p style="font-size: larger">' . __( 'Mapping', 'fpw-fct' ) . '</p><blockquote style="text-align: justify">' . 
					__( 'Each row of the mapping table represents a category and a thumbnail image ID assigned to it.', 'fpw-fct' ) . ' ' . 
					__( 'First column holds a category name and its ID.', 'fpw-fct' ) . ' ' . 
					__( 'Second column consists of four elements: Image ID - an input field which holds thumbnails image ID,', 'fpw-fct' ) . ' ' . 
					__( 'Get ID, Clear, and Refresh buttons. Third column holds thumbnail\'s preview.', 'fpw-fct' ) . ' ' . 
					__( 'Image ID can be entered manually ( if you remember it ) or by clicking on \'Get ID\' button which will call \'media upload\' overlay.', 'fpw-fct' ) . 
					'</blockquote><p style="font-size: larger">' . __( 'Action Buttons', 'fpw-fct' ) . '</p><blockquote>' . 
					'<table style="width: 100%;"><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Get ID', 'fpw-fct' ) . '" />' . '</td><td style="text-align: justify; vertical-align: middle;">' .  
					__( 'will call \'media upload\' overlay and on return will populate \'Image ID\' input box and \'Preview\' area ( AJAX - without reloading screen )', 'fpw-fct' ) . 
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Clear', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle;">' . 
					__( 'if confirmed it will enter \'0\' as image ID and clear \'Preview\' area ( AJAX - without reloading screen )', 'fpw-fct' ) . 
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Refresh', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle;">' . 
					__( 'when clicked after entering of an image ID manually it will populate \'Preview\' area ( AJAX - without reloading screen )', 'fpw-fct' ) . 
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Update', 'fpw-fct' ) . '" /></td><td>' . __( 'saves modified options and mapping to the database', 'fpw-fct' ) .  
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Apply Mapping', 'fpw-fct' ) . '" /></td><td>' . __( 'adds thumbnails to existing posts / pages based on category mapping', 'fpw-fct' ) . 
					'</td></tr><tr><td style="text-align: left; vertical-align: middle;">' . 
					'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
					__( 'Remove Thumbnails', 'fpw-fct' ) . '" /></td><td>' . __( 'removes thumbnails from all posts /pages regardless of the category', 'fpw-fct' ) . '
					</td></tr></table></blockquote>';
			
		$current_screen->add_help_tab( array(
   			'title'   => __( 'Mapping & Actions', 'fpw-fct' ),
    		'id'      => 'fpw-fct-help-mapping',
	   		'content' => $mapping,
		) );
			
		$faq =		'<p style="font-size: larger">' . __( 'Frequently Asked Questions', 'fpw-fct' ) . '</p><blockquote style="text-align: justify"><strong>' . 
					__( 'Question:', 'fpw-fct' ) . '</strong> ' .
					__( 'I got an ID for the image and assigned it to the category, and the plugin does not display it in posts.', 'fpw-fct' ) . '<br /><strong>' . 
					__( 'Answer:', 'fpw-fct' ) . '</strong> ' . __( 'The plugin does not display thumbnails by itself. This is your theme\'s role.', 'fpw-fct' ) . ' ' . 
					__( 'Read this article', 'fpw-fct' ) . ' ' . 
					'<a href="http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/" target="_blank" rel="nofollow">' . 
					'New in WordPress 2.9 post thumbnail images</a> ' . 
					__( 'by', 'fpw-fct' ) . ' Mark Jaquith ' . __( 'about enabling theme\'s support for post thumbnails.', 'fpw-fct' ) . '<br /><br /><strong>' . 
					__( 'Question:', 'fpw-fct' ) . '</strong> ' . 
					__( 'I\'ve entered ID of a picture from NextGen Gallery and thumbnail doesn\'t show.', 'fpw-fct' ) . '<br><strong>' . 
					__( 'Answer:', 'fpw-fct' ) . '</strong> ' . 
					__( 'IDs from NextGen Gallery must be entered with ngg- prefix, so ID 230 should be entered as ngg-230.', 'fpw-fct' ) . '</blickquote>'; 
			
		$current_screen->add_help_tab( array(
   			'title'   => __( 'FAQ', 'fpw-fct' ),
    		'id'      => 'fpw-fct-help-faq',
	   		'content' => $faq,
		) );
	}
	
	//	Contextual help for Wordpress older than 3.3
	public function help( $contextual_help, $screen_id, $screen ) {

		if ( $screen_id == $this->pluginPage ) {
			$my_help  = '<table class="widefat">';
			$my_help .= '<thead>';
			$my_help .= '<tr>';
			$my_help .= '<th width="50%" style="text-align: left;">' . __( 'Introduction', 'fpw-fct' ) . '</th>';
			$my_help .= '<th width="50%" style="text-align: left;">' . __( 'Options', 'fpw-fct' ) . '</th>';
			$my_help .= '</thead>';
			$my_help .= '<tbody>';
			$my_help .= '<tr>';
			$my_help .= '<td style="vertical-align: top;"><p style="text-align: justify;">' . 
						__( 'Setting featured images for posts / pages could be very time consuming, especially when your media library holds hundreds of pictures.', 'fpw-fct' ) . ' ' . 
						__( 'Very often we select the same thumbnail for posts in particular category.', 'fpw-fct' ) . ' ' . 
						__( 'This plugin automates the process by inserting a thumbnail based on category / thumbnail mapping while post / page is being created or updated.', 'fpw-fct' ) . '</p>' . 
						'<p style="font-size: larger">' . __( 'Note', 'fpw-fct' ) . '</p>' . '<blockquote style="text-align: justify">' . 
						__( 'Please remember that the active theme must support post thumbnails.', 'fpw-fct' ) . '</blockquote></td>';
			$my_help .= '<td style="vertical-align: top;"><p style="text-align: justify"><strong>' . __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) . 
						'</strong> ' . __( '( checked ) - while the post is being saved the originally set thumbnail will be preserved', 'fpt-fct' ) . 
						'<br /><strong>' . __( 'Removes plugin\'s data from database on uninstall', 'fpw-fct' ) . '</strong> ' . 
						__( '( checked ) - during uninstall procedure all plugin\'s information ( options, mappings ) will be removed from the database', 'fpt-fct' ) . 
						'<br /><strong>' . __( 'Show plugin\'s info widget on the Dashboard', 'fpw-fct' ) . 
						'</strong> ' . __( '( checked ) - a new metabox, showing the state of plugin\'s options, will be added to the Dashboard page', 'fpw-fct' ) . 
						'<br /><strong>' . __( 'Add this plugin to the Admin Bar', 'fpw-fct' ) . 
						'</strong> ' . __( '( checked ) - the plugin\'s link to its settings page will be added to the Admin Bar', 'fpw-fct' ) . 
						'<br /><strong>' . __( 'width of Image ID column in pixels', 'fpw-fct' ) . '</strong> - ' . 
						__( 'this value may need to be adjusted for non-English translations of the plugin as widths of buttons could be different', 'fpw-fct' ) . '</p></td>';
			$my_help .= '</tr>';
			$my_help .= '</tbody>';
			$my_help .= '</table><br />';						

			$my_help .= '<table class="widefat">';
			$my_help .= '<thead>';
			$my_help .= '<tr>';
			$my_help .= '<th width="50%" style="text-align: left;">' . __( 'Mapping & Actions', 'fpw-fct' ) . '</th>';
			$my_help .= '<th width="50%" style="text-align: left;">' . __( 'FAQ', 'fpw-fct' ) . '</th>';
			$my_help .= '</thead>';
			$my_help .= '<tbody>';
			$my_help .= '<tr>';
			$my_help .= '<td style="vertical-align: top;"><p style="text-align: justify;">' . 
						__( 'Each row of the mapping table represents a category and a thumbnail image ID assigned to it.', 'fpw-fct' ) . ' ' . 
						__( 'First column holds a category name and its ID.', 'fpw-fct' ) . ' ' . 
						__( 'Second column consists of four elements: Image ID - an input field which holds thumbnails image ID,', 'fpw-fct' ) . ' ' . 
						__( 'Get ID, Clear, and Refresh buttons. Third column holds thumbnail\'s preview.', 'fpw-fct' ) . ' ' . 
						__( 'Image ID can be entered manually ( if you remember it ) or by clicking on \'Get ID\' button which will call \'media upload\' overlay.', 'fpw-fct' ) . 
						'</p><p style="font-size: larger">' . __( 'Action Buttons', 'fpw-fct' ) . '</p><blockquote>' . 
						'<table style="width: 100%; border: 0; border-collapse: collapse; padding: 0;"><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Get ID', 'fpw-fct' ) . '" />' . '</td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' .  
						__( 'will call \'media upload\' overlay and on return will populate \'Image ID\' input box and \'Preview\' area ( AJAX - without reloading screen )', 'fpw-fct' ) . 
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Clear', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'if confirmed it will enter \'0\' as image ID and clear \'Preview\' area ( AJAX - without reloading screen )', 'fpw-fct' ) . 
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Refresh', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'when clicked after entering of an image ID manually it will populate \'Preview\' area ( AJAX - without reloading screen )', 'fpw-fct' ) . 
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Update', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'saves modified options and mapping to the database', 'fpw-fct' ) .  
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Apply Mapping', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'adds thumbnails to existing posts / pages based on category mapping', 'fpw-fct' ) . 
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Remove Thumbnails', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'removes thumbnails from all posts /pages regardless of the category', 'fpw-fct' ) . 
						'</td></tr></table></blockquote></td>';
			$my_help .= '<td style="vertical-align: top;"><p style="text-align: justify"><strong>' . 
						__( 'Question:', 'fpw-fct' ) . '</strong> ' .
						__( 'I got an ID for the image and assigned it to the category, and the plugin does not display it in posts.', 'fpw-fct' ) . '<br /><strong>' . 
						__( 'Answer:', 'fpw-fct' ) . '</strong> ' . __( 'The plugin does not display thumbnails by itself. This is your theme\'s role.', 'fpw-fct' ) . ' ' . 
						__( 'Read this article', 'fpw-fct' ) . ' ' . 
						'<a href="http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/" target="_blank" rel="nofollow">' . 
						'New in WordPress 2.9 post thumbnail images</a> ' . 
						__( 'by', 'fpw-fct' ) . ' Mark Jaquith ' . __( 'about enabling theme\'s support for post thumbnails.', 'fpw-fct' ) . '<br /><br /><strong>' . 
						__( 'Question:', 'fpw-fct' ) . '</strong> ' . 
						__( 'I\'ve entered ID of a picture from NextGen Gallery and thumbnail doesn\'t show.', 'fpw-fct' ) . '<br><strong>' . 
						__( 'Answer:', 'fpw-fct' ) . '</strong> ' . 
						__( 'IDs from NextGen Gallery must be entered with ngg- prefix, so ID 230 should be entered as ngg-230.', 'fpw-fct' ) . '</p></td>';
			$my_help .= '</tr>';
			$my_help .= '</tbody>';
			$my_help .= '</table>';						

			$contextual_help = $my_help;
		}
		return $contextual_help; 
	}

	//	Disable Flash uploader ( WordPress older than 3.3 )
	public function disableFlashUploader() {
		if ( basename( $_SERVER['SCRIPT_FILENAME'] ) == 'media-upload.php' && array_key_exists( 'fpw_fs_field', $_GET ) )
			add_filter( 'flash_uploader', array( &$this, create_function( '$a','return false;' ) ), 5 );
	}

	// AJAX wrapper to get image HTML
	public function fpw_fs_get_file_ajax() {
		if ( 'ngg-' == substr( $_REQUEST['id'], 0, 4 ) ) {
			$id = substr( $_REQUEST['id'], 4 );
			$picture = nggdb::find_image($id);
			$pic = $picture->imageURL;
			$w = $picture->meta_data['thumbnail']['width'];
			$h = $picture->meta_data['thumbnail']['height'];
			$pic = '<img width="' . $w . '" height="' . $h . '" src="' . $pic . '" />';
			echo $pic;
		} else {
			if ( wp_attachment_is_image( $_REQUEST['id'] ) ) {
				echo wp_get_attachment_image( $_REQUEST['id'], $_REQUEST['size'] );
			} else {
				echo '';
			}
		}
		die();
	}

	//	Add update information after plugin meta
	public function afterPluginMeta( $file, $plugin_data ) {
		$current = get_site_transient( 'update_plugins' );
		if ( !isset( $current -> response[ $file ] ) ) 
			return false;
		$url = "http://fw2s.com/fpwcatthumbsupdate.txt";
		$update = wp_remote_fopen( $url );
		echo '<tr class="plugin-update-tr"><td></td><td></td><td class="plugin-update"><div class="update-message">' . 
			'<img class="alignleft" src="' . $this->pluginUrl . '/Thumbs_Up.png" width="64">' . $update . '</div></td></tr>';
	}

	//	Add link to Donation to plugins meta
	public function pluginMetaLinks( $links, $file ) {
		if ( 'fpw-category-thumbnails/fpw-category-thumbnails.php' == $file ) 
			$links[] = '<a href="http://fw2s.com/payments-and-donations/" target="_blank">' . __( "Donate", "fpw-fct" ) . '</a>';
		return $links;
	}
	
	//	Add link to settings page in plugins list
	public function pluginLinks( $links, $file ) {
   		$settings_link = '<a href="' . site_url( '/wp-admin/' ) . 'options-general.php?page=fpw-category-thumbnails">' . __( 'Settings', 'fpw-fct' ) . '</a>';
		array_unshift( $links, $settings_link );
    	return $links;
	}

	//	Register plugin's dashboard widget
	public function addDashboardWidget() {
		$widget_title = __( 'FPW Category Thumbnails', 'fpw-fct' );
		wp_add_dashboard_widget( 'fpw_fct_dashboard_widget', $widget_title, array( &$this, 'dashboardWidget' ) );
	}

	//	Display plugin Dashboard Widget's content
	public function dashboardWidget() {
		global	$wp_version;
	
		if ( !current_theme_supports( 'post-thumbnails') )
			echo 	'<p style="font-family:arial;font-size:.9em;color:red;"><strong>' . 
					__( 'WARNING: Your theme has no support for <em>post thumbnails</em>!', 'fpw-fct' ) . '</strong></p>'; 
	
		if ( $this->pluginOptions[ 'donotover' ] ) {
			$dont = __( 'On', 'fpw-fct' );
		} else {
			$dont = __( 'Off', 'fpw-fct' );
		}
	
		if ( $this->pluginOptions[ 'clean' ] ) {
			$clean = __( 'On', 'fpw-fct' );
		} else {
			$clean = __( 'Off', 'fpw-fct' );
		}
	
		if ( '3.1' <= $wp_version ) { 
			if ( $this->pluginOptions[ 'abar' ] ) {
				$abar = __( 'On', 'fpw-fct' );
			} else {
				$abar = __( 'Off', 'fpw-fct' );
			}
		}
	
		echo '<p style="font-family:arial;font-size:.9em;">' . __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) . ' ( <strong>' . $dont . '</strong> )<br />';
	
		if ( '3.1' <= $wp_version ) 
			echo __( 'Add this plugin to the Admin Bar', 'fpw-fct' ) . ' ( <strong>' . $abar . '</strong> )<br />';
	
		echo __( 'Remove plugin\'s data from database on uninstall', 'fpw-fct' ) . ' ( <strong>' . $clean . '</strong> )</p>';
	} 
	
	//	Uninstall file maintenance
	public function pluginActivate() {
		//	if cleanup requested make uninstall.php otherwise make uninstall.txt
		if ( $this->pluginOptions[ 'clean' ] ) {
			if ( file_exists( $this->pluginPath . '/uninstall.txt' ) ) 
				rename( $this->pluginPath . '/uninstall.txt', $this->pluginPath . '/uninstall.php' );
		} else {
			if ( file_exists( $this->pluginPath . '/uninstall.php' ) ) 
				rename( $this->pluginPath . '/uninstall.php', $this->pluginPath . '/uninstall.txt' );
		}
	}	
	
	//	Add plugin to admin bar ( WordPress 3.1+ )	
	public function pluginToAdminBar() {
		if ( current_user_can( 'manage_options' ) ) {
			global 	$wp_admin_bar,
					$wp_version;

			$main = array(
				'id' => 'fpw_plugins',
				'title' => __( 'FPW Plugins', 'fpw-fct' ),
				'href' => '#' );

			$subm = array(
				'id' => 'fpw_bar_category_thumbnails',
				'parent' => 'fpw_plugins',
				'title' => __( 'FPW Category Thumbnails', 'fpw-fct' ),
				'href' => get_admin_url() . 'options-general.php?page=fpw-category-thumbnails' );

			if ( '3.3' <= $wp_version ) {
				$addmain = ( is_array( $wp_admin_bar->get_node( 'fpw_plugins' ) ) ) ? false : true;
				// echo $addmain; die();
			} else {
				$addmain = ( is_array( $wp_admin_bar->menu->fpw_plugins ) ) ? false : true;
			} 

			if ( $addmain )
				$wp_admin_bar->add_menu( $main );
			$wp_admin_bar->add_menu( $subm );
		}
	}
	
	//	Plugin's Settings page
	public function pluginSettings() {
		global $wp_version;

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
			$this->pluginOptions[ 'clean' ] = ( $_POST[ 'cleanup' ] == 'yes' );
			$this->pluginOptions[ 'donotover' ] = ( $_POST[ 'donotover' ] == 'yes' );
			$this->pluginOptions[ 'dash' ] = ( $_POST[ 'dash' ] == 'yes' );
			if ( '3.1' <= $wp_version ) 
				$this->pluginOptions[ 'abar' ] = ( $_POST[ 'abar' ] == 'yes' );
			if ( !ctype_digit( $_POST[ 'cwidth' ] ) ) { 
				$this->pluginOptions[ 'width' ] = '283';
			} else {
				$this->pluginOptions[ 'width' ] = $_POST[ 'cwidth' ];
			}
		
			$update_options_ok = ( update_option( 'fpw_category_thumb_opt', $this->pluginOptions ) );
		
			// 	if any changes to options then check uninstall file's extension
			if ( $update_options_ok ) 
				$this->pluginActivate();

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
						$post_id = $post->ID;
						//	make sure this is not a revision nor draft
						if ( ( 'revision' != $post->post_type ) && ( 'draft' != $post->post_status ) )
							$this->updateID( $post_id, $post );
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
		echo '<div id="icon-options-general" class="icon32"></div><h2>' . __( 'FPW Category Thumbnails', 'fpw-fct' ) . ' (' . $this->pluginVersion . ')</h2>';

    	//	display warning if current theme doesn't support post thumbnails
    	if ( !current_theme_supports( 'post-thumbnails' ) ) {
    		echo '	<div id="message" class="error fade" style="background-color: #CCFFFF; color: red;"><p><strong>';
			echo __( 'WARNING: Your theme has no support for <em>post thumbnails</em>!', 'fpw-fct' ) . ' '; 
			echo __( 'You can continue with <em>Settings</em> but until you add <code>add_theme_support( \'post-thumbnails\' );</code> to the theme\'s functions.php you will not be able to display thumbnails.', 'fpw-fct' ); 
			echo '</strong></p></div>';
		}

		//	display message about update status
		if ( 'Update' == $_POST[ 'buttonPressed' ] )
			if ( $update_options_ok || $update_mapping_ok ) {
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'Updated successfully.', 'fpw-fct' ) . '</strong></p></div>';
			} else {
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'No changes detected. Nothing to update.', 'fpw-fct' ) . '</strong></p></div>';
			}

		//	display message about apply status
		if ( 'Apply' == $_POST[ 'buttonPressed' ] )
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'Applied thumbnails to existing posts / pages successfully.', 'fpw-fct' ) . '</strong></p></div>';

		//	display message about remove status
		if ( 'Remove' == $_POST[ 'buttonPressed' ] )
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'All thumbnails removed successfully.', 'fpw-fct' ) . '</strong></p></div>';

		//	the form starts here
		echo '<p>';
		echo '<form name="fpw_cat_thmb_form" action="';
		print '?page=' . basename( __FILE__, '.php' );
		echo '" method="post">';

		//	protect this form with nonce
		echo '<input name="fpw-fct-nonce" type="hidden" value="' . wp_create_nonce( 'fpw-fct-nonce' ) . '" />';

		//	do not overwrite checkbox
		echo '<input type="checkbox" name="donotover" value="yes"';
		if ( $this->pluginOptions[ 'donotover' ] ) 
			echo ' checked';
		echo '> ' . __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) . '<br />';

		//	cleanup checkbox
		echo '<input type="checkbox" name="cleanup" value="yes"';
		if ( $this->pluginOptions[ 'clean' ] ) 
			echo ' checked';
		echo '> ' . __( "Remove plugin's data from database on uninstall", 'fpw-fct' ) . '<br />';

		//	dashboard widget checkbox
		echo '<input type="checkbox" name="dash" value="yes"';
		if ( $this->pluginOptions[ 'dash' ] ) 
			echo ' checked';
		echo '> ' . __( "Show plugin's info widget on the Dashboard", 'fpw-fct' ) . '<br />';

		//	add plugin to admin bar checkbox
		if ( '3.1' <= $wp_version ) {
			echo '<input type="checkbox" name="abar" value="yes"';
			if ( $this->pluginOptions[ 'abar' ] ) 
				echo ' checked';
			echo '> ' . __( 'Add this plugin to the Admin Bar', 'fpw-fct' ) . '<br />';
		}

		//	width of Image ID column
		echo '<br /><input type="text" name="cwidth" size="3" maxlength="3" value="' . $this->pluginOptions[ 'width' ] . '" style="text-align: right">px - ';
		echo __( 'width of Image ID column in pixels', 'fpw-fct' ) . '<br /><br />';

		// start of the table
		echo '<table class="widefat">';
		echo '<thead>';
		echo '<tr>';
		echo '<th style="width: 25%; text-align: left;">' . __( 'Category (ID)', 'fpw-fct' ) . '</th>';
		echo '<th style="width: ' . $this->pluginOptions[ 'width' ] . 'px; text-align: left;">' . __( 'Image ID', 'fpw-fct' ) . '</th>';
		echo '<th style="text-align: left;">' . __( 'Preview', 'fpw-fct' ) . '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<th style="width: 25%; text-align: left;">' . __( 'Category (ID)', 'fpw-fct' ) . '</th>';
		echo '<th style="width: ' . $this->pluginOptions[ 'width' ] . 'px; text-align: left;">' . __( 'Image ID', 'fpw-fct' ) . '</th>';
		echo '<th style="text-align: left;">' . __( 'Preview', 'fpw-fct' ) . '</th>';
		echo '</tr>';
		echo '</tfoot>';
		echo '<tbody>';

		//	build form's input fields and buttons
		reset( $assignments );
		reset( $categories );
		$i = 0;
		while ( strlen( key( $assignments ) ) ) {
			echo '<tr>';
			echo '<td style="vertical-align: middle;">'; 
			$indent = str_repeat( '&nbsp;', $categories[ key( $categories )][ 0 ] * 4);
			echo $indent . $categories[ key( $categories ) ][ 1 ] -> cat_name . ' (' . $categories[ key( $categories )][ 1 ] -> cat_ID . ')'; 
			echo '</td>' . PHP_EOL;
			$this->button( 'val-for-id-' . key( $assignments ) . '-field', $assignments[ key( $assignments ) ], key( $assignments ), $label = __( 'Get ID', 'fpw-fct' ) );
			echo '</tr>';
			$i++;
			next( $assignments );
			next( $categories );
		}

		//	end of the table
		echo '</tbody>';
		echo '</table>';

		//	submit buttons
		echo '<br /><div class="inputbutton"><input title="' . 
			 __( 'Writes modified options and mapping to the database', 'fpw-fct' ) . 
		 	 '" onclick="confirmUpdate();" id="update" class="button-primary fpw-submit" type="button" name="fpw_cat_thmb_submit" value="' . __( 'Update', 'fpw-fct' ) . '" /> ';
		echo '<input onclick="confirmApply();" title="' . 
		 	 __( 'Adds post thumbnail to every existing post / page belonging to the category which has thumbnail id mapped to', 'fpw-fct' ) . 
		 	 '" id="apply" class="button-primary fpw-submit" type="button" name="fpw_cat_thmb_submit_apply" value="' . __( 'Apply Mapping', 'fpw-fct' ) . '" /> ';
		echo '<input onclick="confirmRemove();" title="' . 
			 __( 'Removes thumbnails from all existing posts / pages regardless of the category', 'fpw-fct' ) . 
		 	 '" id="remove" class="button-primary fpw-submit" type="button" name="fpw_cat_thmb_submit_remove" value="' . __( 'Remove Thumbnails', 'fpw-fct' ) . '" />';
		echo '<input id="buttonPressed" type="hidden" value="" name="buttonPressed" /></div>';

		//	end of form
		echo '</form>';
		echo '</p>';
		echo '</div>';
	}
	
	//	Bulid settings form fields
	private function button( $name, $value, $catid, $label = 'Get ID', $preview_size = 'thumbnail', $removable = false ) { ?>
		<td style="vertical-align: middle;"><div>
			<input type="text" size="10" maxlength="10" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>" class="fpw-fs-value" />
			<input type="button" class="button-secondary fpw-fs-button" title="<?php echo __( 'fetches image ID from media library', 'fpw-fct' ); ?>" value="<?php echo __( 'Get ID', 'fpw-fct' ); ?>" />
			<input class="button-secondary btn-for-clear" title="<?php echo __( 'clears \'Image ID\' input value and \'Preview\' area', 'fpw-fct' ); ?>" id="clear-for-id-<?php echo $catid; ?>" type="button" value="<?php echo __( 'Clear', 'fpw-fct' ); ?>" />		
			<input class="button-secondary btn-for-refresh" title="<?php echo __( 'refreshes \'Preview\' area', 'fpw-fct' ); ?>" id="refresh-for-id-<?php echo $catid; ?>" type="button" value="<?php echo __( 'Refresh', 'fpw-fct' ); ?>" />		
			<input type="hidden" value="<?php echo esc_attr( $preview_size ); ?>" name="<?php echo esc_attr( $name ); ?>_preview-size" id="<?php echo esc_attr( $name ); ?>_preview-size" class="fpw-fs-preview-size" />
		</div></td>	
		<td style="vertical-align: middle;">
			<div class="fpw-fs-preview" id="<?php echo esc_attr( $name ); ?>_preview">
			<?php
				if ( $value ) {
					if ( '0' == $value ) {
						echo '';
					} else {
						if ( ( 'ngg-' == substr( $value, 0, 4 ) ) && class_exists( 'nggdb' ) ) {
							$id = substr( $value, 4 );
							$picture = nggdb::find_image($id);
							if ( !$picture ) {
								echo '';
							} else {
								$pic = $picture->imageURL;
								$w = $picture->meta_data['thumbnail']['width'];
								$h = $picture->meta_data['thumbnail']['height'];
								$pic = '<img width="' . $w . '" height="' . $h . '" src="' . $pic . '" />';
								echo $pic;
							}
						} else {
							if ( wp_attachment_is_image( $value ) ) {
								echo wp_get_attachment_image( $value, $preview_size );
							} else {
								echo '';
							}
						}
					}
				}
			?>
			</div>
		</td>
	<?php }
	
	//	Get plugin's options ( build if not exists )
	private function getOptions() {
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
					'width'		=> '293',
					'abar'		=> FALSE );
			} else {
				$opt = array( 
					'clean'		=> FALSE,
					'donotover' => FALSE,
					'dash'		=> FALSE,
					'width'		=> '293' );
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
			if ( !array_key_exists( 'width', $opt ) || !ctype_digit( $opt[ 'width' ] ) ) { 
				$needs_update = TRUE;
				$opt[ 'width' ] = '293';
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

	/*	------------------------------------------------------------------
	Main action - sets the value of post's _thumbnail_id based on category
	assignments
	------------------------------------------------------------------- */
	public function updateID( $post_id, $post = NULL ) {
		if ( NULL === $post ) 
			return;
		//	we don't want to apply changes to post's revision or drafts
		if ( ( 'revision' == $post->post_type ) || ( 'draft' == $post->post_status ) ) 
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
				if ( $post->post_date === $post->post_modified ) {
					//	in case of a new post we have to ignore setting of $do_notover flag
					//	as the thumbnail of default category will be there already
					if ( array_key_exists( $c->cat_ID, $map ) )
						update_post_meta( $post_id, '_thumbnail_id', $map[ $c->cat_ID ] );
				} else {
					//	modified post - observe $do_notover flag
					if ( ( array_key_exists( $c->cat_ID, $map ) ) && ( ( '' == $thumb_id ) || !( $do_notover ) ) )
						update_post_meta( $post_id, '_thumbnail_id', $map[ $c->cat_ID ] );
				}
  			}
		}
	}	
	 
}

new fpwCategoryThumbnails;

?>