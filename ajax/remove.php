<?php
//	AJAX request to Remove thumbnails

//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
	die( "Direct access to this script is forbidden!" );

$assignments = $this->getAssignmentsArray( $this->getAllCategories() );

while ( strlen( key( $assignments ) ) ) {
	$catid = key( $assignments );
	$parg = array(
		'numberofposts' => -1,
		'nopaging' => true,
		'category' => $catid,
		'post_type' => 'any' );
	$posts = get_posts( $parg );

	foreach ( $posts as $post ) {
		$post_id = $post -> ID;
		//	make sure this is not a revision
		if ( 'revision' != $post -> post_type )
			delete_post_meta( $post_id, '_thumbnail_id' );
	}

	next( $assignments );
}
reset( $assignments );
echo '<p><strong>' . __( 'All thumbnails removed successfully.', 'fpw-fct' ) . '</strong></p>';
die();
?>