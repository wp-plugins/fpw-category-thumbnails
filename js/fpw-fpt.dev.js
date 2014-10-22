//	FPW Post Thumbnails JS

function isInArray(arr, obj) {
    for(var i=0; i<arr.length; i++) {
        if (arr[i] == obj) return true;
    }
    return false;
}

function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
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
			vcpos		= $( "#content-position" ).find( ":selected" ).text();
			vcradius	= $( "#content-border-radius" ).attr( "value" );
			vcbwidth	= $( "#content-border-width" ).attr( "value" );
			vcbocol		= $( "#content-border-color" ).attr( "value" );
			vcbacol		= $( "#content-background-color" ).attr( "value" );
			vcshhlen	= $( "#content-sh-hor-length" ).attr( "value" );
			vcshvlen	= $( "#content-sh-ver-length" ).attr( "value" );
			vcshblur	= $( "#content-sh-blur-radius" ).attr( "value" );
			vcshcol		= $( "#content-sh-color" ).attr( "value" );
			vcshopa		= $( "#content-sh-opacity" ).attr( "value" );
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
			vepos		= $( "#excerpt-position" ).find( ":selected" ).text();
			veradius	= $( "#excerpt-border-radius" ).attr( "value" );
			vebwidth	= $( "#excerpt-border-width" ).attr( "value" );
			vebocol		= $( "#excerpt-border-color" ).attr( "value" );
			vebacol		= $( "#excerpt-background-color" ).attr( "value" );
			veshhlen	= $( "#excerpt-sh-hor-length" ).attr( "value" );
			veshvlen	= $( "#excerpt-sh-ver-length" ).attr( "value" );
			veshblur	= $( "#excerpt-sh-blur-radius" ).attr( "value" );
			veshcol		= $( "#excerpt-sh-color" ).attr( "value" );
			veshopa		= $( "#excerpt-sh-opacity" ).attr( "value" );
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
				content_position:			vcpos,
				content_border_radius:		vcradius,
				content_border_width:		vcbwidth,
				content_border_color:		vcbocol,
				content_background_color:	vcbacol,
				content_sh_hor_length:		vcshhlen,
				content_sh_ver_length:		vcshvlen,
				content_sh_blur_radius:		vcshblur,
				content_sh_color:			vcshcol,
				content_sh_opacity:			vcshopa,
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
				excerpt_position:			vepos,
				excerpt_border_radius:		veradius,
				excerpt_border_width:		vebwidth,
				excerpt_border_color:		vebocol,
				excerpt_background_color:	vebacol,
				excerpt_sh_hor_length:		veshhlen,
				excerpt_sh_ver_length:		veshvlen,
				excerpt_sh_blur_radius:		veshblur,
				excerpt_sh_color:			veshcol,
				excerpt_sh_opacity:			veshopa,
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
			if ( $( "#box-content-shadow" ).is( ":checked" ) ) {
				$( "#box-excerpt-shadow" ).attr( "checked", true );
			} else {
				$( "#box-excerpt-shadow" ).attr( "checked", false );
			}
			$( "#excerpt-width" ).val( $( "#content-width" ).attr( "value" ) );
			$( "#excerpt-height" ).val( $( "#content-height" ).attr( "value" ) );
			$( "#excerpt-position" ).val( $( "#content-position" ).find( ":selected" ).text() );
			$( "#excerpt-border-radius" ).val( $( "#content-border-radius" ).attr( "value" ) );
			$( "#excerpt-border-width" ).val( $( "#content-border-width" ).attr( "value" ) );
			$( "#excerpt-border-color" ).attr( "style", $( "#content-border-color" ).attr( "style" ) );			
			$( "#excerpt-border-color" ).val( $( "#content-border-color" ).attr( "value" ) );
			$( "#excerpt-background-color" ).attr( "style", $( "#content-background-color" ).attr( "style" ) );			
			$( "#excerpt-background-color" ).val( $( "#content-background-color" ).attr( "value" ) );
			$( "#excerpt-sh-hor-length" ).val( $( "#content-sh-hor-length" ).attr( "value" ) );
			$( "#excerpt-sh-ver-length" ).val( $( "#content-sh-ver-length" ).attr( "value" ) );
			$( "#excerpt-sh-blur-radius" ).val( $( "#content-sh-blur-radius" ).attr( "value" ) );
			$( "#excerpt-sh-color" ).attr( "style", $( "#content-sh-color" ).attr( "style" ) );			
			$( "#excerpt-sh-color" ).val( $( "#content-sh-color" ).attr( "value" ) );
			$( "#excerpt-sh-opacity" ).val( $( "#content-sh-opacity" ).attr( "value" ) );
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
			if ( $( "#box-excerpt-shadow" ).is( ":checked" ) ) {
				$( "#box-content-shadow" ).attr( "checked", true );
			} else {
				$( "#box-content-shadow" ).attr( "checked", false );
			}
			$( "#content-width" ).val( $( "#excerpt-width" ).attr( "value" ) );
			$( "#content-height" ).val( $( "#excerpt-height" ).attr( "value" ) );
			$( "#content-position" ).val( $( "#excerpt-position" ).find( ":selected" ).text() );
			$( "#content-border-radius" ).val( $( "#excerpt-border-radius" ).attr( "value" ) );
			$( "#content-border-width" ).val( $( "#excerpt-border-width" ).attr( "value" ) );
			$( "#content-border-color" ).attr( "style", $( "#excerpt-border-color" ).attr( "style" ) );			
			$( "#content-border-color" ).val( $( "#excerpt-border-color" ).attr( "value" ) );
			$( "#content-background-color" ).attr( "style", $( "#excerpt-background-color" ).attr( "style" ) );			
			$( "#content-background-color" ).val( $( "#excerpt-background-color" ).attr( "value" ) );
			$( "#content-sh-hor-length" ).val( $( "#excerpt-sh-hor-length" ).attr( "value" ) );
			$( "#content-sh-ver-length" ).val( $( "#excerpt-sh-ver-length" ).attr( "value" ) );
			$( "#content-sh-blur-radius" ).val( $( "#excerpt-sh-blur-radius" ).attr( "value" ) );
			$( "#content-sh-color" ).attr( "style", $( "#excerpt-sh-color" ).attr( "style" ) );			
			$( "#content-sh-color" ).val( $( "#excerpt-sh-color" ).attr( "value" ) );
			$( "#content-sh-opacity" ).val( $( "#excerpt-sh-opacity" ).attr( "value" ) );
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

	//	content Preview button - AJAX
	if ( $( "#content-preview" ).length) {
		$( "#content-preview" ).click( function() {
			var barr, vcwidth, vcheight, vcpos, vcradius, vcbwidth;
			var vcbocol, vcbacol, vcpt, vcpl, vcpb, vcpr, vcmt, vcml, vcmb, vcmr;
			var vcshhlen, vcshvlen, vcshblur, vcshcol, vcshopa;
			barr = $( "input:checkbox:checked.fpt-option-group" ).map( function() {
				return this.value
			}).get();
			vcwidth		= $( "#content-width" ).attr( "value" );
			vcheight	= $( "#content-height" ).attr( "value" );
			vcpos		= $( "#content-position" ).find( ":selected" ).text();
			vcradius	= $( "#content-border-radius" ).attr( "value" );
			vcbwidth	= $( "#content-border-width" ).attr( "value" );
			vcbocol		= $( "#content-border-color" ).attr( "value" );
			vcbacol		= $( "#content-background-color" ).attr( "value" );
			vcshhlen	= $( "#content-sh-hor-length" ).attr( "value" );
			vcshvlen	= $( "#content-sh-ver-length" ).attr( "value" );
			vcshblur	= $( "#content-sh-blur-radius" ).attr( "value" );
			vcshcol		= $( "#content-sh-color" ).attr( "value" );
			vcshopa		= $( "#content-sh-opacity" ).attr( "value" );
			vcpt		= $( "#content-padding-top" ).attr( "value" );
			vcpl		= $( "#content-padding-left" ).attr( "value" );
			vcpb		= $( "#content-padding-bottom" ).attr( "value" );
			vcpr		= $( "#content-padding-right" ).attr( "value" );
			vcmt		= $( "#content-margin-top" ).attr( "value" );
			vcml		= $( "#content-margin-left" ).attr( "value" );
			vcmb		= $( "#content-margin-bottom" ).attr( "value" );
			vcmr		= $( "#content-margin-right" ).attr( "value" );
			$( ".wp-post-image-content" ).css( "float", vcpos );
			if ( isInArray( barr, 'content_border' ) ) {
				$( ".wp-post-image-content" ).css( "border", "solid " + vcbwidth + "px " + vcbocol );
				$( ".wp-post-image-content" ).css( "background-color", vcbacol );
				$( ".wp-post-image-content" ).css( "border-radius", vcradius + "px" );
				$( ".wp-post-image-content" ).css( "-moz-border-radius", vcradius + "px" );
				$( ".wp-post-image-content" ).css( "-webkit-border-radius", vcradius + "px" );
				if ( isInArray ( barr, 'content_shadow' ) ) { 
					red = hexToRgb( vcshcol ).r;
					green = hexToRgb( vcshcol ).g;
					blue = hexToRgb( vcshcol ).b;
					$( ".wp-post-image-content" ).css( "box-shadow", vcshhlen + "px " + vcshvlen + "px " + vcshblur + "px 0px rgba(" + red + "," + green + "," + blue + "," + vcshopa + ")" );
					$( ".wp-post-image-content" ).css( "-webkit-box-shadow", vcshhlen + "px " + vcshvlen + "px " + vcshblur + "px 0px rgba(" + red + "," + green + "," + blue + "," + vcshopa + ")" );
					$( ".wp-post-image-content" ).css( "-moz-box-shadow", vcshhlen + "px " + vcshvlen + "px " + vcshblur + "px 0px rgba(" + red + "," + green + "," + blue + "," + vcshopa + ")" );		
				};
			} else {
				$( ".wp-post-image-content" ).css( "border", "none 0px transparent" );
				$( ".wp-post-image-content" ).css( "background-color", "transparent" );
			};
			$( ".wp-post-image-content" ).css( "padding", vcpt + "px " + vcpr + "px " + vcpb + "px " + vcpl + "px" );
			$( ".wp-post-image-content" ).css( "margin", vcmt + "px " + vcmr + "px " + vcmb + "px " + vcml + "px" );
            $( ".wp-post-image-content" ).css( "width", vcwidth + "px" );
			return true;
		});
	}

	//	excerpt Preview button - AJAX
	if ( $( "#excerpt-preview" ).length) {
		$( "#excerpt-preview" ).click( function() {
			var barr, vewidth, veheight, vepos, veradius, vebwidth;
			var vebocol, vebacol, vept, vepl, vepb, vepr, vemt, veml, vemb, vemr;
			var vcshhlen, vcshvlen, vcshblur, vcshcol, vcshopa;
			barr = $( "input:checkbox:checked.fpt-option-group" ).map( function() {
				return this.value
			}).get();
			vewidth		= $( "#excerpt-width" ).attr( "value" );
			veheight	= $( "#excerpt-height" ).attr( "value" );
			vepos		= $( "#excerpt-position" ).find( ":selected" ).text();
			veradius	= $( "#excerpt-border-radius" ).attr( "value" );
			vebwidth	= $( "#excerpt-border-width" ).attr( "value" );
			vebocol		= $( "#excerpt-border-color" ).attr( "value" );
			vebacol		= $( "#excerpt-background-color" ).attr( "value" );
			veshhlen	= $( "#excerpt-sh-hor-length" ).attr( "value" );
			veshvlen	= $( "#excerpt-sh-ver-length" ).attr( "value" );
			veshblur	= $( "#excerpt-sh-blur-radius" ).attr( "value" );
			veshcol		= $( "#excerpt-sh-color" ).attr( "value" );
			veshopa		= $( "#excerpt-sh-opacity" ).attr( "value" );
			vept		= $( "#excerpt-padding-top" ).attr( "value" );
			vepl		= $( "#excerpt-padding-left" ).attr( "value" );
			vepb		= $( "#excerpt-padding-bottom" ).attr( "value" );
			vepr		= $( "#excerpt-padding-right" ).attr( "value" );
			vemt		= $( "#excerpt-margin-top" ).attr( "value" );
			veml		= $( "#excerpt-margin-left" ).attr( "value" );
			vemb		= $( "#excerpt-margin-bottom" ).attr( "value" );
			vemr		= $( "#excerpt-margin-right" ).attr( "value" );
			$( ".wp-post-image-excerpt" ).css( "float", vepos );
			if ( isInArray( barr, 'excerpt_border' ) ) {
				$( ".wp-post-image-excerpt" ).css( "border", "solid " + vebwidth + "px " + vebocol );
				$( ".wp-post-image-excerpt" ).css( "background-color", vebacol );
				$( ".wp-post-image-excerpt" ).css( "border-radius", veradius + "px" );
				$( ".wp-post-image-excerpt" ).css( "-moz-border-radius", veradius + "px" );
				$( ".wp-post-image-excerpt" ).css( "-webkit-border-radius", veradius + "px" );
				if ( isInArray ( barr, 'excerpt_shadow' ) ) {
					red = hexToRgb( veshcol ).r;
					green = hexToRgb( veshcol ).g;
					blue = hexToRgb( veshcol ).b;
					$( ".wp-post-image-excerpt" ).css( "box-shadow", veshhlen + "px " + veshvlen + "px " + veshblur + "px 0px rgba(" + red + "," + green + "," + blue + "," + veshopa + ")" );
					$( ".wp-post-image-excerpt" ).css( "-webkit-box-shadow", veshhlen + "px " + veshvlen + "px " + veshblur + "px 0px rgba(" + red + "," + green + "," + blue + "," + veshopa + ")" );
					$( ".wp-post-image-excerpt" ).css( "-moz-box-shadow", veshhlen + "px " + veshvlen + "px " + veshblur + "px 0px rgba(" + red + "," + green + "," + blue + "," + veshopa + ")" );		
				};
			} else {
				$( ".wp-post-image-excerpt" ).css( "border", "none 0px transparent" );
				$( ".wp-post-image-excerpt" ).css( "background-color", "transparent" );
			};
			$( ".wp-post-image-excerpt" ).css( "padding", vept + "px " + vepr + "px " + vepb + "px " + vepl + "px" );
			$( ".wp-post-image-excerpt" ).css( "margin", vemt + "px " + vemr + "px " + vemb + "px " + veml + "px" );
            $( ".wp-post-image-excerpt" ).css( "width", vewidth + "px" );
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

	$( "#colorpicker-content-sh-color" ).hide();
	$( "#colorpicker-content-sh-color" ).farbtastic( "#content-sh-color" );
	
	$( "#content-sh-color" ).click( function() {
		$( "#colorpicker-content-sh-color" ).fadeIn()
	});

	$( document ).mousedown( function() {
		$( "#colorpicker-content-sh-color" ).each( function() {
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

	$( "#colorpicker-excerpt-sh-color" ).hide();
	$( "#colorpicker-excerpt-sh-color" ).farbtastic( "#excerpt-sh-color" );
	
	$( "#excerpt-sh-color" ).click( function() {
		$( "#colorpicker-excerpt-sh-color" ).fadeIn()
	});

	$( document ).mousedown( function() {
		$( "#colorpicker-excerpt-sh-color" ).each( function() {
			var b = $( this ).css( "display" );
			if ( b== "block" )
				$( this ).fadeOut()
		});
	});
	
});