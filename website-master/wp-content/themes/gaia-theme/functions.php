<?php

/* Includes */
include_once('administration/admin_client.php');
include_once('administration/modules.php');

/* Remove admin bar */
function admin_bar()
{
	return false;
}
add_filter('show_admin_bar', 'admin_bar');

/* Enable post thumbnail support for a theme */
add_theme_support('post-thumbnails');

/* Remove accents from files */
add_filter('sanitize_file_name', 'remove_accents');

/* Limit WPCF7 usage */
add_filter('wpcf7_load_js', '__return_false');
add_filter('wpcf7_load_css', '__return_false');

/* Languages */
if (function_exists('pll_register_string')) {
	$theme = 'Bienvenue à Gaïa';
	pll_register_string('', '', $theme);
}

/* Functions */
function get_assets($target)
{
	return get_bloginfo('template_directory') . '/assets/' . $target;
}

function get_thumbnail_alt($id)
{
	$alt = get_post_meta(get_post_thumbnail_id($id), '_wp_attachment_image_alt', true);
	return $alt ? $alt : get_the_title();
}

function get_thumbnail_src($id, $size)
{
	return get_the_post_thumbnail_url($id, $size);
}
