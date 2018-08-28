<div class="imageune">
     <h2><?php the_title() ?></h2>
    <?php $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'); ?>
     <img src="<?php echo $image_url[0]; ?>" class="alaune"/>
   
</div>
<div class="pagesmenu home">
    <?php
        $args = array(
            'theme_location'  => '',
            'menu'            => 'pages',
            'container'       => '',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'menupages',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
            );
            wp_nav_menu( $args );
    ?>
</div>
  <?php
        $root = get_category_by_slug( 'pedagogie' );
        $args = array(
            'type'                     => 'post',
            'child_of'                 => $root->term_id,
            'parent'                   => $root->term_id,
            'orderby'                  => 'name',
            'order'                    => 'ASC',
            'hide_empty'               => 1,
            'hierarchical'             => 1,
            'exclude'                  => '',
            'include'                  => '',
            'number'                   => '',
            'taxonomy'                 => 'category',
            'pad_counts'               => false 
            ); 

            $categories = get_categories( $args );
            echo '<div class="crithome">';
            echo '<ul class="pedaghome">';
            $class='';
                foreach($categories as $k=>$category)
                {
                    $args = array(
                        'post_type' => 'attachment',
                        'name' => sanitize_title($category->slug).'-home',
                        'posts_per_page' => 1,
                        'post_status' => 'inherit',
                    );
                    $src=get_template_directory_uri().'/images/blank.png';
                    $img=get_posts($args);
                    if($img) $src=wp_get_attachment_url($img[0]->ID);
                    //if($k==count($categories)-1) $class=' class="nomargin"';
                    //echo '<li'.$class.'><a href="'. get_category_link( $category->term_id ) .'"><span>'.$category->name.'</span><img src="'.$src.'"/></a></li>';
                    echo '<li><a href="'. get_category_link( $category->term_id ) .'"><span>'.$category->name.'</span><img src="'.$src.'"/></a></li>';
                }
            echo '</ul>';
            echo '<p class="clearboth"></p>';
            echo '</div>';

    ?>

<h3 class="inscript clearboth"><a href="<?php echo get_site_url().'/inscription'?>">INSCRIVEZ-VOUS</a></h3>

<h3><?php the_content() ?></h3>