<?php

// Create Custom Post Type 
function university_post_types()
{
  // Event Post Type
  register_post_type(
    'event',
    array(
      'show_in_rest' => true,

      // Custom post type에 wordpress 고유 기능 부여(Members의 권한에서 Event type post에 대한 권한을 만들기 위함)
      'capability_type' => 'event',
      'map_meta_cap' => true, // 고유 기능이 필요할 때 자동으로 기능을 활성화.

      'supports' => array('title', 'editor', 'excerpt'),
      'rewrite' => array('slug' => 'events'),
      'has_archive' => true,
      'public' => true,
      'labels' => array(
        'name' => 'Events',
        'add_new' => 'Add New Event',
        'edit_item' => 'Edit Event',
        'all_items' => 'All Events',
        'singular_name' => 'Event'
      ),
      'menu_icon' => 'dashicons-calendar'
    )
  );

  // Program Post Type
  register_post_type(
    'program',
    array(
      'show_in_rest' => true,
      'supports' => array('title'),
      'rewrite' => array('slug' => 'programs'),
      'has_archive' => true,
      'public' => true,
      'labels' => array(
        'name' => 'Programs',
        'add_new' => 'Add New Program',
        'edit_item' => 'Edit Programs',
        'all_items' => 'All Programs',
        'singular_name' => 'Program'
      ),
      'menu_icon' => 'dashicons-awards'
    )
  );

  // Professor Post Type
  register_post_type(
    'professor',
    array(
      'show_in_rest' => true,
      'supports' => array('title', 'editor', 'thumbnail'),
      'public' => true,
      'labels' => array(
        'name' => 'Professors',
        'add_new' => 'Add New Professor',
        'edit_item' => 'Edit Professors',
        'all_items' => 'All Professors',
        'singular_name' => 'Professor'
      ),
      'menu_icon' => 'dashicons-welcome-learn-more'
    )
  );

  // Campus Post Type
  register_post_type(
    'campus',
    array(
      'show_in_rest' => true,
      'capability_type' => 'campus',
      'map_meta_cap' => true,
      'supports' => array('title', 'editor', 'excerpt'),
      'rewrite' => array('slug' => 'campuses'),
      'has_archive' => true,
      'public' => true,
      'labels' => array(
        'name' => 'Campuses',
        'add_new' => 'Add New Campus',
        'edit_item' => 'Edit Campus',
        'all_items' => 'All Campuses',
        'singular_name' => 'Campus'
      ),
      'menu_icon' => 'dashicons-location-alt'
    )
  );

  // Note Post Type
  register_post_type(
    'note',
    array(
    'capability_type' => 'note',
    'map_meta_cap' => true,
    'show_in_rest' => true,
    'supports' => array('title', 'editor'),
    'public' => false,
    'show_ui' => true, // public 한 포스트 타입이 아니기 때문에 admin 페이지에서 표시하지 않는다.
    'labels' => array(
    'name' => 'Notes',
    'add_new' => 'Add New Note',
    'edit_item' => 'Edit Notes',
    'all_items' => 'All Notes',
    'singular_name' => 'Note'
    ),
      'menu_icon' => 'dashicons-welcome-write-blog'
    )
  );

    // Like Post Type
    register_post_type(
        'like',
        array(
            'supports' => array('title'), // 'editor'를 포함하지 않았기 때문에 content 작성하는 폼은 생성되지 않는다.
            'public' => false,
            'show_ui' => true,
            'labels' => array(
                'name' => 'Likes',
                'add_new' => 'Add New Like',
                'edit_item' => 'Edit Likes',
                'all_items' => 'All Likes',
                'singular_name' => 'Like'
            ),
            'menu_icon' => 'dashicons-heart'
        )
    );
}

add_action('init', 'university_post_types');

?>