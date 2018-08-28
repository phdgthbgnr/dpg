<?php
add_action('admin_menu','questionnaire');

function questionnaire(){
    add_menu_page('gestion du questionnaire','gestion du questionnaire','activate_plugins','quest','question',null,86);
}

function question()
{
    global $wpdb;
    $table = $wpdb->prefix.'questiontitre';
    $table2 = $wpdb->prefix.'questions';
    $res=$wpdb->get_results("SELECT * FROM $table, $table2 WHERE $table2.titreid=$table.idtitrequest ORDER BY ordretitre, ordrequestion");
    $error=false;
    $titre='';
    //print_r($res);
    ?>
        <div id="wpbody">
            <div id="wpbody-content">
                <div class="wrap">
                    <form action="" method="post">
                        <div id="icon-options-general" class="icon32"><br/></div>
                        <h2>Gestion du questionnaire</h2>
                        <br/><br/>
                        <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 100%;text-align:left">titre</th>
                                </tr>
                            </thead>
                             <tbody id="the-list">
                                  <?php 
                                     foreach($res as $rs){
                                        echo '<tr>';

                                        if ($titre!=$rs->titrequest){
                                            echo '<td><input type="text" value="'.$rs->titrequest.'" style="width:612px;font-weight:bold"/></td></tr><tr>';
                                            $titre=$rs->titrequest;
                                        }
                                         $class=$rs->actif==1?'desactiver':'activer';
                                         $libel=$rs->actif==1?'Désactiver':'Activer';
                                         echo '<td style="padding-left:20px;"><input type="text" value="'.$rs->quest_texte.'" style="width:600px"/><a href="q'.$rs->quest_id.'" id="clk'.$rs->quest_id.'" class="'.$class.'" style="margin-left:10px">'.$libel.'</a></td>';
                                         echo '</tr>';
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                
                $('.desactiver').each(function(){
                        desactiver(this);
                })
                
                $('.activer').each(function(){
                        activer(this);
                })
                
                function desactiver(t){
                        $(t).click(function(){
                            var id=$(this).attr('href');
                            var tt=this;
                            id=id.substr(1);
                            $.ajax({
                                url: '<?php echo get_template_directory_uri(); ?>/inc/desac_question.php',
                                type: 'POST',
                                data: 'action=desactiver&id='+id,
                                success: function(response){
                                    //console.log(response);
                                    if($.isArray(response)){
                                        var id=response[0];
                                        $(tt).text('Activer');
                                        $(tt).toggleClass('activer desactiver');
                                        $(tt).unbind('click');
                                        activer(tt);
                                    }
                                }
                            });
                        return false;  
                     });
                }
                
                function activer(t){
                    $(t).click(function(){
                        var id=$(this).attr('href');
                        var tt=this;
                        id=id.substr(1);
                        $.ajax({
                            url: '<?php echo get_template_directory_uri(); ?>/inc/desac_question.php',
                            type: 'POST',
                            data: 'action=activer&id='+id,
                            success: function(response){
                            //console.log(response);
                                if($.isArray(response)){
                                    var id=response[0];
                                    $(tt).text('Désactiver');
                                    $(tt).toggleClass('activer desactiver');
                                    $(tt).unbind('click');
                                    desactiver(tt);
                                }
                            }
                        });
                        return false; 
                    });
                }
                
            });
            
                
        </script>
    <?php
}
?>