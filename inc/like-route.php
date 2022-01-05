<?php

function academyLikeRoutes(){
    register_rest_route('academy/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));

    register_rest_route('academy/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}

function createLike($data){
    if(is_user_logged_in()){
        $teacher = sanitize_text_field($data['teacherId']);
        
        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_teacher_id',
                    'compare' => '=',
                    'value' => $teacher
                )
            )
        ));

        //If user did not already liked this teacher, only one like per user for teacher
        if($existQuery->found_posts == 0 AND get_post_type($teacher) == 'teacher'){
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => '2nd PHP Test',
                'meta_input' => array(
                    'liked_teacher_id' => $teacher
                )
            ));
        } else {
            die("Invalid professor id");
        }
    } else {
        die("Only logged in users can create a like.");
    }
   
}

function deleteLike($data){
    $likeId = sanitize_text_field($data['like']);
    if(get_current_user_id() == get_post_field('post_author' , $likeId) AND get_post_type($likeId) == 'like'){
        wp_delete_post($likeId, true);
        return 'Congrats. Like deleted.';
    } else {
        die("You do not have permission to delete that.");
    }
}

add_action('rest_api_init', 'academyLikeRoutes');