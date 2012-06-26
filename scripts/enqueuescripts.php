<?php
			wp_register_style( 'fpw-fs-alerts', plugins_url( '/fpw-category-thumbnails/js/css/jquery.alerts.css' ) );
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'fpw-fs-alerts' );
			wp_enqueue_script( 'fpw-fs-alerts', plugins_url( '/fpw-category-thumbnails/js/jquery.alerts.js' ), array( 'jquery' ), false, true );
			wp_enqueue_script( 'fpw-file-select', plugins_url( '/fpw-category-thumbnails/js/fpw-file-select.js' ), array( 'jquery', 'fpw-fs-alerts', 'media-upload', 'thickbox' ), false, true );
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
				'wait_msg'			=> esc_html( __( 'Please wait...', 'fpw-fct' ) ),
				'help_link_text'	=> esc_html( __( 'Help for FPW Category Thumbnails', 'fpw-fct' ) )
				));
			wp_localize_script( 'fpw-fs-alerts', 'fpw_fs_alerts', array (
				'text_ok'			=> esc_html( __( 'OK', 'fpw-fct' ) ),
				'text_cancel'		=> esc_html( __( 'Cancel', 'fpw-fct' ) )
			));
?>