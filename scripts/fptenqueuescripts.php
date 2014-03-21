<?php
			//	prevent direct access
			if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) ) 
				die( "Direct access to this script is forbidden!" );

			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_script( 'farbtastic' );
			wp_enqueue_style( 'thickbox' );

			//	load our script in the footer
			wp_enqueue_script( 'fpw-fpt', plugins_url( '/fpw-category-thumbnails/js/fpw-fpt.js' ), array( 'jquery', 'farbtastic', 'thickbox' ), false, true );

			$protocol = isset( $_SERVER[ 'HTTPS' ] ) ? 'https://' : 'http://';

			wp_localize_script( 'fpw-fpt', 'fpw_fpt', array(
				'ajaxurl'			=> admin_url( 'admin-ajax.php', $protocol ),
				'wait_msg'			=> esc_html( __( 'Please wait...', 'fpw-category-thumbnails' ) ),
				'help_link_text'	=> esc_html( __( 'Help for FPW Post Thumbnails', 'fpw-category-thumbnails' ) )
			));
?>