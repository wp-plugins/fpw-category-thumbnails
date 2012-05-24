<?php
//	front end class
class fpwCategoryThumbnails {
	public	$pluginOptions;
	public	$pluginPath;
	public	$pluginUrl;
	public	$pluginVersion;
	public	$pluginPage;
	public	$wpVersion;
	
	//	constructor
	public	function __construct( $path, $version ) {
		global $wp_version;

		//	set plugin's path
		$this->pluginPath = $path;
		
		//	set plugin's url
		$this->pluginUrl = WP_PLUGIN_URL . '/fpw-category-thumbnails';
		
		//	set version
		$this->pluginVersion = $version;

		//	set WP version
		$this->wpVersion = $wp_version;
	}	

	/*	------------------------------------------------------------------
	Main action - sets the value of post's _thumbnail_id based on category
	assignments
	------------------------------------------------------------------- */
	public function addThumbnailToPost( $post_id, $post = NULL ) {
		if ( NULL === $post ) 
			return;
		//	we don't want to apply changes to post's revision or drafts
		if ( ( 'revision' == $post->post_type ) || ( 'draft' == $post->post_status ) ) 
			return;
		//	this is actual post
		$thumb_id = get_post_meta( $post_id, '_thumbnail_id', TRUE );
		$do_notover = get_option( 'fpw_category_thumb_opt' );
		if ( $do_notover )
			$do_notover = $do_notover[ 'donotover' ]; 
		$map = get_option( 'fpw_category_thumb_map' );
		if ( $map ) {
			$cat = get_the_category( $post_id );
			foreach ( $cat as $c ) {
				if ( $post->post_date === $post->post_modified ) {
					//	in case of a new post we have to ignore setting of $do_notover flag
					//	as the thumbnail of default category will be there already
					if ( array_key_exists( $c->cat_ID, $map ) )
						if ( $map[ $c->cat_ID ] === 'Author' ) {
							$auth_pic_id = self::getAuthorsPictureID( $post->post_author );
							if ( '0' != $auth_pic_id ) 
								update_post_meta( $post_id, '_thumbnail_id', $auth_pic_id );
						} else { 
							update_post_meta( $post_id, '_thumbnail_id', $map[ $c->cat_ID ] );
						}
				} else {
					//	modified post - observe $do_notover flag
					if ( array_key_exists( $c->cat_ID, $map ) ) 
						if ( !( $do_notover ) ) {
							if ( $map[ $c->cat_ID ] === 'Author' ) {
								$auth_pic_id = self::getAuthorsPictureID( $post->post_author );
								if ( '0' != $auth_pic_id )
									 update_post_meta( $post_id, '_thumbnail_id', $auth_pic_id );
							} else {
								update_post_meta( $post_id, '_thumbnail_id', $map[ $c->cat_ID ] );
							}
						} else {
							if ( '' == $thumb_id )
								if ( $map[ $c->cat_ID ] === 'Author' ) {
									 $auth_pic_id = self::getAuthorsPictureID( $post->post_author );
									 if ( '0' != $auth_pic_id )
									 	update_post_meta( $post_id, '_thumbnail_id', $auth_pic_id );
								} else {
									update_post_meta( $post_id, '_thumbnail_id', $map[ $c->cat_ID ] );
								}
						}
				}
				$thumb_id = get_post_meta( $post_id, '_thumbnail_id', TRUE );
  			}
		}
	}
	
	//	get author's picture id - helper function
	private static function getAuthorsPictureID( $author_id ) {
		global $wpdb;
		$pic_id = 0;
		$all_media = $wpdb->get_results( "SELECT DISTINCT * FROM " . $wpdb->prefix . "posts " .
			"WHERE post_type = 'attachment' AND guid LIKE '%author_" . $author_id . ".jpg%' ORDER " .
			"BY post_date DESC" );
		if ( 0 < count( $all_media ) ) {
			$obj = $all_media[0];
			$pic_id	= $obj->ID;
		} else {
			$active_plugins = get_option( 'active_plugins' );
			$length = count( $active_plugins );
			$nextGenActive = FALSE;
			$i = 0;
			while ( $i < $length ) {
				if ( 0 < strpos( $active_plugins[ $i ], 'nggallery.php' ) ) {
					$nextGenActive = TRUE;
					$i = $length;
				}
				$i++;
			}
			if ( $nextGenActive ) {
				$tmp = $wpdb->get_results( "SELECT DISTINCT * FROM " . $wpdb->prefix . "ngg_gallery WHERE slug = 'authors'" );
				if ( 0 < count( $tmp ) ) {
					$obj = $tmp[0];
					$galleryID = $obj->gid;
					$tmp = $wpdb->get_results( "SELECT DISTINCT * FROM " . $wpdb->prefix . "ngg_pictures " .
						"WHERE galleryid = " . $galleryID . " AND filename LIKE '%" . $author_id . ".jpg%'" );
					if ( 0 < count( $tmp ) ) {
						$obj = $tmp[0];
						$pic_id = 'ngg-' . $obj->pid;
					}
				}
			}
		}	
		return $pic_id;
	}	
	 
}
?>