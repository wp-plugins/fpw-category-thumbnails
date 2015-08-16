<?php
//	AJAX request to Remove all thumbnails

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$postsFound = $this->doRemoveThumbnails();

echo '<p><strong>';

if ( $postsFound ) {
	echo __( 'Thumbnails removed from all posts successfully. Backup created.', 'fpw-category-thumbnails' );
} else {
	echo __( 'No thumbnails to be removed found.', 'fpw-category-thumbnails' );
}

echo '</strong></p>';
die();
