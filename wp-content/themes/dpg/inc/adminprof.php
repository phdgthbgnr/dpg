<?php

// supprime les champs inutiles

remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");

function ozh_personal_options() {
?>
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("#your-profile .form-table:first, #your-profile h3:first").remove();
  });
</script>
<?php
}

add_action( 'personal_options', 'ozh_personal_options');


//remove the bio
function remove_plain_bio($buffer) {
    $titles = array('#<h3>'._x('About Yourself').'</h3>#','#<h3>'._x('About the user').'</h3>#');
    $buffer=preg_replace($titles,'<h3>'._x('Password').'</h3>',$buffer,1);
    $biotable='#<h3>'._x('Password').'</h3>.+?<table.+?/tr>#s';
    $buffer=preg_replace($biotable,'<h3>'._x('Password').'</h3> <table class="form-table">',$buffer,1);
    return $buffer;
}
function profile_admin_buffer_start() { ob_start("remove_plain_bio"); }
function profile_admin_buffer_end() { ob_end_flush(); }
add_action('admin_head', 'profile_admin_buffer_start');
add_action('admin_footer', 'profile_admin_buffer_end');






// ajout champs supplementaires

/*

function modify_contact_methods($profile_fields) {
    
   unset($profile_fields['url']);

	// Add new fields
	$profile_fields['ancient'] = 'Ancienneté dans l\'enseignement';
	$profile_fields['titre1'] = 'Titre';
	$profile_fields['expe1'] = 'Année d\'exercice';
    $profile_fields['descrip1'] = 'Description';
    
    $profile_fields['titre2'] = 'Titre';
	$profile_fields['expe2'] = 'Année d\'exercice';
    $profile_fields['descrip2'] = 'Description';
    
    $profile_fields['titre3'] = 'Titre';
	$profile_fields['expe3'] = 'Année d\'exercice';
    $profile_fields['descrip3'] = 'Description';
    //echo '<tr><th>Description</th><td><textarea name="descrip3" id="descrip3" class="regular-text">'.esc_attr( get_the_author_meta( 'descrip3', $user->ID ) ).'</textarea></td></tr>';

	return $profile_fields;
}

//add_filter('user_contactmethods', 'modify_contact_methods');
*/


add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );


// photo et age

