<?php

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_session-hors-conferences',
		'title' => 'Session hors-conférences',
		'fields' => array (
			array (
				'key' => 'field_5696fa2612a5c',
				'label' => 'Session hors-conférences',
				'name' => 'is_not_session',
				'type' => 'true_false',
				'message' => '',
				'default_value' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'session',
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
}
