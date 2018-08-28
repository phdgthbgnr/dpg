<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
$vers=$_SERVER['HTTP_USER_AGENT'];
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <?php
        if (strpos($vers,'iPad') && strpos($vers,'Mobile') && strpos($vers,'Version/5.1')){
            echo '<meta name="viewport" content="width=977, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" />';
        }else{
            echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" >';
        }
    ?>
	<title><?php  wp_title( '-', true, 'left' ); ?></title>
    
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <!--<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />-->
    <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); echo '?ver=' . filemtime(get_stylesheet_directory() . '/style.css'); ?>" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>
    
<body>
    <div id="page" class="main">
        <?php
            //if(is_front_page())
            $accueil=get_site_url();
            if(is_front_page())
            {
                $args = array(
                    'post_type' => 'attachment',
                    'name' => sanitize_title('dpg-logo-home'),
                    'posts_per_page' => 1,
                    'post_status' => 'inherit',
                );
            }else{
                $args = array(
                    'post_type' => 'attachment',
                    'name' => sanitize_title('dpg-logo-home'),
                    'posts_per_page' => 1,
                    'post_status' => 'inherit',
                );
            }
            $img=get_posts($args);
            if($img) $src=wp_get_attachment_url($img[0]->ID);
        ?>

        <header id="masthead" class="site-header" role="banner">
            <div class="menu-general-container">
            <?php
            // menu general
            $connid = get_page_by_path( 'identification' );
            $ageneleve = get_page_by_path( 'planning-eleve' );
            $agenprof = get_page_by_path( 'planning-professeur' );
            $profileleve = get_page_by_path( 'mon-profil' );
            $inscript = get_page_by_path( 'inscription' );
            $rechcours = get_page_by_path( 'rechercher-un-cours' );
            $profilprof = get_page_by_path( 'profil-professeur' );

            
            $menu_name = 'general';
            
            if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) )
            {
                $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
            }

            $menu_items = wp_get_nav_menu_items($menu->term_id);

            $menu_list = '<ul id="menu-' . $menu_name . '" class="menu">';
            $current_user = wp_get_current_user();
            foreach ( (array) $menu_items as $key => $menu_item ) { 
                //$class=$menu_item->classes[0]!=''?' class="'.$menu_item->classes[0].'"':'';
                $class=$menu_item->object_id==$post->ID?' class="menuselect"':'';
                $title = $menu_item->title;
                $url = $menu_item->url;
                if ( is_user_logged_in() && $menu_item->object_id==$connid->ID) continue;
                if ( !is_user_logged_in() && $menu_item->object_id==$ageneleve->ID) continue;
                if ( !is_user_logged_in() && $menu_item->object_id==$agenprof->ID) continue;
                if ( !is_user_logged_in() && $menu_item->object_id==$profileleve->ID) continue;
                if ( !is_user_logged_in() && $menu_item->object_id==$rechcours->ID) continue;
                if ( !is_user_logged_in() && $menu_item->object_id==$profilprof->ID) continue;
                if ( is_user_logged_in() && $menu_item->object_id==$inscript->ID) continue;
                
                if ( is_user_logged_in() && $menu_item->object_id==$rechcours->ID && $current_user->roles[0]!='appren') continue;
                if ( is_user_logged_in() && $menu_item->object_id==$agenprof->ID && $current_user->roles[0]!='prof') continue;
                if ( is_user_logged_in() && $menu_item->object_id==$ageneleve->ID && $current_user->roles[0]!='appren') continue;
                if ( is_user_logged_in() && $menu_item->object_id==$profileleve->ID && $current_user->roles[0]!='appren') continue;
                if ( is_user_logged_in() && $menu_item->object_id==$profilprof->ID && $current_user->roles[0]!='prof') continue;
                $menu_list .= '<li'.$class.'><a href="' . $url . '">' . $title . '</a></li>';
	        }
            
            if( is_user_logged_in())  $menu_list .= '<li><a href="'.wp_logout_url($accueil).'">Déconnexion</a></li>';
            echo $menu_list.'</ul>';

            ?>
                </div>
        </header><!-- #masthead -->
        <div class="logo"><a href="<?php echo $accueil ?>"><img src="<?php echo $src?>"/></a></div>
        <div id="main" class="site-main">