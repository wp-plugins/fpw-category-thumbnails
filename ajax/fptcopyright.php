<?php
//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) ) 
	die( "Direct access to this script is forbidden!" );

echo	'<p><strong>' . __( 'Values copied from the left to the right panel.', 'fpw-fct' ) . 
		'</strong></p>';
die();
?>