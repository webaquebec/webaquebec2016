<?php

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_images',
		'title' => 'Images',
		'fields' => array (
			array (
				'key' => 'field_5692b35f66e1b',
				'label' => 'Image présentation',
				'name' => 'image_presentation',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_5692b37e66e1c',
				'label' => 'Image grille',
				'name' => 'image_grille',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_5692b38f66e1d',
				'label' => 'Image thumbnail',
				'name' => 'image_thumbnail',
				'type' => 'image',
				'instructions' => 'Image dans le petit cercle',
				'save_format' => 'object',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'speaker',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'sponsor',
					'order_no' => 0,
					'group_no' => 1,
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'benevole',
					'order_no' => 0,
					'group_no' => 2,
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
