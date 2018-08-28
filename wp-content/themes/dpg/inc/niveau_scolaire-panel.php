<?php
add_action('admin_menu','niveau_scolaire_panel');

function niveau_scolaire_panel()
{
    add_menu_page('gestion des niveaux scolaires','gestion des niveaux scolaires','activate_plugins','niv_scol','niv_scol_cp',null,84);
}

function niv_scol_cp()
{
    global $wpdb;
    
    
     // ajout niveau scolaire
    if(isset($_POST['action']))
    {
        if($_POST['action']=='ajouter')
        {
            $niv= wp_strip_all_tags($_POST['nivscol']);
            $niv=trim($niv);
            if(!empty($niv))
            {
                $table = $wpdb->prefix.'niveauscolaire';
                $max = $wpdb->get_var( "SELECT max(ordreniv) FROM $table" );
                $max++;
                $wpdb->insert( $table,array('nivscol'=>$niv,'ordreniv'=>$max));
            }
        }
    }
    
    // affichage niveau scolaire

    $table=$wpdb->prefix.'niveauscolaire';
    $max = $wpdb->get_var( "SELECT max(ordreniv) FROM $table" );
    $sql=$wpdb->prepare("SELECT * from $table ORDER BY ordreniv ASC",1);
    $rows = $wpdb->get_results($sql,ARRAY_A);
    ?>
    <div id="wpbody">
    <div id="wpbody-content">
        <div class="wrap">
        <form action="" method="post">
            <div id="icon-options-general" class="icon32"><br/></div>
            <h2>Gestion des niveaux scolaires (pour la pré-inscription)</h2>
    <?php
    if(count($rows)>0)
    {
        ?>
        <br/><br/>
        <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0" style="width:90%">
        <thead>
        <tr>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 10%;text-align:center">Id</th>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 80%;text-align:center">Niveau scolaire</th>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 15%;text-align:center">Ordre</th>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 15%;text-align:center">Action</th>
        </tr>
        </thead>
        <tbody id="the-list">
        <?php
        foreach ($rows as $val)
        {
            echo '<tr>';
            foreach($val as $k=>$v)
            {
                if($k=='id_niv') $id=$v;
                if($k=='inactif') continue;
                if($k=='ordreniv')
                {
                    echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center">';
                    if($v>0) echo '<a href="#" id="ordre'.$id.'" class="monter">Monter</a><br/>';
                    if($v<$max)echo '<a href="#" id="ordre'.$id.'" class="descendre">Descendre</a>';
                    echo '</th>';
                    continue;
                }
                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px">'.$v.'</th>';
            }
            if($v==0)
            {
            echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center"><a href="#" id="niv'.$id.'" class="desactniv">Désactiver</a></th>';
            }else{
                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px;text-align:center"><a href="#" id="niv'.$id.'" class="actniv">Activer</a></th>';
            }
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        
    }else{
        ?>
        <br/><br/>
        <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 5%;text-align:center">Aucun niveau scolaire</th>
        </tr>
        </thead>
        </table>
    <?php
    }
    ?>
        <br/>
        <div class="tablenav bottom">
            <div class="alignleft actions">
                <input type="text" name="nivscol" id="nivscol" />
                <input type="submit" name="" id="doaction2" class="button-secondary action" value="ajouter"/>
                <input type="hidden" name="action" id="ajouter" class="button-secondary action" value="ajouter"/>
            </div>
        </div>    
        </form>
        </div>
        </div>
        </div>
        
        <script type="text/javascript">
            
        jQuery(document).ready(function($){
            
            // desactive niveau scolaire
            $('.desactniv').each(function(){
                $(this).click(function(){
                    var id=$(this).attr('id');
                    $.ajax({
                        url: '<?php echo get_template_directory_uri(); ?>/inc/gest_nivscol.php',
                        type: 'POST',
                        data: 'action=desactive&cid='+id,
                        success: function(response){
                            window.location.reload();
                                        
                        }
                    });
                    return false;
                });
                
            });
            
            // reactive niveau scolaire
            $('.actniv').each(function(){
                $(this).click(function(){
                    var id=$(this).attr('id');
                    $.ajax({
                        url: '<?php echo get_template_directory_uri(); ?>/inc/gest_nivscol.php',
                        type: 'POST',
                        data: 'action=active&cid='+id,
                        success: function(response){
                            window.location.reload();
                                        
                        }
                    });
                    return false;
                });
                
            });
            
            // ordre : monter
            $('.monter').each(function(){
                $(this).click(function(){
                    var id=$(this).attr('id');
                    $.ajax({
                        url: '<?php echo get_template_directory_uri(); ?>/inc/gest_nivscol.php',
                        type: 'POST',
                        data: 'action=monter&cid='+id,
                        success: function(response){
                           window.location.reload();
                                        
                        }
                    });
                    return false;
                });
                
            });
            
            // ordre : descendre
            $('.descendre').each(function(){
                $(this).click(function(){
                    var id=$(this).attr('id');
                    $.ajax({
                        url: '<?php echo get_template_directory_uri(); ?>/inc/gest_nivscol.php',
                        type: 'POST',
                        data: 'action=descendre&cid='+id,
                        success: function(response){
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