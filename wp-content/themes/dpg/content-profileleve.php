<?php
$current_user = wp_get_current_user();
?>

<div class="frminscript">
<h2><?php the_title() ?></h2>
<h3><?php the_content() ?></h3>
    <form action="" method="post">
        
        <p class="input-b">
            <label for="elevenon">Nom de l'élève</label>
            <input type="text" name="nomeleve" id="elevenon" value="<?php echo get_user_meta($current_user->ID, 'last_name', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="eleveprenom">Prénom de l'élève</label>
            <input type="text" name="prenomeleve" id="eleveprenom" value="<?php echo get_user_meta($current_user->ID, 'first_name', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="parentnom">Nom du responsable</label>
            <input type="text" name="nomparent" id="parentnom" value="<?php echo get_user_meta($current_user->ID, 'nomparent', true); ?>" required>
        </p>
        
         <p class="input-b">
            <label for="parentprenom">Prénom du responsable</label>
            <input type="text" name="prenomparent" id="prenomparent" value="<?php echo get_user_meta($current_user->ID, 'prenomparent', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="parentnaiss" class="llieu">lieu de naissance de l'élève</label>
            <input type="text" name="parentnaiss" id="parentnaiss" value="<?php echo get_user_meta($current_user->ID, 'lieunaissparent', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="bitrhday" class="lbirth">Date de naissance de l'élève (jj/mm/aaaa)</label>
            <input type="text" name="naisseleve" id="bitrhday" value="<?php echo get_user_meta($current_user->ID, 'datenaissance', true); ?>" required class="inbirth">
        </p>
        
         <p class="input-b">
            <label for="nivscol">Niveau scolaire</label>
             <? $nivc=get_user_meta($current_user->ID, 'nivscol', true); ?>
             <select name="nivscol" class="lselect" required>
                <option value="">Sélectionnez un niveau scolaire</option>
                <?php
                    global $wpdb;
                    $table=$wpdb->prefix.'niveauscolaire';
                    $sql=$wpdb->prepare("SELECT * from $table WHERE inactif=0 ORDER BY 'ordre' DESC",1);
                    $rows = $wpdb->get_results($sql,ARRAY_A);
                    if(count($rows)>0)
                    {
                        foreach($rows as $row)
                        {
                            $niv=$row['id_niv'];
                            $slect=$niv==$nivc?' selected="selected"':'';
                            echo '<option value="'.$niv.'"'.$slect.'>'.$row['nivscol'].'</option>';
                        }
                    }
                ?>
             </select>
        </p>
        
        <p class="input-b">
            <label for="adresse">Adresse</label>
            <input type="text" name="adress" id="adresse" value="<?php echo get_user_meta($current_user->ID, 'adresse', true); ?>" required>
        </p>

        <p class="input-b">
            <label for="cpost">Code Postal</label>
            <input type="text" name="cp" id="cpost" value="<?php echo get_user_meta($current_user->ID, 'cp', true); ?>" required>
        </p>

        <p class="input-b">
            <label for="cville">Ville</label>
            <input type="text" name="ville" id="cville" value="<?php echo get_user_meta($current_user->ID, 'ville', true); ?>" required>
        </p>

        <p class="input-b">
            <label for="cemail">Adresse mail</label>
            <input type="text" name="cmail" id="cemail" value="<?php echo $current_user->user_email; ?>" required>
        </p>

        <p class="input-b">
            <label for="ctel">Téléphone</label>
            <input type="text" name="tel" id="ctel" value="<?php echo get_user_meta($current_user->ID, 'tel', true); ?>" required>
        </p>

        <p class="input-b">
            <label for="cmob">Mobile</label>
            <input type="text" name="mobile" id="cmob" value="<?php echo get_user_meta($current_user->ID, 'mobile', true); ?>" required>
        </p>
        <p class="input-b brappel" style="display:none" id="brappel">
                Votre Code postal n'est pas présent dans notre zone d'intervention<br/><br/>
                <input type="checkbox" name="rappel" id="crappel" value="1" class="cnfrmcp" >En cochant cette case nous vous avertirons dès qu'un professeur pourra intervenir chez vous
        </p>
        
    
        <input type="hidden" name="action" value="modifprofil" id="action"/>
        <?php wp_nonce_field('ajax_modifprofil_nonce','security'); ?>
        <input id="sendinscrp" type="submit" value="Envoyer"/>
    
    </form>
</div>