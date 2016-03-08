<?php

$args = array(
    'posts_per_page'   => 10,
    'post_type'        => 'feed_item'
);

if(isset($_GET['olderThan'])){
    $args['date_query'] = array(
		array(
			'before'     => $_GET['olderThan'],
			'inclusive' => false,
		),
	);
}
else if(isset($_GET['newerThan'])){
    $args['date_query'] = array(
		array(
			'after'     => $_GET['newerThan'],
			'inclusive' => false,
		),
	);
}

$posts = get_posts($args);

foreach ($posts as $key => $post) {
    $posts[$key] = (array) $post;
    $posts[$key]['details'] = get_post_meta ($post->ID,'feed_details', true );
}

header("Access-Control-Request-Headers:origin, content-type, accept");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo json_encode($posts);
