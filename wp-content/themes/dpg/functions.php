<?php

// enlever l'affichage du n° de version de WP
remove_action('wp_head', 'wp_generator');

// ajoute le support des menus
//**/add_theme_support('menus');

// ajoute le support des images a la une
add_theme_support( 'post-thumbnails');

// renregistre menu general
register_nav_menu( 'general', 'menu general');

// message d'erreur personnalisés dans l'admin
function check_fields($errors, $update, $user) {
    //print_r($errors);
    //print_r($_POST);
    if($_POST['role']=='appren')
    {
        foreach($_POST as $k => $v)
        {
            switch($k)
            {
                case 'nomparent':
                    if (empty($v)) $errors->add('nomparent',__('Le nom du responsable est obligatoire'));
                break;
                case 'prenomparent':
                    if (empty($v)) $errors->add('prenomparent',__('Le prénom du responsable est obligatoire'));
                break;
                case 'naissanceresp':
                    if (empty($v)) $errors->add('naissanceresp',__('La date de naissance du responsable est obligatoire'));
                break;
                case 'nivscol':
                    if (empty($v)) $errors->add('nivscol',__('Le niveau scolaire est obligatoire'));
                break;
                case 'adresse':
                    if( empty($v)) $errors->add('adresse',__('L\'adresse est obligatoire'));
                break;
                case 'cp':
                    if (empty($v)) $errors->add('cp',__('Le code postal est obligatoire'));
                break;
                case 'ville':
                    if (empty($v)) $errors->add('ville',__('La ville est obligatoire'));
                break;
                case 'tel':
                    if (empty($v)) $errors->add('tel',__('Le numéro de téléphone est obligatoire'));
                break;
                case 'mobile':
                    if (empty($v)) $errors->add('mobile',__('Le numéro de mobile est obligatoire'));
                break;
                case 'creditheures':
                    if (empty($v) || $v==0) $errors->add('creditheures',__('Le nombre d\'heures créditées est obligatoire'));
                break;
            }
        }
    }
    
    if($_POST['role']=='prof')
    {
        foreach($_POST as $k => $v)
        {
            switch($k)
            {
                case 'adresse':
                    if( empty($v)) $errors->add('adresse',__('L\'adresse est obligatoire'));
                break;
                case 'cp':
                    if (empty($v)) $errors->add('cp',__('Le code postal est obligatoire'));
                break;
                case 'ville':
                    if (empty($v)) $errors->add('ville',__('La ville est obligatoire'));
                break;
                case 'ancient':
                    if (empty($v) || !preg_match ("(^[0-9]*$)", $v) || strlen($v)!=4) $errors->add('ancient',__('Une date d\'ancienneté valide est obligatoire'));
                break;
                case 'titre1':
                case 'titre2':
                case 'titre3':
                    if (empty($v)) $errors->add('titres',__('les champs titre sont obligatoires'));
                break;
                case 'expe1':
                case 'expe2':
                case 'expe3':
                    if (empty($v)) $errors->add('titres',__('les champs années d\'expérience sont obligatoires'));
                break;
                case 'descrip1':
                case 'descrip2':
                case 'descrip3':
                    if (empty($v)) $errors->add('titres',__('les champs description sont obligatoires'));
                break;
                
            }
        }
    }
    
}
add_filter('user_profile_update_errors', 'check_fields', 10, 3);


// enleve l'admin bar
function my_admin_bar(){
  $cc_user = wp_get_current_user();
  if (empty($cc_user->roles) || in_array('appren', $cc_user->roles) || in_array('prof', $cc_user->roles)) {
    return false;
  }
  return true;
}
add_filter( 'show_admin_bar' , 'my_admin_bar') ;


// redirect login wrong
add_action( 'wp_login_failed', 'custom_login_failed' );
function custom_login_failed( $username )
{
    $referrer = wp_get_referer();

    if ( $referrer && ! strstr($referrer, 'wp-login') && ! strstr($referrer,'wp-admin') )
    {
        wp_redirect( add_query_arg('login', 'failed', $referrer) );
        exit;
    }
}





// customize page login

function my_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Le plaisir d\'apprendre';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

function my_login_stylesheet() { 
?>
    <link rel="stylesheet" id="custom_wp_admin_css"  href="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/style-login.css'; ?>" type="text/css" media="all" />
<?php }
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );




