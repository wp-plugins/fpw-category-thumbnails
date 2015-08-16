<?php
//	AJAX request handler for Get Language File button

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$message = $this->doGetLanguage();

echo "<p><strong>$message</strong></p>";
die();
