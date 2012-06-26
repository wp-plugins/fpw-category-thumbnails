<?php
		//	prevent direct access
		if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) ) 
			die( "Direct access to this script is forbidden!" );

		$proceed = false;
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		if ( !in_array( 'fpwfpt' . str_replace( '.', '', $this->fptVersion ), $dismissed ) && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) {
			$proceed = true;
			add_action( 'admin_print_footer_scripts', array( &$this, 'custom_print_footer_scripts' ) );
		}
		if ( $proceed ) {
    		wp_enqueue_style('wp-pointer');
    		wp_enqueue_script('wp-pointer');
    		wp_enqueue_script('utils');
		}
?>