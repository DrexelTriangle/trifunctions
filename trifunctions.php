<?php 
/*
Plugin Name: TriFunctions
Description: This plugin enables many of the core functions of thetriangle.org. Deactivating will cause many aspects of the site to stop working. To flush the rewrite rules, perform an activation cycle. For assistance, please contact web@thetriangle.org.
Version: 2.1
License: The MIT License (MIT)
Author: The Triangle IT Team
Author URI: http://thetriangle.org
*/

//Current URL
function this_url() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

//Set link attributes for prev and next.

add_filter('next_posts_link_attributes', 'n_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'p_posts_link_attributes');
function n_posts_link_attributes() {
	return 'class="next pagination-link"';
}
function p_posts_link_attributes() {
	return 'class="prev pagination-link"';
}

//Login-Page
add_action('login_enqueue_scripts', 'wc_login_scripts');
function wc_login_scripts() {
	wp_enqueue_style('login-styles', plugins_url('/trifunctions/login/login.css'));
	wp_enqueue_script('jquery');
}
function tri_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'tri_login_logo_url' );

function tri_login_logo_title() {
    return 'The Traingle, Drexel&rsquo;s Independent Student Newspaper';
}
add_filter( 'login_headertitle', 'tri_login_logo_title' );

//Favicons Anyone?
function wc_favicons() {
	echo '<link rel="icon" type="image/x-icon" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle.ico">
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-76@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-76.png">
	<link rel="apple-touch-icon-precomposed" sizes="180x180" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-60@3x.png">
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-60@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="60x60" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-60.png">
	<link rel="apple-touch-icon-precomposed" sizes="80x80" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small-40@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="40x40" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small-40.png">
	<link rel="apple-touch-icon-precomposed" sizes="87x87" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small@3x.png">
	<link rel="apple-touch-icon-precomposed" sizes="58x58" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="29x29" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small.png">';
}

add_action ('wp_head', 'wc_favicons');

//Now for the Admin Panel...

function wc_favicons_admin() {
	echo '<link rel="icon" type="image/x-icon" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle.ico">
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-76@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-76.png">
	<link rel="apple-touch-icon-precomposed" sizes="180x180" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-60@3x.png">
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-60@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="60x60" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-60.png">
	<link rel="apple-touch-icon-precomposed" sizes="80x80" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small-40@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="40x40" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small-40.png">
	<link rel="apple-touch-icon-precomposed" sizes="87x87" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small@3x.png">
	<link rel="apple-touch-icon-precomposed" sizes="58x58" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="29x29" href="' . plugins_url('/trifunctions/favicons/') . 'Triangle-Small.png">';
}
add_action ('admin_head', 'wc_favicons_admin');

function wc_ga_code() { ?>

	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-162588-4"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-162588-4');
	</script>

<?php }

add_action( 'wp_head', 'wc_ga_code');

//Add wrappers to media elements.

add_filter('embed_oembed_html', 'wc_oembed_wrapper', 99, 4);
function wc_oembed_wrapper($html, $url, $attr, $post_id) {
	return '<div class="media-box">' . $html . '</div>';
}

function category_has_parent($catid){
    $category = get_category($catid);
    if ($category->category_parent > 0){
        return true;
    }
    return false;
}

// Replace Posts label as Articles in Admin Panel 

function change_post_menu_label() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'Articles';
	$submenu['edit.php'][5][0] = 'Articles';
	$submenu['edit.php'][10][0] = 'Add New';
	echo '';
}
function change_post_object_label() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	$labels->name = 'Articles';
	$labels->singular_name = 'Article';
	$labels->add_new = 'Add New';
	$labels->add_new_item = 'Add New Article';
	$labels->edit_item = 'Edit Article';
	$labels->new_item = 'New Article';
	$labels->view_item = 'View Article';
	$labels->search_items = 'Search Articles';
	$labels->not_found = 'No Articles found';
	$labels->not_found_in_trash = 'No Articles found in Trash';
}
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );

//Register Multimedia Post Type

function post_type_init() {
	$media_labels = array(
		'name'                  =>  'AV Posts',
		'singular_name'         =>  'AV Post',
		'menu_name'             =>  'AV Posts',
		'all_items'             =>  'All AV Posts',
		'add_new'               =>  'Add New',
		'add_new_item'          =>  'Add New AV Post',
		'edit_item'             =>  'Edit Post',
		'new_item'              =>  'New Post',
		'view_item'             =>  'View Post',
		'search_items'          =>  'Search Posts',
		'not_found'             =>  'No Items found',
		'not_found_in_trash'    =>  'No Items found in Trash',
		'parent_item_colon'     =>  'Parent Posts'
	);
	
	$media_arguments = array(
		'labels'                =>  $media_labels,
		'description'           =>  'Multimedia Items',
		'public'                =>  true,
		'exclude_from_search'   =>  false,
		'show_ui'               =>  true,
		'show_in_nav_menus'     =>  true,
		'show_in_menu'          =>  true,
		'show_in_admin_bar'     =>  true,
		'menu_position'         =>  5,
		'capability_type'       => 'post',
		'hierarchical'          =>  true,
		'supports'              =>  array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'revisions', 'page-attributes'),
		'has_archive'           =>  true,
		'can_export'            =>  true
	);
	register_post_type ('multimedia', $media_arguments);
}
add_action ('init', 'post_type_init');

function taxonomy_init() {
	$media_format_labels = array(
		'name'                  =>  'Formats',
		'singular_name'         =>  'Format',
		'menu_name'             =>  'Formats',
		'all_items'             =>  'All Formats',
		'edit_item'             =>  'Edit Format',
		'view_item'             =>  'View Format',
		'update_item'           =>  'Update Format',
		'add_new_item'          =>  'Add New Format',
		'new_item_name'         =>  'New Format',
		'parent_item'           =>  null,
		'parent_item_colon'     =>  null,
		'search_items'          =>  'Search Formats',
		'popular_items'         =>  null,
		'not_found'             =>  'No Formats found.'
	);
	
	$media_format_arguments = array(
		'labels'                =>  $media_format_labels,
		'public'                =>  true,
		'show_ui'               =>  true,
		'show_in_nav_menus'     =>  true,
		'show_tagcloud'         =>  false,
		'show_admin_column'     =>  true,
		'hierarchical'          =>  true,
		'update_count_callback' =>  '',
		'query_var'             =>  'formats',
		'rewrite'               =>  array('slug' => 'media-formats', 'hierarchical' => true),
		'capabilities'          =>  array('manage_terms', 'edit_terms', 'delete_terms', 'assign_terms'),
		'sort'                  =>  false
	);
	register_taxonomy ('media-format', 'multimedia', $media_format_arguments );
	register_taxonomy_for_object_type('media-format', 'multimedia');
}
add_action ('init', 'taxonomy_init');

// Modify MIME Types
require 'inc/mime-types.php';

function tri_activate() {
	flush_rewrite_rules();
}
register_activation_hook( plugins_url('/trifunctions.php', 'trifunctions'), 'tri_activate' );

function tri_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( '/trifunctions.php', 'trifunctions' );