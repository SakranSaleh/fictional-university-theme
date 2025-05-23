<?php get_header() ?>

<?php while (have_posts()) {
    the_post();

    pageBanner();
?>


    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program') ?>"><i class="fa fa-home" aria-hidden="true"></i> Program Home </a> <span class="metabox__main"><?php the_title() ?></span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_field('main_body_content'); ?>
        </div>

        <?php

        $relatedProfessor = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'professor',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_id() . '"'
                    // 'type' => 'numeric'
                ),
            )

        ));

        if ($relatedProfessor->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium"> ' . get_the_title() . ' Professors</h2>';
            echo '<ul class="professor-cards">';
            while ($relatedProfessor->have_posts()) {
                $relatedProfessor->the_post();


        ?>

                <li class="professor-card__list-item"><a class="professor-card" href="<?php the_permalink() ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandScape') ?>">
                        <span class="professor-card__name"><?php the_title() ?></span>
                    </a>
                </li>
            <?php }

            echo '</ul>';
        }
        wp_reset_postdata();



        $today = date('Ymd');
        $homePageEvents = new WP_Query(array(
            'posts_per_page' => 2,
            'post_type' => 'event',
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                ),
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_id() . '"'
                    // 'type' => 'numeric'
                ),
            )

        ));

        if ($homePageEvents->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcomming ' . get_the_title() . ' Event</h2>';
            while ($homePageEvents->have_posts()) {
                $homePageEvents->the_post();

                get_template_part('template-part/content', 'event');
            }
        }
        wp_reset_postdata();
        echo '<hr class="section-break">';
        $relatedCampuses = get_field('related_campus');

        if ($relatedCampuses) {
            echo '<h2 class="headline headline--medium"> ' . get_the_title() . ' is available in this campuses: </h2>';
        }

        echo '<ul class="min-list link-list">';
        if ($relatedCampuses) {
            foreach ($relatedCampuses as $campus) {
            ?>
                <li>
                    <a href="<?php echo get_the_permalink($campus) ?>"> <?php echo get_the_title($campus) ?></a>
                </li>

        <?php
            }
        }
        echo '</ul>';
        // if ($relatedCampuses) {
        //     echo '<h2>' . esc_html(get_the_title()) . ' is available in these campuses:</h2>';
        // }

        ?>
    </div>

<?php } ?>
<?php get_footer() ?>