
<?php
require_once('../../../../wp-load.php');

if($_POST)
{
    if(isset($_POST['cid']) && isset($_POST['action']))
    {
        $action=wp_strip_all_tags($_POST['action']);
        $ret=substr($_POST['cid'],2);
        $ret=intval($ret);
        if(is_numeric($ret))
        {
            global $wpdb;
            $table=$wpdb->prefix.'partenaires';
            if($action=='activer')
            {
                $res=$wpdb->update($table,array('actif'=>1),array('idpartenaire'=>$ret));
                echo json_encode($res);
            }
            
            if($action=='desactiver')
            {
                $res=$wpdb->update($table,array('actif'=>0),array('idpartenaire'=>$ret));
                echo json_encode($res);
            }

        }
    }
}

?>