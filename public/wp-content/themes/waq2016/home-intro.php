<?php
/*
Template Name: Introduction
*/
global $post;

$context = Timber::get_context();
$post = new TimberPost();

// Set context
$context = array_merge($context, array(
    'post' => $post,
    'last_blog_post' =>  Timber::get_post(array(
      	'posts_per_page'   => 1,
      	'post_type'        => 'post'
    ))
));

Timber::render('home/intro.twig', $context);
