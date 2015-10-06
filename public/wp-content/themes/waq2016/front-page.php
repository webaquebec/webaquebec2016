<?php

$page_args = array(
'posts_per_page'   => -1,
'orderby'          => 'menu_order',
'order'            => 'ASC',
'meta_query'       => array(
	array(
		'key' => 'in_home_page',
		'value' => 1,
		'compare' => '='
	)
),
'post_type' => 'page',
);

$pages = get_posts($page_args);
global $post;

ob_start();
foreach($pages as $post){
	setup_postdata( $post );
	$template_name = str_replace('.php', '', get_post_meta( $post->ID, '_wp_page_template', true ));
	$template_name = explode('-',$template_name);
	get_template_part($template_name[0],$template_name[1]);

}

wp_reset_postdata();

if (!class_exists('Timber')){
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}
$context = Timber::get_context();
$context['pages_content'] = ob_get_clean();
$templates = array('home.twig');
Timber::render($templates, $context);
