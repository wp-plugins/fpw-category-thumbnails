<?php
//	AJAX request to Apply maping

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$this->doApplyMapping();

echo '<p><strong>' . __( 'Added thumbnails to existing posts successfully.', 'fpw-category-thumbnails' ) . '</strong></p>';
die();
