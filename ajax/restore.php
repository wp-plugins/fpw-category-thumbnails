<?php
//	AJAX request to Restore thumbnails from backup created by Remove

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$this->doRestoreThumbnails();

echo '<p><strong>' . __( 'All thumbnails restored from the backup successfully.', 'fpw-category-thumbnails' ) . '</strong></p>';
die();
