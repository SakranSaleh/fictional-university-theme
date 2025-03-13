<?php get_header();


pageBanner([
    'title' => 'Our Campases',
    'subtitle' => 'This is convenient of our campases'
]); ?>



<!-- <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php //echo get_theme_file_uri('/assets/images/ocean.jpg'); 
                                                                    ?>)"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">All Events</h1>
        <div class="page-banner__intro">
            <p>See all events in our world!</p>
        </div>
    </div>
</div> -->
<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php while (have_posts()) {
            the_post(); ?>
            <li><a href="<?php the_permalink() ?>"><?php the_title();
                                                    $mapLocation = get_field('map_location');
                                                    print_r('***' . $mapLocation);
                                                    ?></a></li>
        <?php

        }
        ?>
    </ul>
</div>
<?php get_footer(); ?>