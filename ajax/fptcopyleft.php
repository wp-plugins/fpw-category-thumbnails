<?php

//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) ) 
	die( "Direct access to this script is forbidden!" );

echo	'<p><strong>' . __( 'Values copied from the right to the left panel.', 'fpw-category-thumbnails' ) .
		'</strong></p>';
die();
?>