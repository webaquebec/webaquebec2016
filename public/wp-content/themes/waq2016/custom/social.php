<?php

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_medias-sociaux',
		'title' => 'Médias Sociaux',
		'fields' => array (
			array (
				'key' => 'field_5692b132bfcc9',
				'label' => 'Twitter',
				'name' => 'twitter',
				'type' => 'text',
				'instructions' => 'Seulement le nick et ne pas mettre le @',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5692b1aebfcca',
				'label' => 'Facebook',
				'name' => 'facebook',
				'type' => 'text',
				'instructions' => 'Mettre toute l\'adresse du profil',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5692b1cabfccb',
				'label' => 'LinkedIn',
				'name' => 'linkedin',
				'type' => 'text',
				'instructions' => 'Mettre toute l\'adresse du profil',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5692b1dbbfccc',
				'label' => 'Instagram',
				'name' => 'instagram',
				'type' => 'text',
				'instructions' => 'Seulement le nick et ne pas mettre le @',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5692b250bfccd',
				'label' => 'Site Web',
				'name' => 'site_web',
				'type' => 'text',
				'instructions' => 'Mettre l\'adresse avec le http://',
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
