<?php
if ( defined( 'WP_UNINSTALL_PLUGIN' ) && get_option( 'fpw_category_thumb_del' ) )  {
	delete_option( 'fpw_category_thumb_del' );
	delete_option( 'fpw_category_thumb_ids' );
}
?>