<?php
//	prevent direct accesss
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
	die( "Direct access to this script is forbidden!" );

//	plugin's main class
class fpwCategoryThumbnails {
	var	$fctOptions, $fptOptions;
	var $fctMapUpdateOk;
	var	$fctPath;
	var	$fctUrl;
	var	$fctVersion;
	var	$fctPage, $fptPage;
	var	$fctLocale;
	var	$translationURL;
	var	$translationPath;
	var $translationStatus;
	var $translationResponse;
	var	$wpVersion;
	var $mapArray;
	var	$categoryListTable;
	
	//	constructor
	function __construct( $path, $version ) {
		global $wp_version;

		//	set plugin's path
		$this->fctPath = $path;
		
		//	set plugin's url
		$this->fctUrl = WP_PLUGIN_URL . '/fpw-category-thumbnails';
		
		//	set version
		$this->fctVersion = $version;
		
		//	set WP version
		$this->wpVersion = $wp_version;
		
		$this->fctLocale = get_locale();

		//	set translation URL
		$this->translationURL = 'http://svn.wp-plugins.org/fpw-category-thumbnails/translations/' . 
								$this->fctVersion . '/fpw-category-thumbnails-' . $this->fctLocale . '.mo';
								
		//	set translation path
		$this->translationPath = $this->fctPath . '/languages/fpw-category-thumbnails-' . $this->fctLocale . '.mo';
		
		//	actions and filters
		add_action( 'init', array( &$this, 'init' ) );

		register_activation_hook( $this->fctPath . '/fpw-category-thumbnails.php', array( &$this, 'uninstallMaintenance' ) );
		
		//	actions below are not used in front end
		add_action( 'admin_menu', array( &$this, 'adminMenu' ) );
		
		//	AJAX group of actions
		add_action( 'wp_ajax_fpw_fs_get_file', array( &$this, 'fpw_fs_get_file_ajax' ) );
		add_action( 'wp_ajax_fpw_ct_update' , array( &$this, 'fpw_ct_update_ajax' ) );
		add_action( 'wp_ajax_fpw_ct_apply', array( &$this, 'fpw_ct_apply_ajax' ) );
		add_action( 'wp_ajax_fpw_ct_remove', array( &$this, 'fpw_ct_remove_ajax' ) );
		add_action( 'wp_ajax_fpw_ct_language', array( &$this, 'fpw_ct_language_ajax' ) );

		add_action( 'save_post', array( &$this, 'addThumbnailToPost' ), 10, 2 );
		add_action( 'after_plugin_row_fpw-category-thumbnails/fpw-category-thumbnails.php', array( &$this, 'afterPluginMeta' ), 10, 2 );

		add_filter( 'plugin_action_links_fpw-category-thumbnails/fpw-category-thumbnails.php', array( &$this, 'pluginLinks' ), 10, 2);
		add_filter( 'plugin_row_meta', array( &$this, 'pluginMetaLinks'), 10, 2 );
		
		add_filter('manage_edit-category_columns', array( &$this, 'fpw_category_columns_head' ) );
		add_filter('manage_category_custom_column', array( &$this, 'fpw_custom_category_column_content' ), 10, 3 );
		
		//	read plugin's options
		$this->fctOptions = $this->getOptions();

		$anyButtonPressed =
			(	isset( $_POST['submit-getid'] ) || isset( $_POST['submit-author'] ) || 
				isset( $_POST['submit-clear'] ) || isset( $_POST['submit-refresh'] ) || 
				isset( $_POST['submit-update'] ) || isset( $_POST['submit-apply'] ) || 
				isset( $_POST['submit-remove'] ) || isset( $_POST['submit-language'] ) ) ? true : false; 

		if ( $anyButtonPressed ) 
			$this->fctOptions[ 'abar' ] = ( isset( $_POST[ 'abar' ] ) ) ? true : false;
		if ( $this->fctOptions[ 'abar' ] ) 
			add_action( 'admin_bar_menu', array( &$this, 'pluginToAdminBar' ), 1010 );
	}
	
