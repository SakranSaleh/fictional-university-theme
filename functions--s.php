<?php

require get_theme_file_path('inc/search-route.php');


function university_custom_rest()
{
    register_rest_field('post', 'authorName', array(
        'get_callback' => function ($post) {
            return get_the_author_meta('display_name', $post['author']);
        }
    ));
}
add_action('rest_api_init', 'university_custom_rest');



function pageBanner($args = [])
{
    // Set title if not provided
    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }

    // Set subtitle if not provided
    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle') ?: 'Default subtitle';
    }

    // Set background image
    if (!isset($args['photo'])) {
        $bannerImage = get_field('page_banner_background_image');
        if ($bannerImage && isset($bannerImage['sizes']['pageBanner']) && !is_archive() && !is_home()) {
            $args['photo'] = $bannerImage['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/assets/images/ocean.jpg');
        }
    }
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url('<?php echo esc_url($args['photo']); ?>')"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>


<?php }



add_action('rest_api_init', 'university_custom_rests');

function university_custom_rests()
{
    register_rest_field('note', 'nonce', array(
        'get_callback' => function () {
            return wp_create_nonce('wp_rest');
        }
    ));
}


function university_file()
{
    wp_enqueue_style("custom-google-font", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university-main-style", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university-extra-style", get_theme_file_uri("/build/index.css"));



    wp_enqueue_script("main-js", get_theme_file_uri('/build/index.js'), array("jquery"), "1.0", true);


    wp_localize_script("main-js", "universityData", array(
        "root_url" => site_url(),
        "nonce" => wp_create_nonce('wp-rest')
    ));
}

add_action("wp_enqueue_scripts", "university_file");


function university_features()
{
    // register_nav_menu("headerNavMenu", "Header Menu Location");
    // register_nav_menu("footerLocationOne", "Footer Location One");
    // register_nav_menu("footerLocationTwo", "Footer Location Two");
    add_theme_support("title-tag");
    add_theme_support('post-thumbnails');
    add_image_size('professorLandScape', 400, 260, true);
    add_image_size('professorPoitrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action("after_setup_theme", "university_features");


function university_adjust_quiries($query)
{

    if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }
    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
}

add_action('pre_get_posts', 'university_adjust_quiries');


function universityMapKey($api)
{
    $api['key'] = '';
    return $api;
}

add_action('acf/fields/google_map/api', 'universityMapKey');



add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend()
{
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 and $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}



add_action('wp_loaded', 'noSubsAdminbar');

function noSubsAdminbar()
{
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 and $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}


//Customize Login Screen


add_filter('login_headerurl', 'ourHeadUrl');

function ourHeadUrl()
{
    return site_url('/');
}


add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS()
{
    wp_enqueue_style("custom-google-font", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university-main-style", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university-extra-style", get_theme_file_uri("/build/index.css"));
}


add_action('login_headertitle', 'ourLoginTitle');

function ourLoginTitle()
{
    return get_bloginfo('name');
}
