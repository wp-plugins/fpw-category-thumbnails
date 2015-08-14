<?php
//	prevent direct access
if ( ! defined( 'ABSPATH' ) )  
	die( 'Direct access to this script is not allowed!' );

//	plugin's main class
class fpwPostThumbnails {
	var	$fptOptions;
	var	$fptPath;
	var	$fptUrl;
	var $fptVersion;
	var	$fptPage;
	var	$wpVersion;

	//	constructor
	function __construct( $path, $version ) {
		global $wp_version;

		//	set WP version
		$this->wpVersion = $wp_version;

		//	set plugin's path
		$this->fptPath = $path;

		//	set plugin's url
		$this->fptUrl = WP_PLUGIN_URL . '/fpw-category-thumbnails';

		//	set version
		$this->fptVersion = $version;
		define( 'FPW_POST_THUMBNAILS_VERSION', $version );
		
		//	get post thumbnails options
		$this->fptOptions = get_option( 'fpw_post_thumbnails_options' );

		if ( ! is_array( $this->fptOptions ) ) {
			$this->fptOptions = $this->fptBuildOptions(); 
		} else {
			if ( !array_key_exists ( 'nothepostthumbnail' , $this->fptOptions ) )
				$this->fptOptions[ 'nothepostthumbnail' ] = false;						
			if ( !array_key_exists ( 'content' , $this->fptOptions ) )
				$this->fptOptions[ 'content' ] = array();
			if ( !array_key_exists ( 'enabled' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'enabled' ] = false;						
			if ( !array_key_exists ( 'width' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'width' ] = 64;						
			if ( !array_key_exists ( 'height' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'height' ] = 64;						
			if ( !array_key_exists ( 'position' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'position' ] = 'left';						
			if ( !array_key_exists ( 'border' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'border' ] = false;						
			if ( !array_key_exists ( 'border_width' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'border_width' ] = 1;						
			if ( !array_key_exists ( 'border_radius' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'border_radius' ] = 0;						
			if ( !array_key_exists ( 'border_color' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'border_color' ] = '#000000';						
			if ( !array_key_exists ( 'background_color' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'background_color' ] = '#FFFFFF';						
			if ( !array_key_exists ( 'shadow' , $this->fptOptions[ 'content'] ) )
				$this->fptOptions[ 'content' ][ 'shadow' ] = false;						
			if ( !array_key_exists ( 'sh_hor_length' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'sh_hor_length' ] = 0;						
			if ( !array_key_exists ( 'sh_ver_length' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'sh_ver_length' ] = 0;						
			if ( !array_key_exists ( 'sh_blur_radius' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'sh_blur_radius' ] = 0;						
			if ( !array_key_exists ( 'sh_color' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'sh_color' ] = '#000000';						
			if ( !array_key_exists ( 'sh_opacity' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'sh_opacity' ] = 0;						
			if ( !array_key_exists ( 'padding_top' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'padding_top' ] = 0;						
			if ( !array_key_exists ( 'padding_left' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'padding_left' ] = 0;						
			if ( !array_key_exists ( 'padding_bottom' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'padding_bottom' ] = 0;						
			if ( !array_key_exists ( 'padding_right' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'padding_right' ] = 0;						
			if ( !array_key_exists ( 'margin_top' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'margin_top' ] = 0;						
			if ( !array_key_exists ( 'margin_left' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'margin_left' ] = 0;						
			if ( !array_key_exists ( 'margin_bottom' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'margin_bottom' ] = 0;						
			if ( !array_key_exists ( 'margin_right' , $this->fptOptions[ 'content' ] ) )
				$this->fptOptions[ 'content' ][ 'margin_right' ] = 0;						
			if ( !array_key_exists ( 'excerpt' , $this->fptOptions ) )
				$this->fptOptions[ 'excerpt' ] = array();
			if ( !array_key_exists ( 'enabled' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'enabled' ] = false;						
			if ( !array_key_exists ( 'width' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'width' ] = 64;						
			if ( !array_key_exists ( 'height' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'height' ] = 64;						
			if ( !array_key_exists ( 'position' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'position' ] = 'left';						
			if ( !array_key_exists ( 'border' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'border' ] = false;						
			if ( !array_key_exists ( 'border_width' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'border_width' ] = 1;						
			if ( !array_key_exists ( 'border_radius' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'border_radius' ] = 0;						
			if ( !array_key_exists ( 'border_color' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'border_color' ] = '#000000';						
			if ( !array_key_exists ( 'background_color' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'background_color' ] = '#FFFFFF';						
			if ( !array_key_exists ( 'shadow' , $this->fptOptions[ 'excerpt'] ) )
				$this->fptOptions[ 'excerpt' ][ 'shadow' ] = false;						
			if ( !array_key_exists ( 'sh_hor_length' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'sh_hor_length' ] = 0;						
			if ( !array_key_exists ( 'sh_ver_length' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'sh_ver_length' ] = 0;						
			if ( !array_key_exists ( 'sh_blur_radius' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'sh_blur_radius' ] = 0;						
			if ( !array_key_exists ( 'sh_color' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'sh_color' ] = '#000000';						
			if ( !array_key_exists ( 'sh_opacity' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'sh_opacity' ] = 0;						
			if ( !array_key_exists ( 'padding_top' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'padding_top' ] = 0;						
			if ( !array_key_exists ( 'padding_left' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'padding_left' ] = 0;						
			if ( !array_key_exists ( 'padding_bottom' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'padding_bottom' ] = 0;						
			if ( !array_key_exists ( 'padding_right' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'padding_right' ] = 0;						
			if ( !array_key_exists ( 'margin_top' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'margin_top' ] = 0;						
			if ( !array_key_exists ( 'margin_left' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'margin_left' ] = 0;						
			if ( !array_key_exists ( 'margin_bottom' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'margin_bottom' ] = 0;						
			if ( !array_key_exists ( 'margin_right' , $this->fptOptions[ 'excerpt' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'margin_right' ] = 0;						
		}  

		//	actions and filters
		add_action( 'init', array( &$this, 'init' ) );

		//	actions below are not used in front end
		add_action( 'admin_menu', array( &$this, 'adminMenu' ) );

		//	AJAX group of actions
		add_action( 'wp_ajax_fpw_pt_update', array( &$this, 'fpw_pt_update_ajax' ) );
		add_action( 'wp_ajax_fpw_pt_copy_right', array( &$this, 'fpw_pt_copy_right_ajax' ) );
		add_action( 'wp_ajax_fpw_pt_copy_left', array( &$this, 'fpw_pt_copy_left_ajax' ) );

		$anyButtonPressed = ( isset( $_POST['submit-update'] ) ) ? true : false; 
	}

	//	build FPW Post Thumbnails options
	function fptBuildOptions() {
		$opt = array(
			'nothepostthumbnail' 	=> false,	//	since 1.6.4 
			'content' 	=> array(
				'enabled'			=> false,
				'width'				=> 64,
				'height'			=> 64,
				'position'			=> 'left',
				'border'			=> false,
				'border_width'		=> 1,
				'border_radius'		=> 0,
				'border_color'		=> '#000000',
				'background_color'	=> '#FFFFFF',
				'shadow'			=> false,
				'sh_hor_length'		=> 0,
				'sh_ver_length'		=> 0,
				'sh_blur_radius'	=> 0,
				'sh_color'			=> '#000000',
				'sh_opacity'		=> 0,   
				'padding_top'		=> 0,
				'padding_left'		=> 0,
				'padding_bottom'	=> 0,
				'padding_right'		=> 0,
				'margin_top'		=> 0,
				'margin_left'		=> 0,
				'margin_bottom'		=> 0,
				'margin_right'		=> 0 
			),
			'excerpt' => array(
				'enabled'			=> false,
				'width'				=> 64,
				'height'			=> 64,
				'position'			=> 'left',
				'border'			=> false,
				'border_width'		=> 1,
				'border_radius'		=> 0,
				'border_color'		=> '#000000',
				'background_color'	=> '#FFFFFF',
				'shadow'			=> false,
				'sh_hor_length'		=> 0,
				'sh_ver_length'		=> 0,
				'sh_blur_radius'	=> 0,
				'sh_color'			=> '#000000',
				'sh_opacity'		=> 0,     
				'padding_top'		=> 0,
				'padding_left'		=> 0,
				'padding_bottom'	=> 0,
				'padding_right'		=> 0,
				'margin_top'		=> 0,
				'margin_left'		=> 0,
				'margin_bottom'		=> 0,
				'margin_right'		=> 0 
			) 
		);
		update_option( 'fpw_post_thumbnails_options', $opt );
		return $opt;
	}
	
	//	initialize
	function init() {
		load_plugin_textdomain( 'fpw-category-thumbnails', false, 'fpw-category-thumbnails/languages/' );
	}
	
	//	register admin menu
	function adminMenu() {
		$page_title = __( 'FPW Post Thumbnails', 'fpw-category-thumbnails' );
		$menu_title = __( 'FPW Post Thumbnails', 'fpw-category-thumbnails' );
		$this->fptPage = add_submenu_page( null, $page_title, $menu_title, 'manage_options', 
							'fpw-post-thumbnails', array( &$this, 'fptSettings' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueueScripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueuePointerScripts' ) );
		add_action( 'load-' . $this->fptPage, array( &$this, 'fptHelp' ) );
	}
	
	//	register styles, scripts, and localize javascript
	function enqueueScripts( $hook ) {
		if ( $this->fptPage == $hook ) 
			require_once $this->fptPath . '/scripts/fptenqueuescripts.php';
	}
	
	//	enqueue pointer scripts
	function enqueuePointerScripts( $hook ) {
		if ( $this->fptPage == $hook )
			require_once $this->fptPath . '/scripts/fptenqueuepointerscripts.php';
	}
	
	// 	handle pointer
	public function custom_print_footer_scripts() {
		$pointer = 'fpwfpt' . str_replace( '.', '', $this->fptVersion );
    	$pointerContent  = '<h3>' . esc_js( __( "What's new in this version?", 'fpw-category-thumbnails' ) ) . '</h3>';
		$pointerContent .= '<li style="margin-left:25px;margin-top:20px;margin-right:25px;list-style:square">' . 
						   esc_js( __( "Removed some options", 'fpw-category-thumbnails' ) ) . '</li>';
		$pointerContent .= '<li style="margin-left:25px;margin-top:20px;margin-right:25px;list-style:square">' . 
						   esc_js( __( "Modified help to reflect recent changes", 'fpw-category-thumbnails' ) ) . '</li>';
    	?>
    	<script type="text/javascript">
    	// <![CDATA[
    		jQuery(document).ready( function($) {
        		$('#fpt-settings-title').pointer({
        			content: '<?php echo $pointerContent; ?>',
        			position: 'top',
            		close: function() {
						jQuery.post( ajaxurl, {
							pointer: '<?php echo $pointer; ?>',
							action: 'dismiss-wp-pointer'
						});
            		}
				}).pointer('open');
			});
    	// ]]>
    	</script>
    	<?php
	}
	
	//	contextual help
	function fptHelp() {
		require_once $this->fptPath . '/help/fpthelp.php';
	}
	
	// AJAX wrapper to perform options update
	function fpw_pt_update_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fptPath . '/ajax/fptupdate.php';
	}
	
	// AJAX wrapper to perform options update
	function fpw_pt_copy_right_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fptPath . '/ajax/fptcopyright.php';
	}
	
	// AJAX wrapper to perform options update
	function fpw_pt_copy_left_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fptPath . '/ajax/fptcopyleft.php';
	}
	
	private function fptValidateInput( $p ) {
		$plusMinusToCheck = array(
			'sh_hor_length',
			'sh_ver_length'
		);
		
		$upperLimitCheck = array(
			'sh_blur_radius'
		);
		
		$minusOneToOneCheck = array(
			'sh_opacity'
		);
		
		$valuesToCheck = array( 
			'width',
			'height',
			'border_radius',
			'border_width',
			'padding_top',
			'padding_left',
			'padding_bottom',
			'padding_right',
			'margin_top',
			'margin_left',
			'margin_bottom',
			'margin_right'
		);
	
		$checkboxes = array(
			'enabled',
			'border',
			'shadow'
		);
	
		$colorsToCheck = array(
			'border_color',
			'background_color',
			'sh_color'
		);
	
		//$this->fptOptions[ 'clean' ] = ( isset( $p[ 'clean' ] ) ) ? true : false;
		//$this->fptOptions[ 'abar' ] = ( isset( $p[ 'abar' ] ) ) ? true : false;
		$this->fptOptions[ 'nothepostthumbnail' ] = ( isset( $p[ 'nothepostthumbnail' ] ) ) ? true : false;
			
		foreach ( $checkboxes as $ck ) {
			$this->fptOptions[ 'content' ][ $ck ] = 
				( isset( $p[ 'content_' . $ck ] ) ) ? true : false;
			$this->fptOptions[ 'excerpt' ][ $ck ] = 
				( isset( $p[ 'excerpt_' . $ck ] ) ) ? true : false;
		}
		
		$this->fptOptions[ 'content' ][ 'position' ] = $p[ 'content_position' ];
		$this->fptOptions[ 'excerpt' ][ 'position' ] = $p[ 'excerpt_position' ];

		foreach ( $plusMinusToCheck as $val ) {
			$this->fptOptions[ 'content' ][ $val ] = $p[ 'content_' . $val ];
			$this->fptOptions[ 'excerpt' ][ $val ] = $p[ 'excerpt_' . $val ]; 
		}

		foreach ( $upperLimitCheck as $val ) {
			$this->fptOptions[ 'content' ][ $val ] = $p[ 'content_' . $val ];
			$this->fptOptions[ 'excerpt' ][ $val ] = $p[ 'excerpt_' . $val ]; 
		}
		
		foreach ( $minusOneToOneCheck as $val ) {
			$this->fptOptions[ 'content' ][ $val ] = $p[ 'content_' . $val ];
			$this->fptOptions[ 'excerpt' ][ $val ] = $p[ 'excerpt_' . $val ]; 
		}
		
		foreach ( $valuesToCheck as $val ) {
			$this->fptOptions[ 'content' ][ $val ] = $p[ 'content_' . $val ];
			$this->fptOptions[ 'excerpt' ][ $val ] = $p[ 'excerpt_' . $val ]; 
		}

		foreach( $colorsToCheck as $col ) {
			$this->fptOptions[ 'content' ][ $col ] = $p[ 'content_' . $col ];
			$this->fptOptions[ 'excerpt' ][ $col ] = $p[ 'excerpt_' . $col ];
		}

		$response = '';
		$valid = true;

		foreach ( $plusMinusToCheck as $val ) {
			$this->fptOptions[ 'content' ][ $val ] = $p[ 'content_' . $val ];
			$this->fptOptions[ 'excerpt' ][ $val ] = $p[ 'excerpt_' . $val ]; 
		}

		foreach ( $upperLimitCheck as $val ) {
			$this->fptOptions[ 'content' ][ $val ] = $p[ 'content_' . $val ];
			$this->fptOptions[ 'excerpt' ][ $val ] = $p[ 'excerpt_' . $val ]; 
		}
		
		foreach ( $minusOneToOneCheck as $val ) {
			$this->fptOptions[ 'content' ][ $val ] = $p[ 'content_' . $val ];
			$this->fptOptions[ 'excerpt' ][ $val ] = $p[ 'excerpt_' . $val ]; 
		}

		foreach ( $valuesToCheck as $val ) {
			if ( $val == 'sh_opacity' ) {
				if ( !( (float)$p[ 'content_' .$val ] <= 1 ) && ( (float)$p[ 'content_' .$val ] >= 0 ) ) {
					$response = __( 'In Content panel field', 'fpw-category-thumbnails' ) . ' "' .
								str_replace( '_', '-', $val ) . '" ' . 
								__( 'is not a number between 0 and 1.', 'fpw-category-thumbnails' );
					$valid = false;
					break;
				}
				if ( !( (float)$p[ 'excerpt_' .$val ] <= 1 ) && ( (float)$p[ 'excerpt_' .$val ] >= 0 ) ) {
					$response = __( 'In Excerpt panel field', 'fpw-category-thumbnails' ) . ' "' .
								str_replace( '_', '-', $val ) . '" ' . 
								__( 'is not a number between 0 and 1.', 'fpw-category-thumbnails' );
					$valid = false;
					break;
				}
			} else {
				if ( !ctype_digit( (string) $p[ 'content_' . $val ] ) ) { 
					$response = __( 'In Content panel field', 'fpw-category-thumbnails' ) . ' "' .
								str_replace( '_', '-', $val ) . '" ' . 
								__( 'contains non-numeric characters.', 'fpw-category-thumbnails' );
					$valid = false;
					break;
				}
				if ( !ctype_digit( (string) $p[ 'excerpt_' . $val ] ) ) {
					$response = __( 'In Excerpt panel field', 'fpw-category-thumbnails' ) . ' "' .
								str_replace( '_', '-', $val ) . '" ' . 
								__( 'contains non-numeric characters.', 'fpw-category-thumbnails' );
					$valid = false;
					break;
				}
			}
		}
		
		if ( !$valid )  
			return $response;

		foreach ( $plusMinusToCheck as $val ) {
			$v = trim( (string)$p[ 'content_' . $val ], '-' ); 
			if ( !ctype_digit( (string)$v ) ) { 
				$response = __( 'In Content panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'contains non-numeric characters.', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
			if ( (int)$v > 75 ) {
				$response = __( 'In Content panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'must be a number from -75 to 75', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
			$v = trim( (string)$p[ 'excerpt_' . $val ], '-' ); 
			if ( !ctype_digit( (string)$v ) ) { 
				$response = __( 'In Excerpt panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'contains non-numeric characters.', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
			if ( (int)$v > 75 ) {
				$response = __( 'In Excerpt panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'must be a number from -75 to 75', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
		}

		if ( !$valid )  
			return $response;

		foreach ( $upperLimitCheck as $val ) {
			if ( !ctype_digit( (string) $p[ 'content_' . $val ] ) ) { 
				$response = __( 'In Content panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'contains non-numeric characters.', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
			if ( (int)$p[ 'content_' . $val ] > 30 ) {
				$response = __( 'In Content panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'must be a number from 0 to 30', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
			if ( !ctype_digit( (string) $p[ 'excerpt_' . $val ] ) ) {
				$response = __( 'In Excerpt panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'contains non-numeric characters.', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
			if ( (int)$p[ 'excerpt_' . $val ] > 30 ) {
				$response = __( 'In Excerpt panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'must be a number from 0 to 30', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
		}

		if ( !$valid )  
			return $response;

		foreach ( $minusOneToOneCheck as $val ) {
			if ( !( (float)$p[ 'content_' .$val ] <= 1 ) && ( (float)$p[ 'content_' .$val ] >= 0 ) ) {
				$response = __( 'In Content panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'is not a number between 0 and 1', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
			if ( !( (float)$p[ 'excerpt_' .$val ] <= 1 ) && ( (float)$p[ 'excerpt_' .$val ] >= 0 ) ) {
				$response = __( 'In Excerpt panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'is not a number between 0 and 1', 'fpw-category-thumbnails' );
				$valid = false;
				break;
			}
		}

		if ( !$valid )  
			return $response;

		foreach( $colorsToCheck as $col ) {
			if ( !( 7 == strlen( $p[ 'content_' . $col ] ) ) || 
				 !( '#' == substr( $p[ 'content_' . $col ], 0, 1 ) ) ) {
				$response = __( 'In Content panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $col ) . '" ' . 
							__( "must start with '#' charcter followed by 6 hexadecimal digits.", "fpw-category-thumbnails" );
				$valid = false;
				break;
			}
			if ( !( 7 == strlen( $p[ 'excerpt_' . $col ] ) ) || 
				 !( '#' == substr( $p[ 'excerpt_' . $col ], 0, 1 ) ) ) {
				$response = __( 'In Excerpt panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $col ) . '" ' . 
							__( "must start with '#' charcter followed by 6 hexadecimal digits.", "fpw-category-thumbnails" );
				$valid = false;
				break;
			}
			$ac = substr( $p[ 'content_' . $col ], 1, strlen( $p[ 'content_' . $col ] ) - 1 );
			$ae = substr( $p[ 'excerpt_' . $col ], 1, strlen( $p[ 'excerpt_' . $col ] ) - 1 );
			if ( !ctype_xdigit( $ac ) ) {
				$response = __( 'In Content panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $col ) . '" ' . 
							__( "must start with '#' charcter followed by 6 hexadecimal digits.", "fpw-category-thumbnails" );
				$valid = false;
				break;
			}
			if ( !ctype_xdigit( $ae ) ) {
				$response = __( 'In Excerpt panel field', 'fpw-category-thumbnails' ) . ' "' .
							str_replace( '_', '-', $col ) . '" ' . 
							__( "must start with '#' charcter followed by 6 hexadecimal digits.", "fpw-category-thumbnails" );
				$valid = false;
				break;
			}
		}
		
		return $response;
	} 

	//	copy values between panels
	private function copyPanels( $where ) {
		$valuesToCopy = array(
			'enabled',
			'width',
			'height',
			'position',
			'border',
			'border_radius',
			'border_width',
			'border_color',
			'background_color',
			'shadow',
			'sh_hor_length',
			'sh_ver_length',
			'sh_blur_radius',
			'sh_color',
			'sh_opacity',
			'padding_top',
			'padding_left',
			'padding_bottom',
			'padding_right',
			'margin_top',
			'margin_left',
			'margin_bottom',
			'margin_right'
		);
		$from 	= ( 'right' == $where ) ? 'content' : 'excerpt';
		$to		= ( 'right' == $where ) ? 'excerpt' : 'content';
		foreach ( $valuesToCopy as $value )
			$this->fptOptions[ $to ][ $value ] = $this->fptOptions[ $from ][ $value ];
	}

	//	FPW Post Thumbnails - settings page
	function fptSettings() {
		//	check if form was submited
		if ( isset( $_POST[ 'submit-update' ] ) || 
			 isset( $_POST[ 'submit-copy-right' ] ) ||
			 isset( $_POST[ 'submit-copy-left' ] ) ) {
			if ( !isset( $_POST[ 'fpw-fpt-nonce' ] ) ) 
				die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red"><strong>' . 
					 __( 'You did not send any credentials!', 'fpw-category-thumbnails' ) . '</strong></p>' );
			if ( !wp_verify_nonce( $_POST[ 'fpw-fpt-nonce' ], 'fpw-fpt-nonce' ) ) 
				die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red;"><strong>' . 
					 __( 'You did not send the right credentials!', 'fpw-category-thumbnails' ) . '</strong></p>' );
			$resp = $this->fptValidateInput( $_POST );
		}

		$lt43 = version_compare( $this->wpVersion, '4.3', '<' );

		//	HTML starts here
		echo 	'<div class="wrap">';
		echo	'<h' . ( $lt43 ? '2' : '1' ) . ' id="fpt-settings-title">' . 
				__( 'FPW Post Thumbnails', 'fpw-category-thumbnails' ) . ' <a id="fct-link" class="' . ( $lt43 ? 'add-new-h2' : 'page-title-action' ) . '" href="' .
				get_admin_url() . 'themes.php?page=fpw-category-thumbnails">' . 
				__( 'FPW Category Thumbnails', 'fpw-category-thumbnails' ) . '</a></h' . ( $lt43 ? '2' : '1' ) . '>';

		//	the form starts here
		echo '<div>';
		echo '<form name="fpw_post_thmb_form" action="?page=fpw-post-thumbnails" method="post">';

		//	protect this form with nonce
		echo '<input name="fpw-fpt-nonce" type="hidden" value="' . wp_create_nonce( 'fpw-fpt-nonce' ) . '" />';

		//	options section
		echo '<div id="fpw-fpt-options" style="margin-top: 5px">';

		//	hide current theme's the_post_thumbnail() output checkbox
		echo '<input type="checkbox" class="fpt-option-group" id="box-nothepostthumbnail" name="nothepostthumbnail" value="nothepostthumbnail"';
		if ( $this->fptOptions[ 'nothepostthumbnail' ] ) 
			echo ' checked';
		echo '> ' . __( "Hide output of the current theme's", 'fpw-category-thumbnails' ) . ' the_post_thumbnail()<br />';

		//	end of options section
		echo 	'</div>';
		echo	'<div style="margin-top: 5px;">';
		echo	'<div style="display: none; position: relative">';

		//	notification division for AJAX
		echo 	'<div id="fpt-message" class="updated" style="position: absolute; ' . 
				'display: none; z-index: 10; margin-top: 24px"><p>&nbsp;</p></div>';
		echo	'</div>';

		//	Update button
		echo	'&nbsp;&nbsp;<input title="' . 
				__( 'write modified data to the database', 'fpw-category-thumbnails' ) .
				'" id="fpt-update" class="button-primary fpt-submit" ' . 
				'type="submit" name="submit-update" value=" ' . 
				__( 'Update', 'fpw-category-thumbnails' ) . ' " /> ';

        echo	'</div>';

		echo	'<div class="metabox-holder" style="width:49%; float:left; margin-right:10px;">';
        echo	'<div class="postbox">';
		echo	'<h3 style="cursor:default; background-color: #F1F1F1; background-image: -webkit-linear-gradient(top , #F9F9F9, #CCCCCC); background-image: -moz-linear-gradient(top , #F9F9F9, #CCCCCC); background-image: -ms-linear-gradient(top , #F9F9F9, #CCCCCC); background-image: -o-linear-gradient(top , #F9F9F9, #CCCCCC);">' . 
				__( 'Content thumbnails enabled:', 'fpw-category-thumbnails' ) . ' <input type="checkbox" class="fpt-option-group" ' .
				'id="box-content-enabled" name="content_enabled" value="content_enabled"';
		if ( $this->fptOptions[ 'content' ][ 'enabled' ] ) 
		echo	' checked';
		echo	'> <input type="submit" title="' . __( 'copy all values to the right panel', 'fpw-category-thumbnails' ) .
				'" id="fpt-copy-right" name="submit-copy-right" value="' . 
				__( 'Copy', 'fpw-category-thumbnails' ) . ' &raquo;' .
				'" class="button-secondary fpt-submit"> <input alt="#TB_inline?height=300&width=400&inlineId=fptContentPreviev" ' . 
				'title="' . __( 'Content - Preview', 'fpw-category-thumbnails' ) . '" class="thickbox button-secondary hide-if-no-js" ' .
				'type="button" value="' . __( 'Preview', 'fpw-category-thumbnails' ) . '" id="content-preview" />' .
				'</h3>';
		echo	'<div id="fptContentPreviev" class="thickbox" style="display: none;">';
		echo	'<div id="thumbnail-content">';
		echo 	'<img class="wp-post-image-content" src="' .
				$this->fptUrl . '/images/Frank.jpg" /></div><p style="text-align: justify">Lorem ipsum dolor sit amet consectetuer ' .
				'nunc enim laoreet pellentesque augue. Vestibulum Vivamus lacus dis ' . 
				'Nunc semper laoreet platea Pellentesque ultrices metus. Tincidunt ' . 
				'ridiculus nec Lorem orci metus hac Nam Lorem nascetur orci. Sed et ' . 
				'quis aliquet urna tortor ut neque nec elit nibh. At justo condimentum ' . 
				'sit Aenean ac vitae aliquam quis adipiscing dolor. Nibh leo nibh ' . 
				'aliquam laoreet elit convallis condimentum volutpat id consequat. Ut quis.</p>';
		echo	'</div>';			
		echo	'<div class="inside" style="padding:0px 6px 0px 6px;">';
		echo	'<table style="width:100%">';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'width' ] . '" name="content_width" id="content-width" class="content-width-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">width</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'height' ] . '" name="content_height" id="content-height" class="content-height-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">height</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle"><select name="content_position" id="content-position" style="width: 70px">' .
				'<option value="left"';
		if ( 'left' == $this->fptOptions[ 'content' ][ 'position' ] )
			echo ' selected="selected"';
		echo	'>left</option>' . 
				'<option value="right"';
		if ( 'right' == $this->fptOptions[ 'content' ][ 'position' ] ) 
			echo ' selected="selected"'; 
		echo 	'>right</option></select></td>'; 
		echo	'<td style="verical-align: middle">float</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="checkbox" class="fpt-option-group" id="box-content-border" name="content_border" value="content_border"';
		if ( $this->fptOptions[ 'content' ][ 'border' ] ) 
			echo	' checked';
		echo	'></td>';
		echo	'<td style="verical-align: middle">border</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'border_width' ] . '" name="content_border_width" id="content-border-width" class="content-border-width-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">border-width</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'border_radius' ] . '" name="content_border_radius" id="content-border-radius" class="content-border-radius-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">border-radius</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'border_color' ] . '" name="content_border_color" id="content-border-color" class="content-border-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-content-border-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">border-color</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'background_color' ] . '" name="content_background_color" id="content-background-color" class="content-background-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-content-background-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">background-color</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="checkbox" class="fpt-option-group" id="box-content-shadow" name="content_shadow" value="content_shadow"';
		if ( $this->fptOptions[ 'content' ][ 'shadow' ] ) 
			echo	' checked';
		echo	'></td>';
		echo	'<td style="verical-align: middle">shadow</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'sh_hor_length' ] . '" name="content_sh_hor_length" id="content-sh-hor-length" class="content-sh-hor-length-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">shadow-horizontal-length (-75...75)</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'sh_ver_length' ] . '" name="content_sh_ver_length" id="content-sh-ver-length" class="content-sh-ver-length-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">shadow-vertical-length (-75...75)</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'sh_blur_radius' ] . '" name="content_sh_blur_radius" id="content-sh-blur-radius" class="content-sh-blur-radius-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">shadow-blur-radius (0...30)</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'sh_color' ] . '" name="content_sh_color" id="content-sh-color" class="content-sh-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-content-sh-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">shadow-color</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'sh_opacity' ] . '" name="content_sh_opacity" id="content-sh-opacity" class="content-sh-opacity-value" />';
		echo	'</td>';
		echo	'<td style="verical-align: middle">shadow-opacity (0...1)</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'padding_top' ] . '" name="content_padding_top" id="content-padding-top" class="content-padding-top-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-top</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'padding_right' ] . '" name="content_padding_right" id="content-padding-right" class="content-padding-right-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-right</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'padding_bottom' ] . '" name="content_padding_bottom" id="content-padding-bottom" class="content-padding-bottom-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-bottom</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'padding_left' ] . '" name="content_padding_left" id="content-padding-left" class="content-padding-left-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-left</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'margin_top' ] . '" name="content_margin_top" id="content-margin-top" class="content-margin-top-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-top</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'margin_right' ] . '" name="content_margin_right" id="content-margin-right" class="content-margin-right-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-right</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'margin_bottom' ] . '" name="content_margin_bottom" id="content-margin-bottom" class="content-margin-bottom-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-bottom</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'margin_left' ] . '" name="content_margin_left" id="content-margin-left" class="content-margin-left-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-left</td>';
		echo	'</tr>';
		echo	'</table>';
		echo	'</div>';
		echo	'</div>';
    	echo	'</div>';
    	echo	'<div class="metabox-holder" style="width:49%; float:left;">';
        echo	'<div class="postbox">';
		echo	'<h3 style="cursor:default;background-color: #F1F1F1; background-image: -webkit-linear-gradient(top , #F9F9F9, #CCCCCC); background-image: -moz-linear-gradient(top , #F9F9F9, #CCCCCC); background-image: -ms-linear-gradient(top , #F9F9F9, #CCCCCC); background-image: -o-linear-gradient(top , #F9F9F9, #CCCCCC);">' . 
				__( 'Excerpt thumbnails enabled:', 'fpw-category-thumbnails' ) . ' <input type="checkbox" class="fpt-option-group" ' .
				'id="box-excerpt-enabled" name="excerpt_enabled" value="excerpt_enabled"';
		if ( $this->fptOptions[ 'excerpt' ][ 'enabled' ] ) 
		echo	' checked';
		echo	'> <input type="submit" title="' . __( 'copy all values to the left panel', 'fpw-category-thumbnails' ) .
				'" id="fpt-copy-left" name="submit-copy-left" value="&laquo; ' . 
				__( 'Copy', 'fpw-category-thumbnails' ) .
				'" class="button-secondary fpt-submit"> <input alt="#TB_inline?height=300&width=400&inlineId=fptExcerptPreviev" ' . 
				'title="' . __( 'Excerpt - Preview', 'fpw-category-thumbnails' ) . '" class="thickbox button-secondary hide-if-no-js" ' .
				'type="button" value="' . __( 'Preview', 'fpw-category-thumbnails' ) . '" id="excerpt-preview" /></h3>';
		echo	'<div id="fptExcerptPreviev" class="thickbox" style="display: none;">';
		echo	'<div id="thumbnail-excerpt"><img class="wp-post-image-excerpt" src="' .
				$this->fptUrl . '/images/Frank.jpg" /></div><p style="text-align: justify">Lorem ipsum dolor sit amet consectetuer ' .
				'nunc enim laoreet pellentesque augue. Vestibulum Vivamus lacus dis ' . 
				'Nunc semper laoreet platea Pellentesque ultrices metus. Tincidunt ' . 
				'ridiculus nec Lorem orci [...]</p>';
		echo	'</div>';			
		echo	'<div class="inside" style="padding:0px 6px 0px 6px;">';
		echo	'<table style="width:100%">';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'width' ] . '" name="excerpt_width" id="excerpt-width" class="excerpt-width-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">width</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'height' ] . '" name="excerpt_height" id="excerpt-height" class="excerpt-height-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">height</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle"><select name="excerpt_position" id="excerpt-position" style="width: 70px">' . 
				'<option value="left"';
		if ( 'left' == $this->fptOptions[ 'excerpt' ][ 'position' ] ) 
			echo ' selected="selected"'; 
		echo	'>left</option>' . 
				'<option value="right"';
		if ( 'right' == $this->fptOptions[ 'excerpt' ][ 'position' ] ) 
			echo ' selected="selected"'; 
		echo 	'>right</option></select></td>'; 
		echo	'<td style="verical-align: middle">float</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="checkbox" class="fpt-option-group" id="box-excerpt-border" name="excerpt_border" value="excerpt_border"';
		if ( $this->fptOptions[ 'excerpt' ][ 'border' ] ) 
			echo	' checked';
		echo	'></td>';
		echo	'<td style="verical-align: middle">border</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'border_width' ] . '" name="excerpt_border_width" id="excerpt-border-width" class="excerpt-border-width-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">border-width</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'border_radius' ] . '" name="excerpt_border_radius" id="excerpt-border-radius" class="excerpt-border-radius-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">border-radius</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'border_color' ] . '" name="excerpt_border_color" id="excerpt-border-color" class="excerpt-border-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-excerpt-border-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">border-color</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'background_color' ] . '" name="excerpt_background_color" id="excerpt-background-color" class="excerpt-background-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-excerpt-background-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">background-color</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="checkbox" class="fpt-option-group" id="box-excerpt-shadow" name="excerpt_shadow" value="excerpt_shadow"';
		if ( $this->fptOptions[ 'excerpt' ][ 'shadow' ] ) 
			echo	' checked';
		echo	'></td>';
		echo	'<td style="verical-align: middle">shadow</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'sh_hor_length' ] . '" name="excerpt_sh_hor_length" id="excerpt-sh-hor-length" class="excerpt-sh-hor-length-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">shadow-horizontal-length (-75...75)</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'sh_ver_length' ] . '" name="excerpt_sh_ver_length" id="excerpt-sh-ver-length" class="excerpt-sh-ver-length-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">shadow-vertical-length (-75...75)</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'sh_blur_radius' ] . '" name="excerpt_sh_blur_radius" id="excerpt-sh-blur-radius" class="excerpt-sh-blur-radius-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">shadow-blur-radius (0...30)</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'sh_color' ] . '" name="excerpt_sh_color" id="excerpt-sh-color" class="excerpt-sh-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-excerpt-sh-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">shadow-color</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'sh_opacity' ] . '" name="excerpt_sh_opacity" id="excerpt-sh-opacity" class="excerpt-sh-opacity-value" />';
		echo	'</td>';
		echo	'<td style="verical-align: middle">shadow-opacity(0...1)</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'padding_top' ] . '" name="excerpt_padding_top" id="excerpt-padding-top" class="excerpt-padding-top-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-top</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'padding_right' ] . '" name="excerpt_padding_right" id="excerpt-padding-right" class="excerpt-padding-right-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-right</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'padding_bottom' ] . '" name="excerpt_padding_bottom" id="excerpt-padding-bottom" class="excerpt-padding-bottom-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-bottom</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'padding_left' ] . '" name="excerpt_padding_left" id="excerpt-padding-left" class="excerpt-padding-left-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-left</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'margin_top' ] . '" name="excerpt_margin_top" id="excerpt-margin-top" class="excerpt-margin-top-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-top</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'margin_right' ] . '" name="excerpt_margin_right" id="excerpt-margin-right" class="excerpt-margin-right-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-right</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'margin_bottom' ] . '" name="excerpt_margin_bottom" id="excerpt-margin-bottom" class="excerpt-margin-bottom-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-bottom</td>';
		echo	'</tr>';
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'margin_left' ] . '" name="excerpt_margin_left" id="excerpt-margin-left" class="excerpt-margin-left-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-left</td>';
		echo	'</tr>';
		echo	'</table>';
		echo	'</div>';
        echo	'</div>';
    	echo	'</div>';

		//	end of form
		echo	'</form>';
		echo 	'</div>';
		echo	'<div style="clear:both;"></div>';
	}
}
