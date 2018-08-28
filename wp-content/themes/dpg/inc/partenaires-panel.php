<?php
add_action('admin_menu','partenaires_panel');

function partenaires_panel(){
    add_menu_page('page partenaires','page partenaires','activate_plugins','partenaires','render_parten',null,85);
}

function render_parten()
{
    
    global $wpdb;
    $table = $wpdb->prefix.'partenaires';
    $error=false;
    
    // ajout cp
    if(isset($_POST['action']))
    {
        if($_POST['action']=='ajouter')
        {
            $image = wp_strip_all_tags($_POST['image']);
            $pos=strpos($image,'/wp-content');
            $image=substr($image,$pos);
            $lien = wp_strip_all_tags($_POST['lienpart']);
            $texte = wp_strip_all_tags($_POST['textepart']);
            //if(!preg_match ("(^[0-9]*$)", $cp) || strlen($cp)!=5) $error=true;
            if(!$error)
            {  
                $wpdb->insert( $table,array('lienpart'=>$lien,'imgpart'=>$image,'textpart'=>$texte));
            }
        }
    }
    
    ?>
    <div id="wpbody">
    <div id="wpbody-content">
        <div class="wrap">
        <form action="" method="post">
            <div id="icon-options-general" class="icon32"><br/></div>
            <h2>Gestion des partenaires</h2>
            <br/><br/>
            <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0" style="width:100%">
                <thead>
                <tr>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 20%;text-align:left">Image</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 30%;text-align:left">Lien</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 36%;text-align:left">Texte associé</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 7%;text-align:center">Statut</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 7%;text-align:center">Action</th>
                </tr>
                </thead>
                <tbody id="the-list">
                    <?php
                        $rows=$wpdb->get_results("SELECT * FROM $table ORDER BY idpartenaire ASC",ARRAY_A);
                        if(count($rows)>0)
                        {
                            
                            foreach($rows as $row)
                            {
                                echo '<tr>';
                                $active=$row['actif']=='1'?'desactiver':'activer';
                                echo '<td><img src="'.site_url().$row['imgpart'].'"/></td><td><a href="'.$row['lienpart'].'">'.$row['lienpart'].'</a></td><td>'.$row['textpart'].'</td><td><a href="#" id="ac'.$row['idpartenaire'].'" class="'.$active.'">'.$active.'</a></td><td style="text-align:center"><a href="#" id="pr'.$row['idpartenaire'].'" class="effcp">Supprimer</a></td>';
                                echo '<tr>';
                            }
                        }
                    ?>
                </tbody>
            </table>
            <br/>
            <div class="tablenav bottom">
                <div class="alignleft actions">
                    <label for="imagepart" style="display:block;width:120px;float:left;">Image : (250x70)</label>
                    <!--<input type="text" name="imagepart" id="imagepart" /><br/>-->
                    <input type="text" name="image" id="image" value="" class="regular-text" /><input type='button' class="button-primary" value="Téléchargez une photo" id="uploadimage"/><br/>
                    <label for="lienpart" style="display:block;width:120px;float:left;">Lien :</label>
                    <input type="text" name="lienpart" id="lienpart" style="width:300px"/><br/>
                    <label for="textepart" style="display:block;width:120px;float:left;">Texte :</label>
                    <input type="text" name="textepart" id="textepart" style="width:300px" /><br/>
                    <input type="submit" name="" id="doaction3" class="button-secondary action" value="ajouter"/>
                    <input type="hidden" name="action" id="ajouter" class="button-secondary action" value="ajouter"/>
                </div>
            </div>   
            </form>
            </div>
    </div>
    </div>
 <script type="text/javascript">
            
        jQuery(document).ready(function($){
            
            $('.effcp').each(function(){
                $(this).click(function(){
                    var id=$(this).attr('id');
                    $.ajax({
                        url: '<?php echo get_template_directory_uri(); ?>/inc/suppr_partenaire.php',
                        type: 'POST',
                        data: 'cid='+id,
                        success: function(response){
                            //console.log(response);
                            window.location.reload();
                                        
                        }
                    });
                    return false;
                });
                
            });
            
            $('.desactiver').each(function(){
                $(this).click(function(){
                    var id=$(this).attr('id');
                    $.ajax({
                        url: '<?php echo get_template_directory_uri(); ?>/inc/active_partenaire.php',
                        type: 'POST',
                        data: 'action=desactiver&cid='+id,
                        success: function(response){
                            //console.log(response);
                            window.location.reload();
                                        
                        }
                    });
                    return false;
                });
                
            });
            
            $('.activer').each(function(){
                $(this).click(function(){
                    var id=$(this).attr('id');
                    $.ajax({
                        url: '<?php echo get_template_directory_uri(); ?>/inc/active_partenaire.php',
                        type: 'POST',
                        data: 'action=activer&cid='+id,
                        success: function(response){
                            //console.log(response);
                            window.location.reload();
                                        
                        }
                    });
                    return false;
                });
                
            });
            
        });
            
        </script>
    <?php  
}
?>