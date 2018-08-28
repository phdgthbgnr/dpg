		</div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php //get_sidebar( 'main' ); ?>

			<div class="pedagolist">
                <?php
                $root = get_category_by_slug( 'pedagogie' );
                $args = array(
                    'type'                     => 'post',
                    'child_of'                 => $root->term_id,
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

                $categories = get_categories( $args );
                echo '<ul><li>';
                $i=0;
                foreach ( $categories as $category ) 
                {
                    if($category->parent==$root->term_id && $i==0) echo '<a href="' . get_category_link( $category->term_id ) . '">'.$category->name.'</a><ul>';
                    if($category->parent==$root->term_id && $i==1) echo '</ul></li><li><a href="' . get_category_link( $category->term_id ) . '">'.$category->name.'</a><ul>';
                    if($category->parent!=$root->term_id)
                    {
                        echo '<li><a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a></li>';
                    }
                    $i=1;
                }
                
                echo '</ul></li></ul>';

                echo '<p class="clearboth"></p>';

                    $args = array(
                    'theme_location'  => '',
                    'menu'            => 'menufooter',
                    'container'       => '',
                    'container_class' => '',
                    'container_id'    => '',
                    'menu_class'      => 'autres',
                    'menu_id'         => '',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s',
                    'depth'           => 0,
                    'walker'          => ''
                    );
                    wp_nav_menu( $args );
                    echo '</ul>';
                ?>
               <!-- <ul class="autres"><li><a href="<?php echo get_site_url() ?>/mentions-legales">Mentions légales</a></li><li><a href="<?php echo get_site_url() ?>/contacts">Contacts</a></li></ul>-->
                
                <ul class="coord">
                    <li><strong>Adresse :</strong> 49 rue Denfert Rochereau 69004 LYON</li>
                    <li style="padding-right:.5%"><strong>Horaires d'ouverture : </strong><br/>Mardi 16h30-20h &bull; Mercredi 9h30-12h / 14h-18h &bull; Jeudi 16h30-20h &bull; Vendredi 16h30-20h &bull; Samedi 9h-12h</li>
                    <li><strong>Téléphone :</strong> 09 81 67 14 77 (horaires d'ouverture)</li>
                </ul>
                <p class="clearboth"></p>
			</div><!-- pedagolist -->
            
            
            
		</footer><!-- #colophon -->
    <div id="partenaires" class="partenaire">
        
    <?php
        global $wpdb;
        $table=$wpdb->prefix.'partenaires';
        $rows=$wpdb->get_results("SELECT * FROM $table WHERE actif=1 ORDER BY idpartenaire ASC",ARRAY_A);
        if (count($rows)>0)
        {
            echo '<p>Nos partenaires</p>';
            echo '<ul>';
            foreach($rows as $row)
            {
                echo '<li><a href="'.$row['lienpart'].'" target="_blank"><img src="'.site_url().$row['imgpart'].'"alt="'.$row['textpart'].'"/></a>';
            }
            echo '</ul>';
        }
    ?>
        <p class="clearboth"></p>
        
    </div>
	</div><!-- #page -->

	<?php wp_footer(); 
/*
   $current_user = wp_get_current_user();
    print_r(get_currentuserinfo());
    print_r($current_user);
    print_r($current_user->roles);
    */
?>
</body>
</html>