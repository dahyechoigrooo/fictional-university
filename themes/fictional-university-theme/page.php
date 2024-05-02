<?php
get_header();

while(have_posts()) {
    the_post(); 
    pageBanner();
    ?>
    <div class="container container--narrow page-section">
    
    <?php 
    // get_the_ID(); 현재 페이지의 고유 ID를 가져온다.
    // wp_get_post_parent_id(x); ID가 x인 페이지의 부모페이지를 가져온다.
        $theParent = wp_get_post_parent_id(get_the_ID());
        // theParent = 0 이면 부모 페이지가 없다. 그렇지 않으면 있다. 즉 부모페이지가 있는경우 metabox를 표시한다.
        if ($theParent) { ?> 
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>">
                <i class="fa fa-home" aria-hidden="true"></i>Back to <?php echo get_the_title($theParent); ?>
                </a> <span class="metabox__main"><?php the_title(); ?></span>
                <!-- 
                    the_title : 현재 페이지의 제목을 가져온다. 
                    get_the_title : ID의 페이지 제목을 가져온다.
                -->

            </p>
        </div>
    <?php
        }

    ?>
      <?php 
        $testArray = get_pages(array(
          'child_of' => get_the_ID()
        ));

      // get_pages() : 
      // wp_list_page() : 
      if ($theParent or $testArray) { ?> 
      <div class="page-links">
        <!-- 
          get_permarlink(x) : ID x인 페이지의 url을 가져온다. 
          get_the_title(x) : ID가 x인 페이지의 제목을 가져온다.
        -->
        <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent) ?>"><?php echo get_the_title($theParent);?></a></h2>
        <ul class="min-list">
          <?php 
            // 현재 페이지가 About Us 페이지라면 $theParent = 0
            // 현재 페이지가 Our Golas 페이지라면 $theParent = 7
            if ($theParent) {
              // 현재 페이지가 Our Goals 페이지라면 $findChildrenOf = 7
              $findChildrenOf = $theParent;
            } else {
              // 현재 페이지가 About Us 페이지라면 $findChildrenOf = 7(About Us 페이지의 ID)
              $findChildrenOf = get_the_ID();
            }

            wp_list_pages(array(
              'title_li' => NULL,
              'child_of' => $findChildrenOf ,
                // findChildrenOf(7)의 자식 페이지를 표시
              'sort_column' => 'menu_order'
            ));
          ?>
        </ul>
      </div>

      <?php }?>
   
      <div class="generic-content">
        <?php the_content(); ?>
      </div>
    </div>
    
    
    <?php }

    get_footer();
?>