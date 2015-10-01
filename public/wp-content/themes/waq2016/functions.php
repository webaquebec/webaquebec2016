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

		$this->deactivateSearch();

		parent::__construct();
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