<?php 
/*
Plugin Name: TriFunctions
Description: This plugin enables many of the core functions of thetriangle.org. Deactivating will cause many aspects of the site to stop working. To flush the rewrite rules, perform an activation cycle. For assistance, please contact web@thetriangle.org.
Version: 1.1
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

function wc_ga_code() { ?>

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-162588-7', 'auto');
	  ga('send', 'pageview');
	</script>

<?php }
add_action( 'wp_head', 'wc_ga_code');

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



//Widgetizing
function arphabet_widgets_init() {

	register_sidebar( array(
		'name' => 'Home right sidebar',
		'id' => 'home_right_1',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h1 class="rounded">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'arphabet_widgets_init' );


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


// Using a fork of the Single Post Templates Plugin.
class Single_Post_Template {

	function __construct() {

		add_action( 'admin_menu', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ), 1, 2 );

	}

	function get_post_template( $template ) {

		global $post;

		$custom_field = get_post_meta( $post->ID, '_wp_post_template', true );

		if( ! $custom_field )
			return $template;

		/** Prevent directory traversal */
		$custom_field = str_replace( '..', '', $custom_field );

		if( file_exists( get_stylesheet_directory() . "/{$custom_field}" ) )
			$template = get_stylesheet_directory() . "/{$custom_field}";
		elseif( file_exists( get_template_directory() . "/{$custom_field}" ) )
			$template = get_template_directory() . "/{$custom_field}";

		return $template;

	}

	function get_post_templates() {

		$templates = wp_get_theme()->get_files( 'php', 1 );
		$post_templates = array();

		$base = array( trailingslashit( get_template_directory() ), trailingslashit( get_stylesheet_directory() ) );

		foreach ( (array) $templates as $file => $full_path ) {

			if ( ! preg_match( '|Single Post Template:(.*)$|mi', file_get_contents( $full_path ), $header ) )
				continue;

			$post_templates[ $file ] = _cleanup_header_comment( $header[1] );

		}

		return $post_templates;

	}

	function post_templates_dropdown() {

		global $post;

		$post_templates = $this->get_post_templates();

		/** Loop through templates, make them options */
		foreach ( (array) $post_templates as $template_file => $template_name ) {
			$selected = ( $template_file == get_post_meta( $post->ID, '_wp_post_template', true ) ) ? ' selected="selected"' : '';
			$opt = '<option value="' . esc_attr( $template_file ) . '"' . $selected . '>' . esc_html( $template_name ) . '</option>';
			echo $opt;
		}

	}

	function add_metabox() {

		if ( $this->get_post_templates() )
			add_meta_box( 'pt_post_templates', __( 'Single Post Template', 'genesis' ), array( $this, 'metabox' ), 'post', 'normal', 'high' );

	}

	function metabox( $post ) { ?>

		<input type="hidden" name="pt_noncename" id="pt_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

		<label class="hidden" for="post_template"><?php  _e( 'Post Template', 'genesis' ); ?></label><br />
		<select name="_wp_post_template" id="post_template" class="dropdown">
			<option value=""><?php _e( 'Default', 'genesis' ); ?></option>
			<?php $this->post_templates_dropdown(); ?>
		</select><br /><br />
		<p><?php _e( 'Some themes have custom templates you can use for single posts that might have additional features or custom layouts. If so, you will see them above.', 'genesis' ); ?></p>
		
	<?php }

	function metabox_save( $post_id, $post ) {

		/*
		 * Verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times
		 */
		if ( ! wp_verify_nonce( $_POST['pt_noncename'], plugin_basename( __FILE__ ) ) )
			return $post->ID;

		/** Is the user allowed to edit the post or page? */
		if ( 'page' == $_POST['post_type'] )
			if ( ! current_user_can( 'edit_page', $post->ID ) )
				return $post->ID;
		else
			if ( ! current_user_can( 'edit_post', $post->ID ) )
				return $post->ID;

		/** OK, we're authenticated: we need to find and save the data */

		/** Put the data into an array to make it easier to loop though and save */
		$mydata['_wp_post_template'] = $_POST['_wp_post_template'];

		/** Add values of $mydata as custom fields */
		foreach ( $mydata as $key => $value ) {
			/** Don't store custom data twice */
			if( 'revision' == $post->post_type )
				return;

			/** If $value is an array, make it a CSV (unlikely) */
			$value = implode( ',', (array) $value );

			/** Update the data if it exists, or add it if it doesn't */
			if( get_post_meta( $post->ID, $key, false ) )
				update_post_meta( $post->ID, $key, $value );
			else
				add_post_meta( $post->ID, $key, $value );

			/** Delete if blank */
			if( ! $value )
				delete_post_meta( $post->ID, $key );
		}

	}

}

add_action( 'after_setup_theme', 'post_templates_plugin_init' );

/**
 * Instantiate the class after theme has been set up.
 */
function post_templates_plugin_init() {
	new Single_Post_Template;
}

function tri_activate() {
	flush_rewrite_rules();
}
register_activation_hook( plugins_url('/trifunctions.php', 'trifunctions'), 'tri_activate' );

function tri_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( '/trifunctions.php', 'trifunctions' );