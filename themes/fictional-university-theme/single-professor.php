<?php

get_header();

while(have_posts()) {
    the_post(); 
    pageBanner();
    ?>
    
    <div class="container container--narrow page-section">
        <div class="generic-content">
            <div class="row group">

            <div class="one-third">
                <?php the_post_thumbnail('professorPortrait'); ?>
            </div>

            <div class="two-thirds">
                <?php
                // 현재 페이지의 professor와 연결된 Like 타입의 post를 검색한다.
                $likeCount = new WP_Query(array(
                    'post_type' => 'like',
                    'meta_query' => array(
                        array(
                            'key' => 'liked_professor_id',
                            'compare' => '=',
                            'value' => get_the_ID()
                        )
                    )
                ));

                $existStatus = 'no';

                // 로그인한 사용자에 한해서
                if (is_user_logged_in()) {
                    // 현재 페이지의 professor와 연결된 Like 타입 post 중에 작성자가 현재 로그인한 사용자인 데이터가 존재하는지 검색한다.
                    $existQuery = new WP_Query(array(
                        'author' => get_current_user_id(),
                        'post_type' => 'like',
                        'meta_query' => array(
                            array(
                                'key' => 'liked_professor_id',
                                'compare' => '=',
                                'value' => get_the_ID()
                            )
                        )
                    ));

                    // Like 타입의 post가 존재하는지 확인하고 존재한다면 exitStatus를 'yes'로 변경한다.
                    if ($existQuery->found_posts) {
                        $existStatus = 'yes';
                    }
                }
                ?>
                <span class="like-box" data-like="<?php if (isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; ?>" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $existStatus ?>">
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                    <i class="fa fa-heart" aria-hidden="true"></i>
                    <span class="like-count"><?php echo $likeCount->found_posts?></span>
                </span>
                <?php the_content(); ?>
            </div>

            </div>
        </div>

        <?php 
        
          $relatedPrograms = get_field('related_programs');

          if ($relatedPrograms) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
            echo '<ul class="link-list min-list">';
            foreach($relatedPrograms as $program) { ?>
  
              <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
  
            <?php } 
            
            echo '<ul>';
          }
          ?>

    </div>
    <?php }
    get_footer();
?>