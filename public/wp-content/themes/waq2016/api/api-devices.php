<?php

$postdata = file_get_contents("php://input");
$device = json_decode($postdata);

$postArgs = array(
    'post_status' => 'publish',
    'post_title' => $device->uuid,
    'post_type' => 'device',
    'post_date' => date("Y-m-d H:i:s")
);

if ($id = waq2016_device_exists($device->uuid)) {
    //Post arguments generaux
    $postArgs['ID'] = $id;
}

if (isset($postArgs['ID'])) {
} else {
    $id = wp_insert_post($postArgs);
}

if(isset($device->location)){
    if(get_post_meta($id, 'location', true)){
        update_post_meta($id, 'location', $device->location);
    }
    else{
        add_post_meta($id, 'location', $device->location, true);
    }
}

if(isset($device->schedule)){
    if(get_post_meta($id, 'schedule', true)){
        update_post_meta($id, 'schedule', $device->schedule);
    }
    else{
        add_post_meta($id, 'schedule', $device->schedule, true);
    }
}

function waq2016_device_exists($identifier) {
    global $wpdb;
    $post = $wpdb->get_row("SELECT ID FROM " . $wpdb->posts . " WHERE post_type IN ('device') AND post_title='" . $identifier . "'");
    return  ($post) ? $post->ID : false;
}