// renomme l'expediteur des mails
function new_mail_from() { return 'no-reply@dpg-education.fr'; }
function new_mail_from_name() { return '[dpg-education.fr]'; }
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');



// redirige l'ajout d'un nouvel utilisateur vers sa page
add_action("admin_init", "dmt_redirect_after_user_add");
function dmt_redirect_after_user_add() {
	if(!empty($_GET['id']) && !empty($_GET['update']) && ($_GET['update'] == 'add')) {
		$userid = $_GET['id'];
		$user = get_userdata( $userid );
		wp_redirect( admin_url("/user-edit.php?user_id=".$user->ID) );
	}
}



add_action( 'admin_footer-user-new.php', 'adduser_new' );
function adduser_new( $user ) {

		 if($_GET)
         {
             if(isset($_GET['action']) && isset($_GET['id']) && $_GET['action']=='beforeadd_user')
             {
                 
                $id = wp_strip_all_tags($_GET['id']);
                 
                global $wpdb;
                $table=$wpdb->prefix.'inscription';
                $rows = $wpdb->get_results("SELECT * FROM $table WHERE id=$id", ARRAY_A);
                foreach($rows as $row)
                {
                    echo '<script>';
                    echo 'jQuery("#email").val("'.$row['cmail'].'");'."\n";
                    echo 'jQuery("#first_name").val("'.$row['prenomeleve'].'");'."\n";
                    echo 'jQuery("#last_name").val("'.$row['nomeleve'].'");'."\n";
                    echo 'jQuery("#role").val("appren");'."\n";
                    echo 'jQuery("#role option[value=\'prof\']").remove();'."\n";
                    echo 'jQuery("#role option[value=\'contributor\']").remove();'."\n";
                    echo 'jQuery("#role option[value=\'subscriber\']").remove();'."\n";
                    echo 'jQuery("#role option[value=\'author\']").remove();'."\n";
                    echo 'jQuery("#role option[value=\'editor\']").remove();'."\n";
                    echo 'jQuery("#role option[value=\'administrator\']").remove();'."\n";
                    echo '</script>';
                    //echo '<script> jQuery("#role").attr("disabled", "disabled")</script>';
                }
             
            }
        }

}


// action apres ajout user
add_action('user_register','my_user_register');
function my_user_register($userid)
{
    if($_GET)
    {
        $id = wp_strip_all_tags($_GET['id']);
        $user = new WP_User($userid);
        global $wpdb;
        $table=$wpdb->prefix.'inscription';
        $res = $wpdb->update($table,
            array(
            'idwpdb'=>$user->ID,
            'inscrit'=>1
            ),
            array(
            'id'=>$id
            )
        );
    }
    
}


// desactive nos offres
add_filter('wp_nav_menu_items','mam_make_unclickable',10);
function mam_make_unclickable($nav) {
   // print_r('<p>NAV:');print_r(htmlentities($nav));print_r('</p>');
   $hrefpat = '/(href *= *([\"\']?)#\2)/';
   $atagpat = '|(<a\b[^>]*>(.*?)<\/a>)|i';
   $new_nav = $nav;
   if (preg_match_all($atagpat,$new_nav,$atag_matches,PREG_SET_ORDER)) {
      foreach ($atag_matches as $atag_match) {
         $atag = $atag_match[0];
         $link_text = $atag_match[2];
         // print_r('<p>A TAG:');print_r(htmlentities("$atag"));print_r('</p>');
         // print_r('<p>LINK TEXT:');print_r(htmlentities("$link_text"));print_r('</p>');
         if ( preg_match($hrefpat,$atag,$href_matches)) {
            // print_r('<p>HREF MATCH:');print_r(htmlentities("$href_matches[0]"));print_r('</p>');
            $new_nav = str_replace($atag,"<span class='unclickable'>$link_text</span>",$new_nav);
         }
      }
   }
   // print_r('<p>NEW NAV:');print_r(htmlentities($new_nav));print_r('</p>');
   return $new_nav;
}






