<?php
			$my_help  = '<table class="widefat">';
			$my_help .= '<thead>';
			$my_help .= '<tr>';
			$my_help .= '<th width="50%" style="text-align: left;">' . __( 'Introduction', 'fpw-fct' ) . '</th>';
			$my_help .= '<th width="50%" style="text-align: left;">' . __( 'Options', 'fpw-fct' ) . '</th>';
			$my_help .= '</thead>';
			$my_help .= '<tbody>';
			$my_help .= '<tr>';
			$my_help .= '<td style="vertical-align: top;"><p style="text-align: justify;">' . 
						__( 'Setting featured images for posts / pages could be very time consuming, especially when your media library holds hundreds of pictures.', 'fpw-fct' ) . ' ' . 
						__( 'Very often we select the same thumbnail for posts in particular category.', 'fpw-fct' ) . ' ' . 
						__( 'This plugin automates the process by inserting a thumbnail based on category / thumbnail mapping while post / page is being created or updated.', 'fpw-fct' ) . '</p>' . 
						'<p style="font-size: larger">' . __( 'Note', 'fpw-fct' ) . '</p>' . '<blockquote style="text-align: justify">' . 
						__( 'Please remember that the active theme must support post thumbnails.', 'fpw-fct' ) . '</blockquote></td>';
			$my_help .= '<td style="vertical-align: top;"><p style="text-align: justify"><strong>' . __( 'Do not overwrite if post / page has thumbnail assigned already', 'fpw-fct' ) . 
						'</strong> ' . __( '( checked ) - while the post is being saved the originally set thumbnail will be preserved', 'fpt-fct' ) . 
						'<br /><strong>' . __( 'Removes plugin\'s data from database on uninstall', 'fpw-fct' ) . '</strong> ' . 
						__( '( checked ) - during uninstall procedure all plugin\'s information ( options, mappings ) will be removed from the database', 'fpt-fct' ) . 
						'<br /><strong>' . __( 'Add this plugin to the Admin Bar', 'fpw-fct' ) . 
						'</strong> ' . __( '( checked ) - the plugin\'s link to its settings page will be added to the Admin Bar', 'fpw-fct' ) . 
						'<br /><strong>' . __( 'width of Image ID column in pixels', 'fpw-fct' ) . '</strong> - ' . 
						__( 'this value may need to be adjusted for non-English translations of the plugin as widths of buttons could be different', 'fpw-fct' ) . '</p></td>';
			$my_help .= '</tr>';
			$my_help .= '</tbody>';
			$my_help .= '</table><br />';						

			$my_help .= '<table class="widefat">';
			$my_help .= '<thead>';
			$my_help .= '<tr>';
			$my_help .= '<th width="50%" style="text-align: left;">' . __( 'Mapping & Actions', 'fpw-fct' ) . '</th>';
			$my_help .= '<th width="50%" style="text-align: left;">' . __( 'FAQ & Support', 'fpw-fct' ) . '</th>';
			$my_help .= '</thead>';
			$my_help .= '<tbody>';
			$my_help .= '<tr>';
			$my_help .= '<td style="vertical-align: top;"><p style="text-align: justify;">' . 
						__( 'Each row of the mapping table represents a category and a thumbnail image ID assigned to it.', 'fpw-fct' ) . ' ' . 
						__( 'First column holds a category name and its ID.', 'fpw-fct' ) . ' ' . 
						__( 'Second column consists of four elements: Image ID - an input field which holds thumbnails image ID,', 'fpw-fct' ) . ' ' . 
						__( 'Get ID, Clear, and Refresh buttons. Third column holds thumbnail\'s preview.', 'fpw-fct' ) . ' ' . 
						__( 'Image ID can be entered manually ( if you remember it ) or by clicking on \'Get ID\' button which will call \'media upload\' overlay.', 'fpw-fct' ) . 
						'</p><p style="font-size: larger">' . __( 'Action Buttons', 'fpw-fct' ) . '</p><blockquote>' . 
						'<table style="width: 100%; border: 0; border-collapse: collapse; padding: 0;"><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Get ID', 'fpw-fct' ) . '" />' . '</td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' .  
						__( 'will call \'media upload\' overlay and on return will populate \'Image ID\' input box and \'Preview\' area ( AJAX - without reloading screen )', 'fpw-fct' ) . 
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Clear', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'if confirmed it will enter \'0\' as image ID and clear \'Preview\' area ( AJAX - without reloading screen )', 'fpw-fct' ) . 
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input type="button" class="button-secondary" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Refresh', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'when clicked after entering of an image ID manually it will populate \'Preview\' area ( AJAX - without reloading screen )', 'fpw-fct' ) . 
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Update', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'saves modified options and mapping to the database', 'fpw-fct' ) .  
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Apply Mapping', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'adds thumbnails to existing posts / pages based on category mapping', 'fpw-fct' ) . 
						'</td></tr><tr><td style="text-align: left; vertical-align: middle; border: 0; padding: 2px;">' . 
						'<input class="button-primary" type="button" title="' . __( 'Inactive button - presentation only', 'fpw-fct' ) . '" value="' . 
						__( 'Remove Thumbnails', 'fpw-fct' ) . '" /></td><td style="text-align: justify; vertical-align: middle; border: 0; padding: 2px;">' . 
						__( 'removes thumbnails from all posts /pages regardless of the category', 'fpw-fct' ) . 
						'</td></tr></table></blockquote></td>';
			$my_help .= '<td style="vertical-align: top;"><p style="text-align: justify"><strong>' . 
						__( 'Question:', 'fpw-fct' ) . '</strong> ' .
						__( 'I got an ID for the image and assigned it to the category, and the plugin does not display it in posts.', 'fpw-fct' ) . '<br /><strong>' . 
						__( 'Answer:', 'fpw-fct' ) . '</strong> ' . __( 'The plugin does not display thumbnails by itself. This is your theme\'s role.', 'fpw-fct' ) . ' ' . 
						__( 'Read this article', 'fpw-fct' ) . ' ' . 
						'<a href="http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/" target="_blank" rel="nofollow">' . 
						'New in WordPress 2.9 post thumbnail images</a> ' . 
						__( 'by', 'fpw-fct' ) . ' Mark Jaquith ' . __( 'about enabling theme\'s support for post thumbnails.', 'fpw-fct' ) . '<br /><br /><strong>' . 
						__( 'Question:', 'fpw-fct' ) . '</strong> ' . 
						__( 'I\'ve entered ID of a picture from NextGen Gallery and thumbnail doesn\'t show.', 'fpw-fct' ) . '<br><strong>' . 
						__( 'Answer:', 'fpw-fct' ) . '</strong> ' . 
						__( 'IDs from NextGen Gallery must be entered with ngg- prefix, so ID 230 should be entered as ngg-230.', 'fpw-fct' ) . '</p>' . 
						'<p style="font-size: larger">' . __( 'More information', 'fpw-fct' ) . '</p>' . 
						'<blockquote><a href="http://fw2s.com/2010/10/14/fpw-category-thumbnails-plugin/" target="_blank">' . __( 'Plugin\'s site', 'fpw-fct' ) . '</a></blockquote>' . 
						'<p style="font-size: larger">' . __( 'Support', 'fpw-fct' ) . '</p>' . 
						'<blockquote><a href="http://wordpress.org/tags/fpw-category-thumbnails?forum_id=10" target="_blank">WordPress</a><br />' . 
						'<a href="http://fw2s.com/support/fpw-category-thumbnails-support/" target="_blank">FWSS</a></blockquote></td>';
			$my_help .= '</tr>';
			$my_help .= '</tbody>';
			$my_help .= '</table>';						

			$contextual_help = $my_help;
?>