function my_show_extra_profile_fields( $user ) { 
    
    
    if($user->roles[0]=='prof')
    {
?>
<br/>
<br/>
<h3>Informations sur le professeur</h3>
 
<table class="form-table">
     <tr>
        <th><label for="adresse">Adresse</label></th>
        <td>
        <input type="text" name="adresse" id="adresse" value="<?php echo esc_attr( get_the_author_meta( 'adresse', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    <tr>
        <th><label for="adresse">Code postal</label></th>
        <td>
        <input type="text" name="cp" id="cp" value="<?php echo esc_attr( get_the_author_meta( 'cp', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    <tr>
        <th><label for="adresse">Ville</label></th>
        <td>
        <input type="text" name="ville" id="ville" value="<?php echo esc_attr( get_the_author_meta( 'ville', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <th><label for="ancient">Ancienneté dans l'enseignement</label></th>
        <td>
        <input type="text" name="ancient" id="ancient" value="<?php echo esc_attr( get_the_author_meta( 'ancient', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    
     <tr>
        <th><label for="titre1">Titre (1)</label></th>
        <td>
        <input type="text" name="titre1" id="titre1" value="<?php echo esc_attr( get_the_author_meta( 'titre1', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    <tr>
        <th><label for="expe1">Années d'expérience (1)</label></th>
        <td>
        <input type="text" name="expe1" id="expe1" value="<?php echo esc_attr( get_the_author_meta( 'expe1', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    
    <tr>
        <th><label for="descrip1">Description (1)</label></th>
        <td>
        <textarea name="descrip1" id="descrip1" class="regular-text"><?php echo esc_attr( get_the_author_meta( 'descrip1', $user->ID ) )?></textarea>
        <span class="description">Description du poste</span>
        </td>
    </tr>
    
    <tr>
        <th><label for="titre2">Titre (2)</label></th>
        <td>
        <input type="text" name="titre2" id="titre2" value="<?php echo esc_attr( get_the_author_meta( 'titre2', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    <tr>
        <th><label for="expe2">Années d'expérience (2)</label></th>
        <td>
        <input type="text" name="expe2" id="expe2" value="<?php echo esc_attr( get_the_author_meta( 'expe2', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    
    <tr>
        <th><label for="descrip2">Description (2)</label></th>
        <td>
        <textarea name="descrip2" id="descrip2" class="regular-text"><?php echo esc_attr( get_the_author_meta( 'descrip2', $user->ID ) )?></textarea>
        <span class="description">Description du poste</span>
        </td>
    </tr>
    
    <tr>
        <th><label for="titre3">Titre (3)</label></th>
        <td>
        <input type="text" name="titre3" id="titre3" value="<?php echo esc_attr( get_the_author_meta( 'titre3', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    <tr>
        <th><label for="expe3">Années d'expérience (3)</label></th>
        <td>
        <input type="text" name="expe3" id="expe3" value="<?php echo esc_attr( get_the_author_meta( 'expe3', $user->ID ) ); ?>" class="regular-text" />
        <span class="description"></span>
        </td>
    </tr>
    
    <tr>
        <th><label for="descrip3">Description (3)</label></th>
        <td>
        <textarea name="descrip3" id="descrip3" class="regular-text"><?php echo esc_attr( get_the_author_meta( 'descrip3', $user->ID ) )?></textarea>
        <span class="description">Description du poste</span>
        </td>
    </tr>
    
    
 
<tr>
<th><label for="image">Photo</label></th>
 
<td>
<img src="<?php echo esc_attr( get_the_author_meta( 'image', $user->ID)); ?>" style="height:50px;">
<input type="text" name="image" id="image" value="<?php echo esc_attr( get_the_author_meta( 'image', $user->ID ) ); ?>" class="regular-text" /><input type='button' class="button-primary" value="Téléchargez une photo" id="uploadimage"/><br />
<span class="description">Téléchargez une photo.</span>
</td>
</tr>
 
<tr>
<th><label for="image">Votre Age</label></th>
 
<td>
<input type="text" name="age" id="age" value="<?php echo esc_attr( get_the_author_meta( 'age', $user->ID ) ); ?>" class="regular-text" />
<span class="description">Entrez votre âge.</span>
</td>
</tr>
    
<tr>
    <th><label for="inactif">Professeur inactif</label></th>
    <td><input type="checkbox" id="inactif" name="inactif" value="1" class="checkbox" <?php echo getchecked('inactif', $user->ID) ?>/></td>
</tr>

 
</table>
<?php 
    }
    if($user->roles[0]=='appren')
    {
        $nomparent=esc_attr( get_the_author_meta( 'nomparent', $user->ID ) );
        $prenomparent=esc_attr( get_the_author_meta( 'prenomparent', $user->ID ) );
        $naissanceresp=esc_attr( get_the_author_meta( 'lieunaissparent', $user->ID ) );
        $datenaissance=esc_attr( get_the_author_meta( 'datenaissance', $user->ID ) );
        $nivscol=esc_attr( get_the_author_meta( 'nivscol', $user->ID ) );
        $adresse=esc_attr( get_the_author_meta( 'adresse', $user->ID ) );
        $cp=esc_attr( get_the_author_meta( 'cp', $user->ID ) );
        $ville=esc_attr( get_the_author_meta( 'ville', $user->ID ) );
        $tel=esc_attr( get_the_author_meta( 'tel', $user->ID ) );
        $mobile= esc_attr( get_the_author_meta( 'mobile', $user->ID ) );
        $codeclient= esc_attr( get_the_author_meta( 'codeclient', $user->ID ) );
        
        //echo 'code client : '.$codeclient;
        
        if (empty($codeclient))
        {
            $i=strlen($user->ID);
            $i=$i*-1;
            $str='00000';
            $str=substr($str,0,$i);
            //echo $str;
            $codeclient='dpg'.$str.$user->ID;
        }
        
        // charge les donnees d'un nouvel inscrit
        global $wpdb;
        $table=$wpdb->prefix.'inscription';
        $rows = $wpdb->get_results("SELECT * FROM $table WHERE  idwpdb=".$user->ID, ARRAY_A,0);
        
       if(count($rows)>0)
       {
            $row=$rows[0];
            
            $nomparent=empty($nomparent)?$row['nomparent']:$nomparent;
            $prenomparent=empty($prenomparent)?$row['prenomparent']:$nomparent;
            $naissanceresp=empty($naissanceresp)?$row['lieunaissparent']:$naissanceresp;
            $datenaissance=empty($datenaissance)?$row['naisseleve']:$datenaissance;
            $nivscol=empty($nivscol)?$row['scolniv']:$nivscol;
            $adresse=empty($adresse)?$row['adresse']:$adresse;
            $cp=empty($cp)?$row['cp']:$cp;
            $ville=empty($ville)?$row['ville']:$ville;
            $tel=empty($tel)?$row['tel']:$tel;
            $mobile=empty($mobile)?$row['mobile']:$mobile;
       }
        
        $table=$wpdb->prefix.'heures';
        $rows = $wpdb->get_results("SELECT * FROM $table WHERE close=0 AND eleve_id=".$user->ID, ARRAY_A,0);

        if(count($rows)>0)
        {
            $consos=$rows[0]['consomme'];
            $credit=$rows[0]['credit'];
            $credits=$credit*60;
            $tot=$credits-$consos;
            $heures=  floor($tot/60).'h '.($tot%60).'mn';
            $conso=floor($consos/60).'h '.($consos%60).'mn';
        }else{
            $conso=0;
            $credit=0;
            $heures=0;
        }
        
    ?>
<p>&nbsp;</p>
        <h3>Code client</h3>
        <table class="form-table">
            <tr>
                <th><label for="codeclient">Code client :</label></th>
                <td><input type="text" name="codeclient" id="codeclient" value="<?php echo $codeclient ?>" class="regular-text" /></td>
            </tr>
        </table>
<p>&nbsp;</p>
        <h3>Crédit d'heures</h3>
    <table class="form-table">
        <tr>
            <th><label for="creditheures">Nombre d'heures créditées :</label></th>
            <td><input type="text" name="creditheures" id="creditheures" value="<?php echo $heures ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="creditheure">Nombre total d'heures :</label></th>
            <td><input type="text" name="creditheure" id="creditheure" value="<?php echo $credit ?>" class="regular-text" disabled="disabled"/></td>
        </tr>
        <tr>
            <th><label for="consoh">Nombre d'heures consommées :</label></th>
            <td><input type="text" name="consoh" id="consoh" value="<?php echo $conso ?>" class="regular-text" disabled="disabled" /></td>
        </tr>
        <tr>
        <td>&nbsp;</td>
        </tr>
    </table>

        <h3>Informations sur l'élève</h3>
        <table class="form-table">
        <tr>
            <th><label for="nomparent">Nom du responsable (titulaire)</label></th>
            <td>
            <input type="text" name="nomparent" id="nomparent" value="<?php echo $nomparent ?>" class="regular-text" />
            <span class="description">Entrez le nom du titulaire du compte</span>
            </td>
        </tr>
        <tr>
            <th><label for="nomparent">Prénom du responsable (itulaire)</label></th>
            <td>
            <input type="text" name="prenomparent" id="prenomparent" value="<?php echo $prenomparent ?>" class="regular-text" />
            <span class="description">Entrez le prénom du titulaire du compte</span>
        </td>
        </tr>
        <tr>
            <th><label for="nomparent">Lieu de naissance du responsable (itulaire)</label></th>
            <td>
            <input type="text" name="naissanceresp" id="naissanceresp" value="<?php echo $naissanceresp ?>" class="regular-text" />
            <span class="description">Entrez le lieu de naissance du titulaire du compte</span>
        </td>
        </tr>    
        <tr>
            <th><label for="datenaissance">Date de naissance de l'élève</label></th>
            <td>
            <input type="text" name="datenaissance" id="datenaissance" value="<?php echo $datenaissance ?>" class="regular-text" />
            <span class="description">Entrez sa date de naissace au format jj/mm/aaaa</span>
            </td>
        </tr>
        <tr>
            <th><label for="nivscol">Niveau scolaire</label></th>
            <td>
                <select name="nivscol" class="lselect" required>
                <option value="">Sélectionnez un niveau scolaire</option>
                <?php
                    global $wpdb;
                    $table=$wpdb->prefix.'niveauscolaire';
                    $sql=$wpdb->prepare("SELECT * from $table WHERE inactif=0 ORDER BY 'ordreniv' DESC",1);
                    $rows = $wpdb->get_results($sql,ARRAY_A);
                    if(count($rows)>0)
                    {
                        foreach($rows as $row)
                        {
                            $slect=$nivscol==$row['id_niv']?' SELECTED="SELECTED"':'';
                            echo '<option value="'.$row['id_niv'].'"'.$slect.'>'.$row['nivscol'].'</option>';
                        }
                    }
                ?>
                    </select>
            <span class="description">Entrez le niveau scolaire</span>
            </td>
        </tr>
        <tr>
            <th><label for="adresse">Adresse</label></th>
            <td>
            <input type="text" name="adresse" id="adresse" value="<?php echo $adresse ?>" class="regular-text" />
            <span class="description">N° et rue</span>
            </td>
        </tr>
        <tr>
            <th><label for="cp">Code Postal</label></th>
            <td>
            <input type="text" name="cp" id="cp" value="<?php echo $cp ?>" class="regular-text" />
            <span class="description">Entrez le Code Postal (5 chiffres)</span>
            </td>
        </tr>
        <th><label for="ville">Ville</label></th>
            <td>
            <input type="text" name="ville" id="ville" value="<?php echo $ville ?>" class="regular-text" />
            <span class="description">Entrez la ville</span>
            </td>
        </tr>
        <th><label for="tel">Téléphone (fixe)</label></th>
            <td>
            <input type="text" name="tel" id="tel" value="<?php echo $tel ?>" class="regular-text" />
            <span class="description">(10 chiffres)</span>
            </td>
        </tr>
        <th><label for="mobile">Mobile</label></th>
            <td>
            <input type="text" name="mobile" id="mobile" value="<?php echo $mobile ?>" class="regular-text" />
            <span class="description">(10 chiffres)</span>
            </td>
        </tr>
        </table>
    <?php
    }
}


