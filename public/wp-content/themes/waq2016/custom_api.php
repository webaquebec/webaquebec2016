<?php
/*
Template Name: API
*/

if(array_key_exists('api_type',$wp_query->query_vars)){
    $apiType = $wp_query->query_vars['api_type'];

    if (locate_template('api/api-' . $apiType . '.php') != '') {
    	get_template_part('api/api', $apiType );
    } else {
        echo 'No <i>'.$apiType.'</i> handler found.';
    }
}
else{
    $routes = scandir(get_template_directory().'/api');
    $routes = array_slice($routes, 2);

    echo '<h1>WAQ API</h1>';
    echo '<h2>Public routes availables</h2>';
    foreach ($routes as $key => $route) {
        $cleanName = str_replace('api-','',$route);
        $cleanName = str_replace('.php','',$cleanName);
        echo '<a href="'.$cleanName.'">'.ucfirst($cleanName).'</a><br>';
    }
}
