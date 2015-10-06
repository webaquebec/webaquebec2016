<?php

/**
 * Add the benevole post type
 */
add_action( 'init', 'waq2016_add_benevole_post_type' );
function waq2016_add_benevole_post_type() {
    register_post_type( 'benevole',
        array(
            'labels' => array(
                'name' => __( 'Bénévoles', 'waq2016' ),
                'singular_name' => __( 'Bénévole', 'waq2016' )
            ),
            'rewrite' => array (
                'slug'                => 'benevoles',
                'with_front'          => true,
                'pages'               => false,
                'feeds'               => false,
            ),
            'menu_icon'=>'dashicons-admin-users',
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail')
        )
    );
}

if(function_exists("register_field_group"))
{

}
