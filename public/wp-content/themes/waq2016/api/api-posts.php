<?php

$posts = get_posts(array(
    'posts_per_page'   => -1,
    'post_type'        => 'post'
));

foreach ($posts as $key => $post) {
    $acfs = get_fields($post->ID);
    $posts[$key] = array_merge((array) $post,(array) $acfs);
    $posts[$key]['post_author'] = getUser($posts[$key]['post_author']);
}

function getUser($id){
    $user = get_user_meta($id);
    return array(
        'name' => get_the_author_meta('user_nicename',$id),
        'pic' => get_avatar($id, 512)
    );
}

header('Content-Type: application/json');
echo json_encode($posts);
