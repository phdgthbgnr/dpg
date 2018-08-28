<?php
/*
Plugin Name: contenu user mail
Description: Change le contenu du mail envoyé à un nouvel utilisateur
*/



global $phpmailer;
/*
add_action( 'phpmailer_init', function(&$phpmailer)use($file,$uid,$name){
    $phpmailer->SMTPKeepAlive = true;
    $phpmailer->AddEmbeddedImage($file, $uid, $name);
});
*/


// Redefine user notification function
if ( !function_exists('wp_new_user_notification') ) {

	function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        
        $file1 = dirname(__FILE__).'/header_mail.jpg'; //phpmailer will load this file
        $uid1 = 'header-mail-uid'; //will map it to this UID
        $name1 = 'header-mail.jpg'; //this will be the file name for the attachment
        
        $file2 = dirname(__FILE__).'/signature-dpg.jpg'; //phpmailer will load this file
        $uid2 = 'signature-dpg-uid'; //will map it to this UID
        $name2 = 'signature-dpg.jpg'; //this will be the file name for the attachment
        
        add_action( 'phpmailer_init', function(&$phpmailer)use($file1,$uid1,$name1){
            $phpmailer->SMTPKeepAlive = true;
            $phpmailer->AddEmbeddedImage($file1, $uid1, $name1);
        });
        
         add_action( 'phpmailer_init', function(&$phpmailer)use($file2,$uid2,$name2){
            $phpmailer->SMTPKeepAlive = true;
            $phpmailer->AddEmbeddedImage($file2, $uid2, $name2);
        });


		$user = new WP_User( $user_id );

		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );

		$message  = sprintf( __('New user registration on %s:'), get_option('blogname') ) . "\r\n\r\n";
		$message .= sprintf( __('Username: %s'), $user_login ) . "\r\n\r\n";
		$message .= sprintf( __('E-mail: %s'), $user_email ) . "\r\n";

		@wp_mail(get_option('admin_email'),sprintf(__('[%s] New User Registration'), get_option('blogname') ),$message);

		if ( empty( $plaintext_pass ) )
			return;
		/*
		$message  = __('Hi there,') . "\r\n\r\n";
		$message .= sprintf( __("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
		$message .= wp_login_url() . "\r\n";
		$message .= sprintf( __('Username: %s'), $user_login ) . "\r\n";
		$message .= sprintf( __('Password: %s'), $plaintext_pass ) . "\r\n\r\n";
		$message .= sprintf( __('If you have any problems, please contact me at %s.'), get_option('admin_email') ) . "\r\n\r\n";
		$message .= __('Adios!');
		*/
		
		//$data1='http://www.dpg-education.fr/elements_mail/signature-dpg.jpg';
        $data1='cid:signature-dpg-uid';
		
		//$data2='http://www.dpg-education.fr/elements_mail/header_mail.jpg';
        
        $data2='cid:header-mail-uid';
		
		$message='<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#cccccc">';
		$message.='<tr><td>&nbsp;</td></tr>';
		$message.='<tr>';
		$message.='<td align="center">';
		$message.='<table cellspacing="0" cellpadding="0" border="0" width="650" bgcolor="#ffffff">';
		$message.='<tr>';
		$message.='<td><img src="'.$data2.'" width="650" height="100" /></td>';
		$message.='</tr><tr>';
		$message.='<td height="50" style="padding:10px"><font size="3" face="Arial, Helvetica, sans-serif"><strong>Vos identifiants de connexion :</strong></font><td>';
		$message.='</tr>';
		$message.='<tr><td style="padding:10px"><font size="3" face="Arial, Helvetica, sans-serif">'.sprintf(__('Username: %s'), $user_login).'</font></td></tr>';
		$message.='<tr><td style="padding:10px"><font size="3" face="Arial, Helvetica, sans-serif">'.sprintf(__('Password: %s'), $plaintext_pass).'</font></td></tr>';
		$message.='<tr><td>&nbsp;</td></tr>';
		$message.='<tr><td>';
		$message.='<img src="'.$data1.'" width="650" height="100"/>';
		$message.='</td></tr></table>';
		$message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
		
		
		add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		
		wp_mail($user_email,sprintf( __('[%s] Your username and password'), get_option('blogname') ),$message);
		
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
	}
}
?>