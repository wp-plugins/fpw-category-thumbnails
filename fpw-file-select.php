<?php
/*	Get Image ID Logic ( based on code from SLT File Select plugin by Steve Taylor )

	Added support for NextGEN Gallery plugin
	Added jQuery alerts
*/
	
if ( ! function_exists( 'add_action' ) ) {
	_e( "This cannot be called directly.", "fpw-fct" );
	exit;
}

//	Register and enqueue scripts & styles
function fpw_fct_enqueue_scripts( $hook ) {
	if ( ( 'settings_page_fpw-category-thumbnails' == $hook ) || ( 'media-upload-popup' == $hook ) ) {
		wp_register_style( 'fpw-fs-alerts', plugins_url( '/fpw-category-thumbnails/js/css/jquery.alerts.css' ) );
		wp_register_script( 'fpw-fs-alerts', plugins_url( '/fpw-category-thumbnails/js/jquery.alerts.js' ), array( 'jquery' ) );
		wp_register_script( 'fpw-file-select', plugins_url( '/fpw-category-thumbnails/js/fpw-file-select.js' ), array( 'jquery', 'fpw-fs-alerts', 'media-upload', 'thickbox' ) );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'fpw-fs-alerts');
		wp_enqueue_script( 'fpw-fs-alerts' );
		wp_enqueue_script( 'fpw-file-select' );
		$protocol = isset( $_SERVER[ 'HTTPS' ] ) ? 'https://' : 'http://';
		wp_localize_script( 'fpw-file-select', 'fpw_file_select', array(
			'ajaxurl'			=> admin_url( 'admin-ajax.php', $protocol ),
			'text_select_file'	=> esc_html( __( 'Get ID', 'fpw-fct' ) ),
			'apply_line_1_1'	=> esc_html( __( 'This action will add thumbnails based on current settings to', 'fpw-fct' ) ),
			'apply_line_1_2'	=> esc_html( __( 'ALL', 'fpw-fct' ) ),
			'apply_line_1_3'	=> esc_html( __( 'existing posts / pages.', 'fpw-fct' ) ),
			'apply_line_1_4'	=> esc_html( __( 'Option', 'fpw-fct' ) ),
			'apply_line_1_5'	=> esc_html( __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) ),
			'apply_line_1_6'	=> esc_html( __( 'will be respected.', 'fpw-fct' ) ),
			'apply_line_2'		=> esc_html( __( 'Are you sure you want to proceed?', 'fpw-fct' ) ),
			'remove_line_1_1'	=> esc_html( __( 'This action', 'fpw-fct' ) ),
			'remove_line_1_2'	=> esc_html( __( 'WILL REMOVE', 'fpw-fct' ) ),
			'remove_line_1_3'	=> esc_html( __( 'thumbnails from', 'fpw-fct' ) ),
			'remove_line_1_4'	=> esc_html( __( 'ALL', 'fpw-fct' ) ),
			'remove_line_1_5'	=> esc_html( __( 'existing posts / pages.', 'fpw-fct' ) ),
			'remove_line_1_6'	=> esc_html( __( 'Option', 'fpw-fct' ) ),
			'remove_line_1_7'	=> esc_html( __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) ),
			'remove_line_1_8'	=> esc_html( __( 'WILL NOT', 'fpw-fct' ) ),
			'remove_line_1_9'	=> esc_html( __( 'be respected!', 'fpw-fct' ) ),
			'clear_line_1'		=> esc_html( __( 'Are you sure you want to clear this ID?', 'fpw-fct' ) ),
			'tb_show_title'		=> esc_html( __( 'Get Image ID', 'fpw-fct' ) ),
			'confirm_header'	=> esc_html( __( 'Please confirm', 'fpw-fct' ) ),
			'help_link_text'	=> esc_html( __( 'FPW Category Thumbnails - Help') )
		));
		wp_localize_script( 'fpw-fs-alerts', 'fpw_fs_alerts', array (
			'text_ok'			=> esc_html( __( 'OK', 'fpw-fct' ) ),
			'text_cancel'		=> esc_html( __( 'Cancel', 'fpw-fct' ) )
		));
	}
}