	//	set heading for custom column 'Thumbnail' - Categories admin screen
	function fpw_category_columns_head( $defaults ) {
		$defaults[ 'thumbnail_column' ]  = __( 'Thumbnail', 'fpw-category-thumbnails' );
		return $defaults;
	}
	
	//	show value of custom column 'Thumbnail' - Categories admin screen
	function fpw_custom_category_column_content( $data, $column, $id ) {
		if ( $column == 'thumbnail_column' ) {
			$map = get_option( 'fpw_category_thumb_map' );
			$thumbnail_id = '0';
			if ( array_key_exists( $id, $map ) ) {
    	    	$value = $map[ $id ];
				$preview_size = 'thumbnail';
				if ( 'ngg-' == substr( $value, 0, 4 ) ) {
					if ( class_exists( 'nggdb' ) ) {
						$thumbnail_id = substr( $value, 4 );
						$picture = nggdb::find_image($thumbnail_id);
						if ( !$picture ) {
							return 	'<span style="font-size: large; color: red">' .
									__( 'NextGen Gallery: picture not found!', 'fpw-category-thumbnails' ) . '</span>';
						} else {
							return '<img src="' . $picture->thumbURL . '" />';
						}
					} else {
						return 	'<span style="font-size: large; color: red">' .
								__( 'NextGen Gallery: not active!', 'fpw-category-thumbnails' ) . '</span>';
					}
				} else {
					if ( 'Author' === $value ) {
						return '[ ' . __( 'Picture', 'fpw-category-thumbnails' ) . ' ]';
					} else {
						if ( wp_attachment_is_image( $value ) ) {
							return wp_get_attachment_image( $value, $preview_size );
						} else {
							return 	'<span style="font-size: large; color: red">' .
								__( 'Media Library: picture not found!', 'fpw-category-thumbnails' ) . '</span>';
						}
					}
				}
    	    } else {
				return '';
			}
    	}
    	return $data;
	}
	
	//	check translation file availability
	function translationAvailable() {
		
    	//	if language file exist, do not load it again
		if ( is_readable( $this->translationPath ) ) 
			return 'installed';

		$this->translationResponse = wp_remote_get( $this->translationURL, array( 'timeout' => 300 ) );

		//	if no translation file exists exit the check
		if ( is_wp_error( $this->translationResponse ) || $this->translationResponse[ 'response' ][ 'code' ] != '200' )
			return 'not_exist';
		
		return 'available';		
	}
    
	//	register plugin's textdomain
	function init() {
		load_plugin_textdomain( 'fpw-category-thumbnails', false, 'fpw-category-thumbnails/languages/' );

		if ( !( 'en_US' == $this->fctLocale ) ) 
			$this->translationStatus = $this->translationAvailable();
	} 

	//	register admin menu
	function adminMenu() {
		$page_title = __( 'FPW Category Thumbnails', 'fpw-category-thumbnails' );
		$menu_title = __( 'FPW Category Thumbnails', 'fpw-category-thumbnails' );
		$this->fctPage = add_theme_page( $page_title, $menu_title, 'manage_options', 
							'fpw-category-thumbnails', array( &$this, 'fctSettings' ) );
		
		add_action( 'load-' . $this->fctPage, array( &$this, 'help' ) );
		add_action( 'load-' . $this->fctPage, array( &$this, 'addScreenOptions' ) );

		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueueScripts' ) );

		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueuePointerScripts' ) );
	}

	//	register styles, scripts, and localize javascript
	function enqueueScripts( $hook ) {
		if ( ( $this->fctPage == $hook ) || ( 'media-upload-popup' == $hook ) ) {
			require_once $this->fctPath . '/scripts/enqueuescripts.php';
		}
	}
	
	//	enqueue pointer scripts
	function enqueuePointerScripts( $hook ) {
		if ( $this->fctPage == $hook )
			require_once $this->fctPath . '/scripts/enqueuepointerscripts.php';
	}

