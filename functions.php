<?php
// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
load_child_theme_textdomain( 'spring-gulch', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'spring-gulch' ) );

// Add color select to WordPress theme customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Spring Gulch' );
define( 'CHILD_THEME_URL', 'https://www.springgulchhorsetrials.com/' );
define( 'CHILD_THEME_VERSION', '1.0' );

// Enqueue scripts and styles.
add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
function genesis_sample_enqueue_scripts_styles() {

	wp_enqueue_style( 'spring-gulch-fonts', '//fonts.googleapis.com/css?family=Playfair+Display:400|Quattrocento+Sans:400,400i,700,700i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'spring-gulch-ionicons', '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css', array(), CHILD_THEME_VERSION );

	wp_enqueue_script( 'spring-gulch-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'spring-gulch-matchHeights', get_stylesheet_directory_uri() . '/js/jquery.matchHeight.js', array( 'jquery' ), '0.5.2', true );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'spring-gulch-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menus' . $suffix . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script(
		'spring-gulch-responsive-menu',
		'genesis_responsive_menu',
		spring_gulch_responsive_menu_settings()
	);

}

// Define our responsive menu settings.
function spring_gulch_responsive_menu_settings() {
	$settings = array(
		'mainMenu'          => __( 'Menu', 'spring-gulch' ),
		'menuIconClass'     => 'ionicons-before ion-drag',
		'subMenu'           => __( 'Menu', 'spring-gulch' ),
		'subMenuIconsClass' => 'ionicons-before ion-ios-arrow-down',
		'menuClasses'       => array(
			'combine' => array(
				'.nav-primary',
				'.nav-secondary',
			),
			'others'  => array(),
		),
	);
	return $settings;
}

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Add accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom header.
add_theme_support( 'custom-header', array(
	'width'           => 300,
	'height'          => 300,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Rename menus.
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Left Menu', 'spring-gulch' ), 'secondary' => __( 'Right Menu', 'spring-gulch' ) ) );

// Reposition left menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 5 );

// Reposition right menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_header', 'genesis_do_subnav', 12 );

// Remove header right widget area.
unregister_sidebar( 'header-right' );

// Remove secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Remove site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Remove output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

// Remove navigation meta box.
add_action( 'genesis_theme_settings_metaboxes', 'spring_gulch_remove_genesis_metaboxes' );
function spring_gulch_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {

	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );

}

// Modify size of Gravatar in author box.
add_filter( 'genesis_author_box_gravatar_size', 'spring_gulch_author_box_gravatar' );
function spring_gulch_author_box_gravatar( $size ) {

	return 90;

}

// Modify size of Gravatar in entry comments.
add_filter( 'genesis_comment_list_args', 'spring_gulch_comments_gravatar' );
function spring_gulch_comments_gravatar( $args ) {

	$args['avatar_size'] = 120;

	return $args;

}

// Hook sticky message widget area.
add_action( 'genesis_before', 'spring_gulch_sticky_message' );
function spring_gulch_sticky_message() {

	genesis_widget_area( 'sticky-message', array(
		'before' => '<div class="sticky-message">',
		'after'  => '</div>',
	) );

}

// Hook before header widget area.
add_action( 'genesis_before_header', 'spring_gulch_before_header' );
function spring_gulch_before_header() {

	genesis_widget_area( 'before-header', array(
		'before' => '<div class="before-header"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}

// Hook before footer widget area.
add_action( 'genesis_before_footer', 'spring_gulch_before_footer' );
function spring_gulch_before_footer() {

	genesis_widget_area( 'before-footer', array(
		'before' => '<div class="before-footer"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}

// Register widget areas.
genesis_register_sidebar( array(
	'id'          => 'sticky-message',
	'name'        => __( 'Sticky Message', 'spring-gulch' ),
	'description' => __( 'This is the sticky message section.', 'spring-gulch' ),
) );
genesis_register_sidebar( array(
	'id'          => 'before-header',
	'name'        => __( 'Before Header', 'spring-gulch' ),
	'description' => __( 'This is the before header section.', 'spring-gulch' ),
) );
genesis_register_sidebar( array(
	'id'          => 'before-footer',
	'name'        => __( 'Before Footer', 'spring-gulch' ),
	'description' => __( 'This is the before footer section.', 'spring-gulch' ),
) );
add_action( 'init', function() {
    add_shortcode( 'tribe_events', function( $atts ) {
        $shortcode = new Tribe__Events__Pro__Shortcodes__Tribe_Events( $atts );
        return $shortcode->output();
    } );
}, 100 );
