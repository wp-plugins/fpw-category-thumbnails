<?php
		if ( 'ngg-' == substr( $_REQUEST['id'], 0, 4 ) ) {
			$id = substr( $_REQUEST['id'], 4 );
			$picture = nggdb::find_image($id);
			$pic = $picture->imageURL;
			$w = $picture->meta_data['thumbnail']['width'];
			$h = $picture->meta_data['thumbnail']['height'];
			$pic = '<img width="' . $w . '" height="' . $h . '" src="' . $pic . '" />';
			echo $pic;
		} elseif ( 'Author' === $_REQUEST['id'] ) {
			echo '[ ' . __( 'Picture', 'fpw-fct' ) . ' ]';
		} else {
			if ( wp_attachment_is_image( $_REQUEST['id'] ) ) {
				echo wp_get_attachment_image( $_REQUEST['id'], $_REQUEST['size'] );
			} else {
				echo '';
			}
		}
		die();
?>