<?php
//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
	die( "Direct access to this script is forbidden!" );

global $current_user;
get_currentuserinfo();
$x = $this->setScreenOption( $status, 'edit_category_per_page', $_POST[ 'per_page' ] );
update_user_meta( $current_user->ID, 'edit_category_per_page', $_POST[ 'per_page' ] );
$this->categoryListTable->prepare_items();
$this->categoryListTable->display();
die();
?>