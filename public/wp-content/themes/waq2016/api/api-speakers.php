<?php

$posts = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'speaker'
));

foreach ($posts as $key => $post) {
    $acfs = get_fields($post->ID);
    $posts[$key] = array_merge((array) $post,(array) $acfs);
}

header('Content-Type: application/json');
echo json_encode($posts);
