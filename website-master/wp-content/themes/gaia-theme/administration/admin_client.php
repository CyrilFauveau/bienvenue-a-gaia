<?php

/* Remove widgets from the dashboard */
function remove_dashboard_widgets()
{
	global $wp_meta_boxes;

	if (!current_user_can('manage_options')) {
		remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // right now
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // recent comments
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); // incoming links
		remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); // plugins
		remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // quick press
		remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); // recent drafts
		remove_meta_box('dashboard_primary', 'dashboard', 'side'); // wordpress blog
		remove_meta_box('dashboard_secondary', 'dashboard', 'side'); // other wordpress news
	}
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

/* Menu */
function register_menus()
{
	register_nav_menus(
		array(
			'menu' => __('Menu'),
		)
	);
}
// add_action('init', 'register_menus');

/* Delete menus */
function delete_menu_items()
{
	if (!current_user_can('manage_options')) {
		remove_menu_page('link-manager.php'); // Links
		remove_menu_page('themes.php'); // Appearance
		remove_menu_page('plugins.php'); // Plugins
		remove_menu_page('tools.php'); // Tools
		remove_menu_page('options-general.php'); // Settings
		remove_menu_page('wpcf7');
	}

	remove_menu_page('edit-comments.php'); // Comments
	remove_menu_page('users.php'); // Users
}
add_action('admin_menu', 'delete_menu_items');

/* Supprimer des menus de la barre d'administration du haut */
function custom_admin_bar()
{
	global $wp_admin_bar;

	$wp_admin_bar->remove_node('wp-logo');
	$wp_admin_bar->remove_node('comments');
	$wp_admin_bar->remove_node('wpseo-menu');
	$wp_admin_bar->remove_node('new-content');
	$wp_admin_bar->remove_node('view-site');
}
add_action('wp_before_admin_bar_render', 'custom_admin_bar');

/* Delete all update notifications */
if (!current_user_can('manage_options')) {
	add_filter('pre_site_transient_update_core', function ($a) {
		return null;
	});
	remove_action('load-update-core.php', 'wp_update_themes');
	add_filter('pre_site_transient_update_themes', function ($a) {
		return null;
	});
	remove_action('load-update-core.php', 'wp_update_plugins');
	add_filter('pre_site_transient_update_plugins', function ($a) {
		return null;
	});
}

function hide_wp_update_nag()
{
	remove_action('admin_notices', 'update_nag', 3);
}
add_action('admin_menu', 'hide_wp_update_nag');

/* Footer customization */
function remove_footer_admin()
{
	echo "<a href='http://www.bienvenueagaia.fr/' target='_blank'>Bienvenue à Gaïa</a> - ";
	echo bloginfo('name');
	echo "&nbsp;&copy;&nbsp;";
	echo date('Y');
}
add_filter('admin_footer_text', 'remove_footer_admin');


/* Remove all information about the version of WP */
remove_action('wp_head', 'wp_generator');
foreach (array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $action) {
	remove_action($action, 'the_generator');
}

function put_my_url()
{
	return "";
}
add_filter('login_headerurl', 'put_my_url');

function put_my_title()
{
	return "Design by Bienvenue à Gaïa";
}
add_filter('login_headertext', 'put_my_title');
