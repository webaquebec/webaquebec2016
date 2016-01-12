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
    register_field_group(array (
		'id' => 'acf_linked_author',
		'title' => 'Auteur',
		'fields' => array (
			array (
				'key' => 'field_56956a6f5242c',
				'label' => 'Auteur lié',
				'name' => 'linked_author',
				'type' => 'user',
				'role' => array (
					0 => 'all',
				),
				'field_type' => 'select',
				'allow_null' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'benevole',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));

	register_field_group(array (
		'id' => 'acf_informations',
		'title' => 'Informations',
		'fields' => array (
			array (
				'key' => 'field_56956b5140287',
				'label' => 'Comité',
				'name' => 'committee',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'benevole',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
