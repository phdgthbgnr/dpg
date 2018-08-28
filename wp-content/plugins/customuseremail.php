<?php
/*
Plugin Name: Custom New User Email
Description: Changes the copy in the email sent out to new users
*/
 
// Redefine user notification function
if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
	
        $user = new WP_User($iduser);
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
		$message .= sprintf(__('Username: %s'), $user_login);
		$message.='</td></tr>';
		$message.='<tr><td>';
		$message .= sprintf(__('E-mail: %s'), $user_email);
		$message.='</td></tr>';
		$message.='<tr><td>&nbsp;</td></tr>';
		$message.='<tr><td><img src="http://www.dpg-education.fr/elements_mail/signature-dpg.jpg" alt="dpg-education - 49, rue Denfert Rochereau 69004 LYON" width="650" width="100"/></td></tr>';
		$message.='</table>';
		$message.='</td></tr><tr><td>&nbsp;</td></tr></table>';
		
		wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);
		
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
 
    }
}
 
?>