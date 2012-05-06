<?php
class fpwCategoryThumbnails {
	public	$pluginOptions;
	public	$pluginPath;
	public	$pluginUrl;
	public	$pluginVersion;
	public	$pluginPage;
	public	$wpVersion;
	
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
		
		//	actions and filters
		add_action( 'init', array( &$this, 'init' ) );
		
		//	actions below are not used in front end
		add_action( 'admin_menu', array( &$this, 'adminMenu' ) );
		add_action( 'wp_ajax_fpw_fs_get_file', array( &$this, 'fpw_fs_get_file_ajax' ) );
		add_action( 'save_post', array( &$this, 'addThumbnailToPost' ), 10, 2 );
		add_action( 'after_plugin_row_fpw-category-thumbnails/fpw-category-thumbnails.php', array( &$this, 'afterPluginMeta' ), 10, 2 );

		add_filter( 'plugin_action_links_fpw-category-thumbnails/fpw-category-thumbnails.php', array( &$this, 'pluginLinks' ), 10, 2);
		add_filter( 'plugin_row_meta', array( &$this, 'pluginMetaLinks'), 10, 2 );

		register_activation_hook( __FILE__, array( &$this, 'pluginActivate' ) );
		
		//	Read plugin's options
		$this->pluginOptions = $this->getOptions();

