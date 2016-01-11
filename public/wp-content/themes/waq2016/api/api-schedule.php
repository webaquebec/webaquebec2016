<?php

$timeslots = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'time_slot',
));

$days = array();

foreach ($timeslots as $key => $slot) {
    $customs = get_post_custom($slot->ID);
    $acfs = get_fields($slot->ID);
    $slot = $timeslots[$key] = array_merge((array) $slot,(array) $customs,(array) $acfs);

    $dayStart = date('Y-m-d\T00:00:00',get_post_meta($slot['ID'],'_conferencer_starts',true));
    $dayEnd = date('Y-m-d\T23:59:59',get_post_meta($slot['ID'],'_conferencer_ends',true));
    if(!array_key_exists($dayStart,$days)){
        $day = array(
            'start' => $dayStart,
            'end' => $dayEnd,
            'blocks' => array()
        );
        $days[$dayStart] = $day;
    }

    $block = array(
        'start' => date('Y-m-d\TH:i:s',get_post_meta($slot['ID'],'_conferencer_starts',true)),
        'end' => date('Y-m-d\TH:i:s',get_post_meta($slot['ID'],'_conferencer_ends',true)),
        'waq_title' => $slot['post_title'],
        'events' => getSessionsForTimeSlot($slot)
    );

    $days[$dayStart]['blocks'][] = $block;
}

foreach ($days as $key => $day) {
    usort($day['blocks'], "sortByDates");
    $days[$key] = $day;
}

usort($days, "sortByDates");

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo json_encode($days);


function sortByDates($a, $b)
{
    if (strtotime($a['start']) == strtotime($b['start'])) {
        return 0;
    }
    return (strtotime($a['start']) < strtotime($b['start'])) ? -1 : 1;
}


function getSessionsForTimeSlot($slot){
    $posts = get_posts(array(
        'posts_per_page'   => -1,
        'post_type'        => 'session',
    	'meta_query' => array(
    		array(
    			'key'     => '_conferencer_time_slot',
    			'value'   => $slot['ID'],
    			'compare' => '=',
    		),
    	),
    ));

    $formatedPosts = array();
    foreach ($posts as $key => $post) {
        $formatedPost = formatSession($post);
        $formatedPost['schedule'] = array(
            'start' => date('Y-m-d\TH:i:s',get_post_meta($slot['ID'],'_conferencer_starts',true)),
            'end' => date('Y-m-d\TH:i:s',get_post_meta($slot['ID'],'_conferencer_ends',true))
        );
        $formatedPosts[] = $formatedPost;
    }
    return $formatedPosts;
}

function getSpeakers($ids){
    if(is_array($ids)){
        $ids = array_map('intval', $ids);
    }
    $posts = get_posts(array(
        'posts_per_page'   => -1,
        'post_type'        => 'speaker',
    	'post__in'         => (is_array($ids) ? $ids : array())
    ));
    $formatedPosts = array();
    foreach ($posts as $key => $post) {
        $acfs = get_fields($post->ID);
        $post = array_merge((array) $post,(array) $acfs);
        $formatedPosts[] = array(
            'name' => $post['post_title'],
            'pic' => (array_key_exists('image_thumbnail',$post) ? $post['image_thumbnail'] : null)
        );
    }
    return $formatedPosts;
}

function getRoom($id){
    $post = get_post($id);
    $acfs = get_fields($post->ID);
    $post = array_merge((array) $post,(array) $acfs);
    return array(
        'id' => $post['ID'],
        'name' => $post['post_title'],
        'color' => ''
    );
}

function formatSession($post){
    $acfs = get_fields($post->ID);
    $customs = array();
    foreach (get_post_custom($post->ID) as $ckey => $value) {
        if (strpos($ckey, '_conferencer_') !== 0) continue;
        $ckey = substr($ckey, 13);
        $customs[$ckey] = @unserialize($value[0]) ? @unserialize($value[0]) : $value[0];
    }
    $post = array_merge((array) $post,(array) $customs,(array) $acfs);

    $room = get_post($post['room']);

    return array(
        'id' => $post['ID'],
        'title' => $post['post_title'],
        'by' => getSpeakers($post['speakers']),
        'room' => getRoom($post['room']),
        'details' => $post['post_content']
    );
}