function zkr_profile_upload_js() 
{ 
?>
<script type="text/javascript">
    
    jQuery(document).ready(function() {
        jQuery(document).find("input[id^='uploadimage']").live('click', function(){
        //var num = this.id.split('-')[1];
        formfield = jQuery('#image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
         
        window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery('#image').val(imgurl);
            tb_remove();
        }
         
        return false;
        });
        
        
        jQuery('.niveau').each(function(index){
            jQuery(this).click(function(){
                jQuery(this).parent().parent().parent().parent().children().children('input').attr('checked', true);
                //console.log(jQuery(this).parent().parent().parent().html());
                
                var check=0;
                jQuery(this).parent().parent().parent().children('li').each(function(){
           
                    if(jQuery(this).children().children('input').attr('checked')=='checked') check++;
                });
                if (check==0)  jQuery(this).parent().parent().parent().parent().children().children('input').attr('checked', false);
                
            });
        });
    });
    
</script>


<?php
}

add_action('admin_head','zkr_profile_upload_js');
 
// the following is the js and css for the upload functionality
function zkr_enque_scripts_init(){
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}
add_action('init', 'zkr_enque_scripts_init');



//add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );


 
function my_save_extra_profile_fields( $user_id ) {
 
    $errors=array();
    
    if ( !current_user_can( 'edit_user', $user_id ) )
    return false;
    
    $user = new WP_User( $user_id );
     
   if($user->roles[0]=='prof')
   {
        update_user_meta( $user_id, 'image', $_POST['image'] );
        update_user_meta( $user_id, 'age', $_POST['age'] );
           
       $inactif=0;
       if(isset($_POST['inactif']) && $_POST['inactif']=='1'){
           $inactif=1;
       }
       
       // ----- ancienne gestion matiere --------------------------------------------------
       /*
        if(is_array($_POST['matiere']))
        {
            $tbmat=$_POST['matiere'];
            
            foreach ($tbmat as &$value) {
                $value = str_replace(" ", "", $value);
            }
            unset($value);
            $matiere=implode(" ", $tbmat);   
        }else{
            $matiere= $_POST['matiere'];
        }
        update_user_meta( $user_id, 'matiere', $matiere );
        */
        update_user_meta( $user_id, 'inactif', $inactif );
       
        update_user_meta( $user_id, 'adresse',$_POST['adresse'] );
       
       update_user_meta( $user_id, 'cp',$_POST['cp'] );
       
        update_user_meta( $user_id, 'ville',$_POST['ville'] );
           
        update_user_meta( $user_id, 'ancient', $_POST['ancient'] );
           
        update_user_meta( $user_id, 'titre1', $_POST['titre1'] );
        update_user_meta( $user_id, 'expe1', $_POST['expe1'] );
        update_user_meta( $user_id, 'descrip1', $_POST['descrip1'] );
           
        update_user_meta( $user_id, 'titre2', $_POST['titre2'] );
        update_user_meta( $user_id, 'expe2', $_POST['expe2'] );
        update_user_meta( $user_id, 'descrip2', $_POST['descrip2'] );
           
        update_user_meta( $user_id, 'titre3', $_POST['titre3'] );
        update_user_meta( $user_id, 'expe3', $_POST['expe3'] );
        update_user_meta( $user_id, 'descrip3', $_POST['descrip3'] );
       
       /*
       add_settings_error(
        'myUniqueIdentifyer',
        esc_attr( 'settings_updated' ),
        'pas bon',
        'error'
        );
       
        settings_errors( 'myUniqueIdentifyer' );
        */

       // ------------------------------------------------------------------------------------
       
       
        if(isset($_POST['matiere']) && is_array($_POST['matiere']))
        {
            $tbmat=$_POST['matiere'];
            
            global $wpdb;
            $table1=$wpdb->prefix.'profmatiere';
            $table2=$wpdb->prefix.'profmatinterv';
            //efface toutes les entrees du prof dans profmatiere
            $wpdb->delete( $table1, array( 'idprof' => $user_id ) );
            // et dans profmatinterv
            $wpdb->delete( $table2, array( 'profid' => $user_id ) );
            foreach($tbmat as $val)
            {
                $wpdb->insert($table1,array('idprof'=>$user_id,'idmatiere'=>$val));
                $pst='nvscol'.$val;
                if(isset($_POST[$pst]))
                {
                    //echo $pst.' : ';
                    //print_r($_POST[$pst]);
                    if(is_array($_POST[$pst]))
                    {
                        //$wpdb->delete( $table2, array( 'profid' => $user_id ) );
                        $tabniv=$_POST[$pst];
                        foreach($tabniv as $niv)
                        {
                           $wpdb->insert($table2,array('profid'=>$user_id,'matiereid'=>$val,'nivscolid'=>$niv)); 
                        }
                    }
                }
            }
        }
       
       
       // zone geo
       $incr=0;
       
       if(!empty($_POST['newzonegeo']))
        {
            $nzgeo=$_POST['newzonegeo'];
            if(!preg_match ("(^[0-9]*$)", $nzgeo) || strlen($nzgeo)!=5)
            {
                array_push($errors,'newzonegeo');
                $nzgeo='0';
            }else{
                // ajout du code postal dans la table cpost (zone geographique d'intervention)
                global $wpdb;
                $table = $wpdb->prefix.'cpost';
                
                $res=$wpdb->
                $res=$wpdb->insert(
                    $table,
                    array('cp'=>$nzgeo)
                    );
                if($res) $incr=$wpdb->insert_id;
            }
       }
       
       
       if(isset($_POST['zonegeo']) && is_array($_POST['zonegeo']))
        {
            $tbgeo=$_POST['zonegeo'];
            
            foreach ($tbgeo as &$value) {
                $value = str_replace(" ", "", $value);
            }
            unset($value);
           if($incr>0) array_push($tbgeo, $incr);
            $zonegeo=implode(" ", $tbgeo);
            update_user_meta( $user_id, 'zonegeo', $zonegeo );
        }else{
            update_user_meta( $user_id, 'zonegeo', '' );
            if($incr>0) update_user_meta( $user_id, 'zonegeo', $incr );
       }
           
       
       

       // niveaux intervention
       $incr=0;
       
       if(!empty($_POST['newnivinterv']))
        {
            $ninterv=$_POST['newnivinterv'];

            // ajout du code postal dans la table cpost (zone geographique d'intervention)
            global $wpdb;
            $table = $wpdb->prefix.'niveauscolaire';
            $max = $wpdb->get_var( "SELECT max(ordreniv) FROM $table" );
            $max++;
            $res=$wpdb->insert(
                $table,
                array('nivscol'=>$ninterv,'ordreniv'=>$max)
                );
            if($res) $incr=$wpdb->insert_id;
       }
       
       if(isset($_POST['nivinterv']) && is_array($_POST['nivinterv']))
       {
            $tbinterv=$_POST['nivinterv'];
            
            foreach ($tbinterv as &$value) {
                $value = str_replace(" ", "", $value);
            }
            unset($value);
           if($incr>0) array_push($tbinterv, $incr);
            $interv=implode(" ", $tbinterv);
            update_user_meta( $user_id, 'nivinterv', $interv );
        }else{
            update_user_meta( $user_id, 'nivinterv', '' );
            if($incr>0) update_user_meta( $user_id, 'nivinterv', $incr );
       }
            
       
       
   }
    
    
    if($user->roles[0]=='appren'){
        update_user_meta( $user_id, 'nomparent', $_POST['nomparent'] );
        update_user_meta( $user_id, 'prenomparent', $_POST['prenomparent'] );
        update_user_meta( $user_id, 'lieunaissparent', $_POST['naissanceresp'] );
        update_user_meta( $user_id, 'datenaissance', $_POST['datenaissance'] );
        update_user_meta( $user_id, 'nivscol', $_POST['nivscol'] );
        update_user_meta( $user_id, 'adresse', $_POST['adresse'] );
        update_user_meta( $user_id, 'ville', $_POST['ville'] );
        update_user_meta( $user_id, 'cp', $_POST['cp'] );
        update_user_meta( $user_id, 'tel', $_POST['tel'] );
        update_user_meta( $user_id, 'mobile', $_POST['mobile'] );
        update_user_meta( $user_id, 'codeclient', $_POST['codeclient'] );
        $credit=0;
        $tmp=wp_strip_all_tags($_POST['creditheures']);
        if(is_numeric($tmp)) $credit=$tmp;
        
        // enregistrement credit heures
        global $wpdb;
        $table=$wpdb->prefix.'heures';
        $res=$wpdb->get_results("SELECT heureid FROM $table WHERE eleve_id=$user->ID");
            if (count($res)>0)
            {
                $udp=$wpdb->update(
                    $table,
                    array('credit'=>$credit),
                    array('eleve_id'=>$user->ID));
            }else{
                $ins=$wpdb->insert(
                    $table,
                    array('eleve_id'=>$user->ID, 'credit'=>$credit)
                );
            }
        
       }
}

