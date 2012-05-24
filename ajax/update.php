<?php
		//	AJAX request to update options
		$boxes = $_REQUEST['boxes'];
		$donotover = ( in_array( 'donotover', $boxes ) ) ? true : false;
		$cleanup = ( in_array( 'cleanup', $boxes ) ) ? true : false;
		$abar = ( in_array( 'abar', $boxes ) ) ? true : false;
		$opt = get_option( 'fpw_category_thumb_opt' );
		$opt[ 'clean' ] = $cleanup;
		$opt[ 'donotover' ]	= $donotover;
		$opt[ 'abar' ] = $abar;
		$ok = ( update_option( 'fpw_category_thumb_opt', $opt ) );
		echo '<p><strong>';
		if ( $ok ) {
			$this->pluginOptions = $opt;
			$this->uninstallMaintenance();
			echo __( 'Options updated successfully.', 'fpw-fct' );
		} else {
			echo __( 'No changes. Nothing to update.');
		}
		echo '</strong></p>';
		die();
?>