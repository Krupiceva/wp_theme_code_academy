<?php get_header();

while (have_posts()) {
	the_post();
    pageBanner();
    ?>

    <div class="container container--narrow page-section">
        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                    <?php the_post_thumbnail('teacherPortrait'); ?>
                </div>
                <div class="two-thirds">
                    <?php the_content(); ?>
                </div>
            </div>   
        </div>

        <?php
        $relatedCourses = get_field('related_courses');

        if($relatedCourses){
            echo '<hr class="section-break" />';
            echo '<h2 class="headline headline--medium">Courses Taught</h2>';
            echo '<ul class="link-list min-list">';
            foreach($relatedCourses as $course){ ?>
            
                <li><a href="<?php echo get_the_permalink($course); ?>"><?php echo get_the_title($course); ?> </a></li>

            <?php }
            echo '</ul>';
        }      
        ?>

    </div>
<?php
}
get_footer(); ?>
