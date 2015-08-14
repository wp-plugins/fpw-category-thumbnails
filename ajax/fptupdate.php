<?php
//	AJAX request to update options

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$p = $_POST;

if ( isset( $_POST[ 'boxes' ] ) ) { 
	$boxes = $_POST[ 'boxes' ];

	foreach ( $boxes as $b ) 
		$p[ $b ] = $b;

}

$resp = $this->fptValidateInput( $p );
echo '<p><strong>';

if ( '' == $resp ) { 
	$ok = update_option( 'fpw_post_thumbnails_options', $this->fptOptions );

	if ( $ok ) {
		echo __( 'Changes saved successfully.', 'fpw-category-thumbnails' );
	} else {
		echo __( 'No changes detected.', 'fpw-category-thumbnails' );
	}

} else {
	echo __( 'Validation failed!', 'fpw-category-thumbnails' ) . ' ' . $resp;
}

echo '</strong></p>';
die();