		if ( '3.1' <= $this->wpVersion ) {
			if ( isset( $_POST[ 'buttonPressed' ] ) ) 
				$this->pluginOptions[ 'abar' ] = ( isset( $_POST[ 'abar' ] ) ) ? true : false;
			if ( $this->pluginOptions[ 'abar' ] ) 
				add_action( 'admin_bar_menu', array( &$this, 'pluginToAdminBar' ), 1010 );
		}
	}

	//	register plugin's textdomain
	function init() {
		load_plugin_textdomain( 'fpw-fct', false, 'fpw-category-thumbnails/languages/' );
	} 

	//	register admin menu
	function adminMenu() {
		$page_title = __( 'FPW Category Thumbnails', 'fpw-fct' ) . ' (' . $this->pluginVersion . ')';
		$menu_title = __( 'FPW Category Thumbnails', 'fpw-fct' );
		$this->pluginPage = add_options_page( $page_title, $menu_title, 'manage_options', 'fpw-category-thumbnails', array( &$this, 'pluginSettings' ) );
		
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueueScripts' ) );
	
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
			include $this->pluginPath . '/code/enqueuescripts.php';
		}
	}
	
	//	enqueue pointer scripts
	function enqueuePointerScripts( $hook ) {
		$proceed = false;
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		if ( !in_array( 'fpwfct149', $dismissed ) && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) ) {
			$proceed = true;
			add_action( 'admin_print_footer_scripts', array( &$this, 'custom_print_footer_scripts' ) );
		}
		if ( $proceed ) {
    		wp_enqueue_style('wp-pointer');
    		wp_enqueue_script('wp-pointer');
    		wp_enqueue_script('utils');
		}
	}

	// 	handle pointer
	function custom_print_footer_scripts() {
    	$pointerContent  = '<h3>' . esc_js( __( "What's new in this version?", 'fpw-fct' ) ) . '</h3>';
		$pointerContent .= '<li style="margin-left:25px;margin-top:20px;margin-right:25px;list-style:square">' . __( 'Added code to prevent plugin being activated when WordPress version is lower than 2.9', 'fpw-fct' ) . '</li>';
		$pointerContent .= '<li style="margin-left:25px;margin-right:25px;list-style:square">' . __( 'Exposed method "fpwCategoryThumbnails::addThumbnailToPost" for both back and front end', 'fpw-fct' ) . '</li>';
    	?>
    	<script type="text/javascript">
    	// <![CDATA[
    		jQuery(document).ready( function($) {
        		$('#fct-settings-title').pointer({
        			content: '<?php echo $pointerContent; ?>',
        			position: 'top',
            		close: function() {
						jQuery.post( ajaxurl, {
							pointer: 'fpwfct149',
							action: 'dismiss-wp-pointer'
						});
            		}
				}).pointer('open');
			});
    	// ]]>
    	</script>
    	<?php
	}

	//	contextual help for WordPress 3.3+
	function help33() {
		if ( '3.3' <= $this->wpVersion ) 
			include $this->pluginPath . '/help/help33.php';
	}
	
	//	contextual help for Wordpress older than 3.3
	function help( $contextual_help, $screen_id, $screen ) {
		if ( $screen_id == $this->pluginPage ) {
			include $this->pluginPath . '/help/help.php';
		}	
		return $contextual_help; 
	}

	// AJAX wrapper to get image HTML
	function fpw_fs_get_file_ajax() {
		if ( defined("DOING_AJAX") && DOING_AJAX ) 
			include $this->pluginPath . '/ajax/fpwfctajax.php';
	}

	//	add update information after plugin meta
	function afterPluginMeta( $file, $plugin_data ) {
		$current = get_site_transient( 'update_plugins' );
		if ( !isset( $current -> response[ $file ] ) ) 
			return false;
		$url = "http://fw2s.com/fpwcatthumbsupdate.txt";
		$update = wp_remote_fopen( $url );
		echo '<tr class="plugin-update-tr"><td></td><td></td><td class="plugin-update"><div class="update-message">' . 
			'<img class="alignleft" src="' . $this->pluginUrl . '/Thumbs_Up.png" width="64">' . $update . '</div></td></tr>';
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
	function pluginActivate() {
		//	if cleanup requested make uninstall.php otherwise make uninstall.txt
		if ( $this->pluginOptions[ 'clean' ] ) {
			if ( file_exists( $this->pluginPath . '/uninstall.txt' ) ) 
				rename( $this->pluginPath . '/uninstall.txt', $this->pluginPath . '/uninstall.php' );
		} else {
			if ( file_exists( $this->pluginPath . '/uninstall.php' ) ) 
				rename( $this->pluginPath . '/uninstall.php', $this->pluginPath . '/uninstall.txt' );
		}
	}	
	
	//	add plugin to admin bar ( WordPress 3.1+ )	
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

		//	build initial associative array(category_id => thumbnail_id)
		//	where all values are 0
		$assignments = array();

		foreach ( $categories as $category ) {
			$assignments[ $category[1] -> cat_ID ] = 0;
		}

		//	create a copy of above array which will be used to strip
		//	all elements with 0 values from the array passed to
		//	update_option function
		$azeroes = $assignments;

		//	initialize update flags
		$update_options_ok = FALSE;
		$update_mapping_ok = FALSE;
	
		//	check nonce if any of buttons was pressed
		if ( isset( $_POST[ 'buttonPressed' ] ) ) {
			if ( !isset( $_POST[ 'fpw-fct-nonce' ] ) ) 
				die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red"><strong>' . __( 'You did not send any credentials!', 'fpw-fct' ) . '</strong></p>' );
			if ( !wp_verify_nonce( $_POST[ 'fpw-fct-nonce' ], 'fpw-fct-nonce' ) ) 
				die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red;"><strong>' . __( 'You did not send the right credentials!', 'fpw-fct' ) . '</strong></p>' );

			//	check ok - update options
			$this->pluginOptions[ 'clean' ] = ( isset( $_POST[ 'cleanup' ] ) ) ? true : false;
			$this->pluginOptions[ 'donotover' ] = ( isset( $_POST[ 'donotover' ] ) ) ? true : false;
			if ( '3.1' <= $this->wpVersion ) 
				$this->pluginOptions[ 'abar' ] = ( isset( $_POST[ 'abar' ] ) ) ? true : false;
			if ( !ctype_digit( $_POST[ 'cwidth' ] ) ) { 
				$this->pluginOptions[ 'width' ] = '396';
			} else {
				$this->pluginOptions[ 'width' ] = $_POST[ 'cwidth' ];
			}
		
			$update_options_ok = ( update_option( 'fpw_category_thumb_opt', $this->pluginOptions ) );
		
			// 	if any changes to options then check uninstall file's extension
			if ( $update_options_ok ) 
				$this->pluginActivate();

			//	update mappings
			//	inserting posted values into $assignments array 
        	reset( $assignments );
			while ( strlen( key( $assignments ) ) ) {
				//	validation
				$v = (string) $_POST[ 'val-for-id-' . key( $assignments ) . '-field' ];
				if ( strlen( $v ) > 0 ) {
					if ( ctype_digit( $v ) ) {
						if ( strlen( $v ) > 1 ) $v = ltrim( $v, '0' );
					} elseif ( 'Author' === $v ) {
					} else {
						if ( 'ngg-' == substr( $v, 0, 4 ) ) {
							$v = 'ngg-' . ltrim( substr( $v, 4 ), '0' );
						} else {
							$v = '0';
						}
					}
				} else {
					$v = '0';
				}
        		$assignments[ key( $assignments ) ] = $v;
				next($assignments);
			}

			//	create array with all 0 valued elements removed
			$option = array_diff_assoc( $assignments, $azeroes );

			//	database update
			$update_mapping_ok = ( update_option( 'fpw_category_thumb_map', $option ) );

			//	check if remove button was pressed
			if ( 'Remove' == $_POST[ 'buttonPressed' ] ) {
				reset( $assignments );
		
				while ( strlen( key( $assignments ) ) ) {
					$catid = key( $assignments );
					$parg = array(
						numberofposts => -1,
						nopaging => true,
						category => $catid,
						post_type => 'any' );
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
			if ( 'Apply' == $_POST[ 'buttonPressed' ] ) {
				$map = get_option( 'fpw_category_thumb_map' );
				if ( $map )
					while ( strlen( key( $map ) ) ) {
						$catid = key($map);
						$parg = array(
							numberofposts => -1,
							nopaging => true,
							category => $catid,
							post_type => 'any' );
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

		/*	------------------------------
		Settings page HTML starts here
		--------------------------- */

		echo '<div class="wrap">' . PHP_EOL;
		echo '<div id="icon-options-general" class="icon32"></div><h2 id="fct-settings-title">' . __( 'FPW Category Thumbnails', 'fpw-fct' ) . ' (' . $this->pluginVersion . ')</h2>';

    	//	display warning if current theme doesn't support post thumbnails
    	if ( !current_theme_supports( 'post-thumbnails' ) ) {
    		echo '	<div id="message" class="error fade" style="background-color: #CCFFFF; color: red;"><p><strong>';
			echo __( 'WARNING: Your theme has no support for <em>post thumbnails</em>!', 'fpw-fct' ) . ' '; 
			echo __( 'You can continue with <em>Settings</em> but until you add <code>add_theme_support( \'post-thumbnails\' );</code> to the theme\'s functions.php you will not be able to display thumbnails.', 'fpw-fct' ); 
			echo '</strong></p></div>';
		}

		//	check if any of submit buttons was pressed
		if ( isset( $_POST[ 'buttonPressed' ] ) ) { 
		
			//	display message about update status
			if ( 'Update' == $_POST[ 'buttonPressed' ] )
				if ( $update_options_ok || $update_mapping_ok ) {
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Updated successfully.', 'fpw-fct' ) . '</strong></p></div>';
				} else {
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'No changes detected. Nothing to update.', 'fpw-fct' ) . '</strong></p></div>';
				}

			//	display message about apply status
			if ( 'Apply' == $_POST[ 'buttonPressed' ] )
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'Applied thumbnails to existing posts / pages successfully.', 'fpw-fct' ) . '</strong></p></div>';

			//	display message about remove status
			if ( 'Remove' == $_POST[ 'buttonPressed' ] )
				echo '<div id="message" class="updated fade"><p><strong>' . __( 'All thumbnails removed successfully.', 'fpw-fct' ) . '</strong></p></div>';
		}
		
		//	the form starts here
		echo '<p>';
		echo '<form name="fpw_cat_thmb_form" action="';
		print '?page=' . basename( __FILE__, '.class.php' );
		echo '" method="post">';

		//	protect this form with nonce
		echo '<input name="fpw-fct-nonce" type="hidden" value="' . wp_create_nonce( 'fpw-fct-nonce' ) . '" />';

		//	do not overwrite checkbox
		echo '<input type="checkbox" name="donotover" value="yes"';
		if ( $this->pluginOptions[ 'donotover' ] ) 
			echo ' checked';
		echo '> ' . __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) . '<br />';

		//	cleanup checkbox
		echo '<input type="checkbox" name="cleanup" value="yes"';
		if ( $this->pluginOptions[ 'clean' ] ) 
			echo ' checked';
		echo '> ' . __( "Remove plugin's data from database on uninstall", 'fpw-fct' ) . '<br />';

		//	add plugin to admin bar checkbox
		if ( '3.1' <= $this->wpVersion ) {
			echo '<input type="checkbox" name="abar" value="yes"';
			if ( $this->pluginOptions[ 'abar' ] ) 
				echo ' checked';
			echo '> ' . __( 'Add this plugin to the Admin Bar', 'fpw-fct' ) . '<br />';
		}

		//	width of Image ID column
		echo '<br /><input type="text" name="cwidth" size="3" maxlength="3" value="' . $this->pluginOptions[ 'width' ] . '" style="text-align: right">px - ';
		echo __( 'width of Image ID column in pixels ( for English - 396 )', 'fpw-fct' ) . '<br /><br />';

		// start of the table
		echo '<table class="widefat">';
		echo '<thead>';
		echo '<tr>';
		echo '<th style="width: 25%; text-align: left;">' . __( 'Category (ID)', 'fpw-fct' ) . '</th>';
		echo '<th style="width: ' . $this->pluginOptions[ 'width' ] . 'px; text-align: left;">' . __( 'Image ID', 'fpw-fct' ) . '</th>';
		echo '<th style="text-align: left;">' . __( 'Preview', 'fpw-fct' ) . '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<th style="width: 25%; text-align: left;">' . __( 'Category (ID)', 'fpw-fct' ) . '</th>';
		echo '<th style="width: ' . $this->pluginOptions[ 'width' ] . 'px; text-align: left;">' . __( 'Image ID', 'fpw-fct' ) . '</th>';
		echo '<th style="text-align: left;">' . __( 'Preview', 'fpw-fct' ) . '</th>';
		echo '</tr>';
		echo '</tfoot>';
		echo '<tbody>';

		//	build form's input fields and buttons
		reset( $assignments );
		reset( $categories );
		$i = 0;
		while ( strlen( key( $assignments ) ) ) {
			echo '<tr>';
			echo '<td style="vertical-align: middle;">'; 
			$indent = str_repeat( '&nbsp;', $categories[ key( $categories )][ 0 ] * 4);
			echo $indent . $categories[ key( $categories ) ][ 1 ] -> cat_name . ' (' . $categories[ key( $categories )][ 1 ] -> cat_ID . ')'; 
			echo '</td>' . PHP_EOL;
			$this->button( 'val-for-id-' . key( $assignments ) . '-field', $assignments[ key( $assignments ) ], key( $assignments ), $label = __( 'Get ID', 'fpw-fct' ) );
			echo '</tr>';
			$i++;
			next( $assignments );
			next( $categories );
		}

		//	end of the table
		echo '</tbody>';
		echo '</table>';

		//	submit buttons
		echo '<br /><div class="inputbutton"><input title="' . 
			 __( 'Writes modified options and mapping to the database', 'fpw-fct' ) . 
		 	 '" onclick="confirmUpdate();" id="update" class="button-primary fpw-submit" type="button" name="fpw_cat_thmb_submit" value="' . __( 'Update', 'fpw-fct' ) . '" /> ';
		echo '<input onclick="confirmApply();" title="' . 
		 	 __( 'Adds post thumbnail to every existing post / page belonging to the category which has thumbnail id mapped to', 'fpw-fct' ) . 
		 	 '" id="apply" class="button-primary fpw-submit" type="button" name="fpw_cat_thmb_submit_apply" value="' . __( 'Apply Mapping', 'fpw-fct' ) . '" /> ';
		echo '<input onclick="confirmRemove();" title="' . 
			 __( 'Removes thumbnails from all existing posts / pages regardless of the category', 'fpw-fct' ) . 
		 	 '" id="remove" class="button-primary fpw-submit" type="button" name="fpw_cat_thmb_submit_remove" value="' . __( 'Remove Thumbnails', 'fpw-fct' ) . '" />';
		echo '<input id="buttonPressed" type="hidden" value="" name="buttonPressed" /></div>';

		//	end of form
		echo '</form>';
		echo '</p>';
		echo '</div>';
	}
	
	//	bulid settings form fields
	private function button( $name, $value, $catid, $label = 'Get ID', $preview_size = 'thumbnail', $removable = false ) { ?>
		<td style="vertical-align: middle;"><div>
			<input type="text" size="10" maxlength="10" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>" class="fpw-fs-value" />
			<input type="button" class="button-secondary fpw-fs-button" title="<?php echo __( 'fetches image ID from media library', 'fpw-fct' ); ?>" value="<?php echo __( 'Get ID', 'fpw-fct' ); ?>" />
			<input type="button" class="button-secondary btn-for-author" title="<?php echo __( 'will use author\'s picture as a thumbnail', 'fpw-fct' ); ?>" id="author-for-id-<?php echo $catid; ?>" value="<?php echo __( 'Author\'s picture', 'fpw-fct' ); ?>" />
			<input class="button-secondary btn-for-clear" title="<?php echo __( 'clears \'Image ID\' input value and \'Preview\' area', 'fpw-fct' ); ?>" id="clear-for-id-<?php echo $catid; ?>" type="button" value="<?php echo __( 'Clear', 'fpw-fct' ); ?>" />		
			<input class="button-secondary btn-for-refresh" title="<?php echo __( 'refreshes \'Preview\' area', 'fpw-fct' ); ?>" id="refresh-for-id-<?php echo $catid; ?>" type="button" value="<?php echo __( 'Refresh', 'fpw-fct' ); ?>" />		
			<input type="hidden" value="<?php echo esc_attr( $preview_size ); ?>" name="<?php echo esc_attr( $name ); ?>_preview-size" id="<?php echo esc_attr( $name ); ?>_preview-size" class="fpw-fs-preview-size" />
		</div></td>	
		<td style="vertical-align: middle;">
			<div class="fpw-fs-preview" id="<?php echo esc_attr( $name ); ?>_preview">
			<?php
				if ( $value ) {
					if ( '0' == $value ) {
						echo '';
					} elseif ( 'Author' === $value ) {
						echo '[ ' . __( 'Picture', 'fpw-fct' ) . ' ]';
					} else {
						if ( ( 'ngg-' == substr( $value, 0, 4 ) ) && class_exists( 'nggdb' ) ) {
							$id = substr( $value, 4 );
							$picture = nggdb::find_image($id);
							if ( !$picture ) {
								echo '';
							} else {
								$pic = $picture->imageURL;
								$w = $picture->meta_data['thumbnail']['width'];
								$h = $picture->meta_data['thumbnail']['height'];
								$pic = '<img width="' . $w . '" height="' . $h . '" src="' . $pic . '" />';
								echo $pic;
							}
						} else {
							if ( wp_attachment_is_image( $value ) ) {
								echo wp_get_attachment_image( $value, $preview_size );
							} else {
								echo '';
							}
						}
					}
				}
			?>
			</div>
		</td>
	<?php }
	
	//	get plugin's options ( build if not exists )
	private function getOptions() {
	
		$needs_update = FALSE;
		$opt = get_option( 'fpw_category_thumb_opt' );
	
		if ( !is_array( $opt ) ) {
			$needs_update = TRUE;
			if ( '3.1' <= $this->wpVersion ) {
				$opt = array( 
					'clean'		=> FALSE,
					'donotover' => FALSE,
					'width'		=> '396',
					'abar'		=> FALSE );
			} else {
				$opt = array( 
					'clean'		=> FALSE,
					'donotover' => FALSE,
					'width'		=> '396' );
			}
		} else {
			if ( !array_key_exists( 'clean', $opt ) || !is_bool( $opt[ 'clean' ] ) ) { 
				$needs_update = TRUE;
				$opt[ 'clean' ] = FALSE;
			}
			if ( !array_key_exists( 'donotover', $opt ) || !is_bool( $opt[ 'donotover' ] ) ) { 
				$needs_update = TRUE;
				$opt[ 'donotover' ] = FALSE;
			}
			if ( !array_key_exists( 'width', $opt ) || !ctype_digit( $opt[ 'width' ] ) ) { 
				$needs_update = TRUE;
				$opt[ 'width' ] = '396';
			}
			if ( '3.1' <= $this->wpVersion ) 
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