<?php
add_action('admin_menu','inscr_panel');

function inscr_panel(){
    add_menu_page('gestion des inscriptions','gestion des inscriptions','activate_plugins','inscr','render_inscr',null,86);
}

function render_inscr(){
    
    $mois='00';
    $annee='00';
    $curdate1=0;
    $curdate2=0;
    $curprof=0;
    
    if($_POST)
    {
        $curprof = wp_strip_all_tags($_POST['prof']);
        $mois = wp_strip_all_tags($_POST['mois']);
        $annee = wp_strip_all_tags($_POST['annee']);
        
        $num=array('01'=>31,'02'=>28,'03'=>31,'04'=>30,'05'=>31,'06'=>30,'07'=>31,'08'=>31,'09'=>30,'10'=>31,'11'=>30,'12'=>31);
        
        if($mois!='00' && !empty($mois) && $annee!='00' && !empty($annee))
        {
            $date1 = new DateTime($annee.'-'.$mois.'-01');
            $date2 = new DateTime($annee.'-'.$mois.'-'.$num[$mois]);
            $curdate1=$date1->getTimestamp();
            $curdate2=$date2->getTimestamp();
        }
    }
    
   $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'role'         => 'appren',
	'meta_key'     => '',
	'meta_value'   => '',
	'meta_compare' => '',
	'meta_query'   => array(),
	'include'      => array(),
	'exclude'      => array(),
	'orderby'      => 'display_name',
	'order'        => 'ASC',
	'offset'       => '',
	'search'       => '',
	'number'       => '',
	'count_total'  => false,
	'fields'       => 'all',
	'who'          => ''
    );
    
    $apprens = get_users($args);
    
        
    global $wpdb;
    
    //$table=$wpdb->prefix.'agendaprof';
    $table2=$wpdb->prefix.'users';
    
    $MNTTZ = new DateTimeZone('Europe/Paris');
    
    if($curdate1!=0 && $curdate2!=0)
    {
        if($curprof!=0){
            $rows=$wpdb->get_results("SELECT * FROM $table, $table2 WHERE $table.id_prof=$curprof AND tstart>$curdate1 AND tend<$curdate2 AND validee=1 AND $table.eleveid=$table2.ID ORDER BY 'tend' ASC", ARRAY_A);
        }else{
            $rows=$wpdb->get_results("SELECT * FROM $table, $table2 WHERE tstart>$curdate1 AND tend<$curdate2 AND validee=1 AND $table.eleveid=$table2.ID ORDER BY 'tend' ASC", ARRAY_A);
        }
    }
        
    ?>
    <div id="wpbody">
        <div id="wpbody-content">
            <div class="wrap">
                <div id="icon-options-general" class="icon32"><br/></div>
                <h2>Gestion des Heures professeur</h2>
                <br/><br/>
                <br/><br/>
                <form action="<?php echo get_template_directory_uri(); ?>/inc/exportcsvappren.php" method="post">
                    <input type="submit" name="send" id="doaction" class="button-secondary action" value="Exporter en CSV"/>
                    <input type="hidden" name="mois" value="<?php echo $mois?>" />
                    <input type="hidden" name="annee" value="<?php echo $annee?>" />
                    <input type="hidden" name="prof" value="<?php echo $curprof?>" />
                </form>
                <br/><br/>
            <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Nom élève</th>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Prénom élève</th>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Code client</th>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Nom titulaire</th>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Mail</th>
                </tr>
                </thead>
                
                <?php
                                    
                        if(count($apprens)>0)
                        {
                            foreach ($apprens as $appren)
                            {
                                echo '</tr>';
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.$appren->last_name.'</th>';
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.$appren->first_name.'</th>';
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.esc_attr( get_the_author_meta( 'codeclient', $appren->ID ) ).'</th>';
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.esc_attr( get_the_author_meta( 'nomparent', $appren->ID ) ).'</th>';
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.$appren->user_email.'</th>';
                                echo '</tr>';
                            }
                        }
                ?>
            </table>  
            </div>
        </div>
    </div>
    <?php    
}
?>