// matieres du prof



function champs_matieres($user)
{
    
    if($user->roles[0]!='prof') return;
    
    // recupere liste niveaux scolaires
    global $wpdb;
    $table=$wpdb->prefix.'niveauscolaire';
    $rows=$wpdb->get_results("SELECT * FROM $table WHERE inactif=0 ORDER BY ordreniv ASC",ARRAY_A,0);
    
    
    $root = get_category_by_slug( 'pedagogie' );
    $args = array(
            'type'          => 'post',
            'child_of'      => $root->term_id,
            'parent'        => $root->term_id,
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => 1,
            'hierarchical'  => 1,
            'exclude'       => '1',
            'include'       => '',
            'number'        => '',
            'taxonomy'      => 'category',
            'pad_counts'    => false );

    $categories=get_categories($args);
         
    echo '<br/><br/><h3>Matières associées au professeur : </h3>';
    echo '<ul>';
    $ind=0;                                           
    foreach ($categories as $categorys) 
    {
        echo '<li style="display:block;border:1px solid #666;width:auto;padding:8px"><span style="font-size:1.2em;display:block;margin-bottom:5px"><strong>'.$categorys->name.'</strong></span>';
        echo '<ul>';
            $args=array(
                    'type'          => 'post',
                    'child_of'      => $categorys->term_id,
                    'parent'        => '',
                    'orderby'       => 'name',
                    'order'         => 'ASC',
                    'hide_empty'    => 1,
                    'hierarchical'  => 1,
                    'exclude'       => '1',
                    'include'       => '',
                    'number'        => '',
                    'taxonomy'      => 'category',
                    'pad_counts'    => false );
            
            $category=get_categories($args);
            
            foreach ($category as $categorie) 
            {
                ?>
                <li style="display:block;margin-top:10px"><label for="<?php echo $categorie->slug ?>"><input id="<?php echo $categorie->slug ?>" type="checkbox" name="matiere[]" value="<?php echo $categorie->cat_ID ?>" class="checkbox" <?php echo getvalB('matiere', $categorie->cat_ID ,$user->ID) ?> /><label class="checkbox">&nbsp;</label><strong><?php echo $categorie->name ?></strong></label>
                <?php
                    if($rows)
                    {
                        echo '<ul>';
                        foreach ($rows as $row)
                        {
                            ?>
                            <li style="display:inline;margin-left:20px"><label for="<?php echo $categorie->slug.$row['id_niv'] ?>"><input id="<?php echo $categorie->slug.$row['id_niv'] ?>" type="checkbox" name="nvscol<?php echo $categorie->cat_ID?>[]" value="<?php echo $row['id_niv']?>" class="niveau" <?php echo getvalC('niveau', $row['id_niv'] ,$categorie->cat_ID, $user->ID) ?>/><label class="checkbox">&nbsp;</label><?php echo $row['nivscol']?></label></li>
                            <?php
                        }
                        echo '</ul>';
                    }
                ?>
                </li>
                <?php
                $ind++;
            }
         
            echo '</ul>';
           echo  '</li>';
    }
    
    echo '</ul>';  
    //echo $user->ID;
}


