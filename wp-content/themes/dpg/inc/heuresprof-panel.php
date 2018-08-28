<?php


add_action('admin_menu','heuresprof_panel');

function heuresprof_panel(){
    add_menu_page('gestion des heures professeur','gestion des heures professeur','activate_plugins','heuresprof','render_heuresprof',null,83);
}

function render_heuresprof(){
    
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
	'role'         => 'prof',
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
    
    $profs = get_users($args);
        
    global $wpdb;
    
    $table=$wpdb->prefix.'agendaprof';
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
                <form action="" method="post">
                    <select name="prof" onchange="submit()">
                        <option value="0">Tous les professeurs</option>
                        <?php
                            foreach($profs as $prof)
                            {
                                $sel='';
                                if($curprof==$prof->ID) $sel=' SELECTED="SELECTED"';
                                echo '<option value="'.$prof->ID.'"'.$sel.'>'.$prof->display_name.'</option>';
                            }
                        ?>
                    </select>
                    <br/><br/>Période :<br/>
                    <select name="mois" onchange="submit()")>
                        <option value="00"> Mois </option>
                        <option value="01" <?php if($mois=='01') echo 'SELECTED="SELECTED"';?>>Janvier</option>
                        <option value="02" <?php if($mois=='02') echo 'SELECTED="SELECTED"';?>>Février</option>
                        <option value="03" <?php if($mois=='03') echo 'SELECTED="SELECTED"';?>>Mars</option>
                        <option value="04" <?php if($mois=='04') echo 'SELECTED="SELECTED"';?>>Avril</option>
                        <option value="05" <?php if($mois=='05') echo 'SELECTED="SELECTED"';?>>Mai</option>
                        <option value="06" <?php if($mois=='06') echo 'SELECTED="SELECTED"';?>>Juin</option>
                        <option value="07" <?php if($mois=='07') echo 'SELECTED="SELECTED"';?>>Juillet</option>
                        <option value="08" <?php if($mois=='08') echo 'SELECTED="SELECTED"';?>>Août</option>
                        <option value="09" <?php if($mois=='09') echo 'SELECTED="SELECTED"';?>>Septembre</option>
                        <option value="10" <?php if($mois=='10') echo 'SELECTED="SELECTED"';?>>Octobre</option>
                        <option value="11" <?php if($mois=='11') echo 'SELECTED="SELECTED"';?>>Novembre</option>
                        <option value="12" <?php if($mois=='12') echo 'SELECTED="SELECTED"';?>>Décembre</option>
                    </select>
                    <select name="annee" onchange="submit()">
                        <option value="00"> Année </option>
                        <option value="2014" <?php if($annee=='2014') echo 'SELECTED="SELECTED"';?>>2014</option>
                        <option value="2015" <?php if($annee=='2015') echo 'SELECTED="SELECTED"';?>>2015</option>
                        <option value="2016" <?php if($annee=='2016') echo 'SELECTED="SELECTED"';?>>2016</option>
                        <option value="2017" <?php if($annee=='2017') echo 'SELECTED="SELECTED"';?>>2017</option>
                        <option value="2018" <?php if($annee=='2018') echo 'SELECTED="SELECTED"';?>>2018</option>
                        <option value="2019" <?php if($annee=='2019') echo 'SELECTED="SELECTED"';?>>2019</option>
                        <option value="2020" <?php if($annee=='2020') echo 'SELECTED="SELECTED"';?>>2020</option>
                        <option value="2021" <?php if($annee=='2021') echo 'SELECTED="SELECTED"';?>>2021</option>
                        <option value="2022" <?php if($annee=='2022') echo 'SELECTED="SELECTED"';?>>2022</option>
                        <option value="2023" <?php if($annee=='2023') echo 'SELECTED="SELECTED"';?>>2023</option>
                        <option value="2024" <?php if($annee=='2024') echo 'SELECTED="SELECTED"';?>>2024</option>
                        <option value="2025" <?php if($annee=='2025') echo 'SELECTED="SELECTED"';?>>2025</option>
                        <option value="2026" <?php if($annee=='2026') echo 'SELECTED="SELECTED"';?>>2026</option>
                        <option value="2027" <?php if($annee=='2027') echo 'SELECTED="SELECTED"';?>>2027</option>
                        <option value="2028" <?php if($annee=='2028') echo 'SELECTED="SELECTED"';?>>2028</option>
                        <option value="2029" <?php if($annee=='2029') echo 'SELECTED="SELECTED"';?>>2029</option>
                        <option value="2030" <?php if($annee=='2030') echo 'SELECTED="SELECTED"';?>>2030</option>
                    </select>
                    </form>
                <br/><br/>
                <form action="<?php echo get_template_directory_uri(); ?>/inc/exportcsv.php" method="post">
                    <input type="submit" name="send" id="doaction" class="button-secondary action" value="Exporter en CSV"/>
                    <input type="hidden" name="mois" value="<?php echo $mois?>" />
                    <input type="hidden" name="annee" value="<?php echo $annee?>" />
                    <input type="hidden" name="prof" value="<?php echo $curprof?>" />
                </form>
                <br/><br/>
            <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Nom professeur</th>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Nom élève</th>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Code client</th>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Date</th>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Heure de début</th>
                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="text-align:center">Heure de fin</th>
                </tr>
                </thead>
                
                <?php
                                    
                        if(isset($rows))
                        {
                            foreach ($rows as $val)
                            {
                                echo '</tr>';
                                if($curprof==0)
                                {
                                    $infoprof=get_userdata( $val['id_prof'] );
                                }else{
                                    $infoprof=get_userdata( $curprof );
                                }
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.$infoprof->last_name.' '.$infoprof->first_name.'</th>';
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.$val['display_name'].'</th>';
                                $ideleve=$val['eleveid'];
								echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.esc_attr( get_the_author_meta( 'codeclient', $ideleve ) ).'</th>';
                                $deb=intval($val['tstart']);
                                $hdeb=new DateTime("@$deb");
                                $hdeb->setTimezone($MNTTZ);
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.date_format($hdeb,"d-m-Y").'</th>';
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.date_format($hdeb,"H:i").'</th>';
                                $fin=intval($val['tend']);
                                $hfin=new DateTime("@$fin");
                                $hfin->setTimezone($MNTTZ);
                                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">'.date_format($hfin,"H:i").'</th>';
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