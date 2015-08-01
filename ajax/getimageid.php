<?php
//	AJAX request to update Preview field and database

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$map = get_option( 'fpw_category_thumb_map' );
$cat = $_REQUEST['cat'];
$pid = $_REQUEST['id'];

if ( 'ngg-' == substr( $_REQUEST['id'], 0, 4 ) ) {
	if ( class_exists( 'nggdb' ) ) {
		$id = substr( $_REQUEST['id'], 4 );
		$picture = nggdb::find_image($id);

		if ( !$picture ) {
			$pic =	'' ;
		} else {
			$pic = '<img src="' . $picture->thumbURL . '" />';
			$map[$cat] = $pid;
			update_option( 'fpw_category_thumb_map', $map );
		}

	} else {
		$pic =	' ';
	}
	echo $pic;
} elseif ( 'Author' === $_REQUEST['id'] ) {
	echo '[ ' . __( 'Picture', 'fpw-category-thumbnails' ) . ' ]';
	$map[$cat] = 'Author';
	update_option( 'fpw_category_thumb_map', $map );
} elseif ( '0' == $_REQUEST['id'] ) {
	echo ' ';
	$map[$cat] = '0';
	$map_filtered = array();

	foreach( $map as $key => $value ) 
		if( $value != '0' ) 
   			$map_filtered[ $key ] = $value;

   	update_option( 'fpw_category_thumb_map', $map_filtered );
} elseif ( wp_attachment_is_image( $_REQUEST['id'] ) ) {
	echo wp_get_attachment_image( $_REQUEST['id'], $_REQUEST['size'] );
	$map[$cat] = $pid;
	update_option( 'fpw_category_thumb_map', $map );
} else {
	echo	'';
}
die();
?>