<?php

$posts = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'device'
));

foreach ($posts as $key => $post) {
    $metas = get_post_meta($post->ID);
    $posts[$key] = array_merge((array) $post,(array) $metas);
}

header('Content-Type: application/json');
echo json_encode($posts);
