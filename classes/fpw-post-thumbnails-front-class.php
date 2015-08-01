<?php
//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

//	front end class
class fpwPostThumbnails {
	public	$fptPath;
	public	$fptUrl;
	public	$fptVersion;
	public	$pluginPage;
	public	$wpVersion;
	public	$fptOptions;

	//	constructor
	public	function __construct( $path, $version ) {
		global $wp_version;

		//	set plugin's path
		$this->fptPath = $path;

		//	set plugin's url
		$this->fptUrl = WP_PLUGIN_URL . '/fpw-category-thumbnails';

		//	set version
		$this->fptVersion = $version;

		//	set WP version
		$this->wpVersion = $wp_version;

		//	read options
		$this->fptOptions = get_option( 'fpw_post_thumbnails_options' );

		add_action( 'after_setup_theme', array( &$this, 'addImageSizes' ) );
		add_action( 'wp_head', array( &$this, 'dynamicThumbnailStyles' ) );
		
		if ( is_array( $this->fptOptions ) ) {
			if ( $this->fptOptions[ 'content' ][ 'enabled' ] ) 
				add_filter( 'the_content', array( &$this, 'fptContent' ) );
			if ( $this->fptOptions[ 'excerpt' ][ 'enabled' ] )  
				add_filter( 'the_excerpt', array( &$this, 'fptExcerpt' ) );
		}
	}
	
	private function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		return $rgb; // returns an array with the rgb values
	}
		
	//	add image sizes
	function addImageSizes() {
		add_image_size( 'content-thumbnail', $this->fptOptions[ 'content' ][ 'width' ], $this->fptOptions[ 'content' ][ 'height' ], false );
		add_image_size( 'excerpt-thumbnail', $this->fptOptions[ 'excerpt' ][ 'width' ], $this->fptOptions[ 'excerpt' ][ 'height' ], false );
	}

	function dynamicThumbnailStyles() {
		if ( $this->fptOptions[ 'content' ][ 'enabled' ] ) {
?>
<style>
.wp-post-image-content {
	float: <?php echo $this->fptOptions[ 'content' ][ 'position' ] ?>;
	padding-top: <?php echo $this->fptOptions[ 'content' ][ 'padding_top' ] ?>px;
	padding-left: <?php echo $this->fptOptions[ 'content' ][ 'padding_left' ] ?>px;
	padding-bottom: <?php echo $this->fptOptions[ 'content' ][ 'padding_bottom' ] ?>px;
	padding-right: <?php echo $this->fptOptions[ 'content' ][ 'padding_right' ] ?>px;
	margin-top: <?php echo $this->fptOptions[ 'content' ][ 'margin_top' ] ?>px;
	margin-left: <?php echo $this->fptOptions[ 'content' ][ 'margin_left' ] ?>px;
	margin-bottom: <?php echo $this->fptOptions[ 'content' ][ 'margin_bottom' ] ?>px;
	margin-right: <?php echo $this->fptOptions[ 'content' ][ 'margin_right' ] ?>px;
<?php
			if ( $this->fptOptions[ 'content' ][ 'border' ] ) {
?>
	background-color: <?php echo $this->fptOptions[ 'content' ][ 'background_color' ] ?>;
	border: <?php echo $this->fptOptions[ 'content' ][ 'border_width' ] ?>px solid <?php echo $this->fptOptions[ 'content' ][ 'border_color' ] ?>;
	-webkit-border-radius: <?php echo $this->fptOptions[ 'content' ][ 'border_radius' ] ?>px !important;
	-moz-border-radius: <?php echo $this->fptOptions[ 'content' ][ 'border_radius' ] ?>px !important;
	border-radius: <?php echo $this->fptOptions[ 'content' ][ 'border_radius' ] ?>px !important;
<?php
				if ( $this->fptOptions[ 'content' ][ 'shadow' ] ) {
					$hexColor = $this->fptOptions[ 'content' ][ 'sh_color' ];
					$rgb = $this->hex2rgb( $hexColor );
					$hlen = $this->fptOptions[ 'content' ][ 'sh_hor_length' ];
					$vlen = $this->fptOptions[ 'content' ][ 'sh_ver_length' ];
					$brad = $this->fptOptions[ 'content' ][ 'sh_blur_radius' ];
					$opac = $this->fptOptions[ 'content' ][ 'sh_opacity' ];
?>
	box-shadow: <?php echo $hlen . 'px ' . $vlen . 'px ' . $brad . 'px 0px rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $opac . ')' ?> !important;
	-webkit-box-shadow: <?php echo $hlen . 'px ' . $vlen . 'px ' . $brad . 'px 0px rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $opac . ')' ?> !important;
	-moz-box-shadow: <?php echo $hlen . 'px ' . $vlen . 'px ' . $brad . 'px 0px rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $opac . ')' ?> !important;
<?php
				}
			}
