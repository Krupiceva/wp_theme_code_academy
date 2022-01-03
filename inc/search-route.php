<?php
//Custom REST API ROUTE that include all of custom post types

function academyRegisterSearch(){
    register_rest_route('academy/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE, //GET
        'callback' => 'academySearchResults'
    ));
}

function academySearchResults($data){
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'teacher', 'course', 'campus', 'event'),
        's' => sanitize_text_field($data['term'])
    )); 

    $results = array(
        'generalInfo' => array(),
        'teachers' => array(),
        'courses' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while($mainQuery->have_posts()){
        $mainQuery->the_post();
        if(get_post_type() == 'post' OR get_post_type() == 'page'){
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }
        if(get_post_type() == 'teacher'){
            array_push($results['teachers'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'teacherLandscape')
            ));
        }
        if(get_post_type() == 'course'){
            array_push($results['courses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
        if(get_post_type() == 'event'){
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if(has_excerpt()){
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            }

            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
            ));
        }
        if(get_post_type() == 'campus'){
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
    }

    return $results;
}

add_action('rest_api_init', 'academyRegisterSearch');