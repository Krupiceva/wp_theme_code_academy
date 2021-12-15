<?php get_header();

while (have_posts()) {
	the_post(); 
    pageBanner();
    ?>
    
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('course'); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All Courses
                </a> 
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php 
                //Teachers that thaught this course
                $relatedTeachers = new WP_Query(array(
                    'posts_per_page' => -1,
                    'post_type' => 'teacher',
                    'orderby' => 'title',
                    'order' => "ASC",
                    'meta_query' => array(
                        array(
                            'key' => 'related_courses',
                            'compare' => 'LIKE',
                            'value' => '"' . get_the_ID() . '"'
                        ),
                    )
                ));

                if($relatedTeachers->have_posts()){
                    echo '<hr class="section-break" />';
                    echo '<h2 class="headline headline--medium">' . get_the_title() . ' Teachers</h2>';

                    echo '<ul class="professor-cards">';
                    while($relatedTeachers->have_posts()){
                        $relatedTeachers->the_post(); ?>
                        <li class="professor-card__list-item">
                            <a class="professor-card" href="<?php the_permalink(); ?>">
                                <img class="professor-card__image" src="<?php the_post_thumbnail_url('teacherLandscape'); ?>">
                                <span class="professor-card__name"><?php the_title(); ?></span>
                            </a>
                        </li>
                    <?php } wp_reset_postdata();
                    echo '</ul>';
                }



                //Related events custom query
                $today = date('Ymd');
                $relatedEvents = new WP_Query(array(
                    'posts_per_page' => 2,
                    'post_type' => 'event',
                    'meta_key' => 'event_date',
                    'orderby' => 'meta_value_num',
                    'order' => "ASC",
                    'meta_query' => array(
                        array(
                            'key' => 'event_date',
                            'compare' => '>=',
                            'value' => $today,
                            'type' => 'numeric'
                        ),
                        array(
                            'key' => 'related_courses',
                            'compare' => 'LIKE',
                            'value' => '"' . get_the_ID() . '"'
                        ),
                    )
                ));

                if($relatedEvents->have_posts()){
                    echo '<hr class="section-break" />';
                    echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

                    while($relatedEvents->have_posts()){
                        $relatedEvents->the_post();
                        get_template_part('template-parts/content-event');
                    } wp_reset_postdata();
                }

            $relatedCampuses = get_field('related_campus');

            if($relatedCampuses){
                echo '<hr class="section-break" />';
                echo '<h2 class="headline headline--medium">' . get_the_title() . ' is Available At This Campuses</h2>';
                echo '<ul class="min-list link-list">';
                foreach($relatedCampuses as $campus){ ?>
                    <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a></li>
                <?php }
                echo '</ul>';
            }
            ?>    

    </div>
<?php
}
get_footer(); ?>