add_action( 'show_user_profile', 'champs_matieres' );
add_action( 'edit_user_profile', 'champs_matieres' );

add_action( 'show_user_profile', 'niveauinterv' );
add_action( 'edit_user_profile', 'niveauinterv' );


function zonegeo($user)
{
    if($user->roles[0]!='prof') return;
    echo '<br/><br/><h3>Zone géographique d\'intervention</h3>';
    $zone=esc_attr( get_the_author_meta( 'zonegeo', $user->ID));
    global $wpdb;
    $table=$wpdb->prefix.'cpost';
    $rows=$wpdb->get_results("SELECT * FROM $table ORDER BY cp ASC",ARRAY_A,0);
    echo '<ul>';
    foreach ($rows as $row)
    {
        //$selct=$zone==$row['cp']?' CHECKED="CHECKED"':'';
        echo '<li><label for="zone'.$row['id'].'"><input type="checkbox" name="zonegeo[]" value="'.$row['id'].'"'.getval('zonegeo', $row['id'],$user->ID).' id="zone'.$row['id'].'"/><label class="checkbox">&nbsp;</label>'.$row['cp'].'</li>';
    }
    echo '</ul>';
    echo '  et / ou nouvelle zone géographique (nouveau code postal) : ';
    
    echo '<input type="text" name="newzonegeo"/>';
}

