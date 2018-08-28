<?php
/*
 * Template Name: rechercher un cours
 *
*/
if ( !is_user_logged_in())
{
    wp_redirect( home_url() ); 
    exit;
}else{
    $current_user = wp_get_current_user();
    if($current_user->roles[0]!='appren')
    {
        wp_redirect( home_url() ); 
        exit;
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
            <div id="content" class="rechcours">
                    <?php while ( have_posts() ) : the_post(); ?>
    
                        <?php //get_template_part( 'content', 'rajudo' ); ?>
                        <?php get_template_part( 'content-rechcours', get_post_format() ); ?>
    
                    <?php endwhile; // end of the loop. 
                $cp=esc_attr( get_the_author_meta( 'cp', $current_user->ID ) );
                $niv=esc_attr( get_the_author_meta( 'nivscol', $current_user->ID ) );
                global $wpdb;
                $table=$wpdb->prefix.'niveauscolaire';
                $row=$wpdb->get_row("SELECT nivscol FROM $table WHERE id_niv = ".$niv);
                $nivscol=$row->nivscol;
                ?>
                <br/><p>Votre code postal : <strong><?php echo $cp ?></strong></p><br/>
                <p>Votre niveau scolaire : <strong><?php echo $nivscol ?></strong></p>
                <br/>
                <?php
                    $table=$wpdb->prefix.'cpost';
                    $row=$wpdb->get_row("SELECT id FROM $table WHERE cp = ".$cp);
                    $idcp=is_object($row)?$row->id:0;
        
                    if($idcp>0)
                    {
                    $args = array(
                            'blog_id'      => $GLOBALS['blog_id'],
                            'role'         => 'prof',
                            'meta_query'   => array (array('key' => 'nivinterv', 'value' => $niv, 'type'=>'CHAR', 'compare' => '='), array('key' => 'zonegeo', 'value' => $idcp, 'compare' => 'LIKE'), array('key' => 'inactif', 'value' => 0, 'compare' => '=')),
                            'orderby'      => 'last_name',
                            'order'        => 'ASC',
                            'offset'       => '',
                            'search'       => '',
                            'number'       => '',
                            'count_total'  => false,
                            'fields'       => 'all',
                            'who'          => ''
                            ); 

                    $profs=get_users($args);
                    echo '<ul class="coursprof">';
                    if(count($profs)>0)
                        {
                        foreach($profs as $prof)
                        {
                            $mat=esc_attr( get_the_author_meta( 'matiere', $prof->ID ) );
                            $matieres=explode(' ',$mat);
                            $cats='';
                            foreach($matieres as $matiere)
                            {
                                $curmat=substr($matiere,1,-1);
                                $cat=get_category($curmat);
                                $cats.='<span class="profmat">'.$cat->name.'</span>';
                            }
                            echo '<li><a href="#">Professeur : '.$prof->display_name.'</a></li>';
                            echo '<li>Matières enseignées par ce professeur : '.$cats;
                        }
                        echo '</ul>';
                        }else{
                            echo 'Il n\'y a aucun professeur disponible correspondant à votre zone d\'intervention (<strong>'.$cp.'</strong>) et votre niveau scolaire (<strong>'.$nivscol.'</strong>)';
                        }
                    }else{
                        echo 'Il n\'y a aucun professeur disponible correspondant à votre zone d\'intervention (<strong>'.$cp.'</strong>) et votre niveau scolaire (<strong>'.$nivscol.'</strong>)';
                    }
                ?>
                <div class="rechcoursfrm">
                    <h2>Effectuer une recherche personnalisée</h2>
                    <form id="frmrech" method="post">
                        <select name="matiere">
                            <option value="0">Matière</option>
                            <?php
                               $catroot = get_category_by_slug( 'pedagogie' );
                                $args = array(
                                'type'                     => 'post',
                                'child_of'                 => $catroot->term_id,
                                'parent'                   => '',
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
                                
                                $categories=get_categories($args);
                                foreach($categories as $category)
                                {
                                   if($category->parent != $catroot->term_id) echo '<option value="'.$category->term_id.'">'.$category->cat_name.'</option>';
                                }
                            ?>
                        </select>
                        <select name="niveau">
                            <option value="0">Niveau scolaire</option>
                            <?php
                                $table=$wpdb->prefix.'niveauscolaire';
                                $rows=$wpdb->get_results("SELECT * FROM $table WHERE inactif=0 ORDER BY ordreniv ASC");
                                foreach($rows as $row)
                                {
                                    echo '<option value="'.$row->id_niv.'">'.$row->nivscol.'</option>';
                                }
                            ?>
                        </select>
                        <select name="cp">
                            <option value="0">Code postal</option>
                            <?php
                                $table=$wpdb->prefix.'cpost';
                                $rows=$wpdb->get_results("SELECT * FROM $table ORDER BY cp ASC");
                                foreach($rows as $row)
                                {
                                    echo '<option value="'.$row->id.'">'.$row->cp.'</option>';
                                }
                            ?>
                        </select>
                        <br/>
                        <input type="hidden" name="action" value="rechcours" id="action"/>
                        <?php wp_nonce_field('ajax_rechcours_nonce','security'); ?>
                        <input id="sendrech" type="submit" value="Rechercher" class="btsend"/>
                    </form>
                </div><!-- .rechcoursfrm -->
                <div class="resultcours" id="resultcours">
                    
                </div><!-- .resultcours -->
                </div><!-- #content (rechcours)-->
		</div><!-- #content -->
        </div><!-- common -->
<script>var ajaxurl="<?php echo admin_url('admin-ajax.php')?>"</script>
<?php get_footer(); ?>