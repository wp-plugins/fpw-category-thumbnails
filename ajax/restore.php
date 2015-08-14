<?php
//	AJAX request to Restore thumbnails from backup created by Remove

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$backedupThumbnails = get_option( 'fpw_category_thumb_bkp', false );

foreach ( $backedupThumbnails as $key => $value ) {
	$post = get_post( $key );
	
	//	make sure this is not a revision
	if ( 'publish' === $post->post_status )
		update_post_meta( $key, '_thumbnail_id', $value );
}

delete_option( 'fpw_category_thumb_bkp' );

echo '<p><strong>' . __( 'All thumbnails restored from the backup successfully.', 'fpw-category-thumbnails' ) . '</strong></p>';
die();
