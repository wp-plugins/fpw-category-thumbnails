<?php
//	AJAX request to Apply maping

//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

$map = get_option( 'fpw_category_thumb_map' );

if ( $map )

	while ( strlen( key( $map ) ) ) {
		$catid = key($map);
		$parg = array(
			'numberofposts' => -1,
			'nopaging' => true,
			'category' => $catid,
			'post_type' => 'any' );
		$posts = get_posts( $parg );

		foreach ( $posts as $post ) {
			$post_id = $post->ID;
			//	make sure this is not a revision nor draft
			if ( 'publish' === $post->post_status ) 
				$this->addThumbnailToPost( $post_id, $post );
		}

		next($map);
	}

echo '<p><strong>' . __( 'Added thumbnails to existing posts successfully.', 'fpw-category-thumbnails' ) . '</strong></p>';
die();
