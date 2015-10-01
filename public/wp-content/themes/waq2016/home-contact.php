<?php
/*
Template Name: Contact
*/
global $post;

$context = Timber::get_context();
$post = new TimberPost();

// Set homepage context
$context = array_merge($context, array(
    'post' => $post
));

Timber::render('contact.twig', $context);

