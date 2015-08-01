<?php
//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

//	build form's input fields and buttons
reset( $assignments );
reset( $categories );
$this->mapArray = array();
while ( strlen( key( $assignments ) ) ) {
	$indent = str_repeat( '&nbsp;', $categories[ key( $categories ) ][ 0 ] * 4);
	$name = 'val-for-id-' . key( $assignments ) . '-field';
	$value = $assignments[ key( $assignments ) ];
	$catid = key( $assignments );
	$preview_size = 'thumbnail';
	ob_start();
	if ( $value ) {
		if ( '0' == $value ) {
			echo '';
		} elseif ( 'Author' === $value ) {
			echo '[ ' . __( 'Picture', 'fpw-category-thumbnails' ) . ' ]';
		} else {
			if ( 'ngg-' == substr( $value, 0, 4 ) ) {
				if ( class_exists( 'nggdb' ) ) {
					$id = substr( $value, 4 );
					$picture = nggdb::find_image($id);
					if ( !$picture ) {
						echo 	'<span style="font-size: large; color: red">' . 
								__( 'NextGen Gallery: picture not found!', 'fpw-category-thumbnails' ) . '</span>';
					} else {
						echo '<img src="' . $picture->thumbURL . '" />';
					}
				} else {
					echo 	'<span style="font-size: large; color: red">' . 
							__( 'NextGen Gallery: not active!', 'fpw-category-thumbnails' ) . '</span>';
				}
			} else {
				if ( wp_attachment_is_image( $value ) ) {
					echo wp_get_attachment_image( $value, $preview_size );
				} else {
					echo 	'<span style="font-size: large; color: red">' . 
							__( 'Media Library: picture not found!', 'fpw-category-thumbnails' ) . '</span>';
				}
			}
		}
	}
	$picture = ob_get_clean();
	$this->mapArray[]		= array(
		'fpwct_cat_id' =>	$categories[ key( $categories )][ 1 ] -> cat_ID,
		'fpwct_cat_name' => $indent . $categories[ key( $categories ) ][ 1 ] -> cat_name,
		'fpwct_image_id' => '<input type="text" size="10" maxlength="10" value="' . 
							$value . '" name="' . $name . '" id="' . $name . '" class="fpw-fs-value" />' . 
							'<input type="hidden" value="thumbnail" name="' . $name . '_preview-size" id="' . 
							$name . '_preview-size" class="fpw-fs-preview-size" />',
		'fpwct_preview' =>	'<div id="val-for-id-' . $categories[ key( $categories )][ 1 ] -> cat_ID . '-field_preview" class="fpw-fs-preview">' . $picture . '</div>',
		);
	next( $assignments );
	next( $categories );
}
reset( $categories );
reset( $assignments );
require_once $this->fctPath . '/classes/fpw-category-thumbnails-table-class.php';
$this->categoryListTable = new fpw_Category_Thumbnails_Table( $this->mapArray );
?>
<style type="text/css">
<!--
a#show-settings-link {
	background-color: #CCCCCC; 
}
.widefat thead tr th, .widefat tfoot tr th {
    background-color: #F1F1F1;
    background-image: -webkit-linear-gradient(top , #F9F9F9, #CCCCCC);
    background-image: -moz-linear-gradient(top , #F9F9F9, #CCCCCC);
    background-image: -ms-linear-gradient(top , #F9F9F9, #CCCCCC);
    background-image: -o-linear-gradient(top , #F9F9F9, #CCCCCC);
}
th#fpwct_cat_name {
	width: 30%;
}
th#fpwct_image_id {
	width: 113px; 
}
td.fpwct_cat_name, td.fpwct_image_id, td.fpwct_preview {
	vertical-align: middle;
}
.fpw-fs-button, .fpw-btn-author, .fpw-btn-clear, .fpw-btn-refresh {
	background-color: white;
}
-->
</style>
<?php
		echo '<div id="cat_table">';
		$this->categoryListTable->prepare_items();
		$this->categoryListTable->display();
		echo '</div>';
?>