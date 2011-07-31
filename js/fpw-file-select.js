/* Image Select script */
jQuery( document ).ready( function( $ ) {

	// Actions for screens with the file select button
	if ( $( '.fpw-fs-button' ).length ) {

		// Invoke Media Library interface on button click
		$( '.fpw-fs-button' ).click( function() {
			$( 'html' ).addClass( 'File' );
			tb_show( 'Get Image ID', 'media-upload.php?fpw_fs_field=' + $( this ).siblings( 'input.fpw-fs-value' ).attr( 'id' ) + '&type=file&TB_iframe=true' );			
			return false;
		});
	
		// Wipe form values when remove checkboxes are checked
		$( '.fpw-fs-button:first' ).parents( 'form' ).submit( function() {
			$( '.fpw-fs-remove:checked' ).each( function() {
				$( this ).siblings( 'input.fpw-fs-value' ).val( '' );
			});
		});
		
	}
	
	// Actions for the Media Library overlay
	if ( $( "body" ).attr( 'id' ) == 'media-upload' ) {
		// Make sure it's an overlay invoked by this plugin
		var parent_doc, parent_src, parent_src_vars, current_tab;
		var select_button = '<a href="#" class="fpw-fs-insert button-secondary">' + fpw_file_select.text_select_file + '</a>';
		parent_doc = parent.document;
		parent_src = parent_doc.getElementById( 'TB_iframeContent' ).src;
		parent_src_vars = fpw_fs_get_url_vars( parent_src );
		if ( 'fpw_fs_field' in parent_src_vars ) {
			current_tab = $( 'ul#sidemenu a.current' ).parent( 'li' ).attr( 'id' );
			// $( 'ul#sidemenu li#tab-type' ).remove();
			$( 'ul#sidemenu li#tab-type_url' ).remove();
			$( 'p.ml-submit' ).remove();
			switch ( current_tab ) {
				case 'tab-type': {
					// File upload
					$( 'table.describe tbody tr:not(.submit)' ).remove();
					//$( 'table.describe tr.submit td.savesend input' ).replaceWith( select_button );
					$( 'table.describe tr.submit td.savesend input' ).remove();
					$( 'table.describe tr.submit td.savesend' ).prepend( select_button );
					break;
				}
				case 'tab-library': {
					// Media Library
					$( '#media-items .media-item a.toggle' ).remove();
					$( '#media-items .media-item' ).each( function() {
						$( this ).prepend( select_button );
					});
					$( 'a.fpw-fs-insert' ).css({
						'display':				'block',
						'float':				'right',
						'margin':				'7px 20px 0 0'
					});
					break;
				}
				case 'tab-nextgen': {
					// NextGEN Library
					$( '#media-items .media-item a.toggle' ).remove();
					$( '#media-items .media-item' ).each( function() {
						$( this ).prepend( select_button );
					});
					$( 'a.fpw-fs-insert' ).css({
						'display':				'block',
						'float':				'right',
						'margin':				'7px 20px 0 0'
					});
					break;
				}
			}
			
			// Select functionality
			$( 'a.fpw-fs-insert' ).click( function() {
				var item_id;
				if ( $( this ).parent().attr( 'class' ) == 'savesend' ) {
					item_id = $( this ).siblings( '.del-attachment' ).attr( 'id' );
					item_id = item_id.match( /del_attachment_([0-9]+)/ );
					item_id = item_id[1];
				} else {
					item_id = $( this ).parent().attr( 'id' );
					item_id = item_id.match( /media\-item\-([0-9]+)/ );
					item_id = item_id[1];
					if ( current_tab == 'tab-nextgen' ) {
						item_id = 'ngg-' + item_id;
					}
				}
				parent.fpw_fs_select_item( item_id, parent_src_vars['fpw_fs_field'] );
				return false;
			});
		}
	
	}

	// Actions for screens with the clear button
	if ( $( '.btn-for-clear' ).length ) {
		$( '.btn-for-clear' ).click( function() {
			t = this;
			id = t.id;
			id = id.slice( ( id.search( /clear-for-id-/ ) + 13 ), id.length );
			jConfirm('Are you sure you want to clear this ID?', 'Please confirm', function(r) {
				if ( r ) fpw_fs_select_item( 0, 'val-for-id-' + id + '-field' );
			});
			return false;
		});
	}
	
});

// Parse URL variables
// See: http://papermashup.com/read-url-get-variables-withjavascript/
function fpw_fs_get_url_vars( s ) {
	var vars = {};
	var parts = s.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	 	vars[key] = value;
	});
	return vars;
}

function fpw_fs_select_item( item_id, field_id ) {
	var field, preview_div, preview_size;
	field = jQuery( '#' + field_id );
	preview_div = jQuery( '#' + field_id + '_preview' );
	preview_size = jQuery( '#' + field_id + '_preview-size' ).val();
	// Load preview image
	preview_div.html( '' ).load( fpw_file_select.ajaxurl, {
		id: 		item_id,
		size:		preview_size,
		action:	'fpw_fs_get_file'
	});
	// Pass ID to form field
	field.val( item_id );
	// Close interface down
	tb_remove();
	jQuery( 'html' ).removeClass( 'File' );
}
