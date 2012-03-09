<?php
/*
Plugin Name: FPW Category Thumbnails
Description: Sets post/page thumbnail based on category.
Plugin URI: http://fw2s.com/2010/10/14/fpw-category-thumbnails-plugin/
Version: 1.4.6
Author: Frank P. Walentynowicz
Author URI: http://fw2s.com/

Copyright 2011 Frank P. Walentynowicz (email : frankpw@fw2s.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( is_admin() ) {
	require_once dirname(__FILE__) . '/classes/fpw-category-thumbnails.class.php';
	new fpwCategoryThumbnails( dirname(__FILE__), '1.4.6' );
}
?>