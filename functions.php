<?php

//Function for loading site resources (styles, scripts)
function academy_files(){
	wp_enqueue_script(
		'academy-main-js',
		get_theme_file_uri('/build/index.js'),
		['jquery'],
		'1.0',
		true
	);
	wp_enqueue_style(
		'google-fonts',
		'//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'
	);
	wp_enqueue_style(
		'font-awesome',
		'//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'
	);
	wp_enqueue_style(
		'academy_main_styles',
		get_theme_file_uri('/build/style-index.css')
	);
	wp_enqueue_style(
		'academy_extra_styles',
		get_theme_file_uri('/build/index.css')
	);
}
add_action('wp_enqueue_scripts', 'academy_files');

function academy_features(){
	//register_nav_menu('headerMenuLocation', 'Header Menu Location');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('teacherLandscape', 400, 260, true);
	add_image_size('teacherPortrait', 480, 650, true);
}

add_action('after_setup_theme', 'academy_features');

//Function for custom query for custom post types that have archives
function academy_adjust_queries($query){
	//Course post type query manipulation
	if(!is_admin() AND is_post_type_archive('course') AND $query->is_main_query()){
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
		$query->set('posts_per_page', -1);
	}

	//Event post type query manipulation
	if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()){
		$today = date('Ymd');
		$query->set('meta_key', 'event_date');
		$query->set('orderby', 'meta_value_num');
		$query->set('order', 'ASC');
		$query->set('meta_query', array(
			array(
				'key' => 'event_date',
				'compare' => '>=',
				'value' => $today,
				'type' => 'numeric'
			)
		));
	}
}
add_action('pre_get_posts', 'academy_adjust_queries');

?>
