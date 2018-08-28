<?php
$current_user = wp_get_current_user();
?>

<div class="frminscript">
<h2><?php the_title() ?></h2>
<h3><?php the_content() ?></h3>
    <form action="" method="post">
        
        <p class="input-b">
            <label for="adresseprof">Adresse</label>
            <input type="text" name="adresseprof" id="adresseprof" value="<?php echo get_user_meta($current_user->ID, 'adresse', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="cpprof">Code postal</label>
            <input type="text" name="cpprof" id="cpprof" value="<?php echo get_user_meta($current_user->ID, 'cp', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="villeprof">Ville</label>
            <input type="text" name="villeprof" id="villeprof" value="<?php echo get_user_meta($current_user->ID, 'ville', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="zoneprof">Zone d'intervention</label>
            <?php
                $zone=esc_attr( get_the_author_meta( 'zonegeo', $current_user->ID));
                global $wpdb;
                $table=$wpdb->prefix.'cpost';
                $rows=$wpdb->get_results("SELECT * FROM $table ORDER BY cp ASC",ARRAY_A,0);
                echo '<ul style="text-align:left">';
                foreach ($rows as $row)
                {
                    //$selct=$zone==$row['cp']?' CHECKED="CHECKED"':'';
                    echo '<li style="display:inline;margin-right:1%"><label for="zone'.$row['id'].'"><input type="checkbox" name="zonegeo[]" value="'.$row['id'].'"'.getval('zonegeo', $row['id'],$current_user->ID).' id="zone'.$row['id'].'"/><label class="checkbox">&nbsp;</label>'.$row['cp'].'</li>';
                }
                echo '</ul>';
            ?>
            </p>
        <p class="input-b">
            <label for="nzoneprof">ou nouvelle zone : </label><br/>
            <input type="text" name="nzoneprof" id="nzoneprof" value="">
        </p>
        <p>&nbsp;</p>
         <p class="input-b">
            <label for="ancient">Ancienneté</label>
            <input type="text" name="ancient" id="ancient" value="<?php echo get_user_meta($current_user->ID, 'ancient', true); ?>" required>
        </p>
        <p>&nbsp;</p>
        <p><strong>Titres, expériences et description des postes :</strong></p>
        <p>&nbsp;</p>
        
        <p class="input-b">
            <label for="titre1">Titre (1)</label>
            <input type="text" name="titre1" id="titre1" value="<?php echo get_user_meta($current_user->ID, 'titre1', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="expe1">Expérience (1)</label>
            <input type="text" name="expe1" id="expe1" value="<?php echo get_user_meta($current_user->ID, 'expe1', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="descrip1">Description du poste (1)</label>
            <textarea name="descrip1" id="descrip1" required style="margin-bottom:2%"><?php echo get_user_meta($current_user->ID, 'descrip1', true); ?></textarea>
        </p>
        
        <p class="input-b">
            <label for="titre2">Titre (2)</label>
            <input type="text" name="titre2" id="titre2" value="<?php echo get_user_meta($current_user->ID, 'titre2', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="expe2">Expérience (2)</label>
            <input type="text" name="expe2" id="expe2" value="<?php echo get_user_meta($current_user->ID, 'expe2', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="descrip2">Description du poste (2)</label>
            <textarea name="descrip2" id="descrip2" required style="margin-bottom:2%"><?php echo get_user_meta($current_user->ID, 'descrip3', true); ?></textarea>
        </p>
        
        <p class="input-b">
            <label for="titre3">Titre (3)</label>
            <input type="text" name="titre3" id="titre3" value="<?php echo get_user_meta($current_user->ID, 'titre3', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="expe3">Expérience (3)</label>
            <input type="text" name="expe3" id="expe3" value="<?php echo get_user_meta($current_user->ID, 'expe3', true); ?>" required>
        </p>
        
        <p class="input-b">
            <label for="descrip3">Description du poste (3)</label>
            <textarea name="descrip3" id="descrip3" required style="margin-bottom:2%"><?php echo get_user_meta($current_user->ID, 'descrip3', true); ?></textarea>
        </p>
    
        <p>&nbsp;</p>
    
        <input type="hidden" name="action" value="modifprofilprof" id="action"/>
        <?php wp_nonce_field('ajax_modifprofilprof_nonce','security'); ?>
        <input id="sendprofil" type="submit" value="Envoyer" class="btsend"/>
    
    </form>
</div>

<?php
    function getval($key,$val=0,$us)
    {
        $arr=get_the_author_meta( $key, $us );
        $tbarr=explode(' ',$arr);
        if(is_array($tbarr))
        {
            if(in_array($val,$tbarr))
            {
                return ' CHECKED="CHECKED"';
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
?>