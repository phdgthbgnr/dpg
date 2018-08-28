<?php
/*
 * Template Name: login
 *
*/
wp_enqueue_style('login_style', get_template_directory_uri().'/_css/login_block.css',false, '1.0', 'all' );

$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'); 
if(!$image_url) 
{
    $image=$src=get_template_directory_uri().'/images/blank.png';
}else{
    $image=$image_url[0];
}

get_header(); 
?>
    <div class="common">
        <div class="fullimage">
        <img src="<?php echo $image; ?>"/>
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
		<div id="content" class="identification">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php //get_template_part( 'content', 'rajudo' ); ?>
                    <?php get_template_part( 'content-login', get_post_format() ); ?>

				<?php endwhile; // end of the loop. ?>
		</div><!-- #content -->
    </div><!-- common -->
<?php get_footer(); ?>