add_action( 'show_user_profile', 'zonegeo' );
add_action( 'edit_user_profile', 'zonegeo' );


function niveauinterv($user)
{
    if($user->roles[0]!='prof')  return;
    /*
    echo '<br/><br/><br/><h3>Niveau d\'intervention</h3>';
    $nivo=esc_attr( get_the_author_meta( 'nivinterv', $user->ID));
    global $wpdb;
    $table=$wpdb->prefix.'niveauscolaire';
    $rows=$wpdb->get_results("SELECT id_niv, nivscol FROM $table WHERE inactif=0 ORDER BY ordreniv ASC ",ARRAY_A,0);
    echo '<ul>';
    foreach ($rows as $row)
    {
        //$selct=$zone==$row['cp']?' CHECKED="CHECKED"':'';
        echo '<li><label for="niv'.$row['id_niv'].'"><input type="checkbox" name="nivinterv[]" value="'.$row['id_niv'].'"'.getval('nivinterv', $row['id_niv'],$user->ID).' id="niv'.$row['id_niv'].'"/><label class="checkbox">&nbsp;</label>'.$row['nivscol'].'</li>';
    }
    echo '</ul>';
    */
    
    if($user->roles[0]=='prof')
    {
        echo '  <strong>Nouveau niveau d\'intervention : </strong>(ex: collège, seconde, etc..) ';
        echo '<input type="text" name="newnivinterv"/>';
        echo ' (incrémente automatiquement la liste des niveaux scolaires)';
    }
}


function getval($key,$val=0,$us)
{
    $arr=get_the_author_meta( $key, $us );
    $tbarr=explode(' ',$arr);
    if(is_array($tbarr))
    {
        if(in_array($val,$tbarr))
        {
            return ' CHECKED="CHECKED"';
        }else{
            return '';
        }
    }else{
        return '';
    }
}

function getvalB($key,$val,$us)
{
    global $wpdb;
    $table=$wpdb->prefix.'profmatiere';
    $mat=$wpdb->get_var("SELECT idprofmat FROM $table WHERE idprof=$us AND idmatiere=$val");
    if($mat)
    {
        return ' CHECKED="CHECKED"';
    }else{
        return '';
    }
}

function getvalC($key,$n,$m,$us)
{
    global $wpdb;
    $table=$wpdb->prefix.'profmatinterv';
    $niv=$wpdb->get_var("SELECT idinterv FROM $table WHERE nivscolid=$n AND matiereid=$m AND profid=$us");
    print_r($niv);
    if($niv)
    {
        return ' CHECKED="CHECKED"';
    }else{
        return '';
    }
}

function getchecked($key,$us){
    $res=get_the_author_meta( $key, $us );
    if($res==1) return 'CHECKED="CHECKED"';
    return '';
}
                                                
?>