<?php
require_once('../../../../wp-load.php');


if($_POST)
{
    if(isset($_POST['id']))
    {
        $ret=intval($_POST['id']);
        $action=$_POST['action'];
        if($action=='desactiver')
        {
            
            if(is_numeric($ret))
            {
                global $wpdb;
                $table=$wpdb->prefix.'questions';
                $wpdb->update($table, array('actif'=>0), array('quest_id'=>$ret), array('%d'),array('%d'));
                $return=array($ret,$action);
                wp_send_json($return);

            }
        }
        
         if($action=='activer')
        {
            
            if(is_numeric($ret))
            {
                global $wpdb;
                $table=$wpdb->prefix.'questions';
                $wpdb->update($table, array('actif'=>1), array('quest_id'=>$ret), array('%d'),array('%d'));
                $return=array($ret,$action);
                wp_send_json($return);

            }
        }
        
    }
}

?>