<?php
get_header();
$cat_s='';
if (is_category( )) {
    $cat = get_query_var('cat');
    $mcat = get_category ($cat);
    $currentcat = $mcat->term_id;
    $parent=get_category($mcat->parent);
    
    $root = get_category_by_slug( 'pedagogie' );
    //$cur=get_category( $currentcat );
    //$cur= get_the_category($post->ID);
 
    if($parent->parent!=0)
    {
        $cat_s=$parent->slug;
    }else{
        $cat_s=$mcat->slug;
    }
//$category = get_category( get_query_var( 'cat' ) );
//$cat_s = $category->slug;
}
$args = array(
    'post_type' => 'attachment',
    'name' => sanitize_title($cat_s.'-pct'),
    'posts_per_page' => 1,
    'post_status' => 'inherit',
     );
$src=get_template_directory_uri().'/images/blank-pct.png';
$img=get_posts($args);
if($img) $src=wp_get_attachment_url($img[0]->ID);

?>
		<div class="matieres<?php echo ' '.$cat_s?>">
            <div class="hfront">
            <img src="<?php echo $src; ?>"/>
            <h1 class="titre" style="margin-bottom:20px">Matière<br/><?php echo $mcat->name; ?></h1>
            </div>
    <div class="pagesmenu">
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
            <div class="content">
                
				<?php

                if($parent->term_id==$root->term_id)
                {
                    $query = new WP_Query( 'category__in='.$currentcat );
                    while (  $query->have_posts() ) : $query->the_post();
                        echo '<div class="hmatiere"><h2>';
                        the_content();
                        echo '<p class="clearboth"></p></h2></div>';
                    endwhile;
                    wp_reset_postdata();
                }

                while ( have_posts() ) : the_post(); 
    
                    get_template_part( 'content-category', get_post_format() ); 

					endwhile; // end of the loop. 
					wp_reset_query();
				?>
		   </div><!-- #content -->
        </div><!-- #matieres -->
<?php get_footer(); ?>