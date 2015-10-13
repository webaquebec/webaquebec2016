<?php
$context = Timber::get_context();

// Set context
$context = array_merge($context, array(
    'posts' => Timber::get_posts()
));

Timber::render('posts/single.twig', $context);
