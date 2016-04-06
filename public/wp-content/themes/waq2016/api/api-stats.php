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

    $currentTime = time();
    $fifteen = $currentTime - (15*60*1000);
    $thirty = $currentTime - (30*60*1000);
    $fortyfive = $currentTime - (45*60*1000);
    $hour = $currentTime - (60*60*1000);

    $querystr = "
    SELECT wposts.*
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta

    WHERE wposts.ID = wpostmeta.post_id
    AND (wpostmeta.meta_key = 'location' AND (wpostmeta.meta_value LIKE '%,".$roomId.",%' OR wpostmeta.meta_value LIKE '".$roomId.",%' OR wpostmeta.meta_value LIKE '%,".$roomId."' OR wpostmeta.meta_value LIKE '".$roomId."'))
    AND wposts.post_type = 'device'
    AND wposts.post_status = 'publish'
    ";
    $allCount = count($wpdb->get_results($querystr));

    $querystr = "
    SELECT wposts.*
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta

    WHERE wposts.ID = wpostmeta.post_id
    AND (wpostmeta.meta_key = 'location' AND (wpostmeta.meta_value LIKE '%,".$roomId.",%' OR wpostmeta.meta_value LIKE '".$roomId.",%' OR wpostmeta.meta_value LIKE '%,".$roomId."' OR wpostmeta.meta_value LIKE '".$roomId."'))
    AND (wpostmeta.meta_key = 'lastUpdate' AND wpostmeta.meta_value < ".$fifteen.")
    AND wposts.post_type = 'device'
    AND wposts.post_status = 'publish'
    ";
    $count15 = count($wpdb->get_results($querystr));

    $querystr = "
    SELECT wposts.*
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta

    WHERE wposts.ID = wpostmeta.post_id
    AND (wpostmeta.meta_key = 'location' AND (wpostmeta.meta_value LIKE '%,".$roomId.",%' OR wpostmeta.meta_value LIKE '".$roomId.",%' OR wpostmeta.meta_value LIKE '%,".$roomId."' OR wpostmeta.meta_value LIKE '".$roomId."'))
    AND (wpostmeta.meta_key = 'lastUpdate' AND wpostmeta.meta_value < ".$thirty.")
    AND wposts.post_type = 'device'
    AND wposts.post_status = 'publish'
    ";
    $count30 = count($wpdb->get_results($querystr));

    $querystr = "
    SELECT wposts.*
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta

    WHERE wposts.ID = wpostmeta.post_id
    AND (wpostmeta.meta_key = 'location' AND (wpostmeta.meta_value LIKE '%,".$roomId.",%' OR wpostmeta.meta_value LIKE '".$roomId.",%' OR wpostmeta.meta_value LIKE '%,".$roomId."' OR wpostmeta.meta_value LIKE '".$roomId."'))
    AND (wpostmeta.meta_key = 'lastUpdate' AND wpostmeta.meta_value < ".$fortyfive.")
    AND wposts.post_type = 'device'
    AND wposts.post_status = 'publish'
    ";
    $count45 = count($wpdb->get_results($querystr));

    $querystr = "
    SELECT wposts.*
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta

    WHERE wposts.ID = wpostmeta.post_id
    AND (wpostmeta.meta_key = 'location' AND (wpostmeta.meta_value LIKE '%,".$roomId.",%' OR wpostmeta.meta_value LIKE '".$roomId.",%' OR wpostmeta.meta_value LIKE '%,".$roomId."' OR wpostmeta.meta_value LIKE '".$roomId."'))
    AND (wpostmeta.meta_key = 'lastUpdate' AND wpostmeta.meta_value < ".$hour.")
    AND wposts.post_type = 'device'
    AND wposts.post_status = 'publish'
    ";
    $count60 = count($wpdb->get_results($querystr));

    return array('all'=>$allCount,'15'=>$count15,'30'=>$count30,'45'=>$count45,'60'=>$count60);
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
