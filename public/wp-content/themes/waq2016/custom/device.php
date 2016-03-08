<?php

/**
 * Add the device post type
 */
add_action( 'init', 'waq2016_add_device_post_type' );
function waq2016_add_device_post_type() {
 register_post_type( 'device',
     array(
         'labels' => array(
             'name' => __( 'Devices', 'waq2016' ),
             'singular_name' => __( 'Devices', 'waq2016' )
         ),
         'rewrite' => array (
             'slug'                => 'devices',
             'with_front'          => false,
             'pages'               => false,
             'feeds'               => false,
         ),
         'menu_icon'=>'dashicons-admin-users',
         'public' => true,
         'has_archive' => true,
         'supports' => array('title')
     )
 );
}

add_action( 'wp_ajax_nopriv_device_sub', 'waq2016_sub_device');
add_action( 'wp_ajax_device_sub', 'waq2016_sub_device');
function waq2016_sub_device() {
    if (!isset($_POST['identifier']) && !isset($_POST['type'])) {
        wp_die(__('Désolé, un indentifiant et un type sont requis.'));
    }

    $device_id = $_POST['identifier'];
    $postArgs = array(
        'post_status' => 'publish',
        'post_title' => $device_id,
        'post_type' => 'device',
    );

    //Vérification qu'il n'existe pas deja une subscription avec ce courriel
    if ($id = waq2016_already_sub_device($device_id)) {
        //Post arguments generaux
        $postArgs['ID'] = $id;
    }

    //Création de la subscription
    if (isset($postArgs['ID'])) {
        $id = $postArgs['ID'];
        wp_update_post($postArgs);
    } else {
        $id = wp_insert_post($postArgs);
    }

    echo $id;

    exit;
}

function waq2016_already_sub_device($identifier) {
    global $wpdb;
    $post = $wpdb->get_row("SELECT ID FROM " . $wpdb->posts . " WHERE post_type IN ('device') AND post_title='" . $identifier . "'");
    return  ($post) ? $post->ID : false;
}