// Disable Flash uploader when this plugin invokes Media Library overlay
function fpw_fs_disable_flash_uploader() {
	if ( basename( $_SERVER['SCRIPT_FILENAME'] ) == 'media-upload.php' && array_key_exists( 'fpw_fs_field', $_GET ) )
		add_filter( 'flash_uploader', create_function( '$a','return false;' ), 5 );
}
add_action( 'admin_init', 'fpw_fs_disable_flash_uploader' );

// Output form button
function fpw_fs_button( $name, $value, $catid, $label = 'Get ID', $preview_size = 'thumbnail', $removable = false ) { ?>
	<td style="vertical-align: middle;"><div>
		<input type="text" size="10" maxlength="10" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>" class="fpw-fs-value" />
		<input type="button" class="button-secondary fpw-fs-button" title="<?php echo __( 'fetches image ID from media library', 'fpw-fct' ); ?>" value="<?php echo __( 'Get ID', 'fpw-fct' ); ?>" />
		<input class="button-secondary btn-for-clear" title="<?php echo __( 'clears \'Image ID\' input value and \'Preview\' area', 'fpw-fct' ); ?>" id="clear-for-id-<?php echo $catid; ?>" type="button" value="<?php echo __( 'Clear', 'fpw-fct' ); ?>" />		
		<input class="button-secondary btn-for-refresh" title="<?php echo __( 'refreshes \'Preview\' area', 'fpw-fct' ); ?>" id="refresh-for-id-<?php echo $catid; ?>" type="button" value="<?php echo __( 'Refresh', 'fpw-fct' ); ?>" />		
		<input type="hidden" value="<?php echo esc_attr( $preview_size ); ?>" name="<?php echo esc_attr( $name ); ?>_preview-size" id="<?php echo esc_attr( $name ); ?>_preview-size" class="fpw-fs-preview-size" />
	</div></td>	
	<td style="vertical-align: middle;">
		<div class="fpw-fs-preview" id="<?php echo esc_attr( $name ); ?>_preview">
		<?php
			if ( $value ) {
				if ( '0' == $value ) {
					echo '';
				} else {
					if ( ( 'ngg-' == substr( $value, 0, 4 ) ) && class_exists( 'nggdb' ) ) {
						$id = substr( $value, 4 );
						$picture = nggdb::find_image($id);
						if ( !$picture ) {
							echo '';
						} else {
							$pic = $picture->imageURL;
							$w = $picture->meta_data['thumbnail']['width'];
							$h = $picture->meta_data['thumbnail']['height'];
							$pic = '<img width="' . $w . '" height="' . $h . '" src="' . $pic . '" />';
							echo $pic;
						}
					} else {
						if ( wp_attachment_is_image( $value ) ) {
							echo wp_get_attachment_image( $value, $preview_size );
						} else {
							echo '';
						}
					}
				}
			}
		?>
		</div>
	</td>
<?php }

// AJAX wrapper to get image HTML
function fpw_fs_get_file_ajax() {
	if ( 'ngg-' == substr( $_REQUEST['id'], 0, 4 ) ) {
		$id = substr( $_REQUEST['id'], 4 );
		$picture = nggdb::find_image($id);
		$pic = $picture->imageURL;
		$w = $picture->meta_data['thumbnail']['width'];
		$h = $picture->meta_data['thumbnail']['height'];
		$pic = '<img width="' . $w . '" height="' . $h . '" src="' . $pic . '" />';
		echo $pic;
	} else {
		if ( wp_attachment_is_image( $_REQUEST['id'] ) ) {
			echo wp_get_attachment_image( $_REQUEST['id'], $_REQUEST['size'] );
		} else {
			echo '';
		}
	}
	die();
}
add_action( 'wp_ajax_fpw_fs_get_file', 'fpw_fs_get_file_ajax' );