// modification mail envoye au nouvel utilisateur
//remove_filter( 'wpmu_welcome_user_notification', 'wp_mail' );
/*
function new_welcome_user_msg_filter($user_id,$password,$meta=''){
    
    //echo 'user : '.$iduser;
    $user = new WP_User($user_id);
    $user_login = stripslashes($user->user_login);
    $user_email = stripslashes($user->user_email);
    
   // $message  = sprintf(__('inscription sur dpf-formation.fr %s:'), get_option('blogname')) . "\r\n\r\n";
    
    add_filter( 'wp_mail_content_type', 'set_html_content_type' );
    
    $message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
    $message.='<tr><td>&nbsp;</td></tr>';
    $message.='<tr>';
    $message.='<td align="center">';
    $message.='<table cellspacing="0" cellpadding="10" border="0" width="650" bgcolor="#ffffff">';
    $message.='<tr>';
    $message.='<td height="50"><font size="3"><strong>Vos identifiants de connexion :</strong></font><td>';
    $message.='</tr>';
    $message.='<tr><td>';
    $message .= 'Votre identifiant : '. $user_login;
    $message.='</td></tr>';
    $message.='<tr><td>';
    $message .= 'Votre mot de passe : '. $password;
    $message.='</td></tr>';
    $message.='<tr><td>&nbsp;</td></tr>';
    $message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
    $message.='</table>';
    $message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
    
    //wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);
	$subject='DPG-Formation.fr : Votre identifiant et votre mot de passe';
	$headers = 'From: DPG-Education <no-reply@dpg-education.fr>'."\r\n";
	$m=wp_mail('pdo@greengardendigital.com',$subject,$message,$headers);
    
    remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
}
*/
//new_user_email_content 
//add_filter( 'wpmu_welcome_user_notification', 'new_welcome_user_msg_filter' );



// initialisation les scripts

