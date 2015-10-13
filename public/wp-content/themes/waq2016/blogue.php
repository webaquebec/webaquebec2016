<?php
/*
Template Name: Blogue
*/
global $post;

$context = Timber::get_context();
$post = new TimberPost();

// Set context
$context = array_merge($context, array(
    'posts' =>  Timber::get_posts(array(
      	'posts_per_page'   => 10
    ))
));

Timber::render('posts/list.twig', $context);
