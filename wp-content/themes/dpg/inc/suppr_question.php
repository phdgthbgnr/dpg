<?php
require_once('../../../../wp-load.php');

if($_POST)
{
    if(isset($_POST['id']))
    {
        $ret=$_POST['cid'];
        $ret=intval($ret);
        if($POST['action']=='desactiver')
        {
            if(is_numeric($ret))
            {
                global $wpdb;
                $table=$wpdb->prefix.'questions';
                $res=$wpdb->delete($table,array('quest-id'=>$ret));
                //echo json_encode($res);

            }
        }
    }
}

?>