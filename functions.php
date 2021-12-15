<?php

//Function for dinamic page banner
function pageBanner($args = NULL){

	if(!$args['title']){
		$args['title'] = get_the_title();
	}
	if(!$args['subtitle']){
		$args['subtitle'] = get_field('page_banner_subtitle');
	}
	if(!$args['photo']){
		if(get_field('page_banner_background_image') AND !is_archive() AND !is_home()){
			$args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
		} else {
			$args['photo'] = get_theme_file_uri('/images/hacker-1.jpg');
		}
	}

?>
	<div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php
           echo $args['photo'];
        ?>)">
        </div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>
<?php }

//Function for loading site resources (styles, scripts)
function academy_files(){
	$googleMapUrl = '//maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API;
	wp_enqueue_script(
		'googleMap',
		$googleMapUrl,
		NULL,
		'1.0',
		true
	);
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
	add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'academy_features');

//Function for custom query for custom post types that have archives
function academy_adjust_queries($query){
	//Camous post type query manipulation
	if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()){
		$query->set('posts_per_page', -1);
	}

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

//Enable google maps with API Key from Google
function my_acf_init() {
    acf_update_setting('google_api_key', GOOGLE_MAPS_API);
}
 
add_action('acf/init', 'my_acf_init');

?>
