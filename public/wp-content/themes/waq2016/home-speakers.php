<?php
/*
Template Name: Conférenciers
*/
global $post;

$context = Timber::get_context();
$post = new TimberPost();

// Set context
$context = array_merge($context, array(
    'post' => $post,
    'speakers' =>  Timber::get_posts(array(
      	'posts_per_page'   => -1,
      	'post_type'        => 'speaker'
    ))
));

Timber::render('speakers.twig', $context);
