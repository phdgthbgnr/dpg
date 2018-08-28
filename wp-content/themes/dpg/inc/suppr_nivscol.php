<?php
require_once('../../../../wp-load.php');

if($_POST)
{
    if(isset($_POST['cid']))
    {
        $ret=substr($_POST['cid'],3);
        $ret=intval($ret);
        if(is_numeric($ret))
        {
            global $wpdb;
            $table=$wpdb->prefix.'niveauscolaire';
            $res=$wpdb->delete($table,array('id_niv'=>$ret));
            echo json_encode($res);

        }
    }
}

?>