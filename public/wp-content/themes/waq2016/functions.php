<?php
/**
 * Load translations
 */
load_theme_textdomain('waq2016');

/**
 * Include all custom code
 */
include_once('custom/custom.php');

//add_filter( 'use_default_gallery_style', function(){ return false; } );
add_theme_support( 'html5', array('gallery') );

/**
 * Notice if Timber plugin is not activated
 */
if (!class_exists('Timber')){
	add_action( 'admin_notices', function(){
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . admin_url('plugins.php#timber') . '">' . admin_url('plugins.php') . '</a></p></div>';
	});
	return;
}

$waq_custom_post_types = ['sponsor_level','sponsor','track','time_slot','session','speaker', 'company','room'];

function wpsd_add_rest_args(){

        global $wp_post_types, $waq_custom_post_types;

        foreach($waq_custom_post_types as $key){

            if(!$wp_post_types[$key])
                    continue;

            $wp_post_types[$key]->show_in_rest = true;
            $wp_post_types[$key]->rest_base = $key;
        }
}
function waq_register_meta_field() {

	global $waq_custom_post_types;

        $post_types = array_merge(['post'], $waq_custom_post_types);

        foreach($post_types as $key){
            register_rest_field($key,
                'waq_meta',
                array(
                    'get_callback'    => 'waq_get_post_meta',
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
        }
}

function waq_get_post_meta( $object, $field_name, $request ) {
	return get_post_meta( $object[ 'id' ] );
}

add_action('init', 'wpsd_add_rest_args', 30);
add_action('rest_api_init', 'waq_register_meta_field');
/**
 * Site class
 */
class WAQ2016Site extends TimberSite {



	/**
	 * Setup the site
	 */
	function __construct(){
		add_theme_support('post-formats', array('link'));
		add_theme_support('post-thumbnails');
		add_theme_support('menus');

		add_action( 'after_setup_theme', array($this, 'addImagesSizes') );

		add_filter('timber_context', array($this, 'addToContext'));
		add_filter('get_twig', array($this, 'addToTwig'));
        add_action('init', array($this, 'custom_rewrite_basic'));
        add_filter( 'query_vars', array($this, 'api_query_vars') );

		$this->deactivateSearch();

		parent::__construct();
	}

    function custom_rewrite_basic() {
        add_rewrite_rule('api/([^/]+)/?', 'index.php?pagename=api&api_type=$matches[1]', 'top');
    }

    function api_query_vars( $query_vars ) {
        $query_vars[] = 'api_type';
        return $query_vars;
    }

	function addImagesSizes(){

	}

	/**
	 * Add global context
	 * @param $context
	 * @return array
	 */
	function addToContext($context){
		return array_merge($context, array(
			'wp_title' => wp_title( '&mdash;', false, 'right' ),
			'menu' => new TimberMenu('menu'),
			'site' => $this,
		));
	}

	/**
	 * Add extensions, functions and filters to twig
	 * @param Twig_Environment $twig
	 * @return Twig_Environment
	 */
	function addToTwig(Twig_Environment $twig){
		$twig->addExtension(new Twig_Extension_StringLoader());

		return $twig;
	}

	/**
	 * Add action and filter to disable search
	 */
	private function deactivateSearch()
	{
		add_action( 'parse_query', 'WAQ2016Site::filterSearchQuery' );
		add_filter( 'get_search_form', create_function( '$a', "return null;" ) );
	}

	/**
	 * Filter the query to display 404 when trying to search
	 * @param stdClass $query
	 * @param bool $error
     */
	public static function filterSearchQuery( $query, $error = true ){
		if ( is_search() ) {
			$query->is_search = false;
			$query->query_vars['s'] = false;
			$query->query['s'] = false;

			if ( $error == true ){
				$query->is_404 = true;
			}
		}
	}
}
/**
 * Initialize the site
 */
new WAQ2016Site();
