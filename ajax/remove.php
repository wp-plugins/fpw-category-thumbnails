<?php
//	AJAX request to Remove all thumbnails

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$deletedThumbnails = array();

$parg = array(
	'numberofposts' => -1,
	'nopaging' => true,
	'post_type' => 'any' );

$posts = get_posts( $parg );

foreach ( $posts as $post ) {
	$post_id = absint( $post->ID );
	//	make sure this is not a revision
	if ( 'publish' === $post->post_status ) {
		$value = get_post_meta( $post_id, '_thumbnail_id', true );
		if ( !empty( $value ) ) {
			$deletedThumbnails[ $post_id ] = ( string ) $value;
			delete_post_meta( $post_id, '_thumbnail_id' );
		}
	}
}

echo '<p><strong>';

if ( 0 !== count( $deletedThumbnails ) ) {
	update_option( 'fpw_category_thumb_bkp', $deletedThumbnails );
	echo __( 'Thumbnails removed from all posts successfully. Backup created.', 'fpw-category-thumbnails' );
} else {
	echo __( 'No thumbnails to be removed found.', 'fpw-category-thumbnails' );
}

echo '</strong></p>';
die();
