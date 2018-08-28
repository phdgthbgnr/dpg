<?php
/*
 * Template Name: agenda-prof
 *
*/
$logged='no';
if ( is_user_logged_in())
{

    $current_user = wp_get_current_user();
    if($current_user->roles[0]=='appren')
    {
        $logged='appren';
    }
    
     if($current_user->roles[0]=='prof')
    {
        $logged='prof';
    }
}

$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'); 
if(!$image_url) 
{
    $image=$src=get_template_directory_uri().'/images/blank.png';
}else{
    $image=$image_url[0];
}

get_header(); 
?>
        <div class="agenda agendaprof">
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
		<div id="content" class="calendrier">
            <?php
            if($logged=='appren')
            {
                global $wpdb;
                $table=$wpdb->prefix.'heures';
                $row=$wpdb->get_row("SELECT credit, consomme FROM $table WHERE eleve_id=$current_user->ID");
                if(is_object($row))
                {
                    $reste=($row->credit*60)-$row->consomme;
                    $consom=floor($row->consomme/60).'h '.($row->consomme%60).'mn';
                    $rst=floor($reste/60).'h '.($reste%60);
                    echo '<div class="consoheure">';

                    echo '<strong>Votre compte d\'heures : <span class="heures total">'.$row->credit.'</span> / heures consommées : <span class="heures consom">'.$consom.'</span> / il vous reste : <span class="heures reste">'.$rst.'mn</span></strong>';
                    echo '</div>';
                }
            }
            ?>
        
				<?php while ( have_posts() ) : the_post(); ?>

                    <?php 
                        if($logged=='prof') get_template_part( 'content-agendaprof', get_post_format() );  // gestion agenda prof
                        if($logged=='appren') get_template_part( 'content-agendappren', get_post_format() );  // inscription eleve sur agenda prof
                        if($logged=='no') get_template_part( 'content-agenda', get_post_format() );
                    ?>

				<?php endwhile; // end of the loop. ?>
		</div><!-- #content -->
        </div><!-- agenda -->
<script>
    var ajaxurl="<?php echo admin_url('admin-ajax.php')?>";
</script>

<?php 

get_footer(); 

?>