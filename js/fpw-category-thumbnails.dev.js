jQuery( document ).ready( function() {
	jQuery( '.btn-for-get' ).click( function() {
		t = this;
		id = t.id;
		id = id.slice( ( id.search( /get-for-id-/ ) + 11 ), id.length );
		send_to_editor_clone = window.send_to_editor;
		window.send_to_editor = function( html ) {
			std = html.search( /wp-image-/ );
			nxt = html.search( /\[singlepic/ );
			if ( std >= 0 ) {
				img_id = html.slice( ( html.search( /wp-image-/ ) + 9 ), html.length );
				img_id = img_id.replace(/[^\d]/g, "");
			}
			if ( nxt >= 0 ) {
				arr = html.split( " " );
				img_id = 'ngg-' + arr[1].slice( ( arr[1].search( /id=/ ) + 3 ), arr[1].length );
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
		t = this;
		id = t.id;
		id = id.slice( ( id.search( /clear-for-id-/ ) + 13 ), id.length );
		jConfirm('Are you sure you want to clear this ID?', 'Confirmation Dialog', function(r) {
			if ( r ) jQuery( '#val-for-id-' + id ).val( 0 );
		});
		return false;
	});
});
