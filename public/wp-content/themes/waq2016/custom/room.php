<?php

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_salle',
		'title' => 'Salle',
		'fields' => array (
			array (
				'key' => 'field_56ca32944f336',
				'label' => 'Couleur',
				'name' => 'color',
				'type' => 'select',
				'required' => 1,
				'choices' => array (
					'purple' => 'purple',
					'blue' => 'blue',
					'red' => 'red',
					'green' => 'green',
					'orange' => 'orange',
					'yellow' => 'yellow',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'room',
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
