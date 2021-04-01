<?php
/**
 * Plugin Name:       WordPress CPT
 * Plugin URI:        https://habibur.dev
 * Description:       Simple wordpress custom post type plugin for practice purpose.
 * Version:           1.0
 * Author:            Habibur
 * Author URI:        https://profiles.wordpress.org/habiburdev/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wpcpt
 */

 // Register custom post type
 function wpcpt_laptop_init() {
    $labels = array(
        'name'                  => _x( 'Laptops', 'wpcpt' ),
        'singular_name'         => _x( 'Laptop', 'Post type singular name', 'wpcpt' ),
        'menu_name'             => _x( 'Laptops', 'Admin Menu text', 'wpcpt' ),
        'name_admin_bar'        => _x( 'Laptop', 'Add New on Toolbar', 'wpcpt' ),
        'add_new'               => __( 'Add New', 'wpcpt' ),
        'add_new_item'          => __( 'Add New Laptop', 'wpcpt' ),
        'new_item'              => __( 'New Laptop', 'wpcpt' ),
        'edit_item'             => __( 'Edit Laptop', 'wpcpt' ),
        'view_item'             => __( 'View Laptop', 'wpcpt' ),
        'all_items'             => __( 'All Laptops', 'wpcpt' ),
        'search_items'          => __( 'Search Laptops', 'wpcpt' ),
        'parent_item_colon'     => __( 'Parent Laptops:', 'wpcpt' ),
        'not_found'             => __( 'No Laptops found.', 'wpcpt' ),
        'not_found_in_trash'    => __( 'No Laptops found in Trash.', 'wpcpt' ),
        'featured_image'        => _x( 'Laptop Featured Image', 'wpcpt' ),
        'set_featured_image'    => _x( 'Set Featured image', 'wpcpt' ),
        'remove_featured_image' => _x( 'Remove Featured image', 'wpcpt' ),
        'use_featured_image'    => _x( 'Use as Featured image', 'wpcpt' ),
        'archives'              => _x( 'Laptop archives', 'wpcpt' ),
        'insert_into_item'      => _x( 'Insert into Laptop', 'wpcpt' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this Laptop', 'wpcpt' ),
        'filter_items_list'     => _x( 'Filter Laptops list','wpcpt' ),
        'items_list_navigation' => _x( 'Laptops list navigation', 'wpcpt' ),
        'items_list'            => _x( 'Laptops list', 'wpcpt' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'laptop' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 10,
        'menu_icon'          => 'dashicons-laptop',
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
        'taxonomies'         => array( 'category', 'post_tag' ),
    );
 
    register_post_type( 'laptop', $args );
}
 
add_action( 'init', 'wpcpt_laptop_init' );


//Register custom Meta Box
function wpcpt_metabox_register(){

    add_meta_box( 'meta_box_id', 'Laptop Details', 'details_url_callback', 'laptop', 'side', 'high' );
    add_meta_box( 'author_box_id', 'Choose Author', 'choose_author_callback', 'laptop', 'side', 'high' );

}

add_action( 'add_meta_boxes', 'wpcpt_metabox_register' );

function details_url_callback($post){
    ?>
    <p>
        <label for="details">Details URL</label>
        <?php $url = get_post_meta( $post->ID, 'laptop_details_value', true ) ?>
        <input type="text" id="details" value="<?php echo $url; ?>" name="laptop_details" placeholder="https://example.com/laptop">
    </p>
    <p>
        <label for="processor">Processor</label>
        <?php $processor = get_post_meta( $post->ID, 'laptop_processor_value', true ) ?>
        <input type="text" id="processor" value="<?php echo $processor; ?>" name="laptop_processor" placeholder="i3/i5/i7/i7">
    </p>
    <p>
        <label for="processor">Processor Generation</label>
        <?php $processor_gen = get_post_meta( $post->ID, 'processor_gen_value', true ) ?>
        <input type="text" id="processor_gen" value="<?php echo $processor_gen; ?>" name="processor_gen" placeholder="e.g 10th, 11th">
    </p>
    <?php
}


//save meta data
function save_detail_values( $post_id, $post ){

    $laptop_details = isset($_POST['laptop_details']) ? $_POST['laptop_details'] : '';
    $laptop_processor = isset($_POST['laptop_processor']) ? $_POST['laptop_processor'] : '';
    $processor_gen = isset($_POST['processor_gen']) ? $_POST['processor_gen'] : '';

    update_post_meta( $post_id, 'laptop_details_value', $laptop_details );
    update_post_meta( $post_id, 'laptop_processor_value', $laptop_processor );
    update_post_meta( $post_id, 'processor_gen_value', $processor_gen );

}

add_action( 'save_post', 'save_detail_values', 10, 2 );


// custom column, 
/**
 * https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
 * https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
 */

function laptop_custom_columns($columns){

    $columns = array(
        'cb'         => '<input type="checkbox">',
        'title'      => 'Laptop Model',
        'processor'  => 'Processor',
        'generation' => 'Generation',
        'date'       => 'Date',
    );

    return $columns;

}
add_action( 'manage_laptop_posts_columns', 'laptop_custom_columns' );


function laptop_custom_columns_data( $column, $post_id ){

    switch( $column ){
        
        case 'processor':
            $lp_processor = get_post_meta( $post_id, 'laptop_processor_value', true );
            echo $lp_processor;
            break;
        case 'generation':
            $lp_generation = get_post_meta( $post_id, 'processor_gen_value', true );
            echo $lp_generation;
            break;

    }

}


// Custom columns make sortable
add_action( 'manage_laptop_posts_custom_column', 'laptop_custom_columns_data', 10, 2 );

function laptop_sortable_custom_columns( $columns ) {
    $columns['processor'] = 'processor';
    $columns['generation'] = 'generation';
    return $columns;
}
add_filter( 'manage_edit-laptop_sortable_columns', 'laptop_sortable_custom_columns' );

function choose_author_callback($post){
    ?>
    <div>
        <label>Select Author</label>
        <select name="laptop_author">
            <?php 

            $authors = get_users( 
                array(
                    'role' => 'author'
                ));

            $saved_author = get_post_meta( $post->ID, 'laptop_author_value', true );

            foreach( $authors as $index => $author ){
                $selected = '';
                if( $saved_author == $author->ID ){
                    $selected = 'selected="selected"';
                }

            ?>
            <option value="<?php echo $author->ID; ?>" <?php echo $selected; ?>><?php echo $author->display_name; ?></option>
            <?php
            }
            ?>
            
        </select>
    </div>
    <?php
}


function save_author_value( $post_id, $post ){

    $lp_author_id = isset( $_REQUEST['laptop_author'] ) ? intval( $_REQUEST['laptop_author'] ) : '';

    update_post_meta( $post_id, 'laptop_author_value', $lp_author_id );

}

add_action( 'save_post', 'save_author_value', 10, 2 );