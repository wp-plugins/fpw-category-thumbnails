<?php
//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

wp_register_style( 'fpw-fs-alerts', plugins_url( '/fpw-category-thumbnails/js/css/jquery.alerts.css' ) );
wp_enqueue_style( 'thickbox' );
wp_enqueue_style( 'fpw-fs-alerts' );
if ( SCRIPT_DEBUG ) {
	wp_enqueue_script( 'fpw-fs-alerts', plugins_url( '/fpw-category-thumbnails/js/jquery.alerts.dev.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'fpw-file-select', plugins_url( '/fpw-category-thumbnails/js/fpw-file-select.dev.js' ), array( 'jquery', 'fpw-fs-alerts', 'media-upload', 'thickbox' ), false, true );
} else {
	wp_enqueue_script( 'fpw-fs-alerts', plugins_url( '/fpw-category-thumbnails/js/jquery.alerts.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'fpw-file-select', plugins_url( '/fpw-category-thumbnails/js/fpw-file-select.js' ), array( 'jquery', 'fpw-fs-alerts', 'media-upload', 'thickbox' ), false, true );
}

$protocol = isset( $_SERVER[ 'HTTPS' ] ) ? 'https://' : 'http://';

wp_localize_script( 'fpw-file-select', 'fpw_file_select', array(
	'ajaxurl'			=> admin_url( 'admin-ajax.php', $protocol ),
	'text_select_file'	=> esc_html( __( 'Get ID', 'fpw-category-thumbnails' ) ),
	'apply_line_1_1'	=> esc_html( __( 'This action will add thumbnails based on current settings to', 'fpw-category-thumbnails' ) ),
	'apply_line_1_2'	=> esc_html( __( 'ALL', 'fpw-category-thumbnails' ) ),
	'apply_line_1_3'	=> esc_html( __( 'existing posts / pages.', 'fpw-category-thumbnails' ) ),
	'apply_line_1_4'	=> esc_html( __( 'Option', 'fpw-category-thumbnails' ) ),
	'apply_line_1_5'	=> esc_html( __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-category-thumbnails' ) ),
	'apply_line_1_6'	=> esc_html( __( 'will be respected.', 'fpw-category-thumbnails' ) ),
	'apply_line_2'		=> esc_html( __( 'Are you sure you want to proceed?', 'fpw-category-thumbnails' ) ),
	'remove_line_1_1'	=> esc_html( __( 'This action', 'fpw-category-thumbnails' ) ),
	'remove_line_1_2'	=> esc_html( __( 'WILL REMOVE', 'fpw-category-thumbnails' ) ),
	'remove_line_1_3'	=> esc_html( __( 'thumbnails from', 'fpw-category-thumbnails' ) ),
	'remove_line_1_4'	=> esc_html( __( 'ALL', 'fpw-category-thumbnails' ) ),
	'remove_line_1_5'	=> esc_html( __( 'posts of any type.', 'fpw-category-thumbnails' ) ),
	'remove_line_1_6'	=> esc_html( __( 'Option', 'fpw-category-thumbnails' ) ),
	'remove_line_1_7'	=> esc_html( __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-category-thumbnails' ) ),
	'remove_line_1_8'	=> esc_html( __( 'WILL NOT', 'fpw-category-thumbnails' ) ),
	'remove_line_1_9'	=> esc_html( __( 'be respected!', 'fpw-category-thumbnails' ) ),
	'restore_line_1_1'	=> esc_html( __( 'This action', 'fpw-category-thumbnails' ) ),
	'restore_line_1_2'	=> esc_html( __( 'WILL RESTORE', 'fpw-category-thumbnails' ) ),
	'restore_line_1_3'	=> esc_html( __( 'thumbnails from', 'fpw-category-thumbnails' ) ),
	'restore_line_1_4'	=> esc_html( __( 'backup', 'fpw-category-thumbnails' ) ),
	'restore_line_1_5'	=> esc_html( __( 'created by recent Remove Thumbnails.', 'fpw-category-thumbnails' ) ),
	'restore_line_1_6'	=> esc_html( __( 'Option', 'fpw-category-thumbnails' ) ),
	'restore_line_1_7'	=> esc_html( __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-category-thumbnails' ) ),
	'restore_line_1_8'	=> esc_html( __( 'WILL NOT', 'fpw-category-thumbnails' ) ),
	'restore_line_1_9'	=> esc_html( __( 'be respected!', 'fpw-category-thumbnails' ) ),
	'clear_line_1'		=> esc_html( __( 'Are you sure you want to clear this ID?', 'fpw-category-thumbnails' ) ),
	'tb_show_title'		=> esc_html( __( 'Get Image ID', 'fpw-category-thumbnails' ) ),
	'confirm_header'	=> esc_html( __( 'Please confirm', 'fpw-category-thumbnails' ) ),
	'wait_msg'			=> esc_html( __( 'Please wait...', 'fpw-category-thumbnails' ) ),
	'help_link_text'	=> esc_html( __( 'Help', 'fpw-category-thumbnails' ) )
	));

wp_localize_script( 'fpw-fs-alerts', 'fpw_fs_alerts', array (
	'text_ok'			=> esc_html( __( 'OK', 'fpw-category-thumbnails' ) ),
	'text_cancel'		=> esc_html( __( 'Cancel', 'fpw-category-thumbnails' ) )
	));
