<?php
//	plugin's main class
class fpwCategoryThumbnails {
	var $canActivate;
	var	$pluginOptions;
	var	$pluginPath;
	var	$pluginUrl;
	var	$pluginVersion;
	var	$pluginPage;
	var	$pluginLocale;
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
		$this->pluginPath = $path;
		
		//	set plugin's url
		$this->pluginUrl = WP_PLUGIN_URL . '/fpw-category-thumbnails';
		
		//	set version
		$this->pluginVersion = $version;
		
		//	set WP version
		$this->wpVersion = $wp_version;
		
		$this->pluginLocale = get_locale();

		//	set translation URL
		$this->translationURL = 'http://svn.wp-plugins.org/fpw-category-thumbnails/translations/' . 
								$this->pluginVersion . '/fpw-fct-' . $this->pluginLocale . '.mo';
								
		//	set translation path
		$this->translationPath = $this->pluginPath . '/languages/fpw-fct-' . $this->pluginLocale . '.mo';
		
		//	check if WordPress version 3.1+
		$this->canActivate = ( '3.1' <= $this->wpVersion ) ? true : false;										
		
		if ( !$this->canActivate ) {
			add_action(
				'admin_notices', 
				create_function( '', 'printf (\'<div id="message" class="error">' . 
								 '<p><strong>FPW Category Thumbnails</strong> ' . 
								 'plugin requires <strong>WordPress 3.1 or ' . 
								 'higher</strong>. <strong>Please ' . 
								 'deactivate</strong>.</strong></p></div>\' );'
				)
			);
			
			return;
         }

		//	actions and filters
		add_action( 'init', array( &$this, 'init' ) );

		register_activation_hook( $this->pluginPath . '/fpw-category-thumbnails.php', array( &$this, 'uninstallMainenance' ) );
		
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
		
		//	read plugin's options
		$this->pluginOptions = $this->getOptions();

		$anyButtonPressed =
			(	isset( $_POST['submit-getid'] ) || isset( $_POST['submit-author'] ) || 
				isset( $_POST['submit-clear'] ) || isset( $_POST['submit-refresh'] ) || 
				isset( $_POST['submit-update'] ) || isset( $_POST['submit-apply'] ) || 
				isset( $_POST['submit-remove'] ) || isset( $_POST['submit-language'] ) ) ? true : false; 

		if ( $anyButtonPressed ) 
			$this->pluginOptions[ 'abar' ] = ( isset( $_POST[ 'abar' ] ) ) ? true : false;
		if ( $this->pluginOptions[ 'abar' ] ) 
			add_action( 'admin_bar_menu', array( &$this, 'pluginToAdminBar' ), 1010 );
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
		load_plugin_textdomain( 'fpw-fct', false, 'fpw-category-thumbnails/languages/' );

