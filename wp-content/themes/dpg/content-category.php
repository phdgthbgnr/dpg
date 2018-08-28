<?php
    $category = get_category( get_query_var( 'cat' ) );
    $cat_id = $category->cat_ID;
    
    $categs=array();
    
    $categs = get_the_category($post->ID);

    $parentid=$categs[0]->parent;

    $root = get_category_by_slug( 'pedagogie' );

    /*
    echo $parentid;
    echo '<br/>';
    print_r($cat_id.' '.$categs[0]->term_id);
    */

    if($categs[0]->term_id==$cat_id && $parentid==$root->term_id ) return;

    $args = array(
        'post_type' => 'attachment',
        'name' => sanitize_title('silhouette'),
        'posts_per_page' => 1,
        'post_status' => 'inherit',
         );
    $src=get_template_directory_uri().'/images/blank-pct.png';
    $img=get_posts($args);
    if($img) $src=wp_get_attachment_url($img[0]->ID);

?>

<div class="hmatiere">
    <h3><?php the_title() ?></h3>
    <h4>Professeurs <?php the_title() ?></h4>
</div>
<div class="bloccnt">
<div class="colg">
    
	<h5><?php the_content() ?></h5>
    </div>

    <div class="cold">
   
    <?php

    $catslug=0;

    foreach($categs as $categ )
    {
        if( $categ->category_count=='1') 
        {
            $catslug=$categ->cat_ID;
            $catname=$categ->slug;
        }
    }

    if($catslug===0) return;
       // 'meta_key'     => 'matiere',
        //'meta_value'   => $catslug,
        //'meta_compare' => 'IN',
       // 'include'      => array(),
        //'exclude'      => array(),
        //'meta_query'   => array ('meta_key' => 'matiere', 'meta_value' => $catslug, 'meta_compare' => '='),
    $args = array(
        'blog_id'      => $GLOBALS['blog_id'],
        'role'         => 'prof',
        'meta_query'   => array (array('key' => 'matiere', 'value' => '-'.$catslug.'-', 'type'=>'CHAR', 'compare' => 'LIKE'), array('key' => 'inactif', 'value' => 0, 'compare' => '=')),
        'orderby'      => 'last_name',
        'order'        => 'ASC',
        'offset'       => '',
        'search'       => '',
        'number'       => '',
        'count_total'  => false,
        'fields'       => 'all',
        'who'          => ''
        );
    global $wpdb;
    $table=$wpdb->prefix.'profmatinterv';
    $table2=$wpdb->prefix.'niveauscolaire';
    $rows=$wpdb->get_results("SELECT profid, nivscolid, nivscol FROM $table,$table2 WHERE matiereid=$catslug AND $table.nivscolid=$table2.id_niv AND $table2.inactif=0");

    //print_r($rows);
    if(count($rows)>0)
    {
        foreach($rows as $row)
        {
    
        //'meta_query'   => array ( 'key' => 'matiere', 'value' => $catslug, 'compare' => 'IN'),
        $prof = get_user_by('id',$row->profid);
       
			if(is_object($prof))
			{
				echo '<ul id="proflist">';
                $userzone='';
                $userniv=0;
					if ( is_user_logged_in())
					{
						$imgsrc=get_user_meta ($prof->ID,'image',true);
						$current_user = wp_get_current_user();
						if($current_user->roles[0]=='appren')
						{
							$userzone=esc_attr( get_the_author_meta( 'cp', $current_user->ID ) );
							$userniv=esc_attr( get_the_author_meta( 'nivscol', $current_user->ID ) );
						}
					}else{
						$imgsrc=$src;
					}
                $zones=esc_attr( get_the_author_meta( 'zonegeo', $prof->ID ) );
                $zones=explode(' ',$zones);
                $zones=implode(',',$zones);
                $zones=empty($zones)?0:$zones;
                $table3=$wpdb->prefix.'cpost';
                $sql="SELECT cp FROM $table3 WHERE id in($zones)";
                $cps=$wpdb->get_results($sql);
                $zones='';
                //$uszone=esc_attr( get_the_author_meta( 'zonegeo', $prof->ID ) );
                $innivo=$row->nivscolid==$userniv?' innivo':'';
					foreach($cps as $cp)
					{
						$inzone='';
						if($userzone!='' && $userzone==$cp->cp)
						{
							$inzone=' incp';
						}
						$zones.='<span class="cp'.$inzone.'">'.$cp->cp.'</span>';
						$pok=!empty($inzone) && !empty($innivo)?' class="profok"':' class="profnot"';
					}
                $innivo=$row->nivscolid==$userniv?' innivo':'';
                $zones.='<p class="clearboth"></p>';
                /*
                if($userzone!='' && strpos($zones,$userzone)===false)
                {
                    $userzone=' (hors zone)';
                }else{
                    $userzone='';
                */
                $inactif=esc_attr( get_the_author_meta( 'inactif', $prof->ID ) );
                if($inactif==0)echo '<li><a href="'.get_site_url().'/professeur?id='.$prof->ID.'&matiere='.$catname.'"'.$pok.'><img src="'.$imgsrc.'"/><span>'.$prof->display_name.'</span><span class="zones"><span class="niveau'.$innivo.'">'.$row->nivscol.'</span>'.$zones.'</span></a></li>';

				echo '</ul>';
        
			}
		}
    }else{
        echo '<ul>';
        echo ''; //'<li>Aucun professeur disponible pour cette matière</li>';
        echo '</ul>';
    }
    ?>
        
    </div>
    <p class="clearboth"></p>
    </div>