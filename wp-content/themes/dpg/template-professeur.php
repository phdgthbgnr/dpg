<?php
/*
 * Template Name: professeur
 *
*/
$idprof=0;
if($_GET)
{
    $allow=false;
    
    if(!isset($_GET['id']) || !isset($_GET['matiere']))
    {
        wp_redirect( home_url() ); 
        exit;
    }
    $idprof=wp_strip_all_tags($_GET['id']);
    $userprof=get_user_by( 'id', $idprof );
    
    $curmat=wp_strip_all_tags($_GET['matiere']);
    $cat=get_category_by_slug( $curmat );
    
    if($userprof && $userprof->roles[0]=='prof' && $cat) $allow=true;
    
    // gestion picto header
    $args = array(
        'post_type' => 'attachment',
        'name' => sanitize_title('professionnel-pct'),
        'posts_per_page' => 1,
        'post_status' => 'inherit',
         );
    $src=get_template_directory_uri().'/images/blank-pct.png';
    $img=get_posts($args);
    if($img) $src=wp_get_attachment_url($img[0]->ID);
    
    $args = array(
        'post_type' => 'attachment',
        'name' => sanitize_title('silhouette'),
        'posts_per_page' => 1,
        'post_status' => 'inherit',
         );
    $srcprof=get_template_directory_uri().'/images/blank-pct.png';
    $img=get_posts($args);
    if($img) $srcprof=wp_get_attachment_url($img[0]->ID);
    
}
get_header(); 
?>
		<div class="professeur professionnel">
            <div class="hfront">
            <img src="<?php echo $src; ?>"/>
            <h1 class="titre" style="margin-bottom:20px"><?php  wp_title(''); ?></h1>
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
				<?php while ( have_posts() ) : the_post(); ?>

					<?php //get_template_part( 'content', 'rajudo' ); ?>
                    <?php get_template_part( 'content-professeur', get_post_format() ); ?>

				<?php endwhile; // end of the loop. ?>
            
            
        <?php 
        if ($allow)
        { 
                
            $logged=false;
            if ( is_user_logged_in())
            {
                $current_user = wp_get_current_user();
                if ($current_user->roles[0]=='appren') $logged='appren';
                if ($current_user->roles[0]=='prof') $logged='prof';
            }
    
            // matieres associees au prof
            $lstmat='<ul class="lstmatprf"><li class="colmat"><strong>Matières :</strong> </li><li class="colniv"><strong>Niveau : </strong></li>';
            global $wpdb;
            $table=$wpdb->prefix.'profmatinterv';
            $table2=$wpdb->prefix.'niveauscolaire';
            $rows=$wpdb->get_results("SELECT profid, matiereid, nivscol FROM $table,$table2 WHERE $table.profid=$idprof AND $table.nivscolid=$table2.id_niv AND $table2.inactif=0");
            
            foreach($rows as $row)
            {
                $lstmat.='<li class="colmat">'.get_the_category_by_ID( $row->matiereid ).'</li><li class="colniv">'.$row->nivscol.'</li>';
            }
            $lstmat.='</ul>';
            /*
            $mats=get_user_meta ($idprof,'matiere',true);
            $mats=explode(' ',$mats);
            $prefx='';
            foreach($mats as $mat)
            {
                $m=substr($mat,1);
                $lstmat.= $prefx.get_the_category_by_ID( $m );
                $prefx=', ';
            }
            */
            ?>
                
            <div class="titrprof">
                <?php if ($logged!='prof'){ ?>
                <a href="<?php echo get_site_url(); ?>/planning-professeur?id=<?php echo $idprof ?>&matiere=<?php echo $curmat?>" class="planning"><img src="<?php echo get_template_directory_uri(); ?>/images/planning-pct.png"/></a>
                <?php }else{ ?>
                <div class="planning"><img src="<?php echo get_template_directory_uri(); ?>/images/noplanning-pct.png"/></div>
                <?php } ?>
                <h2>
                <?php
                 if( is_user_logged_in()){
                ?>
                    <span class="nom"><?php echo get_user_meta ($idprof,'first_name',true).' '.get_user_meta ($idprof,'last_name',true)?></span>
                <br/>
                <?php
                 }
                ?>
                    <span class="ancient">
                        <?php 
                        $ancient=get_user_meta ($idprof,'ancient',true);
                        if(is_numeric($ancient))
                        {
                            $curyear=date("Y");
                            echo $curyear-$ancient . " ans d'ancienneté dans l'enseigement";
                        }
                        ?>
                    </span>
                    <br/>
                </h2>
                <?php
                 if( is_user_logged_in()){
                ?>
                    <img src="<?php echo get_user_meta ($idprof,'image',true)?>"/>
                <?php
                 }else{
                 ?>
                    <img src="<?php echo $srcprof ?>"/>                    
                <?php
                 }
                ?>
                
            </div>
            <?php echo $lstmat; 
            
            if ($logged=='appren' && $idprof>0){
                
                $eleveid=$current_user->ID;
                $tablea=$wpdb->prefix.'evaluation';
                $tablea2=$wpdb->prefix.'evaluationrep';
                $tablea3=$wpdb->prefix.'questions';
                $res=$wpdb->get_results("SELECT *, SUM(points) as point, COUNT($tablea.profid) as nbp FROM $tablea, $tablea2, $tablea3 WHERE $tablea.profid=$idprof AND $tablea2.ideval=$tablea.eval_id AND $tablea3.quest_id=$tablea2.questid GROUP BY $tablea3.quest_id ORDER BY $tablea3.quest_id");
                if($res){
                    $notes='<table class="evaluation profeval"><tr><td colspan="2" align="center" class="titre"><strong>Évaluation</strong></td></tr>';
                    foreach($res as $rs){
                        $class='point'.ceil($rs->point/$rs->nbp);
                        $notes.='<tr><td>'.$rs->quest_texte.'</td><td><span class="etoiles '.$class.'"></span></td></tr>';
                    }
                    $notes.='</table>';
                    echo $notes;
                }
                
                
            }
            ?>
            
            <ul>
                <li class="titre"><?php echo get_user_meta ($idprof,'titre1',true)?></li>
                <li class="expe"><?php echo get_user_meta ($idprof,'expe1',true)?></li>
                <li class="descript"><?php echo get_user_meta ($idprof,'descrip1',true)?></li>
                <li class="titre"><?php echo get_user_meta ($idprof,'titre2',true)?></li>
                <li class="expe"><?php echo get_user_meta ($idprof,'expe2',true)?></li>
                <li class="descript"><?php echo get_user_meta ($idprof,'descrip2',true)?></li>
                <li class="titre"><?php echo get_user_meta ($idprof,'titre3',true)?></li>
                <li class="expe"><?php echo get_user_meta ($idprof,'expe3',true)?></li>
                <li class="descript"><?php echo get_user_meta ($idprof,'descrip3',true)?></li>
            </ul>
                <?php
               
                    
                if($logged=='appren')
                {
                    ?>
                    <div class="connec">
                        <a href="<?php echo get_site_url(); ?>/planning-professeur?id=<?php echo $idprof ?>&matiere=<?php echo $curmat ?>">Je m'inscris sur le planning de ce professeur pour cette matière</a>
                    </div>
                    <?php
                }else{
                    if ($logged!='prof'){
                    ?>
                <div class="noconnec">
                    Vous n'êtes pas connecté<br/>
                    <a href="<?php echo get_site_url(); ?>/planning-professeur?id=<?php echo $idprof ?>&matiere=<?php echo $curmat ?>">Vous ne pourrez que consulter le planning de ce professeur pour cette matière</a>
                </div>
                    <?php
                    }
                }
    
                // liste des autres professeurs associes aux matières
                $lis='';

                
            /*
                $themat='-'.$cat->term_id.'-';
                //foreach($mats as $mat)
                //{
                    $args = array(
                        'blog_id'      => $GLOBALS['blog_id'],
                        'role'         => 'prof',
                        'meta_query'   => array (array('key' => 'matiere', 'value' => $themat, 'compare' => 'LIKE')),
                        'orderby'      => 'last_name',
                        'order'        => 'ASC',
                        'offset'       => '',
                        'exclude'      => $idprof,
                        'search'       => '',
                        'number'       => '',
                        'count_total'  => false,
                        'fields'       => 'all',
                        'who'          => ''
                        ); 
                
                    $profs = get_users( $args );
                    */
                    $m=$cat->term_id;
                    $profs=$wpdb->get_results("SELECT * from $table, $table2 WHERE $table.matiereid=$m AND $table.profid!=$idprof AND $table.nivscolid=$table2.id_niv AND $table2.inactif=0");
                        //echo ($mat);
                    if(count($profs>0))
                    {
                        foreach($profs as $prof)
                        {
                            $prf=get_user_by( 'id', $prof->profid );
                            if(is_object($prf))
							{
								$ancient=get_user_meta ($prf->ID,'ancient',true);
								$ancien='';
								if(is_numeric($ancient))
								{
									$curyear=date("Y");
									$ancien = $curyear-$ancient . " ans d'ancienneté dans l'enseigement";
								}
								
								if(is_user_logged_in()){
									$imgsrc=get_user_meta ($prf->ID,'image',true);
									$lis.='<li><a href="'.get_site_url().'/professeur?id='.$prf->ID.'&matiere='.$curmat.'"><h4><span class="nom">'.$prf->display_name.'</span><br/><span class="expe">'.$ancien.'</span><br/>Matière enseignée : '.get_the_category_by_ID( $m ).'</h4><img src="'.$imgsrc.'"/></a></li>';
								}else{
									$imgsrc=$srcprof;
									$lis.='<li><a href="'.get_site_url().'/professeur?id='.$prf->ID.'&matiere='.$curmat.'"><h4><span class="expe">'.$ancien.'</span><br/>Matière enseignée : '.get_the_category_by_ID( $m ).'</h4><img src="'.$imgsrc.'"/></a></li>';
								}
                            }
                        }
                    }
               // }
                if (!empty($lis))
                {
                ?>   
                <h4 class="ttre">Les autres professeurs associés à cette matière :</h4>
                <ul class="profassoc">
                    <?php echo $lis; ?>
                </ul>
                
           <?php 
                }
            }    
            ?> 
                
            </div><!-- #content -->
		</div><!-- #professeur -->
<?php get_footer(); ?>