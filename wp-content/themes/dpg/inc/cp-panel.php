<?php
add_action('admin_menu','cp_panel');

function cp_panel(){
    add_menu_page('gestion des codes postaux','gestion des codes postaux','activate_plugins','cp','render_cp',null,81);
}

function render_cp()
{
    global $wpdb;
    $error=false;
    
    // ajout cp
    if(isset($_POST['action']))
    {
        if($_POST['action']=='ajouter')
        {
            $cp= wp_strip_all_tags($_POST['codepost']);
            if(!preg_match ("(^[0-9]*$)", $cp) || strlen($cp)!=5) $error=true;
            if(!$error)
            {
                $table = $wpdb->prefix.'cpost';
                $wpdb->insert( $table,array('cp'=>$cp));
            }
        }
    }
    
    
    // affichage cp

    $table=$wpdb->prefix.'cpost';
    $sql=$wpdb->prepare("SELECT * from $table ORDER BY 'cp' DESC",1);
    $rows = $wpdb->get_results($sql,ARRAY_A);
    ?>
    <div id="wpbody">
    <div id="wpbody-content">
        <div class="wrap">
        <form action="" method="post">
            <div id="icon-options-general" class="icon32"><br/></div>
            <h2>Gestion des codes postaux (zones d'interventions)</h2>
    <?php
    if(count($rows)>0)
    {
        ?>
        <br/><br/>
        <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0" style="width:40%">
        <thead>
        <tr>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 10%;text-align:center">Id</th>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 80%;text-align:center">Code poatal</th>
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 20%;text-align:center">action</th>
        </tr>
        </thead>
        <tbody id="the-list">
        <?php
        foreach ($rows as $val)
        {
            echo '<tr>';
            foreach($val as $k=>$v)
            {
                if($k=='id') $id=$v;
                echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px">'.$v.'</th>';
            }
            echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px"><a href="#" id="cp'.$id.'" class="effcp">Supprimer</a></th>';
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
        <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 5%;text-align:center">aucun code postal</th>
        </tr>
        </thead>
        </table>
    <?php
    }

?>
            <br/>
        <div class="tablenav bottom">
            <div class="alignleft actions">
                <input type="text" name="codepost" id="cpst" />
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
            
            $('.effcp').each(function(){
                $(this).click(function(){
                    var id=$(this).attr('id');
                    $.ajax({
                        url: '<?php echo get_template_directory_uri(); ?>/inc/suppr_cp.php',
                        type: 'POST',
                        data: 'cid='+id,
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