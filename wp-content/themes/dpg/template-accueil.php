<?php
/*
 * Template Name: accueil
 *
*/
get_header(); 
?>
		<div id="content" class="accueil">
            
				<?php while ( have_posts() ) : the_post(); ?>

					<?php //get_template_part( 'content', 'rajudo' ); ?>
                    <?php get_template_part( 'content-accueil', get_post_format() ); ?>

				<?php endwhile; // end of the loop. ?>
            
            <!-- pedagogie-->
    
		</div><!-- #content -->
<?php get_footer(); ?>