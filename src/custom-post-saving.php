<?php

/**
* Set Advanced Custom Fields to Lite mode, so it does not appear
* in the WordPress Administration Menu
*/
//define( 'ACF_LITE', true );

class WPTutsCRM {


	/**
	 * Registers a Custom Post Type called contact
	 */
	function register_custom_post_type() {
	    register_post_type( 'contact', array(
	        'labels' => array(
	            'name'               => _x( 'Contacts', 'post type general name', 'tuts-crm' ),
	            'singular_name'      => _x( 'Contact', 'post type singular name', 'tuts-crm' ),
	            'menu_name'          => _x( 'Contacts', 'admin menu', 'tuts-crm' ),
	            'name_admin_bar'     => _x( 'Contact', 'add new on admin bar', 'tuts-crm' ),
	            'add_new'            => _x( 'Add New', 'contact', 'tuts-crm' ),
	            'add_new_item'       => __( 'Add New Contact', 'tuts-crm' ),
	            'new_item'           => __( 'New Contact', 'tuts-crm' ),
	            'edit_item'          => __( 'Edit Contact', 'tuts-crm' ),
	            'view_item'          => __( 'View Contact', 'tuts-crm' ),
	            'all_items'          => __( 'All Contacts', 'tuts-crm' ),
	            'search_items'       => __( 'Search Contacts', 'tuts-crm' ),
	            'parent_item_colon'  => __( 'Parent Contacts:', 'tuts-crm' ),
	            'not_found'          => __( 'No contacts found.', 'tuts-crm' ),
	            'not_found_in_trash' => __( 'No contacts found in Trash.', 'tuts-crm' ),
	        ),

	        // Frontend
	        'has_archive'        => false,
	        'public'             => false,
	        'publicly_queryable' => false,

	        // Admin
	        'capability_type' => 'post',
	        'menu_icon'     => 'dashicons-businessman',
	        'menu_position' => 10,
	        'query_var'     => true,
	        'show_in_menu'  => true,
	        'show_ui'       => true,
	    ) );
	}

	/**
	* Adds table columns to the Contacts WP_List_Table
	*
	* @param array $columns Existing Columns
	* @return array New Columns
	*/
	function add_table_columns( $columns ) {

	   	$columns['first_name'] = __( 'First name', 'tuts-crm' );
	   	$columns['last_name'] = __( 'Last name', 'tuts-crm' );
	   	$columns['e-mail'] = __( 'Email', 'tuts-crm' );
	   	$columns['country'] = __( 'Country', 'tuts-crm' );
	   	$columns['debate_boolean'] = __('Do you want to debate ?', 'tuts-crm');
        $columns['debate_explanation'] = __('Which theme do you want to talk about ?', 'tuts-crm');

	    return $columns;

	}

	/**
	* Outputs our Contact custom field data, based on the column requested
	*
	* @param string $columnName Column Key Name
	* @param int $post_id Post ID
	*/
	function output_table_columns_data( $columnName, $post_id ) {
	    echo get_field( $columnName, $post_id );
	}

	/**
	* Defines which Contact columsn are sortable
	*
	* @param array $columns Existing sortable columns
	* @return array New sortable columns
	*/
	function define_sortable_table_columns( $columns ) {

	    $columns['e-mail'] = 'e-mail';
	    $columns['first_name'] = 'first_name';
	    $columns['last_name'] = 'last_name';
	    $columns['country'] = 'country';
	    $columns['debate_boolean'] = 'Debate ?';
	    return $columns;

	}

	/**
	* Inspect the request to see if we are on the Contacts WP_List_Table and attempting to
	* sort by email address or phone number.  If so, amend the Posts query to sort by
	* that custom meta key
	*
	* @param array $vars Request Variables
	* @return array New Request Variables
	*/
	function orderby_sortable_table_columns( $vars ) {

	    // Don't do anything if we are not on the Contact Custom Post Type
	    if ( 'contact' != $vars['post_type'] ) return $vars;

	    // Don't do anything if no orderby parameter is set
	    if ( ! isset( $vars['orderby'] ) ) return $vars;

	    // Check if the orderby parameter matches one of our sortable columns
	    if ( $vars['orderby'] == 'e-mail' OR
	        $vars['orderby'] == 'first_name' OR
	        $vars['orderby'] == 'last_name' OR
	        $vars['orderby'] == 'country'
	         ) {
	        // Add orderby meta_value and meta_key parameters to the query
	        $vars = array_merge( $vars, array(
	            'meta_key' => $vars['orderby'],
	            'orderby' => 'meta_value',
	        ));
	    }

	    return $vars;

	}

    /**
     * Constructor. Called when plugin is initialised
     */
    function __construct() {
	    add_action( 'init', array( $this, 'register_custom_post_type' ) );
   		add_filter( 'manage_edit-contact_columns', array( $this, 'add_table_columns' ) );
   		add_action( 'manage_contact_posts_custom_column', array( $this, 'output_table_columns_data'), 10, 2 );
   		add_filter( 'manage_edit-contact_sortable_columns', array( $this, 'define_sortable_table_columns') );
   		add_filter( 'manage_edit-contact_sortable_columns', array( $this, 'define_sortable_table_columns') );
	}



}

$wpTutsCRM = new WPTutsCRM;

?>