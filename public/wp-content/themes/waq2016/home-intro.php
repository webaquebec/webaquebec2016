<?php
/*
Template Name: Introduction
*/
global $post;

$context = Timber::get_context();
$post = new TimberPost();

// Set homepage context
$context = array_merge($context, array(
    'post' => $post
));

Timber::render('intro.twig', $context);