		if ( !( 'en_US' == $this->pluginLocale ) ) 
			$this->translationStatus = $this->translationAvailable();
	} 

	//	register admin menu
	function adminMenu() {
		$page_title = __( 'FPW Category Thumbnails', 'fpw-fct' ) . ' (' . $this->pluginVersion . ')';
		$menu_title = __( 'FPW Category Thumbnails', 'fpw-fct' );
		$this->pluginPage = add_options_page( $page_title, $menu_title, 'manage_options', 
							'fpw-category-thumbnails', array( &$this, 'pluginSettings' ) );
		
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueueScripts' ) );
		add_action( 'load-' . $this->pluginPage, array( &$this, 'addScreenOptions' ) );
	
		if ( '3.3' <= $this->wpVersion ) {
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueuePointerScripts' ) );
			add_action( 'load-' . $this->pluginPage, array( &$this, 'help33' ) );
		} else {
			add_filter( 'contextual_help', array( &$this, 'help'), 10, 3 );
		}
	}

	//	register styles, scripts, and localize javascript
	function enqueueScripts( $hook ) {
		if ( ( 'settings_page_fpw-category-thumbnails' == $hook ) || ( 'media-upload-popup' == $hook ) ) {
			require_once $this->pluginPath . '/scripts/enqueuescripts.php';
		}
	}
	
	//	enqueue pointer scripts
	function enqueuePointerScripts( $hook ) {
		if ( 'settings_page_fpw-category-thumbnails' == $hook )
			require_once $this->pluginPath . '/scripts/enqueuepointerscripts.php';
	}

	// 	AJAX handler for pointer
	function custom_print_footer_scripts() {
		$pointer = 'fpwfct' . str_replace( '.', '', $this->pluginVersion );
    	$pointerContent  = '<h3>' . esc_js( __( "What's new in this version?", 'fpw-fct' ) ) . '</h3>';
		$pointerContent .= '<li style="margin-left:25px;margin-top:20px;margin-right:25px;list-style:square">' . __( 'Dropped support for WordPress versions lower than 3.1', 'fpw-fct' ) . '</li>';
		$pointerContent .= '<li style="margin-left:25px;margin-right:25px;list-style:square">' . __( 'Use WP_List_Table descendant to display category / thumbnail mapping', 'fpw-fct' ) . '</li>';
		$pointerContent .= '<li style="margin-left:25px;margin-right:25px;list-style:square">' . __( 'Full AJAX implementation of all operations', 'fpw-fct' ) . '</li>';
		$pointerContent .= '<li style="margin-left:25px;margin-right:25px;list-style:square">' . __( 'Ensured proper operation when JavaScript is disabled', 'fpw-fct' ) . '</li>';
		$pointerContent .= '<li style="margin-left:25px;margin-right:25px;list-style:square">' . __( "Support for downloading of translation files from plugin's repository", "fpw-fct" ) . '</li>';
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
 		
		$args = array(
			'label'		=> __( 'Categories', 'fpw-fct' ),
			'default'	=> 10,
			'option'	=> 'edit_category_per_page',
		);
 
		add_screen_option( $option, $args );
		add_filter( 'load-' . $this->pluginPage, array( &$this, 'setScreenOption'), 10, 3 );
	}

	function setScreenOption( $status, $option, $value ) {
		if ( 'edit_category_per_page' == $option )
			return $value;
	}

	//	contextual help for WordPress 3.3+
	function help33() {
		if ( '3.3' <= $this->wpVersion ) 
			require_once $this->pluginPath . '/help/help33.php';
	}
	
	//	contextual help for Wordpress older than 3.3
	function help( $contextual_help, $screen_id, $screen ) {
		if ( $screen_id == $this->pluginPage ) {
			require_once $this->pluginPath . '/help/help.php';
		}	
		return $contextual_help; 
	}

	// AJAX wrapper to get image HTML
	function fpw_fs_get_file_ajax() {
		if ( defined("DOING_AJAX") && DOING_AJAX ) 
			require_once $this->pluginPath . '/ajax/getimageid.php';
	}
	
	// AJAX wrapper to perform options update
	function fpw_ct_update_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->pluginPath . '/ajax/update.php';
	}

	// AJAX wrapper to perform apply mapping tasks
	function fpw_ct_apply_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->pluginPath . '/ajax/apply.php';
	}

	// AJAX wrapper to perform remove thumbnails
	function fpw_ct_remove_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->pluginPath . '/ajax/remove.php';
	}

	// AJAX wrapper to perform translation file loading
	function fpw_ct_language_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->pluginPath . '/ajax/language.php';
	}

	//	add update information after plugin meta
	function afterPluginMeta( $file, $plugin_data ) {
		$current = get_site_transient( 'update_plugins' );
		if ( !isset( $current -> response[ $file ] ) ) 
			return false;
		$url = "http://fw2s.com/fpwcatthumbsupdate.txt";
		$update = wp_remote_fopen( $url );
		echo '<tr class="plugin-update-tr"><td></td><td></td><td class="plugin-update"><div class="update-message">' . 
			'<img class="alignleft" src="' . $this->pluginUrl . '/images/Thumbs_Up.png" width="64">' . $update . '</div></td></tr>';
	}

	//	add link to Donation to plugins meta
	function pluginMetaLinks( $links, $file ) {
		if ( 'fpw-category-thumbnails/fpw-category-thumbnails.php' == $file ) 
			$links[] = '<a href="http://fw2s.com/payments-and-donations/" target="_blank">' . __( "Donate", "fpw-fct" ) . '</a>';
		return $links;
	}
	
	//	add link to settings page in plugins list
	function pluginLinks( $links, $file ) {
   		$settings_link = '<a href="' . site_url( '/wp-admin/' ) . 'options-general.php?page=fpw-category-thumbnails">' . __( 'Settings', 'fpw-fct' ) . '</a>';
		array_unshift( $links, $settings_link );
    	return $links;
	}
	
	//	uninstall file maintenance
	function uninstallMaintenance() {
		if ( $this->pluginOptions[ 'clean' ] ) {
			if ( file_exists( $this->pluginPath . '/uninstall.txt' ) ) 
				rename( $this->pluginPath . '/uninstall.txt', $this->pluginPath . '/uninstall.php' );
		} else {
			if ( file_exists( $this->pluginPath . '/uninstall.php' ) ) 
				rename( $this->pluginPath . '/uninstall.php', $this->pluginPath . '/uninstall.txt' );
		}
	}	
	
	//	add plugin to admin bar	
	function pluginToAdminBar() {
		if ( current_user_can( 'manage_options' ) ) {
			global 	$wp_admin_bar;
			
			$main = array(
				'id' => 'fpw_plugins',
				'title' => __( 'FPW Plugins', 'fpw-fct' ),
				'href' => '#' );

			$subm = array(
				'id' => 'fpw_bar_category_thumbnails',
				'parent' => 'fpw_plugins',
				'title' => __( 'FPW Category Thumbnails', 'fpw-fct' ),
				'href' => get_admin_url() . 'options-general.php?page=fpw-category-thumbnails' );

			if ( '3.3' <= $this->wpVersion ) {
				$addmain = ( is_array( $wp_admin_bar->get_node( 'fpw_plugins' ) ) ) ? false : true;
			} else {
				$addmain = ( isset( $wp_admin_bar->menu->fpw_plugins ) ) ? false : true;
			} 

			if ( $addmain )
				$wp_admin_bar->add_menu( $main );
			$wp_admin_bar->add_menu( $subm );
		}
	}
	
	//	plugin's Settings page
	function pluginSettings() {

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
					 __( 'You did not send any credentials!', 'fpw-fct' ) . '</strong></p>' );
			if ( !wp_verify_nonce( $_POST[ 'fpw-fct-nonce' ], 'fpw-fct-nonce' ) ) 
				die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red;"><strong>' . 
					 __( 'You did not send the right credentials!', 'fpw-fct' ) . '</strong></p>' );

			//	check ok - update options
			$this->pluginOptions[ 'clean' ] = ( isset( $_POST[ 'cleanup' ] ) ) ? true : false;
			$this->pluginOptions[ 'donotover' ] = ( isset( $_POST[ 'donotover' ] ) ) ? true : false;
			$this->pluginOptions[ 'abar' ] = ( isset( $_POST[ 'abar' ] ) ) ? true : false;
		
			$update_options_ok = ( update_option( 'fpw_category_thumb_opt', $this->pluginOptions ) );
		
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
		
		echo '<div id="icon-options-general" class="icon32"></div><h2 id="fct-settings-title">' . __( 'FPW Category Thumbnails', 'fpw-fct' ) . ' (' . $this->pluginVersion . ')</h2>';

    	//	display warning if current theme doesn't support post thumbnails
    	if ( !current_theme_supports( 'post-thumbnails' ) ) {
    		echo '	<div id="message" class="error fade" style="background-color: #CCFFFF; color: red;"><p><strong>';
			echo __( 'WARNING: Your theme has no support for <em>post thumbnails</em>!', 'fpw-fct' ) . ' '; 
			echo __( 'You can continue with <em>Settings</em> but until you add <code>add_theme_support( \'post-thumbnails\' );</code> to the theme\'s functions.php you will not be able to display thumbnails.', 'fpw-fct' ); 
			echo '</strong></p></div>';
		} 

		//	notification division
		echo '<div id="message" class="updated fade" style="display: none"></div>';				
		
		//	check if any of submit buttons was pressed
		if ( $anyButtonPressed ) {
			$assignments = $this->updateMapping( $assignments );
			$this->noJavascriptMessage();
			if (	isset( $_POST['submit-getid'] ) || isset( $_POST['submit-author'] ) || 
				 	isset( $_POST['submit-clear'] ) || isset( $_POST['submit-refresh'] ) || 
					isset( $_POST['submit-update'] ) ) { 
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'Updated successfully.', 'fpw-fct' ) . '</strong></p></div>';
			} elseif ( isset( $_POST['submit-apply'] ) ) {
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'Applied thumbnails to existing posts / pages successfully.', 'fpw-fct' ) . '</strong></p></div>';
			} elseif ( isset( $_POST['submit-remove'] ) ) {
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'All thumbnails removed successfully.', 'fpw-fct' ) . '</strong></p></div>';
			} elseif ( isset( $_POST['submit-language'] ) ) {
				if ( 'available' == $this->translationStatus )  
					$handle = @fopen( $this->translationPath, 'wb' );
					fwrite( $handle, $this->translationResponse[ 'body' ] );
					fclose($handle);
					echo '<div id="message" class="updated"><p><strong>' . __( 'Language file downloaded. Click', 'fpw-fct' ) . 
						 ' ' . '<a href="/wp-admin/options-general.php?page=fpw-category-thumbnails">' .
						 __( 'here', 'fpw-fct' ) . '</a> ' . __( 'to reload page.', 'fpw-fct' ) . '</strong></p></div>';
				if ( 'installed' == $this->translationStatus ) 
						echo '<div id="message" class="updated fade"><p><strong>' . __( 'Language file already exists.', 'fpw-fct' ) . 
							 '</strong></p></div>';
				if ( 'not_exist' == $this->translationStatus ) 
						echo '<div id="message" class="updated fade"><p><strong>' . __( 'Language file is not available.', 'fpw-fct' ) . 
							 '</strong></p></div>';
			}
		}
		
		//	the form starts here
		echo '<div>';
		echo '<form name="fpw_cat_thmb_form" action="';
		print '?page=' . basename( __FILE__, '.class.php' );
		echo '" method="post">';
		
		//	protect this form with nonce
		echo '<input name="fpw-fct-nonce" type="hidden" value="' . wp_create_nonce( 'fpw-fct-nonce' ) . '" />';

		//	options section
		echo '<div id="fpw-fct-options">';
		
		//	do not overwrite checkbox
		echo '<br /><input type="checkbox" class="option-group" id="box-donotover" name="donotover" value="donotover"';
		if ( $this->pluginOptions[ 'donotover' ] ) 
			echo ' checked';
		echo '> ' . __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) . '<br />';

		//	cleanup checkbox
		echo '<input type="checkbox" class="option-group" id="box-cleanup" name="cleanup" value="cleanup"';
		if ( $this->pluginOptions[ 'clean' ] ) 
			echo ' checked';
		echo '> ' . __( "Remove plugin's data from database on uninstall", 'fpw-fct' ) . '<br />';

		//	add plugin to admin bar checkbox
		echo '<input type="checkbox" class="option-group" id="box-abar" name="abar" value="abar"';
		if ( $this->pluginOptions[ 'abar' ] ) 
			echo ' checked';
		echo '> ' . __( 'Add this plugin to the Admin Bar', 'fpw-fct' ) . '<br />';

		//	end of options section
		echo '</div>';
		
		require_once $this->pluginPath . '/code/table.php';

		//	end of form
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
	
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
       	update_option( 'fpw_category_thumb_map', $map_filtered );
		return $a;
	}
	
	function noJavascriptMessage() {
    	//	display message about javascript being disabled
   		echo	'<div id="message" class="error"><p><strong>' . 
   				__( 'JavaScript is disabled!', 'fpw-fct' ) . '</strong> ' . 
				__( 'That makes the functionality of ' . 
				'table row actions', 'fpw-fct' ) . ' ( <strong><em>' . 
				__( 'Get ID', 'fpw-fct' ) . '</em></strong>, <strong><em>' . 
				__( 'Author', 'fpw-fct' ) . '</em></strong>, <strong><em>' .
				__( 'Clear', 'fpw-fct' ) . '</em></strong>, and <strong><em>' . 
				__( 'Refresh', 'fpw-fct' ) . '</em></strong> ) ' . 
				__( 'being degraded. Other actions are affected too.', 'fpw-fct' ) . 
				' <strong><em>' . __( 'Screen Options', 'fpw-fct' ) . 
				'</em></strong> ( ' . __( 'which controls number of categories per page', 'fpw-fct' ) . 
				' ) ' . __( 'and contextual', 'fpw-fct' ) . ' <strong><em>' . 
				__( 'Help', 'fpw-fct' ) . '</em></strong> ' . 
				__( 'screen will not be visible.', 'fpw-fct' ) . ' ' . 
				__( 'In this state you have to enter', 'fpw-fct' ) . ' <strong><em>' . 
				__( 'Image ID', 'fpw-fct' ) . '</em></strong> ' . 
				__( 'values manually and click on any row action link or', 'fpw-fct' ) . 
				' <strong><em>' . __( 'Update', 'fpw-fct' ) . '</em></strong> ' . 
				__( 'button to save changes.', 'fpw-fct' ) . '</p><p><strong>' . 
				__( 'Warning', 'fpw-fct' ) . '</strong>: ' . 
				__( 'without JavaScript the only mapping saved on submit will be', 'fpw-fct' ) . 
				' ' . __( "the content of the table's current page.", "fpw-fct" ) . 
				' ' . __( 'Remember to click on', 'fpw-fct' ) . ' <strong><em>' . 
				__( 'Update', 'fpw-fct' ) . '</em></strong> ' . 
				__( 'before switching to another page!', 'fpw-fct' ) . '</p><p>' . 
				'<strong>' . __( 'Enable JavaScript and enjoy fully AJAX' . 
				' powered interface!', 'fpw-fct' ) . '</strong></p></div>';  
	}

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
				'abar'		=> FALSE );
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
	 
}
?>