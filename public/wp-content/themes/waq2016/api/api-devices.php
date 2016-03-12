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
var_dump('location');
var_dump($device->location);

if(isset($device->location)){
    update_post_meta($id, 'location', ($device->location ? $device->location : ""));
}

var_dump('schedule');
var_dump($device->schedule);

if(isset($device->schedule)){
    update_post_meta($id, 'schedule', ($device->schedule ? $device->schedule : ""));
}

$post = get_post($id);
$metas = get_post_meta($post->ID);
$post = array_merge((array) $post,(array) $metas);
header('Content-Type: application/json');
echo json_encode($post);

function waq2016_device_exists($identifier) {
    global $wpdb;
    $post = $wpdb->get_row("SELECT ID FROM " . $wpdb->posts . " WHERE post_type IN ('device') AND post_title='" . $identifier . "'");
    return  ($post) ? $post->ID : false;
}