?>
}
</style>
<?php
		}
		if ( $this->fptOptions[ 'excerpt' ][ 'enabled' ] ) {
?>
<style>
.wp-post-image-excerpt {
	float: <?php echo $this->fptOptions[ 'excerpt' ][ 'position' ] ?>;
	padding-top: <?php echo $this->fptOptions[ 'excerpt' ][ 'padding_top' ] ?>px;
	padding-left: <?php echo $this->fptOptions[ 'excerpt' ][ 'padding_left' ] ?>px;
	padding-bottom: <?php echo $this->fptOptions[ 'excerpt' ][ 'padding_bottom' ] ?>px;
	padding-right: <?php echo $this->fptOptions[ 'excerpt' ][ 'padding_right' ] ?>px;
	margin-top: <?php echo $this->fptOptions[ 'excerpt' ][ 'margin_top' ] ?>px;
	margin-left: <?php echo $this->fptOptions[ 'excerpt' ][ 'margin_left' ] ?>px;
	margin-bottom: <?php echo $this->fptOptions[ 'excerpt' ][ 'margin_bottom' ] ?>px;
	margin-right: <?php echo $this->fptOptions[ 'excerpt' ][ 'margin_right' ] ?>px;
<?php
			if ( $this->fptOptions[ 'excerpt' ][ 'border' ] ) {
?>
	background-color: <?php echo $this->fptOptions[ 'excerpt' ][ 'background_color' ] ?>;
	border: <?php echo $this->fptOptions[ 'excerpt' ][ 'border_width' ] ?>px solid <?php echo $this->fptOptions[ 'excerpt' ][ 'border_color' ] ?>;
	-webkit-border-radius: <?php echo $this->fptOptions[ 'excerpt' ][ 'border_radius' ] ?>px !important;
	-moz-border-radius: <?php echo $this->fptOptions[ 'excerpt' ][ 'border_radius' ] ?>px !important;
	border-radius: <?php echo $this->fptOptions[ 'excerpt' ][ 'border_radius' ] ?>px !important;
<?php
				if ( $this->fptOptions[ 'excerpt' ][ 'shadow' ] ) {
					$hexColor = $this->fptOptions[ 'excerpt' ][ 'sh_color' ];
					$rgb = $this->hex2rgb( $hexColor );
					$hlen = $this->fptOptions[ 'excerpt' ][ 'sh_hor_length' ];
					$vlen = $this->fptOptions[ 'excerpt' ][ 'sh_ver_length' ];
					$brad = $this->fptOptions[ 'excerpt' ][ 'sh_blur_radius' ];
					$opac = $this->fptOptions[ 'excerpt' ][ 'sh_opacity' ];
?>
	box-shadow: <?php echo $hlen . 'px ' . $vlen . 'px ' . $brad . 'px 0px rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $opac . ')' ?> !important;
	-webkit-box-shadow: <?php echo $hlen . 'px ' . $vlen . 'px ' . $brad . 'px 0px rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $opac . ')' ?> !important;
	-moz-box-shadow: <?php echo $hlen . 'px ' . $vlen . 'px ' . $brad . 'px 0px rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $opac . ')' ?> !important;
<?php
				}
			}
?>
}
</style>
<?php
		}
	}

	//	thumbnail for content filter
	function fptContent( $content ) {
		return $this->fptThumbnail( $content, 'content' );
	}

	//	thumbnail for excerpt filter
	function fptExcerpt( $excerpt ) {
		return $this->fptThumbnail( $excerpt, 'excerpt' );
	}

	//  display thumbnail + content / excerpt
	function fptThumbnail( $content, $type ) {
		global $post;
		$count = 1;
		
		$thumbID = get_post_meta( $post->ID, '_thumbnail_id', true );
		if ( !( '' === $thumbID ) ) {
			$catNames = '';
            $categories = get_the_category($post->ID);
			$count = count( $categories );
			if ( $count > 0 ) {
				$catNames = $categories[0]->name;
				for ($i = 1; $i < $count; $i++) {
					$catNames = $catNames . ', ' . $categories[$i]->name;
				}
			}
			$pic  = '<div class="thumbnail thumb-' . $this->fptOptions[ $type ][ 'position' ] . '">';
			$img  = get_the_post_thumbnail( $post->ID,
											array(  $this->fptOptions[ $type ][ 'width' ],
													$this->fptOptions[ $type ][ 'height' ] ),
											array(	'class' => 'wp-post-image-' . $type,
													'title' => $catNames,
													'alt'	=> 'Featured Image' ) );
			if ( substr_count( $img, ' style="display:none"' ) )
				$img = str_replace( ' style="display:none"', '', $img, $count );
			$pic = $pic . $img . '</div>';
		} else {
			$pic = '';
		}
		return $pic . $content;
	}
}