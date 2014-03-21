<?php
//	AJAX request to update options

//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
	die( "Direct access to this script is forbidden!" );

$boxes = $_REQUEST['boxes'];
$donotover = ( in_array( 'donotover', $boxes ) ) ? true : false;
$cleanup = ( in_array( 'cleanup', $boxes ) ) ? true : false;
$abar = ( in_array( 'abar', $boxes ) ) ? true : false;
$fpt = ( in_array( 'fpt', $boxes ) ) ? true : false;
$opt = get_option( 'fpw_category_thumb_opt' );
$opt[ 'clean' ] = $cleanup;
$opt[ 'donotover' ]	= $donotover;
$opt[ 'abar' ] = $abar;
$opt[ 'fpt' ] = $fpt;
$ok = ( update_option( 'fpw_category_thumb_opt', $opt ) );
echo '<p><strong>';

if ( $ok ) {
	$this->fctOptions = $opt;
	$this->uninstallMaintenance();
	echo __( 'Changed data saved successfully.', 'fpw-category-thumbnails' );
} else {
	echo __( 'No changes detected. Nothing to update.', 'fpw-category-thumbnails' );
}

echo '</strong></p>';
die();
?>