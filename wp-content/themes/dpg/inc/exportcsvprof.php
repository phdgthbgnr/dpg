<?php

require_once('../../../../wp-load.php');
// export csv des heures professeur

    $delimit = ";";
    $endline = "\r\n";
    $entetes= array("Nom professeur","Nom éléve","Code client","Date","Heure début","Heure fin");
    //$csvouput[]='Nom professeur'.$delimit.'Nom éléve'.$delimit.'Code client'.$delimit.'Date'.$delimit.'Date'.$delimit.'Heure début'.$delimit.'Heure fin'.$endline;

    foreach($entetes as &$entete) { 
      // Notez l'utilisation de iconv pour changer l'encodage.
      $entete = (is_string($entete)) ? 
        iconv("UTF-8", "Windows-1252//TRANSLIT", $entete) : $entete; 
    }

    if($_POST)
    {
        $curprof = wp_strip_all_tags($_POST['prof']);
        $mois = wp_strip_all_tags($_POST['mois']);
        $annee = wp_strip_all_tags($_POST['annee']);
        
        $num=array('01'=>31,'02'=>28,'03'=>31,'04'=>30,'05'=>31,'06'=>30,'07'=>31,'08'=>31,'09'=>30,'10'=>31,'11'=>30,'12'=>31);
        
        if($mois!='00' && !empty($mois) && $annee!='00' && !empty($annee))
        {
            $date1 = new DateTime($annee.'-'.$mois.'-01');
            $date2 = new DateTime($annee.'-'.$mois.'-'.$num[$mois]);
            $curdate1=$date1->getTimestamp();
            $curdate2=$date2->getTimestamp();
        }
    }
    
/*
    $args = array(
        'blog_id'      => $GLOBALS['blog_id'],
        'role'         => 'prof',
        'meta_key'     => '',
        'meta_value'   => '',
        'meta_compare' => '',
        'meta_query'   => array(),
        'include'      => array(),
        'exclude'      => array(),
        'orderby'      => 'display_name',
        'order'        => 'ASC',
        'offset'       => '',
        'search'       => '',
        'number'       => '',
        'count_total'  => false,
        'fields'       => 'all',
        'who'          => ''
        );
        
        $profs = get_users($args);
            */

        global $wpdb;
        
        $MNTTZ = new DateTimeZone('Europe/Paris');

        $tablea = $wpdb->prefix.'agendaeleve';
        $tableb = $wpdb->prefix.'agendaprof';
        $tablec = $wpdb->prefix.'terms';
        $tabled = $wpdb->prefix.'users';
        
        if($curdate1!=0 && $curdate2!=0)
        {
            //$rows = $wpdb->get_results("SELECT * FROM $tableb, $tablec, $tabled WHERE $tableb.id_prof=$curprof AND $tablec.term_id=$tableb.idmatiere AND $tabled.ID=$tableb.eleveid ORDER BY $tableb.cdate DESC", ARRAY_A);
           $rows = $wpdb->get_results("SELECT * FROM $tableb, $tablec, $tabled WHERE $tableb.id_prof=$curprof AND tstart>=$curdate1 AND tend<=$curdate2 AND validee=1 AND $tablec.term_id=$tableb.idmatiere AND $tabled.ID=$tableb.eleveid ORDER BY $tableb.cdate DESC", ARRAY_A);
        }
        
        /*
        $table=$wpdb->prefix.'agendaprof';
        $table2=$wpdb->prefix.'users';

        if($curdate1!=0 && $curdate2!=0)
        {
            if($curprof!=0){
                $rows=$wpdb->get_results("SELECT * FROM $table, $table2 WHERE $table.id_prof=$curprof AND tstart>$curdate1 AND tend<$curdate2 AND validee=1 AND $table.eleveid=$table2.ID ORDER BY 'tend' ASC", ARRAY_A);
            }else{
                $rows=$wpdb->get_results("SELECT * FROM $table, $table2 WHERE tstart>$curdate1 AND tend<$curdate2 AND validee=1 AND $table.eleveid=$table2.ID ORDER BY 'tend' ASC", ARRAY_A);
            }
        }
        */


    //ob_end_clean();
	
	ob_start();
	
    $date=date("Y-m-d H:i:s");
    $filename="Heuresprof-".$date.".csv";
    
    //$fp = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
	
	$fp = fopen('php://output', 'w');

    // enregistre entete
    fputcsv($fp, $entetes, $delimit);

    // enregistre le reste
	if(count($rows)>0)
	{
		foreach($rows as $row)
		{
			$csvouput=array();
			
			// prof
			if($curprof==0)
			{
				$infoprof=get_userdata( $row['id_prof'] );
				$data=$infoprof->last_name.' '.$infoprof->first_name;
			}else{
				$infoprof=get_userdata( $curprof );
				$data=$infoprof->last_name.' '.$infoprof->first_name;
			}
			
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			//eleve
			$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			//code client
			//$infoprof=get_userdata( $row['eleveid'] );
			$idc=intval($row['eleveid']);
			$data=esc_attr( get_the_author_meta( 'codeclient', $idc ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// date
			$deb=intval($row['tstart']);
			$hdeb=new DateTime("@$deb");
			$hdeb->setTimezone($MNTTZ);
			$data=date_format($hdeb,"d-m-Y");
			array_push($csvouput,$data);
			
			// heure debut
			$data=date_format($hdeb,"H:i");
			array_push($csvouput,$data);
			
			// heure fin
			$fin=intval($row['tend']);
			$hfin=new DateTime("@$fin");
			$hfin->setTimezone($MNTTZ);
			$data=date_format($hfin,"H:i");
			array_push($csvouput,$data);
			
			fputcsv($fp, $csvouput, $delimit);
			
		}
	}
    //rewind($fp);

    $contLength = ob_get_length();
    //rewind($fp);

    $output = stream_get_contents($fp);
	
	fclose($fp);
	
    header( "Content-Type: text/csv; charset=utf-8" );
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header( "Content-Length: ".$contLength);

    echo $output;

?>