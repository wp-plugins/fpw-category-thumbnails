jQuery( document ).ready( function() {
	jQuery( '.button-secondary' ).click( function() {
		t = this;
		id = t.id;
		id = id.slice( ( id.search( /get-for-id-/ ) +11 ), id.length );
		formfield = jQuery( '#val-for-id-' + id ).attr( 'name' );
		send_to_editor_clone = window.send_to_editor;
		window.send_to_editor = function( html ) {
			img_id = jQuery( 'img', html ).attr( 'class' );
			if ( typeof img_id === 'undefined' ) {
				alert( "'Link URL' cannot be empty or malformed!\n\nNext time click on 'File URL' or 'Post URL' preset\nbefore clicking 'Insert into Post'." );
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
});
