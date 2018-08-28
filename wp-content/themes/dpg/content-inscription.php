<div class="frminscript">
<h2><?php the_title() ?></h2>
<h3><?php the_content() ?></h3>
    <form action="" method="post">
        
        <p class="input-b">
            <label for="elevenon">Nom de l'élève</label>
            <input type="text" name="nomeleve" id="elevenon" required>
        </p>
        
        <p class="input-b">
            <label for="eleveprenom">Prénom de l'élève</label>
            <input type="text" name="prenomeleve" id="eleveprenom" required>
        </p>
        
        <p class="input-b">
            <label for="parentnom">Nom du responsable</label>
            <input type="text" name="nomparent" id="parentnom" required>
        </p>
        
         <p class="input-b">
            <label for="parentprenom">Prénom du responsable</label>
            <input type="text" name="prenomparent" id="prenomparent" required>
        </p>
        
        <p class="input-b">
            <label for="parentnaiss" class="llieu">lieu de naissance<br/>du responsable</label>
            <input type="text" name="parentnaiss" id="parentnaiss" required>
        </p>
        
        <p class="input-b">
            <label for="bitrhday" class="lbirth">Date de naissance de l'élève (jj/mm/aaaa)</label>
            <input type="text" name="naisseleve" id="bitrhday" required class="inbirth">
        </p>
        
         <p class="input-b">
            <label for="nivscol">Niveau scolaire</label>
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
                            echo '<option value="'.$row['id_niv'].'">'.$row['nivscol'].'</option>';
                        }
                    }
                ?>
             </select>
        </p>
        
        <p class="input-b">
            <label for="adresse">Adresse</label>
            <input type="text" name="adress" id="adresse" required>
        </p>

        <p class="input-b">
            <label for="cpost">Code Postal</label>
            <input type="text" name="cp" id="cpost" required>
        </p>

        <p class="input-b">
            <label for="cville">Ville</label>
            <input type="text" name="ville" id="cville" required>
        </p>

        <p class="input-b">
            <label for="cemail">Adresse mail</label>
            <input type="text" name="cmail" id="cemail" required>
        </p>
        
        <p class="input-b">
            <label for="cemail">Confirmation mail</label>
            <input type="text" name="cmailcnfrm" id="cmailcnfrm" required>
        </p>

        <p class="input-b">
            <label for="ctel">Téléphone</label>
            <input type="text" name="tel" id="ctel" required>
        </p>

        <p class="input-b">
            <label for="cmob">Mobile</label>
            <input type="text" name="mobile" id="cmob" required>
        </p>
        <p class="input-b brappel" style="display:none" id="brappel">
                Votre Code postal n'est pas présent dans notre zone d'intervention<br/><br/>
                <input type="checkbox" name="rappel" id="crappel" value="1" class="cnfrmcp" >En cochant cette case nous vous avertirons dès qu'un professeur pourra intervenir chez vous
        </p>
    
        <input type="hidden" name="action" value="inscript" id="action"/>
        <?php wp_nonce_field('ajax_inscript_nonce','security'); ?>
        <input id="sendinscrp" type="submit" value="Envoyer" class="btsend"/>
    
    </form>
</div>