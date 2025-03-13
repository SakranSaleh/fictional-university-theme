<?php get_header();


pageBanner(); ?>



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
    <?php while (have_posts()) {
        the_post();

        get_template_part('template-part/content', 'event');
    ?>
    <?php

    }
    echo paginate_links();
    ?>
    <hr class="section-break">
    <p>Lokking for a recap for past events? <a href="<?php echo site_url('/past-events') ?>">Checkout our past events archive </a></p>
</div>
<?php get_footer(); ?>