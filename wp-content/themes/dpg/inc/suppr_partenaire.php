
<?php
require_once('../../../../wp-load.php');

if($_POST)
{
    if(isset($_POST['cid']))
    {
        $ret=substr($_POST['cid'],2);
        $ret=intval($ret);
        if(is_numeric($ret))
        {
            global $wpdb;
            $table=$wpdb->prefix.'partenaires';
            $res=$wpdb->delete($table,array('idpartenaire'=>$ret));
            echo json_encode($res);

        }
    }
}

?>