function initialiser_scripts() {
    if(!is_admin())
    {
        wp_deregister_script('jquery');
 		wp_register_script('jquery','http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', '',false, true);
        
        //wp_register_script('jqueryuicustmin', get_template_directory_uri().'/js/jquery-ui.custom.min.js', false, '');
        
        // charger jQuery
		wp_enqueue_script('jquery');
        
        $logged='no';
            
        if ( is_user_logged_in())
        {
        
            $current_user = wp_get_current_user();
            if($current_user->roles[0]=='appren')
            {
                $logged='appren';
            }
            
             if($current_user->roles[0]=='prof')
            {
                $logged='prof';
            }
        }
        
        if( is_page_template('template-agendaprof.php'))
        {
            wp_register_style('jqueryuicustmincss',get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.css','',false,'screen');
            wp_register_style('fullcalendarcss',get_template_directory_uri().'/js/fullcalendar/fullcalendar.css','',false,'screen');
            wp_register_style('fullcalendarprintcss',get_template_directory_uri().'/js/fullcalendar/fullcalendar.print.css','',false,'print');
            wp_enqueue_style( 'jqueryuicustmincss' );
            wp_enqueue_style( 'fullcalendarcss' );
            wp_enqueue_style( 'fullcalendarprintcss' );
            
            
            wp_register_script('jqueryuicustmin', get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.min.js', '', false, true);
            wp_register_script('fullcalendarmin', get_template_directory_uri().'/js/fullcalendar/fullcalendar.min.js','', false, true);
            wp_enqueue_script('jqueryuicustmin');
            wp_enqueue_script('fullcalendarmin');
            
            // chargement JS prof
            if($logged=='prof')
            {
                wp_register_script('agendaprof', get_template_directory_uri().'/js/agenda-prof.js','', false, true);
                wp_enqueue_script('agendaprof');
                
                // refresh
                wp_localize_script(
                    'agendaprof',
                    '_ajax_refreshprof',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('refresh_nonce_calprof')
                    )
                );
                
                // eventresize
                wp_localize_script(
                    'agendaprof',
                    '_ajax_eventResize',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('resize_nonce_event')
                    )
                );
                
                 // eventdrop
                wp_localize_script(
                    'agendaprof',
                    '_ajax_eventDrop',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('drop_nonce_event')
                    )
                );
                
                if(is_page('planning-professeur'))
                {
                    wp_localize_script(
                        'agendaprof',
                        '_ajax_resumecoursprof',
                        array(
                            'url' => admin_url( 'admin-ajax.php' ),
                            'nonce' => wp_create_nonce('resumecours_nonce_prof')
                        )
                    );
                    }
                
            }
            
            // chargement JS common (non logué)
            if($logged=='no')
            {
                wp_register_script('agendacommon', get_template_directory_uri().'/js/agenda-common.js','', false, true);
                wp_enqueue_script('agendacommon');
                
                $id=0;
                if($_GET && isset($_GET['id'])) $id=wp_strip_all_tags($_GET['id']);
                
                 wp_localize_script(
                    'agendacommon',
                    '_ajax_refreshprof',
                    array(
                        'id'=>$id,
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('refresh_nonce_calprof')
                    )
                );
                
            }
            
            // chargement JS apprenant (connexion au planning du professeur
            if($logged=='appren' && !is_page('planning-eleve'))
            {
                wp_register_script('agendappren', get_template_directory_uri().'/js/agenda-appren.js','', false, true);
                wp_enqueue_script('agendappren');
                
                $id=0;
                if($_GET && isset($_GET['id'])) $id=wp_strip_all_tags($_GET['id']);
                
                 wp_localize_script(
                    'agendappren',
                    '_ajax_refreshprofappren',
                    array(
                        'id'=>$id,
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('refresh_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'agendappren',
                    '_ajax_reserve',
                    array(
                        'id'=>$id,
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('reserve_nonce_event')
                    )
                );
                
                wp_localize_script(
                    'agendappren',
                    '_ajax_deprogramm',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('deprogramm_nonce_event')
                    )
                );
                
                wp_localize_script(
                    'agendappren',
                    '_ajax_testconso',
                    array(
                        'id'=>$current_user->ID,
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('testconso_nonce')
                    )
                );
                
                
            }
            
            if($logged=='appren' && is_page('planning-eleve'))
            {
                
                wp_register_script('appren-priv', get_template_directory_uri().'/js/agenda-appren-priv.js', '', false, true);
                wp_enqueue_script('appren-priv');
                
                wp_register_style('fancyboxcss',get_template_directory_uri().'/js/fancybox/jquery.fancybox.css','',false,'screen');
                wp_enqueue_style( 'fancyboxcss' );
                
                wp_register_script('fancybox', get_template_directory_uri().'/js/fancybox/jquery.fancybox.pack.js', '', false, true);
                wp_enqueue_script('fancybox');
                
                wp_localize_script(
                    'appren-priv',
                    '_ajax_refreshapprenpriv',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('refreshpriv_nonce_appren')
                    )
                );
                
                 wp_localize_script(
                    'appren-priv',
                    '_ajax_resumecours',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('resumecours_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'appren-priv',
                    '_ajax_evaluercours',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('evaluercours_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'appren-priv',
                    '_ajax_affeval',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('affeval_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'appren-priv',
                    '_ajax_valider',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('valider_nonce_appren')
                    )
                );
				
				wp_localize_script(
                    'appren-priv',
                    '_ajax_validerplann',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('validerplann_nonce_appren')
                    )
                );
                
                wp_localize_script(
                    'agendappren',
                    '_ajax_deprogramm',
                    array(
                        'url' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce('deprogramm_nonce_event')
                    )
                );
                
            }
                
        }
        
        if( is_page_template('template-inscription.php'))
        {
            wp_register_script('inscription', get_template_directory_uri().'/js/inscription.js', '', false, true);
            wp_enqueue_script('inscription');
        }
        
         if( is_page_template('template-profilprof.php') && $logged=='prof')
        {
            wp_register_script('profprofil', get_template_directory_uri().'/js/profilprof.js', '', false, true);
            wp_enqueue_script('profprofil');
        }
        
            
        if( is_page_template('template-profileleve.php') && $logged=='appren')
        {
            wp_register_script('inscription', get_template_directory_uri().'/js/inscription.js', '', false, true);
            wp_enqueue_script('inscription');
        }
        
        if( is_page_template('template-contact.php'))
        {
            wp_register_style('jqueryuicustmincss',get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.css','',false,'screen');
            wp_enqueue_style( 'jqueryuicustmincss' );
            wp_register_script('jqueryuicustmin', get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.min.js', '', false, true);
            wp_register_script('contact', get_template_directory_uri().'/js/contact.js', '', false, true);
            wp_enqueue_script('jqueryuicustmin');
            wp_enqueue_script('contact');
        }
        
        if( is_category() )
        {
            wp_register_script('infoprof', get_template_directory_uri().'/js/info-prof.js', '', false, true);
            wp_enqueue_script('infoprof');
        }
        
        if(is_page_template('template-recherchercours.php'))
        {
            wp_register_script('rechercours', get_template_directory_uri().'/js/rechercours.js', '', false, true);
            wp_enqueue_script('rechercours');
        }
    }
}
    
    
add_action('wp_enqueue_scripts', 'initialiser_scripts');


function filter_pagetitle($title) {
    
    if(is_category())
    {
        $strs = array ();
        $categories = get_the_category();
        $category=$categories[0];
        $parents = get_ancestors( $category->term_id, 'category' ) ;
        $strs[]=$category->name;
        foreach($parents as $parent)
        {
            $cur=get_category($parent);
            $strs[]=$cur->name;
        }
        
        $strs[]=get_bloginfo( 'name' );
        
        $strs=array_reverse($strs);
        
        return esc_html( join( ' - ', $strs ) );
    }
    //check if its a blog post
    if (!is_single())
    {
        return get_bloginfo( 'name' ).$title;
    }

    //if wordpress can't find the title return the default
    return et_bloginfo( 'name' ).$title;
}

add_filter('wp_title', 'filter_pagetitle');

/*
// avertir l'utilisateur d'une MAJ de son profil
add_action('profile_update', 'gkp_user_profile_update', 10, 2);
function gkp_user_profile_update( $user_id ) {
        
    $user_info = get_userdata( $user_id );
        
    $subject = get_bloginfo('name'). ' - Profil édité';
        
    $message = "Bonjour " .$user_info->display_name . "\n";
    $message .= "Votre profil a été mise à jour!\n\n";
    $message .= "A bientôt sur \n" . get_bloginfo('url');
        
    wp_mail($user_info->user_email, $subject, $message);
 
}

// avertir l'utilisateur d'une MAJ de son role
add_action('set_user_role', 'gkp_user_role_update', 10, 2);
function gkp_user_role_update( $user_id, $new_role ) {
   
    $user_info = get_userdata( $user_id );
        
    $subject = get_bloginfo('name'). ' - Rôle modifié';
        
    $message = "Bonjour " .$user_info->display_name . ",\n";
    $message .= "votre rôle a été modifié sur " . get_bloginfo('url') . " et vous êtes maintenant ". $new_role."\n\n";
    $message .= "A bientôt sur \n".get_bloginfo('url');
      
    wp_mail($user_info->user_email, $subject, $message);
}

*/

// Hide Administrator From User List 
function isa_pre_user_query($user_search) {
  $user = wp_get_current_user();
  if (!current_user_can('administrator')) { // Is Not Administrator - Remove Administrator
    global $wpdb;

    $user_search->query_where = 
        str_replace('WHERE 1=1', 
            "WHERE 1=1 AND {$wpdb->users}.ID IN (
                 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
                    WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
                    AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')", 
            $user_search->query_where
        );
  }
}
add_action('pre_user_query','isa_pre_user_query');



// empeche non admin d'aller sur wp-admin
function restrict_admin()
{
    if (!current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX) && count($_POST) == 0) 
    {
            //wp_die( __('You are not allowed to access this part of the site') );
            wp_redirect(home_url()); 
            exit;
     }

}
add_action( 'admin_init', 'restrict_admin',1);


// empeche l'utilisateur non loggé d'aller sur wp-admin
function restrict_adminnopriv()
{
   // wp_die($_SERVER['REQUEST_URI']);
    //wp_die( __('You are not allowed to access this part of the site') );
    //$user = wp_get_current_user();
        if( !is_user_logged_in() && strpos($_SERVER['REQUEST_URI'],'wp-admin') && !(defined('DOING_AJAX') && DOING_AJAX) && count($_POST) == 0)
        {
           wp_redirect(home_url()); 
            exit;
        }
}
add_action( 'init', 'restrict_adminnopriv',1);



require(get_template_directory().'/inc/roles.php');

if(is_admin())
{
    // admin eleve - prof
    require(get_template_directory().'/inc/adminprof.php');
    
    // panneau admin de gestion des codes postaux pour zone géo
    require ('inc/cp-panel.php');

    // panneau de gestion des pre-inscription
    require ('inc/preinscr-panel.php');
    
    // panneau de gestion des niveaux scolaires pour la pre-inscription
    require ('inc/niveau_scolaire-panel.php');
    
    // panneau de gestion des heures des professeurs
    require ('inc/heuresprof-panel.php');
    
    // panneau de gestion des partenaires
    require ('inc/partenaires-panel.php');
    
    // panneau de gestion des inscription
    require ('inc/inscrip_panel.php');
    
    // panneau questionnaire
    require ('inc/admin-questionnaire.php');
}


require(get_template_directory().'/inc/ajax.php');

require(get_template_directory().'/inc/validatecron.php');



?>