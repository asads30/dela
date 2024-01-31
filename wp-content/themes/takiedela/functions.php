<?php
/**
 * TakieDela functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package TakieDela
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function takiedela_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on TakieDela, use a find and replace
		* to change 'takiedela' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'takiedela', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'takiedela' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'takiedela_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'takiedela_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function takiedela_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'takiedela_content_width', 640 );
}
add_action( 'after_setup_theme', 'takiedela_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function takiedela_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'takiedela' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'takiedela' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'takiedela_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function takiedela_scripts() {
	wp_enqueue_style( 'takiedela-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'takiedela-font', 'https://design.nuzhnapomosh.ru/fonts/fonts-futura-leksa-romanovsky.css', array(), _S_VERSION );
	wp_enqueue_style( 'takiedela-main', get_template_directory_uri() . '/assets/css/main.css', array(), _S_VERSION );
	wp_style_add_data( 'takiedela-style', 'rtl', 'replace' );

	wp_enqueue_script( 'takiedela-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'takiedela_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

class REFINED_POST_ENDPOINT extends WP_REST_Controller {
    /**
   * Constructor.
   */
  public function __construct() {
    $this->namespace = 'asad/v1';
    $this->rest_base = 'posts';
        $this->post_type = 'post';
  }
  /**
   * Register the component routes.
   */
  public function register_routes() {
    register_rest_route( $this->namespace, '/' . $this->rest_base, array(
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'get_items' ),
        'permission_callback' => array( $this, 'get_items_permissions_check' ),
        'args'                => $this->get_collection_params(),
      )
    ) );
  }
  
  /**
   * Retrieve posts.
   */
  public function get_items( $request ) {
    $args = array(
      'post_type'      => $this->post_type,
      'posts_per_page' => $request['per_page'],
      'paged'           => $request['page']
    );
  
    // use WP_Query to get the results with pagination
    $query = new WP_Query( $args ); 
    // if no posts found return 
    if( empty($query->posts) ){
      return new WP_Error( 'no_posts', __('No post found'), array( 'status' => 404 ) );
    }
    // set max number of pages and total num of posts
    $posts = $query->posts;
    
    $max_pages = $query->max_num_pages;
    $total = $query->found_posts;
    foreach ( $posts as $post ) {
      $response = $this->prepare_item_for_response( $post, $request );
      $data[] = $this->prepare_response_for_collection( $response );  
    }
	$result = array(
		'data' => $data,
		'total' => $total,
		'max_pages' => $max_pages
	);
  
    // set headers and return response      
    $response = new WP_REST_Response($result, 200);
    $response->header( 'X-WP-Total', $total ); 
    $response->header( 'X-WP-TotalPages', $max_pages );
    return $response;
  }
  
  /**
   * Check if a given request has access to post items.
   */
  public function get_items_permissions_check( $request ) {
    return true;
  }
  
  /**
   * Get the query params for collections
   */
  public function get_collection_params() {
    return array(
      'page'     => array(
        'description'       => 'Current page of the collection.',
        'type'              => 'integer',
        'default'           => 1,
        'sanitize_callback' => 'absint',
      ),
      'per_page' => array(
        'description'       => 'Maximum number of items to be returned in result set.',
        'type'              => 'integer',
        'default'           => 10,
        'sanitize_callback' => 'absint',              
      ),
    );
  }
  
  /**
   * Prepares post data for return as an object.
   */
  public function prepare_item_for_response( $post, $request ) {
	$post_categories = wp_get_post_categories( $post->ID );
	$categories = array();
	foreach($post_categories as $c){
		$cat = get_category( $c );
		$categories[] = $cat->name;
	}
    $data = array(
      'title'    => $post->post_title,
      'content' => $post->post_content,
      'excerpt' => $post->post_excerpt,
      'featured_img' => get_the_post_thumbnail_url($post->ID, 'full'),
	  'author' => $post->post__author,
	  'categories' => implode(' • ', $categories),
      'date' => date( "Y.m.d, h:i", strtotime($post->post_date))
    );
  
    return $data;
  }
}
add_action( 'rest_api_init', function () {
  $controller = new REFINED_POST_ENDPOINT();
  $controller->register_routes();
} );

add_action( 'wp_enqueue_scripts', function() {

	wp_enqueue_script( 'jquery' );

	wp_register_script( 'trueajax', get_stylesheet_directory_uri() . '/assets/js/ajax.js', array(), time(), true );
	wp_localize_script(
		'trueajax',
		'misha',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		)
	);
	wp_enqueue_script( 'trueajax' );

} );

add_action( 'wp_ajax_loadmore', 'true_loadmore' );
add_action( 'wp_ajax_nopriv_loadmore', 'true_loadmore' );

function true_loadmore() {
	$paged = ! empty( $_POST[ 'paged' ] ) ? $_POST[ 'paged' ] : 1;
	$paged++;
	$args = array(
		'paged' => $paged,
		'post_status' => 'publish'
	);
	$taxonomy = ! empty( $_POST[ 'taxonomy' ] ) ? $_POST[ 'taxonomy' ] : '';
	$term_id = ! empty( $_POST[ 'term_id' ] ) ? $_POST[ 'term_id' ] : 0;
	if( $taxonomy && $term_id ) {

		$args[ 'tax_query' ] = array(
			array(
				'taxonomy' => $taxonomy,
				'terms' => $term_id
			)
		);
	}
	query_posts( $args );
	ob_start();
	while( have_posts() ) : the_post();

		get_template_part( 'template-parts/content', get_post_type() );

	endwhile;
	$posts = ob_get_contents();
	ob_get_clean();
	ob_start();
	the_posts_pagination();
	$pagination = ob_get_contents();
	ob_get_clean();
	echo json_encode( array(
		'posts' => $posts,
		'pagination' => str_replace( admin_url( 'admin-ajax.php' ), $_POST[ 'pagenumlink' ], $pagination )
	) );
	die;
}

add_action( 'wp_ajax_filter', 'true_filter' );
add_action( 'wp_ajax_nopriv_filter', 'true_filter' );

function true_filter() {
  $filter_date = ! empty( $_POST[ 'filter_date' ] ) ? $_POST[ 'filter_date' ] : '00/00/0000';
  $dates = explode("/", $filter_date);
	$args = array(
		'date_query' => array(
      array(
        'year'  => $dates[2],
        'month' => $dates[1],
        'day'   => $dates[0],
      ),
    ),
    'post_status' => 'publish'
	);
	query_posts( $args );
	ob_start();
	while( have_posts() ) : the_post();
		get_template_part( 'template-parts/content', get_post_type() );
	endwhile;
	$posts = ob_get_contents();
	ob_get_clean();
	ob_start();
	ob_get_clean();
	echo json_encode( array(
		'posts' => $posts
	) );
	die;
}