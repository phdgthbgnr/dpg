<?php
add_action('admin_menu','preinscr_panel');

function preinscr_panel(){
    add_menu_page('gestion des pré-inscriptions','gestion des pré-inscriptions','activate_plugins','preinscr','render_preinscr',null,82);
}

function render_preinscr()
{
    global $wpdb;
    $table=$wpdb->prefix.'inscription';
    $table2=$wpdb->prefix.'niveauscolaire';
    if(isset($_POST['rech']))
    {
        $rech = wp_strip_all_tags($_POST['rech']);
        $strech = wp_strip_all_tags($_POST['txtrech']);
        $strech=$strech."%";
        switch($rech)
        {
            case 'nom':
            $sql=$wpdb->prepare("SELECT nomeleve,prenomeleve,prenomparent,nomparent,lieunaissparent,naisseleve,nivscol,cp,ville,cmail,tel,mobile,rappel,id FROM $table, $table2 WHERE inscrit=0 and nomeleve LIKE %s and $table2.id_niv=$table.scolniv ORDER BY $table.idate DESC",$strech);
            break;
            case 'prenom':
            $sql=$wpdb->prepare("SELECT nomeleve,prenomeleve,prenomparent,nomparent,lieunaissparent,naisseleve,nivscol,cp,ville,cmail,tel,mobile,rappel,id FROM $table, $table2 WHERE inscrit=0 and prenomeleve LIKE %s and $table2.id_niv=$table.scolniv ORDER BY $table.idate DESC",$strech);
            break;
            case 'parent':
            $sql=$wpdb->prepare("SELECT nomeleve,prenomeleve,prenomparent,nomparent,lieunaissparent,naisseleve,nivscol,cp,ville,cmail,tel,mobile,rappel,id FROM $table, $table2 WHERE inscrit=0 and nomparent LIKE %s and $table2.id_niv=$table.scolniv ORDER BY $table.idate DESC",$strech);
            break;
            case 'cp':
            $sql=$wpdb->prepare("SELECT nomeleve,prenomeleve,prenomparent,nomparent,lieunaissparent,naisseleve,nivscol,cp,ville,cmail,tel,mobile,rappel,id FROM $table, $table2 WHERE inscrit=0 and cp LIKE %s and $table2.id_niv=$table.scolniv ORDER BY $table.idate DESC",$strech);
            break;
            case 'ville':
            $sql=$wpdb->prepare("SELECT nomeleve,prenomeleve,prenomparent,nomparent,lieunaissparent,naisseleve,nivscol,cp,ville,cmail,tel,mobile,rappel,id FROM $table, $table2 WHERE inscrit=0 and ville LIKE %s and $table2.id_niv=$table.scolniv ORDER BY $table.idate DESC",$strech);
            break;
            case 'email':
            $sql=$wpdb->prepare("SELECT nomeleve,prenomeleve,prenomparent,nomparent,lieunaissparent,naisseleve,nivscol,cp,ville,cmail,tel,mobile,rappel,id FROM $table, $table2 WHERE inscrit=0 and cmail LIKE %s and $table2.id_niv=$table.scolniv ORDER BY $table.idate DESC",$strech);
            break;
            case 'tel':
            $sql=$wpdb->prepare("SELECT nomeleve,prenomeleve,prenomparent,nomparent,lieunaissparent,naisseleve,nivscol,cp,ville,cmail,tel,mobile,rappel,id FROM $table, $table2 WHERE inscrit=0 and tel LIKE %s and $table2.id_niv=$table.scolniv ORDER BY $table.idate DESC",$strech);
            break;
            case 'mob':
            $sql=$wpdb->prepare("SELECT nomeleve,prenomeleve,prenomparent,nomparent,lieunaissparent,naisseleve,nivscol,cp,ville,cmail,tel,mobile,rappel,id FROM $table, $table2 WHERE inscrit=0 and mobile LIKE %s and $table2.id_niv=$table.scolniv ORDER BY $table.idate DESC",$strech);
            break;
        }
    }else{
        $sql=$wpdb->prepare("SELECT nomeleve,prenomeleve,prenomparent,nomparent,lieunaissparent,naisseleve,nivscol,cp,ville,cmail,tel,mobile,rappel,id FROM $table, $table2 WHERE inscrit=0 and $table2.id_niv=$table.scolniv ORDER BY $table.idate DESC",1);
    }
    
    $rows = $wpdb->get_results($sql,ARRAY_A);
    ?>
    <div id="wpbody">
    <div id="wpbody-content">
        <div class="wrap">
            <div id="icon-options-general" class="icon32"><br/></div>
            <h2>Gestion des pré-inscriptions</h2>
            <br/><br/>
            <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 10%;text-align:center">Nom</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 10%;text-align:center">Prénom</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 10%;text-align:center">Prénom resp.</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 10%;text-align:center">Nom resp.</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 10%;text-align:center">Lieu naiss. resp.</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 8%;text-align:center">Date de naissance</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 8%;text-align:center">Niveau scolaire</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 5%;text-align:center">CP</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 8%;text-align:center">Ville</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 13%;text-align:center">Mail</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 7%;text-align:center">Tél</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 7%;text-align:center">Mobile</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 4%;text-align:center">Rappel</th>
                <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 4%;text-align:center">Action</th>
            </tr>
            </thead>
            <tbody id="the-list">
            <?php
            foreach ($rows as $val)
            {
                echo '<tr>';
                foreach($val as $k=>$v)
                {
                    if($k=='id') echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px"><a href="'.admin_url("/user-new.php?action=beforeadd_user&id=$v"). '" class="effcp">Inscrire</a></th>';
                    echo '<th scope="row" class="check-column" style="border-right:1px solid #ccc;padding-left:6px">'.$v.'</th>';
                }
                echo '</tr>';
            }
            ?>
            </tbody>
            </table>
            <h3></h3>
            <form action="" method="post">
                <table class="wp-list-table widefat fixed posts" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
               <th scope="col" id="num" class="manage-column column-num sortable desc" style="width: 100%;text-align:left;padding-left:20px">Faire une recherche sur :</th>
                </tr>
                </thead>
                <tbody id="the-list">
                    <tr>
                    <th><input type="text" id="idrech" name="txtrech" style="width:300px"/> (entrez les premières lettres)</th>
                    </tr>
                    <tr>
                        <th>
                        <input type="radio" id="idnom" name="rech" value="nom"><label for="idnom"> Le nom</label>
                        <input type="radio" id="idprenom" name="rech" value="prenom"><label for="idprenom"> Le prénom</label>
                        <input type="radio" id="idparent" name="rech" value="parent"><label for="idparent"> Nom resp.</label>
                        <!--<input type="radio" id="idnaiss" name="rech" value="naissance"><label for="idnaiss"> La date de naissance</label>-->
                        <input type="radio" id="idcp" name="rech" value="cp"><label for="idcp"> Le code postal</label>
                        <input type="radio" id="idville" name="rech" value="ville"><label for="idville"> La ville</label>
                        <input type="radio" id="idmail" name="rech" value="email"><label for="idmail"> Le mail</label>
                        <input type="radio" id="idtel" name="rech" value="tel"><label for="idtel"> Le N° de téléphone</label>
                        <input type="radio" id="idmob" name="rech" value="mob"><label for="idmob"> Le N° de mobile</label>
                        </th>
                    </tr>
                    <tr>
                        <th><input type="submit" name="send" id="doaction" class="button-secondary action" value="Rechercher"/></th>
                    </tr>
                    </tbody>
                </table>
            </form>
            <?php
}

?>