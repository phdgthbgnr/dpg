<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
// gestion picto header

$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'); 
if(!$image_url) 
{
    $image=$src=get_template_directory_uri().'/images/blank2.png';
}else{
    $image=$image_url[0];
}

get_header(); ?>
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
        <!--<h1 class="titre" style="margin-bottom:20px"><?php  wp_title(''); ?></h1>-->
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
        </div><!-- #common -->
<?php get_footer(); ?>