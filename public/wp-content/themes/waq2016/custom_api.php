<?php
/*
Template Name: API
*/

$apiType = $wp_query->query_vars['api_type'];

if (locate_template('api/api-' . $apiType . '.php') != '') {
	get_template_part('api/api', $apiType );
} else {
    echo 'No <i>'.$apiType.'</i> handler found.';
}
