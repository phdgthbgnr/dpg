<?php

if ( is_user_logged_in())
{

    $current_user = wp_get_current_user();
    
}  
    
?>
<div class="content">
    <h1><?php the_title() ?></h1>
    <?php the_content() ?>
    <br/>
    <div class="frminscript">
        <h4><strong>Merci de remplir ce formulaire</strong></h4>
        <br/>
        <form action="" method="post">
        
        <p class="input-b">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" required>
        </p>
        
        <p class="input-b">
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" required>
        </p>

        <p class="input-b">
            <label for="cemail">Adresse mail</label>
            <input type="text" name="mail" id="mail" required>
        </p>

        <p class="input-b">
            <label for="ctel">Téléphone</label>
            <input type="text" name="tel" id="tel" required>
        </p>

        <p class="input-b">
            <label for="cmob">Message</label>
        </p>
        <textarea name="message" required></textarea>
            
        <input type="hidden" name="action" value="contact" id="action"/>
        <?php wp_nonce_field('ajax_contact_nonce','security'); ?>
        <input id="sendcontact" type="submit" value="Envoyer" class="btsend"/>
    
    </form>
    </div>
</div>

<div id="confirmcntct" style="display:none">Votre demande a bien été enregistrée</div>

