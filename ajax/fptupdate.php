<?php
//	AJAX request to update options

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$message = $this->doFormUpdate();

echo "<p><strong>$message</strong></p>";
die();
