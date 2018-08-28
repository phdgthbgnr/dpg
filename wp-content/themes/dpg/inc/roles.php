<?php

//role

function modify_capabilities() {
	$subscriber_role = get_role('subscriber');
	add_role('prof', 'Professeur', $subscriber_role->capabilities);
	$owner_role = get_role('professeur');
	//$owner_role->add_cap('edit_theme_options');
	//$owner_role->add_cap('list_users');
     
    $author_role = get_role('author');
	add_role('appren', 'Apprenant', $author_role->capabilities);
	$owner_role = get_role('apprenant');
	//$owner_role->add_cap('edit_theme_options');
	//$owner_role->add_cap('list_users');
}
add_action('init','modify_capabilities');



// restriction de l'acces à m'admin
function gkp_restrict_access_administration(){
    if ( current_user_can('subscriber') || current_user_can('author') ) {
        wp_redirect( get_bloginfo('url') );
	exit();
    }
}
add_action('admin_init', 'gkp_restrict_access_administration');	
?>