	// 	AJAX handler for pointer
	function custom_print_footer_scripts() {
		$pointer = 'fpwfct' . str_replace( '.', '', $this->fctVersion );
    	$pointerContent  = '<h3>' . esc_js( __( "What's new in this version?", 'fpw-category-thumbnails' ) ) . '</h3>';
		$pointerContent .= '<li style="margin-left:25px;margin-top:20px;margin-right:25px;list-style:square">' . 
						   esc_js( __( 'fixed version check not working when major version changes', 'fpw-category-thumbnails' ) ) . '</li>';
    	?>
    	<script type="text/javascript">
    	// <![CDATA[
    		jQuery(document).ready( function($) {
        		$('#fct-settings-title').pointer({
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
	
	//	add screen options
	function addScreenOptions() {
		$option = 'per_page';
		
		$categories_per_page = get_user_option( 'edit_category_per_page', get_current_user_id() );
		if ( !$categories_per_page )
			$categories_per_page = 10;
 		
		$args = array(
			'label'		=> _n( 'Category', 'Categories', $categories_per_page, 'fpw-category-thumbnails' ),
			'default'	=> 10,
			'option'	=> 'edit_category_per_page',
		);
 
		add_screen_option( $option, $args );
		add_filter( 'load-' . $this->fctPage, array( &$this, 'setScreenOption'), 10, 3 );
	}

	//	redisplay table after change of categories per page
	function setScreenOption( $status, $option, $value ) {
		if ( 'edit_category_per_page' == $option ) {
			unset( $this->categoryListTable );
			$this->categoryListTable = new fpw_Category_Thumbnails_Table( $this->mapArray ); 
			$this->categoryListTable->prepare_items();
			$this->categoryListTable->display();
			return $value;
		}
	}

	//	contextual help
	function help() {
		require_once $this->fctPath . '/help/help.php';
	}
	
	// AJAX wrapper to get image HTML
	function fpw_fs_get_file_ajax() {
		if ( defined("DOING_AJAX") && DOING_AJAX ) 
			require_once $this->fctPath . '/ajax/getimageid.php';
	}
	
	// AJAX wrapper to perform options update
	function fpw_ct_update_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fctPath . '/ajax/update.php';
	}

	// AJAX wrapper to perform apply mapping tasks
	function fpw_ct_apply_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fctPath . '/ajax/apply.php';
	}

	// AJAX wrapper to perform remove thumbnails
	function fpw_ct_remove_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fctPath . '/ajax/remove.php';
	}

	// AJAX wrapper to perform translation file loading
	function fpw_ct_language_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fctPath . '/ajax/language.php';
	}

	//	add update information after plugin meta
	function afterPluginMeta( $file, $plugin_data ) {
		$current = get_site_transient( 'update_plugins' );
		if ( !isset( $current -> response[ $file ] ) ) 
			return false;
		$url = "http://fw2s.com/fpwcatthumbsupdate.txt";
		$update = wp_remote_fopen( $url );
		echo '<tr class="plugin-update-tr"><td></td><td></td><td class="plugin-update"><div class="update-message">' . 
			'<img class="alignleft" src="' . $this->fctUrl . '/images/Thumbs_Up.png" width="64">' . $update . '</div></td></tr>';
	}

	//	add link to Donation to plugins meta
	function pluginMetaLinks( $links, $file ) {
		if ( 'fpw-category-thumbnails/fpw-category-thumbnails.php' == $file ) 
			$links[] = '<a href="http://fw2s.com/payments-and-donations/" target="_blank">' . __( "Donate", "fpw-category-thumbnails" ) . '</a>';
		return $links;
	}
	
	//	add link to settings page in plugins list
	function pluginLinks( $links, $file ) {
   		$settings_link = '<a href="' . site_url( '/wp-admin/' ) . 'themes.php?page=fpw-category-thumbnails">' . __( 'Settings', 'fpw-category-thumbnails' ) . '</a>';
		array_unshift( $links, $settings_link );
    	return $links;
	}
	
	//	uninstall file maintenance
	function uninstallMaintenance() {
		if ( class_exists( 'fpwPostThumbnails' ) ) {
			global $fpw_PT;
			if ( $this->fctOptions[ 'clean' ] || $fpw_PT->fptOptions[ 'clean' ] ) {
				if ( file_exists( $this->fctPath . '/uninstall.txt' ) ) 
					rename( $this->fctPath . '/uninstall.txt', $this->fctPath . '/uninstall.php' );
			} else {
				if ( file_exists( $this->fctPath . '/uninstall.php' ) ) 
					rename( $this->fctPath . '/uninstall.php', $this->fctPath . '/uninstall.txt' );
			}
		} else {
			if ( $this->fctOptions[ 'clean' ] ) {
				if ( file_exists( $this->fctPath . '/uninstall.txt' ) ) 
					rename( $this->fctPath . '/uninstall.txt', $this->fctPath . '/uninstall.php' );
			} else {
				if ( file_exists( $this->fctPath . '/uninstall.php' ) ) 
					rename( $this->fctPath . '/uninstall.php', $this->fctPath . '/uninstall.txt' );
			}
		}
	}	

	//	add plugin to admin bar	
	function pluginToAdminBar() {
		if ( current_user_can( 'manage_options' ) ) {
			global 	$wp_admin_bar;
			
			$main = array(
				'id' => 'fpw_plugins',
				'title' => __( 'FPW Plugins', 'fpw-category-thumbnails' ),
				'href' => '#' );

			$subm = array(
				'id' => 'fpw_bar_category_thumbnails',
				'parent' => 'fpw_plugins',
				'title' => __( 'FPW Category Thumbnails', 'fpw-category-thumbnails' ),
				'href' => get_admin_url() . 'themes.php?page=fpw-category-thumbnails' );

			$addmain = ( is_array( $wp_admin_bar->get_node( 'fpw_plugins' ) ) ) ? false : true;

			if ( $addmain )
				$wp_admin_bar->add_menu( $main );
			$wp_admin_bar->add_menu( $subm );
		}
	}
	
	//	plugin's Settings page
	function fctSettings() {

		//	get all categories
		$categories		= $this->getAllCategories();
		
		//	get assignments array
		$assignments	= $this->getAssignmentsArray( $categories );

		//	initialize update flags
		$update_options_ok = FALSE;
		
		//	check nonce if any of buttons was pressed
		$anyButtonPressed =
			(	isset( $_POST['submit-getid'] ) || isset( $_POST['submit-author'] ) || 
				isset( $_POST['submit-clear'] ) || isset( $_POST['submit-refresh'] ) || 
				isset( $_POST['submit-update'] ) || isset( $_POST['submit-apply'] ) || 
				isset( $_POST['submit-remove'] ) || isset( $_POST['submit-language'] ) ) ? true : false;
				
		if ( $anyButtonPressed ) {
			if ( !isset( $_POST[ 'fpw-fct-nonce' ] ) ) 
				die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red"><strong>' . 
					 __( 'You did not send any credentials!', 'fpw-category-thumbnails' ) . '</strong></p>' );
			if ( !wp_verify_nonce( $_POST[ 'fpw-fct-nonce' ], 'fpw-fct-nonce' ) ) 
				die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red;"><strong>' . 
					 __( 'You did not send the right credentials!', 'fpw-category-thumbnails' ) . '</strong></p>' );

			//	check ok - update options
			$this->fctOptions[ 'clean' ] = ( isset( $_POST[ 'cleanup' ] ) ) ? true : false;
			$this->fctOptions[ 'donotover' ] = ( isset( $_POST[ 'donotover' ] ) ) ? true : false;
			$this->fctOptions[ 'abar' ] = ( isset( $_POST[ 'abar' ] ) ) ? true : false;
			$this->fctOptions[ 'fpt'] = ( isset( $_POST[ 'fpt' ] ) ) ? true : false;
		
			$update_options_ok = ( update_option( 'fpw_category_thumb_opt', $this->fctOptions ) );
		
			// 	if any changes to options then check uninstall file's extension
			if ( $update_options_ok ) 
				$this->uninstallMaintenance();

			//	check if translation button was pressed
			if ( isset( $_POST['submit-language'] ) ) {
				if ( 'available' == $translationStatus ) {
					$handle = @fopen( $this->translationPath, 'wb');
					fwrite( $handle, $this->response[ 'body' ] );
					fclose($handle);
				}
			}

			//	check if remove button was pressed
			if ( isset( $_POST['submit-remove'] ) ) {
				reset( $assignments );
		
				while ( strlen( key( $assignments ) ) ) {
					$catid = key( $assignments );
					$parg = array(
						'numberofposts' => -1,
						'nopaging' => true,
						'category' => $catid,
						'post_type' => 'any' );
					$posts = get_posts( $parg );
					foreach ( $posts as $post ) {
						$post_id = $post -> ID;
						//	make sure this is not a revision
						if ( 'revision' != $post -> post_type )
							delete_post_meta( $post_id, '_thumbnail_id' );
					}
					next( $assignments );
				}
			}

			//	check if apply button was pressed
			if ( isset( $_POST['submit-apply'] ) ) {
				$map = get_option( 'fpw_category_thumb_map' );
				if ( $map )
					while ( strlen( key( $map ) ) ) {
						$catid = key($map);
						$parg = array(
							'numberofposts' => -1,
							'nopaging' => true,
							'category' => $catid,
							'post_type' => 'any' );
						$posts = get_posts( $parg );
						foreach ( $posts as $post ) {
							$post_id = $post->ID;
							//	make sure this is not a revision nor draft
							if ( ( 'revision' != $post->post_type ) && ( 'draft' != $post->post_status ) )
								$this->addThumbnailToPost( $post_id, $post );
						}
						next($map);
					}
			}
		}

