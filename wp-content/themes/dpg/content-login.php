<div class="login">
    <h1><?php the_title() ?></h1>
    <h2><?php the_content() ?></h2>
<?php 
	

    wp_login_form( array( 
					'echo' => true,
					'redirect' => site_url()
				) );

?>
</div>