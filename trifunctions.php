<?php 
/*
Plugin Name: TriFunctions
Description: This plugin enables many of the core functions of thetriangle.org. Deactivating will cause many aspects of the site to stop working. To flush the rewrite rules, perform an activation cycle. For assistance, please contact web@thetriangle.org.
Version: 1.0
License: The MIT License (MIT)
Author: The Triangle Web Department
Author URI: http://thetriangle.org
*/

//Add Thumbnail Sizes
add_image_size('post-feature', 1280, 620, true);
add_image_size('single-post-feature', 1480, 560, true);
add_image_size('feature-wide-small', 840, 320, true);
add_image_size('169-preview-large', 1600, 900, true);
add_image_size('169-preview-medium', 800, 450, true);
add_image_size('169-preview-small', 400, 225, true);

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
	echo '<link rel="icon" type="image/x-icon" href="' . plugins_url('/trifunctions/favicons/ico/') . 'favicon.ico">
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-76@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-72@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-57@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-76.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-72.png">
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-57.png">';
}

add_action ('wp_head', 'wc_favicons');

//Now for the Admin Panel...

function wc_favicons_admin() {
	echo '<link rel="icon" type="image/x-icon" href="' . plugins_url('/trifunctions/favicons/ico/') . 'favicon.ico">
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-76@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-72@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-57@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-76.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-72.png">
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="' . plugins_url('/trifunctions/favicons/png/') . 'favicon-57.png">';
}
add_action ('admin_head', 'wc_favicons_admin');

//Excerpt Settings

function wc_excerpt_length( $length ) {
	return 50;
}
add_filter( 'excerpt_length', 'wc_excerpt_length', 999 );

function new_excerpt_more( $more ) {
	return '&hellip; <em><a href="'.post_permalink( $post->ID ).'">Continued</a></em>';
}
add_filter('excerpt_more', 'new_excerpt_more');

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

// add_filter( 'avatar_defaults', 'new_default_avatar' );
// function new_default_avatar ( $avatar_defaults ) {
// 	//Set the URL where the image file for your avatar is located
// 	$new_avatar_url = get_template_directory_uri() . '/images/avatars/avatar.png';
// 	//Set the text that will appear to the right of your avatar in Settings>>Discussion
// 	$avatar_defaults[$new_avatar_url] = 'Your New Default Avatar';
// 	return $avatar_defaults;
// }

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
	register_taxonomy_for_object_type('media-format', 'media');
}
add_action ('init', 'taxonomy_init');

function tri_activate() {
	flush_rewrite_rules();
}
register_activation_hook( plugins_url('/trifunctions.php', 'trifunctions'), 'tri_activate' );

function tri_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( '/trifunctions.php', 'trifunctions' );