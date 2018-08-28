<?php
/*
 * Template Name: contact
 *
*/
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
            <!--<h1 class="titre" style="margin-bottom:20px"><?php  wp_title(''); ?></h1>-->
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
		<div id="content" role="main">
				<?php while ( have_posts() ) : the_post(); ?>

                    <?php 
                        get_template_part( 'content-contact', get_post_format() ); 

                    ?>

				<?php endwhile; // end of the loop. ?>
		</div><!-- #content -->
        </div><!-- common -->
<script>
    var ajaxurl="<?php echo admin_url('admin-ajax.php')?>";
</script>

<?php 

get_footer(); 

?>