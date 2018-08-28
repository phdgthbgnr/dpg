<?php

    if(!defined('ABSPATH')) die('acces interdit');

   // wp_clear_scheduled_hook('validate_cron');

    // planification cron validate
    add_action('wp', 'validate_cron_scheduled');

    function validate_cron_scheduled()
    {
        if(!wp_next_scheduled('validate_cron'))
        {
            wp_schedule_event(time(),'daily','validate_cron');
        }
    }

    add_action('validate_cron','do_validate_cron');

    function do_validate_cron()
    {
      
        global $wpdb;
        $table=$wpdb->prefix.'agendaprof';
        $table2=$wpdb->prefix.'agendaeleve';
        $date = new DateTime();
        $curdate=$date->getTimestamp();
        
        $rows=$wpdb->get_results("SELECT * FROM $table WHERE retenu=1 AND validee=0");
        $j=3600*24;
         //mkdir('test'.count($rows));
        foreach($rows as $row)
        {
            $tstart=$row->tstart;
            $id=$row->idagn;
            $tstart=$tstart;
            $eleveid=$row->eleveid;
            $profid=$row->id_prof;
            //die($curdate-$tstart);
            
            $test=round(($tstart-$curdate)/$j);
            //$wpdb->update($table,array('test'=>$test), array('idagn'=>$id));
            if($test<3)
            {
                $wpdb->update($table,array('validee'=>1), array('idagn'=>$id), array('%d'));
                $wpdb->update($table2,array('valide'=>1), array('idagenprof'=>$id), array('%d'));
                
                
                
                add_filter( 'wp_mail_content_type', 'set_html_content_type' );
                
                $headers = 'From: dpg-formation.fr <no-reply@dpg-formation.fr>' . "\r\n";
                
                // envoi du mail auto à l'élève
                $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr>';
                $message.='<td align="center">';
                $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
                $message.='<tr>';
                $message.='<td height="50"><font size="3"><strong>Validation automatique du cours</strong></font><td>';
                $message.='</tr>';
                $deb=intval($row->tstart);
                $MNTTZ = new DateTimeZone('Europe/Paris');
                $hdeb=new DateTime("@$deb");
                $hdeb->setTimezone($MNTTZ);
                $message.='<tr>';
                $message.='<td><font size="3">Le cours du '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</font></td>';
                $message.='</tr>';
                $fin=intval($row->tend);
                $hfin=new DateTime("@$fin");
                $diff = $hdeb->diff($hfin);
                
                $message.='<tr>';
                $message.='<td><font size="3">Pour une durée de '.$diff->format('%h').'h '.$diff->format('%i').'mn</font></td>';
                $message.='</tr>';
                $imd=$row->idmatiere;
                $mat=get_category($imd);
                $message.='<tr>';
                $message.='<td><font size="3">Matière enseignée : '.$mat->cat_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr>';
                $prof=get_user_by( 'id', $profid );
                $message.='<td><font size="3">Professeur : '.$prof->first_name.' '.$prof->last_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<p><strong>Ce cours a été validé</strong></p>';
                $message.='</table>';
                $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
                
                $eleve=get_user_by( 'id', $eleveid );
                
                wp_mail( $eleve->user_email, 'dpg-formation.fr : validation de cours', $message, $headers );
                
                
                // envoi du mail auto au professeur
                $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<tr>';
                $message.='<td align="center">';
                $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
                $message.='<tr>';
                $message.='<td height="50"><font size="3"><strong>Validation automatique du cours</strong></font><td>';
                $message.='</tr>';
                $deb=intval($row->tstart);
                $MNTTZ = new DateTimeZone('Europe/Paris');
                $hdeb=new DateTime("@$deb");
                $hdeb->setTimezone($MNTTZ);
                $message.='<tr>';
                $message.='<td><font size="3">Le cours du '.date_format($hdeb,"d-m-Y").' à '.date_format($hdeb,"H:i").'</font></td>';
                $message.='</tr>';
                $fin=intval($row->tend);
                $hfin=new DateTime("@$fin");
                $diff = $hdeb->diff($hfin);
                
                $message.='<tr>';
                $message.='<td><font size="3">Pour une durée de '.$diff->format('%h').'h '.$diff->format('%i').'mn</font></td>';
                $message.='</tr>';
                $imd=$row->idmatiere;
                $mat=get_category($imd);
                $message.='<tr>';
                $message.='<td><font size="3">Matière enseignée : '.$mat->cat_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr>';
                $eleve=get_user_by( 'id', $eleveid );
                $message.='<td><font size="3">Elève : '.$eleve->first_name.' '.$eleve->last_name.'</font><td>';
                $message.='</tr>';
                $message.='<tr><td>&nbsp;</td></tr>';
                $message.='<p><strong>Ce cours a été validé</strong></p>';
                $message.='</table>';
                $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
                
                $prof=get_user_by( 'id', $profid );
                wp_mail( $prof->user_email, 'dpg-formation.fr : validation de cours', $message, $headers );
                                
            }
        }
       // $now=
        //$rows=$w
        
    }

?>