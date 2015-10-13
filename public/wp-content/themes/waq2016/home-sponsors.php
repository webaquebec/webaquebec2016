<?php
/*
Template Name: Partenaires
*/
global $post;

$context = Timber::get_context();
$post = new TimberPost();

// Set context
$context = array_merge($context, array(
    'post' => $post,
    'sponsors' =>  Timber::get_posts(array(
      	'posts_per_page'   => -1,
      	'post_type'        => 'sponsor'
    ))
));

Timber::render('home/sponsors.twig', $context);
