<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch()
{
    register_rest_route(
        'university/v1',
        'search',
        array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => 'universitySearchResults'
        )
    );
}

function universitySearchResults($data)
{
    $mainQuery = new WP_Query(
        array(
            'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
            's' => sanitize_text_field($data['term']) // sanitize_text_field 검색 창에 쿼리를 날려 공격하지 못하도록 하기위해
        )
    );

    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if (get_post_type() == 'post' or get_post_type() == 'page') {
            array_push(
                $results['generalInfo'],
                array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'postType' => get_post_type(),
                    'authorName' => get_the_author()
                )
            );
        }

        if (get_post_type() == 'professor') {
            array_push(
                $results['professors'],
                array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                )
            );
        }

        if (get_post_type() == 'program') {
            $relatedCampuses = get_field('related_campus');

            if ($relatedCampuses) {
                foreach($relatedCampuses as $campus) {
                    array_push($results['campuses'], array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }   

            array_push(
                $results['programs'],
                array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'id' => get_the_id()
                )
            );
        }

        if (get_post_type() == 'campus') {
            array_push(
                $results['campuses'],
                array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink()
                )
            );
        }

        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;

            if (has_excerpt()) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            }

            array_push(
                $results['events'],
                array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                )
            );
        }
    }

    if ($results['programs']) {
        // 검색어가 포함된 모든 programs를 가져와서 표시하기 위함.
        $programsMetaQuery = array('relation' => 'OR');

        foreach ($results['programs'] as $item) {
            array_push(
                $programsMetaQuery,
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . $item['id'] . '"'
                )
            );
        }


        // 검색어와 관련된 모든 professor을 가져오기 위함.
        $programRelationshipQuery = new WP_Query(
            array(
                'post_type' => array('professor', 'event'),
                'meta_query' => $programsMetaQuery
            )
        );

        while ($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();
            
            if (get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;
    
                if (has_excerpt()) {
                    $description = get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 18);
                }
    
                array_push(
                    $results['events'],
                    array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'month' => $eventDate->format('M'),
                        'day' => $eventDate->format('d'),
                        'description' => $description
                    )
                );
            }

            if (get_post_type() == 'professor') {
                array_push(
                    $results['professors'],
                    array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                    )
                );
            }
        }

        // programs와 관련된 professor을 찾으면서 중복된 array를 처리한다.
        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }

    return $results;
}