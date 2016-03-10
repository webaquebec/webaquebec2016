<?php

$postArgs = array(
    'post_status' => 'publish',
    'post_title' => $_POST['uuid'],
    'post_type' => 'device',
    'post_date' => date("Y-m-d H:i:s")
);

if ($id = waq2016_device_exists($_POST['uuid'])) {
    //Post arguments generaux
    $postArgs['ID'] = $id;
}

if (isset($postArgs['ID'])) {
    if(array_key_exists('location',$_POST)){
        add_post_meta($id, 'location', $_POST['location'], true);
    }
    if(array_key_exists('schedule',$_POST)){
        add_post_meta($id, 'schedule', $_POST['schedule'], true);
    }
} else {
    $id = wp_insert_post($postArgs);
    if(array_key_exists('location',$_POST)){
        add_post_meta($id, 'location', $_POST['location'], true);
    }
    if(array_key_exists('schedule',$_POST)){
        add_post_meta($id, 'schedule', $_POST['schedule'], true);
    }
}

function waq2016_device_exists($identifier) {
    global $wpdb;
    $post = $wpdb->get_row("SELECT ID FROM " . $wpdb->posts . " WHERE post_type IN ('device') AND post_title='" . $identifier . "'");
    return  ($post) ? $post->ID : false;
}
