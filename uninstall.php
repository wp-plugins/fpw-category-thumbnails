<?php
$opt = get_option( 'fpw_category_thumb_opt' );
if ( is_array( $opt ) )
	$opt = $opt[ 'clean' ];
if ( defined( 'WP_UNINSTALL_PLUGIN' ) && $opt ) {
	delete_option( 'fpw_category_thumb_opt' );
	delete_option( 'fpw_category_thumb_map' );
}
?>