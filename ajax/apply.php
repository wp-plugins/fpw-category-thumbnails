<?php
		//	AJAX request to Apply maping
		$map = get_option( 'fpw_category_thumb_map' );
		if ( $map )
			while ( strlen( key( $map ) ) ) {
				$catid = key($map);
				$parg = array(
					numberofposts => -1,
					nopaging => true,
					category => $catid,
					post_type => 'any' );
				$posts = get_posts( $parg );
				foreach ( $posts as $post ) {
					$post_id = $post->ID;
					//	make sure this is not a revision nor draft
					if ( ( 'revision' != $post->post_type ) && ( 'draft' != $post->post_status ) )
						$this->addThumbnailToPost( $post_id, $post );
				}
				next($map);
			}
		echo '<p><strong>' . __( 'Applied thumbnails to existing posts / pages successfully.', 'fpw-fct' ) . '</strong></p>'; 
		die();
?>