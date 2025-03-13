<?php get_header();
pageBanner([
    'title' => 'Search Results',
    'subtitle' => 'Your search result &ldquo; ' . esc_html(get_search_query()) . ' &rdquo;'
]);

?>


<div class="container container--narrow page-section">

    <?php
    if (have_posts()) {

        while (have_posts()) {
            the_post();
            get_template_part('template-part/content', get_post_type());

    ?>

    <?php

        }
        echo paginate_links();
    } else {
        echo '<h2 class="heading heading--small-plus">No result found at this post</h2>';
    }
    get_search_form();


    ?>

</div>
<?php get_footer(); ?>