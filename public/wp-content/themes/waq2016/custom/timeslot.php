<?php

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_time-slot',
		'title' => 'Time Slot',
		'fields' => array (
			array (
				'key' => 'field_56ca38f7543ea',
				'label' => 'Special',
				'name' => 'is_special',
				'type' => 'true_false',
				'instructions' => 'Cocher si Pause, 5 à 7, etc.',
				'message' => '',
				'default_value' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'time_slot',
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
