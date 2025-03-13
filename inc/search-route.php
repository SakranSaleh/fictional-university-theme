<?php

function universityRegisterSearch()
{
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'universitySearchResult' // Fixed function name
    ));
}
add_action('rest_api_init', 'universityRegisterSearch');

function universitySearchResult($data)
{
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'event', 'program', 'campus'),
        's' => sanitize_textarea_field($data['term'])
    ));


    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'events' => array(),
        'programs' => array(),
        'campuses' => array()
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() == 'post' || get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }

        if (get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0,  'professorLandScape')
            ));
        }

        if (get_post_type() == 'event') {

            $eventDateTime = new DateTime(get_field('event_date'));
            $description = null;

            if (has_excerpt()) {
                $description =  get_the_excerpt();
            } else {
                $description =  wp_trim_words(get_the_content(), 18);
            }
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDateTime->format('M'),
                'day' => $eventDateTime->format('d'),
                'description' =>  $description
            ));
        }

        if (get_post_type() == 'program') {

            $relatedCanpases = get_field('related_campus');

            if ($relatedCanpases) {
                foreach ($relatedCanpases as $campus) {
                    array_push($results['campuses'], array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }

            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_ID()
            ));
        }

        if (get_post_type() == 'campus') {

            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),

            ));
        }
    }

    if ($results['programs']) {
        $protramMetaQuery = array('relation' => 'OR');

        foreach ($results['programs'] as $item) {
            array_push($protramMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"'
            ));
        }
        $programRelationshipQuery = new WP_Query(array(
            'post_type' => array('professor', 'event'),
            'meta_query' => $protramMetaQuery
        ));


        while ($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();


            if (get_post_type() == 'event') {

                $eventDateTime = new DateTime(get_field('event_date'));
                $description = null;

                if (has_excerpt()) {
                    $description =  get_the_excerpt();
                } else {
                    $description =  wp_trim_words(get_the_content(), 18);
                }
                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDateTime->format('M'),
                    'day' => $eventDateTime->format('d'),
                    'description' =>  $description
                ));
            }

            if (get_post_type() == 'professor') {
                array_push($results['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0,  'professorLandScape')
                ));
            }
        }

        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }


    return $results;
}
