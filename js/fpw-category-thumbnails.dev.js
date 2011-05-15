jQuery( document ).ready( function() {
	jQuery( '.btn-for-get' ).click( function() {
		id = this.id;
		id = id.substr( 11 );
		send_to_editor_clone = window.send_to_editor;
		window.send_to_editor = function( html ) {
			std = html.search( /wp-image-/ );
			nxt = html.search( /\[singlepic/ );
			if ( std >= 0 ) {
				img_id = html.substr( std + 9 );
				img_id = img_id.replace(/[^\d]/g, "");
			}
			if ( nxt >= 0 ) {
				arr = html.split( " " );
				img_id = 'ngg-' + arr[1].substr( 3 );
			}
			if ( std == -1 && nxt == -1 ) {
				jAlert( "To get id of an image from <strong>NextGEN</strong> gallery you <strong>MUST</strong> select <strong>Size</strong> - <strong>Singlepc</strong><br />before clicking <strong>Insert into Post</strong>." );
			}
			else {
				jQuery( '#val-for-id-' + id ).val( img_id );
			}
			tb_remove();
			window.send_to_editor = send_to_editor_clone;
		}
		tb_show( 'Get Image ID', 'media-upload.php?type=image&amp;TB_iframe=true' );
		return false;
	});
	jQuery( '.btn-for-clear').click( function() {
		id = this.id;
		id = id.substr( 13 );
		jConfirm('Are you sure you want to clear this ID?', 'Confirmation Dialog', function(r) {
			if ( r ) jQuery( '#val-for-id-' + id ).val( 0 );
		});
		return false;
	});
});
