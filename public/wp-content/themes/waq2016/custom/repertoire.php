<?php

/**
 * Add the place post type
 */
add_action( 'init', 'waq2016_add_place_post_type' );
function waq2016_add_place_post_type() {
    register_post_type( 'place',
        array(
            'labels' => array(
                'name' => __( 'Places', 'waq2016' ),
                'singular_name' => __( 'Place', 'waq2016' )
            ),
            'rewrite' => array (
                'slug'                => 'places',
                'with_front'          => true,
                'pages'               => false,
                'feeds'               => false,
            ),
            'menu_icon'=>'dashicons-location',
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail')
        )
    );
}

add_filter('get_twig', 'extend_twig_distance');

function extend_twig_distance($twig) {
    /* this is where you can add your own fuctions to twig */
    $twig->addExtension(new Twig_Extension_StringLoader());
    $twig->addFilter('distance', new Twig_Filter_Function('twig_distance'));
    return $twig;
}

function twig_distance($location) {
    return round(lat_lng_distance(46.816743,-71.200461,$location['lat'],$location['lng'],"K"),2);
}

function lat_lng_distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_repertoire',
		'title' => 'Répertoire',
		'fields' => array (
			array (
				'key' => 'field_561c5ab18268d',
				'label' => 'Type',
				'name' => 'type',
				'type' => 'select',
				'choices' => array (
					'resto' => 'Restaurant',
					'hotel' => 'Hotêl',
					'parking' => 'Stationnement',
					'shop' => 'Magasin',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_561398e3d8c03',
				'label' => 'Lien',
				'name' => 'link',
				'type' => 'text',
				'instructions' => '',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_561c5afd8268e',
				'label' => 'Emplacement',
				'name' => 'emplacement',
				'type' => 'google_map',
				'required' => 1,
				'center_lat' => '46.816743',
				'center_lng' => '-71.200461',
				'zoom' => 15,
				'height' => 200,
			),
			array (
				'key' => 'field_561c5b518268f',
				'label' => 'Informations',
				'name' => 'informations',
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
					'value' => 'place',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'permalink',
				1 => 'the_content',
				2 => 'excerpt',
				3 => 'custom_fields',
				4 => 'discussion',
				5 => 'comments',
				6 => 'revisions',
				7 => 'author',
				8 => 'format',
				9 => 'featured_image',
				10 => 'categories',
				11 => 'tags',
				12 => 'send-trackbacks',
			),
		),
		'menu_order' => 0,
	));
}
