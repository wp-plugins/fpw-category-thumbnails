jQuery( document ).ready( function() {
	jQuery( '.btn-for-get' ).click( function() {
		t = this;
		id = t.id;
		id = id.slice( ( id.search( /get-for-id-/ ) + 11 ), id.length );
		send_to_editor_clone = window.send_to_editor;
		window.send_to_editor = function( html ) {
			img_id = jQuery( 'img', html ).attr( 'class' );
			if ( typeof img_id === 'undefined' ) {
				jAlert( "<strong>Link URL</strong> cannot be empty or malformed!<br /><br />Next time click on <strong>File URL</strong> or <strong>Post URL</strong> preset<br />before clicking <strong>Insert into Post</strong>." );
			}
			else {
				img_id = img_id.slice( ( img_id.search( /wp-image-/ ) + 9 ), img_id.length );
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
