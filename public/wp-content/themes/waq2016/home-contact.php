<?php
/*
Template Name: Contact
*/
global $post;

$context = Timber::get_context();
$post = new TimberPost();

// Set context
$context = array_merge($context, array(
    'post' => $post,
    'restos' =>  Timber::get_posts(array(
      	'posts_per_page'   => -1,
      	'post_type'        => 'place',
        'order' => 'ASC',
        'meta_key' => 'type',
        'meta_value' => 'resto',
        'meta_compare' => '='
    )),
    'parkings' =>  Timber::get_posts(array(
      	'posts_per_page'   => -1,
      	'post_type'        => 'place',
        'order' => 'ASC',
        'meta_key' => 'type',
        'meta_value' => 'parking',
        'meta_compare' => '='
    )),
    'hotels' =>  Timber::get_posts(array(
      	'posts_per_page'   => -1,
      	'post_type'        => 'place',
        'order' => 'ASC',
        'meta_key' => 'type',
        'meta_value' => 'hotel',
        'meta_compare' => '='
    )),
    'shops' =>  Timber::get_posts(array(
      	'posts_per_page'   => -1,
      	'post_type'        => 'place',
        'order' => 'ASC',
        'meta_key' => 'type',
        'meta_value' => 'shop',
        'meta_compare' => '='
    )),
    'places' =>  Timber::get_posts(array(
      	'posts_per_page'   => -1,
      	'post_type'        => 'place',
        'order' => 'ASC',
        'orderby' => 'meta_value',
        'meta_key' => 'type'
    ))
));

Timber::render('home/contact.twig', $context);
