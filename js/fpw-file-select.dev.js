/* Image Select script */

function confirmGetLanguage() {
	jQuery( '#buttonPressed' ).val( 'Language' );
	jQuery("form[name='fpw_cat_thmb_form']").submit();
}

jQuery( document ).ready( function( $ ) {

	jQuery("#contextual-help-link").html(fpw_file_select.help_link_text);

	// Actions for screens with the file select button
	if ( $( '.fpw-fs-button' ).length ) {

		// Invoke Media Library interface on button click
		$( '.fpw-fs-button' ).click( function() {
			$( 'html' ).addClass( 'File' );
			id = $(this).attr('id');
			id = id.slice( ( id.search( /b-get-for-/ ) + 10 ), id.length );
			tb_show( fpw_file_select.tb_show_title, 'media-upload.php?post_id=0&fpw_fs_field=val-for-id-' + id + '-field&type=file&TB_iframe=true' );			
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
					$( 'div#media-items' ).css({
						'clear':				'both'
					});
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
				var cat_id;
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
				cat_id = parent_src_vars['fpw_fs_field'];
				cat_id = cat_id.replace( 'val-for-id-', '');
				cat_id = cat_id.replace( '-field', '');
				parent.fpw_fs_select_item( cat_id, item_id, parent_src_vars['fpw_fs_field'] );
				return false;
			});
		}
	
	}

	//	Author link
	if ( $( '.fpw-btn-author' ).length ) {
		$( '.fpw-btn-author' ).click( function() {
			id = $(this).attr('id');
			id = id.slice( ( id.search( /b-author-for-/ ) + 13 ), id.length );
			fpw_fs_select_item( id, 'Author', 'val-for-id-' + id + '-field' );
			return false;
		});
	}

	// 	Clear link
	if ( $( '.fpw-btn-clear' ).length ) {
		$( '.fpw-btn-clear' ).click( function() {
			id = $(this).attr('id');
			id = id.slice( ( id.search( /b-clear-for-/ ) + 12 ), id.length );
			fpw_fs_select_item( id, 0, 'val-for-id-' + id + '-field' );
			return false;
		});
	}

	//	Refresh link
	if ( $( '.fpw-btn-refresh' ).length ) {
		$( '.fpw-btn-refresh' ).click( function() {
			id = $(this).attr('id');
			id = id.slice( ( id.search( /b-refresh-for-/ ) + 14 ), id.length );
			value = $( '#' + 'val-for-id-' + id + '-field' ).attr( 'value' );
			fpw_fs_select_item( id, value, 'val-for-id-' + id + '-field' );
			return false;
		});
	}

	// AJAX - Update Options button
	if ( $( '#update' ).length ) {
		$( '#update' ).click( function() {
			message_div = jQuery( '#message' );
			barr = jQuery('input:checkbox:checked.option-group').map(function () {
  						return this.value; }).get();
  			i = $.inArray( 'fpt', barr );
  			if ( i == -1 ) {
				$( "#fpt-link" ).css( "display", "none" );
			} else {
				$( "#fpt-link" ).css( "display", "" );
			}
			message_div.html( '<p><strong>' + fpw_file_select.wait_msg + '</strong></p>' ).load( fpw_file_select.ajaxurl, {
				boxes:		barr,
				action:		'fpw_ct_update'
			});
  			$('#message').fadeIn(1500).delay(3000).fadeOut(1500);
			return false;
		});
	}

	// AJAX - Apply button
	if ( $( '#apply' ).length ) {
		$( '#apply' ).click( function() {

			msg = fpw_file_select.apply_line_1_1 + ' <strong>';
			msg = msg + fpw_file_select.apply_line_1_2 + '</strong> ';
			msg = msg + fpw_file_select.apply_line_1_3 + ' ';
			msg = msg + fpw_file_select.apply_line_1_4 + ' "<em>';
			msg = msg + fpw_file_select.apply_line_1_5 + '"</em> ';
			msg = msg + fpw_file_select.apply_line_1_6 + '<br /> <br />';
			msg = msg + fpw_file_select.apply_line_2;
			jConfirm(msg, fpw_file_select.confirm_header, function(result){
				if (result) {
					message_div = jQuery( '#message' );
					message_div.html( '<p><strong>' + fpw_file_select.wait_msg + '</strong></p>' ).load( fpw_file_select.ajaxurl, {
						mode:		'apply',
						action:		'fpw_ct_apply'
					});
  					$('#message').fadeIn(1500).delay(3000).fadeOut(1500);
					return false;
				} else {
					return false;
				}
			});
			return false;
		});
	}

	// AJAX - Remove Thumbnails button
	if ( $( '#remove' ).length ) {
		$( '#remove' ).click( function() {
			msg = fpw_file_select.remove_line_1_1 + ' <strong>';
			msg = msg + fpw_file_select.remove_line_1_2 + '</strong> ';
			msg = msg + fpw_file_select.remove_line_1_3 + ' <strong>';
			msg = msg + fpw_file_select.remove_line_1_4 + '</strong> ';
			msg = msg + fpw_file_select.remove_line_1_5 + ' ';
			msg = msg + fpw_file_select.remove_line_1_6 + ' "<em>';
			msg = msg + fpw_file_select.remove_line_1_7 + '</em>" <strong>';
			msg = msg + fpw_file_select.remove_line_1_8 + '</strong> ';
			msg = msg + fpw_file_select.remove_line_1_9 + '<br /> <br />';
			msg = msg + fpw_file_select.apply_line_2;
			jConfirm(msg, fpw_file_select.confirm_header, function(result){
				if (result) {
					message_div = jQuery( '#message' );
					message_div.html( '<p><strong>' + fpw_file_select.wait_msg + '</strong></p>' ).load( fpw_file_select.ajaxurl, {
						mode:		'remove',
						action:		'fpw_ct_remove'
					});
  					$('#message').fadeIn(1500).delay(3000).fadeOut(1500);
					$('#restore').css('display','');
					return false;
				} else {
					return false;
				}
			});
			return false;
		});
	}

	// AJAX - Restore Thumbnails button
	if ( $( '#restore' ).length ) {
		$( '#restore' ).click( function() {
			msg = fpw_file_select.restore_line_1_1 + ' <strong>';
			msg = msg + fpw_file_select.restore_line_1_2 + '</strong> ';
			msg = msg + fpw_file_select.restore_line_1_3 + ' <strong>';
			msg = msg + fpw_file_select.restore_line_1_4 + '</strong> ';
			msg = msg + fpw_file_select.restore_line_1_5 + ' ';
			msg = msg + fpw_file_select.restore_line_1_6 + ' "<em>';
			msg = msg + fpw_file_select.restore_line_1_7 + '</em>" <strong>';
			msg = msg + fpw_file_select.restore_line_1_8 + '</strong> ';
			msg = msg + fpw_file_select.restore_line_1_9 + '<br /> <br />';
			msg = msg + fpw_file_select.apply_line_2;
			jConfirm(msg, fpw_file_select.confirm_header, function(result){
				if (result) {
					message_div = jQuery( '#message' );
					message_div.html( '<p><strong>' + fpw_file_select.wait_msg + '</strong></p>' ).load( fpw_file_select.ajaxurl, {
						mode:		'restore',
						action:		'fpw_ct_restore'
					});
  					$('#message').fadeIn(1500).delay(3000).fadeOut(1500);
					$('#restore').css('display','none');
					return false;
				} else {
					return false;
				}
			});
			return false;
		});
	}

	// AJAX - Get Language File button
	if ( $( '#language' ).length ) {
		$( '#language' ).click( function() {
			message_div = jQuery( '#message' );
			message_div.html( '<p><strong>' + fpw_file_select.wait_msg + '</strong></p>' ).load( fpw_file_select.ajaxurl, {
				action:		'fpw_ct_language'
			});
  			$('#message').fadeIn(1500).delay(3000).fadeOut(1500);
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

function fpw_fs_select_item( cat_id, item_id, field_id ) {
	var field, preview_div, preview_size;
	field = jQuery( '#' + field_id );
	preview_div = jQuery( '#' + field_id + '_preview' );
	preview_size = jQuery( '#' + field_id + '_preview-size' ).val();
	// Load preview image
	preview_div.html( '' ).load( fpw_file_select.ajaxurl, {
		cat:		cat_id,
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