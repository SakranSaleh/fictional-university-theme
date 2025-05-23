<?php get_header() ?>
<?php
while (have_posts()) {
    the_post();
    pageBanner();
?>


    <!-- <div class="page-banner">
        <!-- <div class="page-banner__bg-image" style="background-image: url(<?php //$pageBannerImage = get_field('page_banner_background_image'); -->
                                                                                // echo $pageBannerImage['sizes']['pageBanner'];  
                                                                                ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">
                <p>DON'T FORGET TO REPLICE ME LETER.</p>
            </div>
        </div>
    </div> -->
    <div class="container container--narrow page-section">
        <?php
        $theParent = wp_get_post_parent_id(get_the_id());
        if ($theParent) { ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent) ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>
        <?php } ?>

        <?php
        $testArray = get_pages(array(
            "child_of" => get_the_id()
        ));
        if ($theParent or $testArray) { ?>
            <div class="page-links">
                <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
                <ul class="min-list">
                    <?php
                    if ($theParent) {
                        $findChildrenOf = $theParent;
                    } else {
                        $findChildrenOf = get_the_ID();
                    }

                    wp_list_pages([
                        "title_li" => null,
                        "child_of" => $findChildrenOf,
                        "sort_column" => "menu_order"
                    ]); ?>
                </ul>
            </div>
        <?php } ?>
        <div class="generic-content">

            <?php get_search_form() ?>

        </div>
    </div>
<?php
}
?>
<?php get_footer() ?>