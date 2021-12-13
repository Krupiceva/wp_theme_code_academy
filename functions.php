<?php

//Function for loading site resources (styles, scripts)
function academy_files()
{
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

function academy_features()
{
	add_theme_support('title-tag');
}

add_action('after_setup_theme', 'academy_features');

//https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,700&display=swap

?>
