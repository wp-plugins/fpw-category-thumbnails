<?php
//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
	die( "Direct access to this script is forbidden!" );

if( !class_exists( 'WP_List_Table' ) )
   	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class fpw_Category_Thumbnails_Table extends WP_List_Table {
    var $map;
    
	//	constructor
	function __construct( $mapArray ) {
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'fpwct_category',    //singular name of the listed records
            'plural'    => 'fpwct-categories',  //plural name of the listed records
            'ajax'      => true		        	//does this table support ajax?
        ) );

        $this->map = $mapArray;
    }

    function _js_vars() {
        $current_screen = get_current_screen();

        $args = array(
            'class'  => get_class( $this ),
            'screen' => array(
                'id'   => $current_screen->id,
                'base' => $current_screen->base,
            )
        );

        printf( "<script type='text/javascript'>list_args = %s;</script>\n", json_encode( $args ) );
    }

    function column_default($item, $column_name){
        switch($column_name){
        	//case 'fpwct_cat_id':
        	case 'fpwct_cat_name':
            case 'fpwct_image_id':
            case 'fpwct_preview':
                return $item[$column_name];
            default:
                return $item[$column_name];
        }
    }
   
	//	special column category (id)	    
	function column_fpwct_cat_name( $item ) {
        
        //Build row actions
        $actions = array(
            'getid'     => sprintf( '<input name="submit-getid" type="submit" value="' . 
									__( 'Get ID', 'fpw-fct' ) . '" class="fpw-fs-button" id="b-get-for-' . 
									$item['fpwct_cat_id'] . '" title="' . 
									__( "get thumbnail's picture ID from media library", "fpw-fct" ) . 
									'" style="color:navy;border:none;padding:0 0 0 0;cursor:pointer">'),
            'author'    => sprintf( '<input name="submit-author" type="submit" value="' . 
									__( 'Author', 'fpw-fct' ) . 
									'" class="fpw-btn-author" title="' . 
									__( "set author's picture as thumbnail", "fpw-fct" ) . 
									'" id="b-author-for-' . $item['fpwct_cat_id'] . 
									'" style="color:navy;border:none;padding:0 0 0 0;cursor:pointer">'),
            'clear'     => sprintf( '<input name="submit-clear" type="submit" style="color:navy;border:none;padding:0 0 0 0;cursor:pointer" ' . 
									'class="fpw-btn-clear" id="b-clear-for-' . 
									$item['fpwct_cat_id'] . '" title="' . 
									__( 'clear Image ID and Preview fields', 'fpw-fct' ) . 
									'" value="' . __( 'Clear', 'fpw-fct' ) . '">'),
            'refresh'	=> sprintf( '<input name="submit-refresh" type="submit" style="color:navy;border:none;padding:0 0 0 0;cursor:pointer" '. 
									'class="fpw-btn-refresh" id="b-refresh-for-' . 
									$item['fpwct_cat_id'] . '" title="' .
									__( 'refresh Preview field after manual ' . 
									'changes to Image ID field', 'fpw-fct' ) .
									'" value="' . __( 'Refresh', 'fpw-fct' ) . '">'));
        
        //Return the cat_name contents
        return sprintf('<strong>%1$s</strong> (<strong>%2$s</strong>) <span class="hide-if-no-js">%3$s</span>',
            /*$1%s*/ $item['fpwct_cat_name'],
            /*$2%s*/ $item['fpwct_cat_id'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }

	//	get all columns
    function get_columns(){
        $columns = array(
            'fpwct_cat_name'	=> __( 'Category (ID)', 'fpw-fct' ),
            'fpwct_image_id'	=> __( 'Image ID', 'fpw-fct' ),
            'fpwct_preview'		=> __( 'Preview', 'fpw-fct' ),
        );
        return $columns;
    }
    
	//	get sortable columns - empty
    function get_sortable_columns() {
        $sortable_columns = array();
        return $sortable_columns;
    }
	
	//	preparation of items
	function prepare_items() {
		global $current_user;
		
		get_currentuserinfo(); 
        
        //	how many records per page to show
		$per_page = parent::get_items_per_page( 'edit_category_per_page' );

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        
		//	column headers
        $this->_column_headers = array( $columns, $hidden, $sortable );
        
		//	get data
        $data = $this->map;
                
		//	get current page number
        $current_page = $this->get_pagenum();
        
		//	get total number of rows
        $total_items = count( $data );
        
		//	prepare items for current page
        $data = array_slice( $data, ( ( $current_page-1 ) * $per_page ), $per_page );
        $this->items = $data;
        
        //	prepare pagination
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page ) 
        ) );
    }
    
    //	our extra buttons
	function extra_tablenav( $which ) {
		global $fpw_CT;
		
		if ( $which == 'top' ) {
			echo '<input title="' . 
				 __( 'write modified options and mapping to the database', 'fpw-fct' ) . 
		 	 	 '" id="update" class="button-primary fpw-submit" type="submit" name="submit-update" value="' . __( 'Update', 'fpw-fct' ) . '" /> ';
			echo '<input title="' . 
	 			 __( 'add post thumbnail to every existing post / page belonging to the category which has thumbnail id mapped to', 'fpw-fct' ) . 
	 	 	 	 '" id="apply" class="button-primary fpw-submit" type="submit" name="submit-apply" value="' . __( 'Apply Mapping', 'fpw-fct' ) . '" /> ';
			echo '<input title="' . 
				 __( 'remove thumbnails from all existing posts / pages regardless of the category', 'fpw-fct' ) . 
	 			 '" id="remove" class="button-primary fpw-submit" type="submit" name="submit-remove" value="' . __( 'Remove Thumbnails', 'fpw-fct' ) . '" />';
			echo '<input id="buttonPressed" type="hidden" value="" name="buttonPressed" />';
			
			if ( !( 'en_US' == get_locale() ) && ( ( 'available' == $fpw_CT->translationStatus ) || ( 'not_exist' == $fpw_CT->translationStatus ) ) )  
				echo ' <input title="' . 
					 __( 'download language file for current version', 'fpw-fct' ) . 
					 '" id="language" class="button-primary fpw-submit" type="submit" name="submit-language" value="' . 
					 __( 'Get Language File', 'fpw-fct' ) . '" />';
		} 
	}
}

?>