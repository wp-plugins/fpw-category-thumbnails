<?php
//	AJAX request handler for Get Language File button

//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
	die( "Direct access to this script is forbidden!" );

if ( 'not_exist' == $this->translationStatus ) {
	$m = __( 'Language file for this version is not yet available.', 'fpw-category-thumbnails' );
} elseif ( 'installed' == $this->translationStatus ) {
	$m = __( 'Language file is already installed.', 'fpw-category-thumbnails' );
} else {
	$handle = @fopen( $this->translationPath, 'wb' );
	fwrite( $handle, $this->translationResponse[ 'body' ] );
	fclose($handle);
	$this->translationStatus = 'installed';
	$m = __( 'Language file downloaded successfully. It will be applied as soon as this page is reloaded.', 'fpw-category-thumbnails' );
}			

echo '<p><strong>' . $m . '</strong></p>';
die();
?>