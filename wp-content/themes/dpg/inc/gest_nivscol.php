<?php
require_once('../../../../wp-load.php');

if($_POST)
{
    if(isset($_POST['action']))
    {
        
        switch($_POST['action'])
        {
            case 'desactive':
                if(isset($_POST['cid']))
                {
                    $ret=substr($_POST['cid'],3);
                    $ret=intval($ret);
                    if(is_numeric($ret))
                    {
                        global $wpdb;
                        $table=$wpdb->prefix.'niveauscolaire';
                        //$res=$wpdb->delete($table,array('id_niv'=>$ret));
                        $res = $wpdb->update($table,
                        array(
                            'inactif'=>1
                            ),
                            array(
                            'id_niv'=>$ret
                            )
                        );
                        echo json_encode($res);
                    }
                }
            break;
        
            case 'active':
                if(isset($_POST['cid']))
                {
                    $ret=substr($_POST['cid'],3);
                    $ret=intval($ret);
                    if(is_numeric($ret))
                    {
                        global $wpdb;
                        $table=$wpdb->prefix.'niveauscolaire';
                        //$res=$wpdb->delete($table,array('id_niv'=>$ret));
                        $res = $wpdb->update($table,
                        array(
                            'inactif'=>0
                            ),
                            array(
                            'id_niv'=>$ret
                            )
                        );
                        echo json_encode($res);
                    }
                }
            break;
        
            case 'monter':
                if(isset($_POST['cid']))
                {
                    $ret=substr($_POST['cid'],5);
                    $ret=intval($ret);
                    if(is_numeric($ret))
                    {
                        global $wpdb;
                        $table=$wpdb->prefix.'niveauscolaire';
                        $ordre = $wpdb->get_var( "SELECT ordreniv FROM $table where id_niv=$ret" );
                        $ordre--;
                        $res=$wpdb->query($wpdb->prepare("UPDATE $table SET ordreniv=ordreniv+1 WHERE ordreniv=%d",$ordre));
                        $res=$wpdb->query($wpdb->prepare("UPDATE $table SET ordreniv=ordreniv-1 WHERE id_niv=%d AND ordreniv>0",$ret));
                        //$res=$wpdb->delete($table,array('id_niv'=>$ret));
                        
                        echo json_encode($res);
                    }
                }
            break;
            
            case 'descendre':
                if(isset($_POST['cid']))
                {
                    $ret=substr($_POST['cid'],5);
                    $ret=intval($ret);
                    if(is_numeric($ret))
                    {
                        global $wpdb;
                        $table=$wpdb->prefix.'niveauscolaire';
                        $ordre = $wpdb->get_var( "SELECT ordreniv FROM $table where id_niv=$ret" );
                        $ordre++;
                        $res=$wpdb->query($wpdb->prepare("UPDATE $table SET ordreniv=ordreniv-1 WHERE ordreniv=%d",$ordre));
                        $res=$wpdb->query($wpdb->prepare("UPDATE $table SET ordreniv=ordreniv+1 WHERE id_niv=%d",$ret));
                        //$res=$wpdb->delete($table,array('id_niv'=>$ret));
                        
                        echo json_encode($res);
                    }
                }
            break;
            }
    }
}


?>