<?php
/*
Template Name: Bénévoles
*/
global $post;

$context = Timber::get_context();
$post = new TimberPost();

// Set context
$context = array_merge($context, array(
    'post' => $post,
    'benevoles' =>  Timber::get_posts(array(
      	'posts_per_page'   => -1,
      	'post_type'        => 'benevole'
    ))
));

Timber::render('home/benevoles.twig', $context);
