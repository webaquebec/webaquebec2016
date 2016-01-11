<?php

$posts = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'speaker'
));

foreach ($posts as $key => $post) {
    $acfs = get_fields($post->ID);
    $customs = array();
    foreach (get_post_custom($post->ID) as $ckey => $value) {
        if (strpos($ckey, '_conferencer_') !== 0) continue;
        $ckey = substr($ckey, 13);
        $customs[$ckey] = @unserialize($value[0]) ? @unserialize($value[0]) : $value[0];
    }
    $posts[$key] = array_merge((array) $post,(array) $customs,(array) $acfs);
    $posts[$key]['company'] = ($posts[$key]['company'] ? getCompany($posts[$key]['company']) : '');
}

function getCompany($id){
    $post = get_post($id);
    $acfs = get_fields($post->ID);
    $post = array_merge((array) $post,(array) $acfs);
    return $post;
}

header('Content-Type: application/json');
echo json_encode($posts);
