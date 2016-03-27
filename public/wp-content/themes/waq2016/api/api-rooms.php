<?php

$posts = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'room'
));

foreach ($posts as $key => $post) {
    $acfs = get_fields($post->ID);
    $customs = array();
    foreach (get_post_custom($post->ID) as $ckey => $value) {
        if (strpos($ckey, '_conferencer_') !== 0) continue;
        $ckey = substr($ckey, 13);
        $customs[$ckey] = @unserialize($value[0]) ? @unserialize($value[0]) : $value[0];
    }

    if(isset($_GET['withSessions']) && $_GET['withSessions'] == '1'){
        $sessions = get_posts(array(
            'posts_per_page'   => -1,
            'post_type'        => 'session',
        	'meta_query' => array(
        		array(
        			'key'     => '_conferencer_room',
        			'value'   => $post->ID,
        			'compare' => '=',
        		),
        	),
        ));

        $sessions = array_map("formatSession", (array) $sessions);

        usort($sessions,'sortSessions');

        $sessionsAdd = array(
            'sessions' => $sessions
        );
    }else{
        $sessionsAdd = array();
    }

    $posts[$key] = array_merge((array) $post,(array) $customs,(array) $acfs, (array) $sessionsAdd);
}

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo json_encode($posts);

function sortSessions($a,$b){
    return strtotime($a['timeslot']['start'])-strtotime($b['timeslot']['start']);
}

function getSpeakers($ids){
    $formatedPosts = array();
    if(is_array($ids)){
        $ids = array_map('intval', $ids);
        $posts = get_posts(array(
            'posts_per_page'   => -1,
            'post_type'        => 'speaker',
        	'post__in'         => (is_array($ids) ? $ids : array())
        ));
        foreach ($posts as $key => $post) {
            $acfs = get_fields($post->ID);
            $post = array_merge((array) $post,(array) $acfs);
            $formatedPosts[] = array(
                'name' => $post['post_title'],
                'pic' => (array_key_exists('image_thumbnail',$post) ? $post['image_thumbnail'] : null)
            );
        }
    }
    return $formatedPosts;
}

function getRoom($id){
    if(empty($id)){
        return null;
    }
    $post = get_post($id);
    $acfs = get_fields($post->ID);
    $post = array_merge((array) $post,(array) $acfs);
    return array(
        'id' => $post['ID'],
        'name' => $post['post_title'],
        'color' => (array_key_exists('color',$post) ? $post['color'] : 'grey')
    );
}

function getTrack($id){
    if(empty($id)){
        return null;
    }
    $post = get_post($id);
    $acfs = get_fields($post->ID);
    $post = array_merge((array) $post,(array) $acfs);
    return array(
        'id' => $post['ID'],
        'name' => $post['post_title']
    );
}

function getTimeslot($id){
    if(empty($id)){
        return null;
    }
    $post = get_post($id);
    $acfs = get_fields($post->ID);
    $post = array_merge((array) $post,(array) $acfs);
    return array(
        'id' => $post['ID'],
        'name' => $post['post_title'],
        'start' => date('Y-m-d\TH:i:s',get_post_meta($id,'_conferencer_starts',true)),
        'end' => date('Y-m-d\TH:i:s',get_post_meta($id,'_conferencer_ends',true))
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
        'timeslot' => getTimeslot($post['time_slot']),
        'track' => getTrack($post['track']),
        'details' => (array_key_exists('description',$post) && !empty($post['description']) ? $post['description'] : $post['post_content'])
    );
}