		/*	------------------------------
		Settings page HTML starts here
		--------------------------- */

		echo '<div class="wrap">';
		
		$displayAttr = ( $this->fctOptions[ 'fpt' ] ) ? '' : ' display: none';
		
		echo '<div id="icon-themes" class="icon32"></div><h2 id="fct-settings-title">' . __( 'FPW Category Thumbnails', 'fpw-category-thumbnails' ) .
			 ' <span id="fpt-link" style="font-size: small;' . $displayAttr . '">- <a href="' . get_admin_url() . 
			 'themes.php?page=fpw-post-thumbnails">' . 
			 __( 'FPW Post Thumbnails', 'fpw-category-thumbnails' ) . '</a></span></h2>';
			 
		//	check if any of submit buttons was pressed
		if ( $anyButtonPressed ) {
			$assignments = $this->updateMapping( $assignments );
			if (	isset( $_POST['submit-getid'] ) || isset( $_POST['submit-author'] ) || 
				 	isset( $_POST['submit-clear'] ) || isset( $_POST['submit-refresh'] ) || 
					isset( $_POST['submit-update'] ) ) {
				if ( $this->fctMapUpdateOk || $update_options_ok ) { 
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Changed data saved successfully.', 'fpw-category-thumbnails' ) . '</strong></p></div>';
				} else {
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'No changes detected. Nothing to update.', 'fpw-category-thumbnails' ) . '</strong></p></div>';
				}
			} elseif ( isset( $_POST['submit-apply'] ) ) {
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'Applied thumbnails to existing posts / pages successfully.', 'fpw-category-thumbnails' ) . '</strong></p></div>';
			} elseif ( isset( $_POST['submit-remove'] ) ) {
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'All thumbnails removed successfully.', 'fpw-category-thumbnails' ) . '</strong></p></div>';
			} elseif ( isset( $_POST['submit-language'] ) ) {
				if ( 'available' == $this->translationStatus )  
					$handle = @fopen( $this->translationPath, 'wb' );
					fwrite( $handle, $this->translationResponse[ 'body' ] );
					fclose($handle);
					echo '<div id="message" class="updated"><p><strong>' . __( 'Language file downloaded. Click', 'fpw-category-thumbnails' ) .
						 ' ' . '<a href="/wp-admin/options-general.php?page=fpw-category-thumbnails">' .
						 __( 'here', 'fpw-category-thumbnails' ) . '</a> ' . __( 'to reload page.', 'fpw-category-thumbnails' ) . '</strong></p></div>';
				if ( 'installed' == $this->translationStatus ) 
						echo '<div id="message" class="updated fade"><p><strong>' . __( 'Language file already exists.', 'fpw-category-thumbnails' ) .
							 '</strong></p></div>';
				if ( 'not_exist' == $this->translationStatus ) 
						echo '<div id="message" class="updated fade"><p><strong>' . __( 'Language file is not available.', 'fpw-category-thumbnails' ) .
							 '</strong></p></div>';
			}
		}
		
		//	the form starts here
		echo '<div>';
		echo '<form name="fpw_cat_thmb_form" action="?page=fpw-category-thumbnails" method="post">';
		
		//	protect this form with nonce
		echo '<input name="fpw-fct-nonce" type="hidden" value="' . wp_create_nonce( 'fpw-fct-nonce' ) . '" />';

		//	options section
		echo '<div id="fpw-fct-options" style="position: relative; margin-top: 5px;">';
		
		//	do not overwrite checkbox
		echo '<input type="checkbox" class="option-group" id="box-donotover" name="donotover" value="donotover"';
		if ( $this->fctOptions[ 'donotover' ] ) 
			echo ' checked';
		echo '> ' . __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-category-thumbnails' ) . '<br />';

		//	cleanup checkbox
		echo '<input type="checkbox" class="option-group" id="box-cleanup" name="cleanup" value="cleanup"';
		if ( $this->fctOptions[ 'clean' ] ) 
			echo ' checked';
		echo '> ' . __( "Remove plugin's data from database on uninstall", 'fpw-category-thumbnails' ) . '<br />';

		//	add plugin to admin bar checkbox
		echo '<input type="checkbox" class="option-group" id="box-abar" name="abar" value="abar"';
		if ( $this->fctOptions[ 'abar' ] ) 
			echo ' checked';
		echo '> ' . __( 'Add this plugin to the Admin Bar', 'fpw-category-thumbnails' ) . '<br />';

		//	add plugin to admin bar checkbox
		echo '<input type="checkbox" class="option-group" id="box-fpt" name="fpt" value="fpt"';
		if ( $this->fctOptions[ 'fpt' ] ) 
			echo ' checked';
		echo '> ' . __( 'Enable FPW Post Thumbnails', 'fpw-category-thumbnails' ) . '<br />';

		//	end of options section
		echo '</div>';
		
		//	notification division for AJAX
		echo 	'<div id="message" class="updated" style="position: absolute; ' . 
				'display: none; z-index: 10;margin-top: 73px;"><p>&nbsp;</p></div>';
				
		require_once $this->fctPath . '/code/table.php';

		//	end of form
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
	
	//	update mapping array
	function updateMapping( $a ) {
		$map = get_option( 'fpw_category_thumb_map' );
		foreach ( $_POST as $key => $value ) {
			if ( ( 'val-for-id-' == substr( $key, 0, 11 ) ) && !( '-field_preview-size' == substr( $key, strlen( $key ) - 19, 19 ) ) ) {
				$replace = array( 'val-for-id-', '-field' );
				$with = array( '', '' );
				$id = str_replace( $replace, $with, $key );
				$a[ $id ] = $value;
				$map[ $id ] = $value;
			} 
		}
		$map_filtered = array();
		foreach( $map as $key => $value ) 
   			if( $value != '0' ) 
       			$map_filtered[ $key ] = $value;
       	$this->fctMapUpdateOk = ( update_option( 'fpw_category_thumb_map', $map_filtered ) );
		return $a;
	}
	
	//	build categories' array
	function getAllCategories() {
		$categories = array();
	
		$cats0 = get_categories('hide_empty=0&orderby=name&parent=0');

		foreach ( $cats0 as $cats00 ) {
    		array_push( $categories, array(0,$cats00) );
    		$cats1 = get_categories('hide_empty=0&orderby=name&parent='.$cats00->cat_ID);

    		foreach ( $cats1 as $cats10 ) {
        		array_push( $categories, array(1,$cats10) );
        		$cats2 = get_categories('hide_empty=0&orderby=name&parent='.$cats10->cat_ID);

        		foreach ( $cats2 as $cats20 ) {
            		array_push( $categories, array(2,$cats20) );
            		$cats3 = get_categories('hide_empty=0&orderby=name&parent='.$cats20->cat_ID);

            		foreach ( $cats3 as $cats30 ) {
                		array_push( $categories, array(3,$cats30) );
                		$cats4 = get_categories('hide_empty=0&orderby=name&parent='.$cats30->cat_ID);

                		foreach ( $cats4 as $cats40 ) {
                    		array_push( $categories, array(4,$cats40) );
                    		$cats5 = get_categories('hide_empty=0&orderby=name&parent='.$cats40->cat_ID);

                    		foreach ( $cats5 as $cats50 ) {
                        		array_push( $categories, array(5,$cats50) );
                    		}
                		}
            		}
        		}
    		}
		}
		
		reset( $categories );
		
		return $categories;	
	}

	//	build assignments array
	function getAssignmentsArray( $c ) {
		$assignments	= array();

		foreach ( $c as $category ) {
			$assignments[ $category[1] -> cat_ID ] = 0;
		}

		//	get assignments from database
		$opt = get_option( 'fpw_category_thumb_map' );

		// update $assignments array with values from database
		if ( $opt ) {
	    	reset( $assignments );
			while ( strlen( key( $assignments ) ) ) {
				if ( array_key_exists( key( $assignments ), $opt ) ) {
					$assignments[ key( $assignments ) ] = $opt[ key( $assignments ) ];	
				}
				next( $assignments );
			}
		}
		
		reset( $assignments );
		
		return $assignments;	
	}
	
	//	get plugin's options ( build if not exists )
	function getOptions() {
	
		$needs_update = FALSE;
		$opt = get_option( 'fpw_category_thumb_opt' );
	
		if ( !is_array( $opt ) ) {
			$needs_update = TRUE;
			$opt = array( 
				'clean'		=> FALSE,
				'donotover' => FALSE,
				'abar'		=> FALSE,
				'fpt'		=> FALSE );
		} else {
			if ( !array_key_exists( 'clean', $opt ) || !is_bool( $opt[ 'clean' ] ) ) { 
				$needs_update = TRUE;
				$opt[ 'clean' ] = FALSE;
			}
			if ( !array_key_exists( 'donotover', $opt ) || !is_bool( $opt[ 'donotover' ] ) ) { 
				$needs_update = TRUE;
				$opt[ 'donotover' ] = FALSE;
			}
			if ( !array_key_exists( 'abar', $opt ) || !is_bool( $opt[ 'abar' ] ) ) { 
				$needs_update = TRUE;
				$opt[ 'abar' ] = FALSE;
			}
			if ( !array_key_exists( 'fpt', $opt ) || !is_bool( $opt[ 'fpt' ] ) ) { 
				$needs_update = TRUE;
				$opt[ 'fpt' ] = FALSE;
			}
			if ( $needs_update ) 
				update_option( 'fpw_category_thumb_opt', $opt );
		}
		return $opt;
	}

	/*	------------------------------------------------------------------
	Main action - sets the value of post's _thumbnail_id based on category
	assignments
	------------------------------------------------------------------- */
	function addThumbnailToPost( $post_id, $post = NULL ) {
		if ( NULL === $post ) 
			return;
		//	we don't want to apply changes to post's revision or drafts
		if ( ( 'revision' == $post->post_type ) || ( 'draft' == $post->post_status ) ) 
			return;
		//	this is actual post
		$thumb_id = get_post_meta( $post_id, '_thumbnail_id', TRUE );
		$do_notover = get_option( 'fpw_category_thumb_opt' );
		if ( $do_notover )
			$do_notover = $do_notover[ 'donotover' ]; 
		$map = get_option( 'fpw_category_thumb_map' );
		if ( $map ) {
			$cat = get_the_category( $post_id );
			foreach ( $cat as $c ) {
				if ( $post->post_date === $post->post_modified ) {
					//	in case of a new post we have to ignore setting of $do_notover flag
					//	as the thumbnail of default category will be there already
					if ( array_key_exists( $c->cat_ID, $map ) )
						if ( $map[ $c->cat_ID ] === 'Author' ) {
							$auth_pic_id = self::getAuthorsPictureID( $post->post_author );
							if ( '0' != $auth_pic_id ) 
								update_post_meta( $post_id, '_thumbnail_id', $auth_pic_id );
						} else { 
							update_post_meta( $post_id, '_thumbnail_id', $map[ $c->cat_ID ] );
						}
				} else {
					//	modified post - observe $do_notover flag
					if ( array_key_exists( $c->cat_ID, $map ) ) 
						if ( !( $do_notover ) ) {
							if ( $map[ $c->cat_ID ] === 'Author' ) {
								$auth_pic_id = self::getAuthorsPictureID( $post->post_author );
								if ( '0' != $auth_pic_id )
									 update_post_meta( $post_id, '_thumbnail_id', $auth_pic_id );
							} else {
								update_post_meta( $post_id, '_thumbnail_id', $map[ $c->cat_ID ] );
							}
						} else {
							if ( '' == $thumb_id )
								if ( $map[ $c->cat_ID ] === 'Author' ) {
									 $auth_pic_id = self::getAuthorsPictureID( $post->post_author );
									 if ( '0' != $auth_pic_id )
									 	update_post_meta( $post_id, '_thumbnail_id', $auth_pic_id );
								} else {
									update_post_meta( $post_id, '_thumbnail_id', $map[ $c->cat_ID ] );
								}
						}
				}
				$thumb_id = get_post_meta( $post_id, '_thumbnail_id', TRUE );
  			}
		}
	}
	
	//	get author's picture id - helper function
	private static function getAuthorsPictureID( $author_id ) {
		global $wpdb;
		$pic_id = 0;
		$all_media = $wpdb->get_results( "SELECT DISTINCT * FROM " . $wpdb->prefix . "posts " .
			"WHERE post_type = 'attachment' AND guid LIKE '%author_" . $author_id . ".jpg%' ORDER " .
			"BY post_date DESC" );
		if ( 0 < count( $all_media ) ) {
			$obj = $all_media[0];
			$pic_id	= $obj->ID;
		} else {
			$active_plugins = get_option( 'active_plugins' );
			$length = count( $active_plugins );
			$nextGenActive = FALSE;
			$i = 0;
			while ( $i < $length ) {
				if ( 0 < strpos( $active_plugins[ $i ], 'nggallery.php' ) ) {
					$nextGenActive = TRUE;
					$i = $length;
				}
				$i++;
			}
			if ( $nextGenActive ) {
				$tmp = $wpdb->get_results( "SELECT DISTINCT * FROM " . $wpdb->prefix . "ngg_gallery WHERE slug = 'authors'" );
				if ( 0 < count( $tmp ) ) {
					$obj = $tmp[0];
					$galleryID = $obj->gid;
					$tmp = $wpdb->get_results( "SELECT DISTINCT * FROM " . $wpdb->prefix . "ngg_pictures " .
						"WHERE galleryid = " . $galleryID . " AND filename LIKE '%" . $author_id . ".jpg%'" );
					if ( 0 < count( $tmp ) ) {
						$obj = $tmp[0];
						$pic_id = 'ngg-' . $obj->pid;
					}
				}
			}
		}	
		return $pic_id;
	}
	
	function fptSettings() {
	}	
	 
}
?>