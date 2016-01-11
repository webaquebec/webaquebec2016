<?php

$posts = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'benevole'
));

foreach ($posts as $key => $post) {
    $acfs = get_fields($post->ID);
    $posts[$key] = array_merge((array) $post,(array) $acfs);
}

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo json_encode($posts);
