//	FPW Post Thumbnails JS

function isInArray(arr, obj) {
    for(var i=0; i<arr.length; i++) {
        if (arr[i] == obj) return true;
    }
    return false;
}

jQuery( document ).ready( function( $ ) {

	//	replace contextual Help link
	$( "#contextual-help-link" ).html( fpw_fpt.help_link_text );

	//	Update Options button - AJAX
	if ( $( "#fpt-update" ).length) {
		$( "#fpt-update" ).click( function() {
			message_div = $( "#fpt-message" );
			barr = $( "input:checkbox:checked.fpt-option-group" ).map( function() {
				return this.value
			}).get();
			vcwidth		= $( "#content-width" ).attr( "value" );
			vcheight	= $( "#content-height" ).attr( "value" );
			vcbase		= $( "#content-base" ).find( ":selected" ).text();
			vcpos		= $( "#content-position" ).find( ":selected" ).text();
			vcradius	= $( "#content-border-radius" ).attr( "value" );
			vcbwidth	= $( "#content-border-width" ).attr( "value" );
			vcbocol		= $( "#content-border-color" ).attr( "value" );
			vcbacol		= $( "#content-background-color" ).attr( "value" );
			vcpt		= $( "#content-padding-top" ).attr( "value" );
			vcpl		= $( "#content-padding-left" ).attr( "value" );
			vcpb		= $( "#content-padding-bottom" ).attr( "value" );
			vcpr		= $( "#content-padding-right" ).attr( "value" );
			vcmt		= $( "#content-margin-top" ).attr( "value" );
			vcml		= $( "#content-margin-left" ).attr( "value" );
			vcmb		= $( "#content-margin-bottom" ).attr( "value" );
			vcmr		= $( "#content-margin-right" ).attr( "value" );
			vewidth		= $( "#excerpt-width" ).attr( "value" );
			veheight	= $( "#excerpt-height" ).attr( "value" );
			vebase		= $( "#excerpt-base" ).find( ":selected" ).text();
			vepos		= $( "#excerpt-position" ).find( ":selected" ).text();
			veradius	= $( "#excerpt-border-radius" ).attr( "value" );
			vebwidth	= $( "#excerpt-border-width" ).attr( "value" );
			vebocol		= $( "#excerpt-border-color" ).attr( "value" );
			vebacol		= $( "#excerpt-background-color" ).attr( "value" );
			vept		= $( "#excerpt-padding-top" ).attr( "value" );
			vepl		= $( "#excerpt-padding-left" ).attr( "value" );
			vepb		= $( "#excerpt-padding-bottom" ).attr( "value" );
			vepr		= $( "#excerpt-padding-right" ).attr( "value" );
			vemt		= $( "#excerpt-margin-top" ).attr( "value" );
			veml		= $( "#excerpt-margin-left" ).attr( "value" );
			vemb		= $( "#excerpt-margin-bottom" ).attr( "value" );
			vemr		= $( "#excerpt-margin-right" ).attr( "value" );
			message_div.html( "<p><strong>" + fpw_fpt.wait_msg + "</strong></p>" ).load( fpw_fpt.ajaxurl, {
				boxes:						barr,
				content_width:				vcwidth,
				content_height:				vcheight,
				content_base:				vcbase,
				content_position:			vcpos,
				content_border_radius:		vcradius,
				content_border_width:		vcbwidth,
				content_border_color:		vcbocol,
				content_background_color:	vcbacol,
				content_padding_top:		vcpt,
				content_padding_left:		vcpl,
				content_padding_bottom:		vcpb,
				content_padding_right:		vcpr,
				content_margin_top:			vcmt,
				content_margin_left:		vcml,
				content_margin_bottom:		vcmb,
				content_margin_right:		vcmr,
				excerpt_width:				vewidth,
				excerpt_height:				veheight,
				excerpt_base:				vebase,
				excerpt_position:			vepos,
				excerpt_border_radius:		veradius,
				excerpt_border_width:		vebwidth,
				excerpt_border_color:		vebocol,
				excerpt_background_color:	vebacol,
				excerpt_padding_top:		vept,
				excerpt_padding_left:		vepl,
				excerpt_padding_bottom:		vepb,
				excerpt_padding_right:		vepr,
				excerpt_margin_top:			vemt,
				excerpt_margin_left:		veml,
				excerpt_margin_bottom:		vemb,
				excerpt_margin_right:		vemr,
				action:						"fpw_pt_update"
				}
			).delay( 750 );
			$( "#fpt-message" ).fadeIn( 2500 ).delay( 4e3 ).fadeOut( 1500 );
			return false;
		});
	}

	//	Get Language File button - AJAX
	if ( $( "#fpt-language" ).length) {
		$( "#fpt-language" ).click( function() {
			message_div = $( "#fpt-message" );
			message_div.html( "<p><strong>" + fpw_fpt.wait_msg + "</strong></p>" ).load( fpw_fpt.ajaxurl, {
				action:						"fpw_pt_language"
				}
			).delay( 750 );
			$( "#fpt-message" ).fadeIn( 1500 ).delay( 4e3 ).fadeOut( 1500 );
			return false;
		});
	}

	//	Copy to Right Panel button - AJAX
	if ( $( "#fpt-copy-right" ).length) {
		$( "#fpt-copy-right" ).click( function() {
			message_div = $( "#fpt-message" );
			if ( $( "#box-content-enabled" ).is( ":checked" ) ) {
				$( "#box-excerpt-enabled" ).attr( "checked", true );
			} else {
				$( "#box-excerpt-enabled" ).attr( "checked", false );
			}
			if ( $( "#box-content-border" ).is( ":checked" ) ) {
				$( "#box-excerpt-border" ).attr( "checked", true );
			} else {
				$( "#box-excerpt-border" ).attr( "checked", false );
			}
			$( "#excerpt-width" ).val( $( "#content-width" ).attr( "value" ) );
			$( "#excerpt-height" ).val( $( "#content-height" ).attr( "value" ) );
			$( "#excerpt-base" ).val( $( "#content-base" ).find( ":selected" ).text() );
			$( "#excerpt-position" ).val( $( "#content-position" ).find( ":selected" ).text() );
			$( "#excerpt-border-radius" ).val( $( "#content-border-radius" ).attr( "value" ) );
			$( "#excerpt-border-width" ).val( $( "#content-border-width" ).attr( "value" ) );
			$( "#excerpt-border-color" ).attr( "style", $( "#content-border-color" ).attr( "style" ) );			
			$( "#excerpt-border-color" ).val( $( "#content-border-color" ).attr( "value" ) );
			$( "#excerpt-background-color" ).attr( "style", $( "#content-background-color" ).attr( "style" ) );			
			$( "#excerpt-background-color" ).val( $( "#content-background-color" ).attr( "value" ) );
			$( "#excerpt-padding-top" ).val( $( "#content-padding-top" ).attr( "value" ) );
			$( "#excerpt-padding-left" ).val( $( "#content-padding-left" ).attr( "value" ) );
			$( "#excerpt-padding-bottom" ).val( $( "#content-padding-bottom" ).attr( "value" ) );
			$( "#excerpt-padding-right" ).val( $( "#content-padding-right" ).attr( "value" ) );
			$( "#excerpt-margin-top" ).val( $( "#content-margin-top" ).attr( "value" ) );
			$( "#excerpt-margin-left" ).val( $( "#content-margin-left" ).attr( "value" ) );
			$( "#excerpt-margin-bottom" ).val( $( "#content-margin-bottom" ).attr( "value" ) );
			$( "#excerpt-margin-right" ).val( $( "#content-margin-right" ).attr( "value" ) );
			message_div.html( "<p><strong>" + fpw_fpt.wait_msg + "</strong></p>" ).load( fpw_fpt.ajaxurl, {
				action:	"fpw_pt_copy_right"
				}
			).delay( 750 );
			$( "#fpt-message" ).fadeIn( 1500 ).delay( 1000 ).fadeOut( 1500 );
			return false;
		});
	}

	//	Copy to Left Panel button - AJAX
	if ( $( "#fpt-copy-left" ).length) {
		$( "#fpt-copy-left" ).click( function() {
			message_div = $( "#fpt-message" );
			if ( $( "#box-excerpt-enabled" ).is( ":checked" ) ) {
				$( "#box-content-enabled" ).attr( "checked", true );
			} else {
				$( "#box-content-enabled" ).attr( "checked", false );
			}
			if ( $( "#box-excerpt-border" ).is( ":checked" ) ) {
				$( "#box-content-border" ).attr( "checked", true );
			} else {
				$( "#box-content-border" ).attr( "checked", false );
			}
			$( "#content-width" ).val( $( "#excerpt-width" ).attr( "value" ) );
			$( "#content-height" ).val( $( "#excerpt-height" ).attr( "value" ) );
			$( "#content-base" ).val( $( "#excerpt-base" ).find( ":selected" ).text() );
			$( "#content-position" ).val( $( "#excerpt-position" ).find( ":selected" ).text() );
			$( "#content-border-radius" ).val( $( "#excerpt-border-radius" ).attr( "value" ) );
			$( "#content-border-width" ).val( $( "#excerpt-border-width" ).attr( "value" ) );
			$( "#content-border-color" ).attr( "style", $( "#excerpt-border-color" ).attr( "style" ) );			
			$( "#content-border-color" ).val( $( "#excerpt-border-color" ).attr( "value" ) );
			$( "#content-background-color" ).attr( "style", $( "#excerpt-background-color" ).attr( "style" ) );			
			$( "#content-background-color" ).val( $( "#excerpt-background-color" ).attr( "value" ) );
			$( "#content-padding-top" ).val( $( "#excerpt-padding-top" ).attr( "value" ) );
			$( "#content-padding-left" ).val( $( "#excerpt-padding-left" ).attr( "value" ) );
			$( "#content-padding-bottom" ).val( $( "#excerpt-padding-bottom" ).attr( "value" ) );
			$( "#content-padding-right" ).val( $( "#excerpt-padding-right" ).attr( "value" ) );
			$( "#content-margin-top" ).val( $( "#excerpt-margin-top" ).attr( "value" ) );
			$( "#content-margin-left" ).val( $( "#excerpt-margin-left" ).attr( "value" ) );
			$( "#content-margin-bottom" ).val( $( "#excerpt-margin-bottom" ).attr( "value" ) );
			$( "#content-margin-right" ).val( $( "#excerpt-margin-right" ).attr( "value" ) );
			message_div.html( "<p><strong>" + fpw_fpt.wait_msg + "</strong></p>" ).load( fpw_fpt.ajaxurl, {
				action:						"fpw_pt_copy_left"
				}
			).delay( 750 );
			$( "#fpt-message" ).fadeIn( 1500 ).delay( 1000 ).fadeOut( 1500 );
			return false;
		});
	}

	//	content Preview button
	if ( $( "#content-preview" ).length) {
		$( "#content-preview" ).click( function() {
			barr = $( "input:checkbox:checked.fpt-option-group" ).map( function() {
				return this.value
			}).get();
			vcwidth		= $( "#content-width" ).attr( "value" );
			vcheight	= $( "#content-height" ).attr( "value" );
			vcbase		= $( "#content-base" ).find( ":selected" ).text();
			vcpos		= $( "#content-position" ).find( ":selected" ).text();
			vcradius	= $( "#content-border-radius" ).attr( "value" );
			vcbwidth	= $( "#content-border-width" ).attr( "value" );
			vcbocol		= $( "#content-border-color" ).attr( "value" );
			vcbacol		= $( "#content-background-color" ).attr( "value" );
			vcpt		= $( "#content-padding-top" ).attr( "value" );
			vcpl		= $( "#content-padding-left" ).attr( "value" );
			vcpb		= $( "#content-padding-bottom" ).attr( "value" );
			vcpr		= $( "#content-padding-right" ).attr( "value" );
			vcmt		= $( "#content-margin-top" ).attr( "value" );
			vcml		= $( "#content-margin-left" ).attr( "value" );
			vcmb		= $( "#content-margin-bottom" ).attr( "value" );
			vcmr		= $( "#content-margin-right" ).attr( "value" );
			if ( vcbase == "width" ) {
				$( ".wp-post-image-content" ).css( "width", vcwidth );
				$( ".wp-post-image-content" ).css( "height", "" );
			} else {
				$( ".wp-post-image-content" ).css( "width", "" );
				$( ".wp-post-image-content" ).css( "height", vcheight );
			}
			$( ".wp-post-image-content" ).css( "float", vcpos );
			if ( isInArray( barr, 'content_border' ) ) {
				$( ".wp-post-image-content" ).css( "border", "solid " + vcbwidth + "px " + vcbocol );
				$( ".wp-post-image-content" ).css( "background-color", vcbacol );
				$( ".wp-post-image-content" ).css( "border-radius", vcradius + "px" );
				$( ".wp-post-image-content" ).css( "-moz-border-radius", vcradius + "px" );
				$( ".wp-post-image-content" ).css( "-webkit-border-radius", vcradius + "px" );
			} else {
				$( ".wp-post-image-content" ).css( "border", "none 0px transparent" );
				$( ".wp-post-image-content" ).css( "background-color", "transparent" );
			}
			$( ".wp-post-image-content" ).css( "padding", vcpt + "px " + vcpr + "px " + vcpb + "px " + vcpl + "px" );
			$( ".wp-post-image-content" ).css( "margin", vcmt + "px " + vcmr + "px " + vcmb + "px " + vcml + "px" );
			return true;
		});
	}

	//	excerpt Preview button - AJAX
	if ( $( "#excerpt-preview" ).length) {
		$( "#excerpt-preview" ).click( function() {
			barr = $( "input:checkbox:checked.fpt-option-group" ).map( function() {
				return this.value
			}).get();
			vewidth		= $( "#excerpt-width" ).attr( "value" );
			veheight	= $( "#excerpt-height" ).attr( "value" );
			vebase		= $( "#excerpt-base" ).find( ":selected" ).text();
			vepos		= $( "#excerpt-position" ).find( ":selected" ).text();
			veradius	= $( "#excerpt-border-radius" ).attr( "value" );
			vebwidth	= $( "#excerpt-border-width" ).attr( "value" );
			vebocol		= $( "#excerpt-border-color" ).attr( "value" );
			vebacol		= $( "#excerpt-background-color" ).attr( "value" );
			vept		= $( "#excerpt-padding-top" ).attr( "value" );
			vepl		= $( "#excerpt-padding-left" ).attr( "value" );
			vepb		= $( "#excerpt-padding-bottom" ).attr( "value" );
			vepr		= $( "#excerpt-padding-right" ).attr( "value" );
			vemt		= $( "#excerpt-margin-top" ).attr( "value" );
			veml		= $( "#excerpt-margin-left" ).attr( "value" );
			vemb		= $( "#excerpt-margin-bottom" ).attr( "value" );
			vemr		= $( "#excerpt-margin-right" ).attr( "value" );
			if ( vebase == "width" ) {
				$( ".wp-post-image-excerpt" ).css( "width", vewidth );
				$( ".wp-post-image-excerpt" ).css( "height", "" );
			} else {
				$( ".wp-post-image-excerpt" ).css( "width", "" );
				$( ".wp-post-image-excerpt" ).css( "height", veheight );
			}
			$( ".wp-post-image-excerpt" ).css( "float", vepos );
			if ( isInArray( barr, 'excerpt_border' ) ) {
				$( ".wp-post-image-excerpt" ).css( "border", "solid " + vebwidth + "px " + vebocol );
				$( ".wp-post-image-excerpt" ).css( "background-color", vebacol );
				$( ".wp-post-image-excerpt" ).css( "border-radius", veradius + "px" );
				$( ".wp-post-image-excerpt" ).css( "-moz-border-radius", veradius + "px" );
				$( ".wp-post-image-excerpt" ).css( "-webkit-border-radius", veradius + "px" );
			} else {
				$( ".wp-post-image-excerpt" ).css( "border", "none 0px transparent" );
				$( ".wp-post-image-excerpt" ).css( "background-color", "transparent" );
			}
			$( ".wp-post-image-excerpt" ).css( "padding", vept + "px " + vepr + "px " + vepb + "px " + vepl + "px" );
			$( ".wp-post-image-excerpt" ).css( "margin", vemt + "px " + vemr + "px " + vemb + "px " + veml + "px" );
			return true;
		});
	}

	//	farbtastic magic starts here
	$( "#colorpicker-content-border-color" ).hide();
	$( "#colorpicker-content-border-color" ).farbtastic( "#content-border-color" );
	
	$( "#content-border-color" ).click( function() {
		$( "#colorpicker-content-border-color" ).fadeIn()
	});

	$( document ).mousedown( function() {
		$( "#colorpicker-content-border-color" ).each( function() {
			var b =$( this ).css( "display" );
			if ( b == "block" )
				$( this ).fadeOut()
		});
	});

	$( "#colorpicker-content-background-color" ).hide();
	$( "#colorpicker-content-background-color" ).farbtastic( "#content-background-color" );
	
	$( "#content-background-color" ).click( function() {
		$( "#colorpicker-content-background-color" ).fadeIn()
	});

	$( document ).mousedown( function() {
		$( "#colorpicker-content-background-color" ).each( function() {
			var b = $( this ).css( "display" );
			if ( b== "block" )
				$( this ).fadeOut()
		});
	});

	$( "#colorpicker-excerpt-border-color" ).hide();
	$( "#colorpicker-excerpt-border-color" ).farbtastic( "#excerpt-border-color" );
	
	$( "#excerpt-border-color" ).click( function() {
		$( "#colorpicker-excerpt-border-color" ).fadeIn()
	});

	$( document ).mousedown( function() {
		$( "#colorpicker-excerpt-border-color" ).each( function() {
			var b = $( this ).css( "display" );
			if ( b == "block" )
				$( this ).fadeOut()
		});
	});

	$( "#colorpicker-excerpt-background-color" ).hide();
	$( "#colorpicker-excerpt-background-color" ).farbtastic( "#excerpt-background-color" );
	
	$( "#excerpt-background-color" ).click( function() {
		$( "#colorpicker-excerpt-background-color" ).fadeIn()
	});

	$( document ).mousedown( function() {
		$( "#colorpicker-excerpt-background-color" ).each( function() {
			var b = $( this ).css( "display" );
			if ( b == "block" )
				$( this ).fadeOut()
		});
	});
	
});