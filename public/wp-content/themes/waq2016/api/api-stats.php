<?php

function waq2016_get_devices_count_for_schedule($scheduleId){
    global $wpdb;

    $querystr = "
    SELECT wposts.*
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta

    WHERE wposts.ID = wpostmeta.post_id
    AND (wpostmeta.meta_key = 'schedule' AND (wpostmeta.meta_value LIKE '%,".$scheduleId.",%' OR wpostmeta.meta_value LIKE '".$scheduleId.",%' OR wpostmeta.meta_value LIKE '%,".$scheduleId."' OR wpostmeta.meta_value LIKE '".$scheduleId."'))
    AND wposts.post_type = 'device'
    AND wposts.post_status = 'publish'
    ";
    return count($wpdb->get_results($querystr));
}

function waq2016_get_devices_count_for_room($roomId){
    global $wpdb;

    $querystr = "
    SELECT wposts.*
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta

    WHERE wposts.ID = wpostmeta.post_id
    AND (wpostmeta.meta_key = 'location' AND (wpostmeta.meta_value LIKE '%,".$roomId.",%' OR wpostmeta.meta_value LIKE '".$roomId.",%' OR wpostmeta.meta_value LIKE '%,".$roomId."' OR wpostmeta.meta_value LIKE '".$roomId."'))
    AND wposts.post_type = 'device'
    AND wposts.post_status = 'publish'
    ";
    return count($wpdb->get_results($querystr));
}

$roomPosts = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'room'
));

$rooms = array();
foreach ($roomPosts as $key => $room) {
    $roomStats = array(
        'id' => $room->ID,
        'name' => $room->post_title,
        'devices' => waq2016_get_devices_count_for_room($room->ID)
    );
    $rooms[] = $roomStats;
}

$eventPosts = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'session'
));

$events = array();
foreach ($eventPosts as $key => $event) {
    $eventStats = array(
        'id' => $event->ID,
        'name' => $event->post_title,
        'devices' => waq2016_get_devices_count_for_schedule($event->ID)
    );
    $events[] = $eventStats;
}

$stats = array(
    'rooms' => $rooms,
    'events' => $events
);

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo json_encode($stats);
