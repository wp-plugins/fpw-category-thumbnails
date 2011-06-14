/* SLT Image Select script */
jQuery( document ).ready( function() {
	
	// Actions for screens with the Get button
	if ( jQuery( '.btn-for-get' ).length ) {

		// Invoke Media Library interface on button click
		jQuery( '.btn-for-get' ).click( function() {
			jQuery( 'html' ).addClass( 'File' );
			tb_show( 'Get image ID', 'media-upload.php?fpw_input_field=' + jQuery( this ).siblings( 'input.value-for-id' ).attr( 'id' ) + '&type=file&TB_iframe=true' );
			return false;
		});

	}
	
	// Actions for the Media Library overlay
	if ( jQuery( "body" ).attr( 'id' ) == 'media-upload' ) {
		
		// Make sure it's an overlay invoked by this plugin
		var parent_doc, parent_src, parent_src_vars, current_tab;
		var select_button = '<a href="#" class="btn-for-get button-secondary">' + fpw-category-thumbnails-upload.text_select_file + '</a>';
		parent_doc = parent.document;
		parent_src = parent_doc.getElementById( 'TB_iframeContent' ).src;
		parent_src_vars = fpw_get_url_vars( parent_src );
		if ( 'fpw_input_field' in parent_src_vars ) {
			current_tab = jQuery( 'ul#sidemenu a.current' ).parent( 'li' ).attr( 'id' );
			jQuery( 'ul#sidemenu li#tab-type_url' ).remove();
			jQuery( 'p.ml-submit' ).remove();
			switch ( current_tab ) {
				case 'tab-type': {
					// File upload
					jQuery( 'table.describe tbody tr:not(.submit)' ).remove();
					//$( 'table.describe tr.submit td.savesend input' ).replaceWith( select_button );
					jQuery( 'table.describe tr.submit td.savesend input' ).remove();
					jQuery( 'table.describe tr.submit td.savesend' ).prepend( select_button );
					break;
				}
				case 'tab-library': {
					// Media Library
					jQuery( '#media-items .media-item a.toggle' ).remove();
					jQuery( '#media-items .media-item' ).each( function() {
						jQuery( this ).prepend( select_button );
					});
					jQuery( 'a.fpw-insert' ).css({
						'display':				'block',
						'float':					'right',
						'margin':				'7px 20px 0 0'
					});
					break;
				}
				case 'tab-nextgen': {
					// NextGEN Library
					jQuery( '#media-items .media-item a.toggle' ).remove();
					jQuery( '#media-items .media-item' ).each( function() {
						jQuery( this ).prepend( select_button );
					});
					jQuery( 'a.fpw-insert' ).css({
						'display':				'block',
						'float':					'right',
						'margin':				'7px 20px 0 0'
					});
					break;
				}
			}
			// Select functionality
			jQuery( 'a.fpw-insert' ).click( function() {
				var item_id;
				if ( jQuery( this ).parent().attr( 'class' ) == 'savesend' ) {
					item_id = jQuery( this ).siblings( '.del-attachment' ).attr( 'id' );
					item_id = item_id.match( /del_attachment_([0-9]+)/ );
					item_id = item_id[1];
				} else {
					if ( current_tab == 'tab-nextgen' ) {
						item_id = jQuery( this ).parent().attr( 'id' );
						item_id = item_id.match( /media\-item\-([0-9]+)/ );
						item_id = 'ngg-' + item_id[1];
					} else {
						item_id = jQuery( this ).parent().attr( 'id' );
						item_id = item_id.match( /media\-item\-([0-9]+)/ );
						item_id = item_id[1];
					}
				}
				parent.fpw_select_item( item_id, parent_src_vars['slt_fs_field'] );
				return false;
			});
		}
	
	}

	jQuery( '.btn-for-clear').click( function() {
		id = this.id;
		id = id.substr( 13 );

		jConfirm('Are you sure you want to clear this ID?', 'Please confirm', function(r) {
			if ( r ) jQuery( '#val-for-id-' + id ).val( 0 );
		});

		return false;
	});

	
});

// Parse URL variables
// See: http://papermashup.com/read-url-get-variables-withjavascript/
function fpw_get_url_vars( s ) {
	var vars = {};
	var parts = s.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	 	vars[key] = value;
	});
	return vars;
}

function fpw_select_item( item_id, field_id ) {
	var field, preview_div, preview_size;
	field = jQuery( '#' + field_id );
	preview_div = jQuery( '#' + field_id + '_preview' );
	preview_size = jQuery( '#' + field_id + '_preview-size' ).val();
	// Load preview image
	preview_div.html( '' ).load( fpw-category-thumbnails-upload.ajaxurl, {
		id: 		item_id,
		size:		preview_size,
		action:	'slt_fs_get_file'
	});
	// Pass ID to form field
	field.val( item_id );
	// Close interface down
	tb_remove();
	jQuery( 'html' ).removeClass( 'File' );
}
