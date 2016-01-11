<?php

$posts = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'sponsor'
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
}

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo json_encode($posts);
