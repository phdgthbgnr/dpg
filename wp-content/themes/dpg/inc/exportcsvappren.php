<?php

require_once('../../../../wp-load.php');
// export csv des heures professeur

    $delimit = ";";
    $endline = "\r\n";
    $entetes= array("Nom élève","Prénom éléve","Code client","email","Nom titulairet","Prénom titulaire","Lieu de naissance du titulaire","Date de naissance de l'élève","Niveau scolaire","Adresse","Code postal","Ville","Téléphone","Mobile");
    //$csvouput[]='Nom professeur'.$delimit.'Nom éléve'.$delimit.'Code client'.$delimit.'Date'.$delimit.'Date'.$delimit.'Heure début'.$delimit.'Heure fin'.$endline;

    foreach($entetes as &$entete) { 
      // Notez l'utilisation de iconv pour changer l'encodage.
      $entete = (is_string($entete)) ? 
        iconv("UTF-8", "Windows-1252//TRANSLIT", $entete) : $entete; 
    }

    

    $args = array(
        'blog_id'      => $GLOBALS['blog_id'],
        'role'         => 'appren',
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
        
        $apprens = get_users($args);
            

    //ob_end_clean();
	
	ob_start();
	
    $date=date("Y-m-d H:i:s");
    $filename='inscriptions-'.$date.'.csv';
    
    //$fp = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
	
	$fp = fopen('php://output', 'w');

    // enregistre entete
    fputcsv($fp, $entetes, $delimit);

    // enregistre le reste
	if(count($apprens)>0)
	{
		foreach($apprens as $appren)
		{
			$csvouput=array();
			
			// nom
			$data =$appren->last_name;
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			//prenom
			$data =$appren->first_name;
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			//code client
			$data=esc_attr( get_the_author_meta( 'codeclient', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// email
			$data =$appren->user_email;
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// nom titualire
			$data=esc_attr( get_the_author_meta( 'nomparent', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// prenom titualire
			$data=esc_attr( get_the_author_meta( 'prenomparent', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
		   
			// lieu naissance titualire
			$data=esc_attr( get_the_author_meta( 'lieunaissparent', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// date naissance eleve
			$data=esc_attr( get_the_author_meta( 'datenaissance', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// niveau scolaire
			$data=esc_attr( get_the_author_meta( 'nivscol', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// adresse
			$data=esc_attr( get_the_author_meta( 'adresse', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// cp
			$data=esc_attr( get_the_author_meta( 'cp', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// ville
			$data=esc_attr( get_the_author_meta( 'ville', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			// tel
			$data=esc_attr( get_the_author_meta( 'tel', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
			array_push($csvouput,$data);
			
			 // mobile
			$data=esc_attr( get_the_author_meta( 'mobile', $appren->ID ) );
			//$data =$row['display_name'];
			$data = (is_string($data)) ? iconv("UTF-8", "Windows-1252//TRANSLIT", $data) : $data;
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