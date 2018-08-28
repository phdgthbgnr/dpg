<?php
    $current_user = wp_get_current_user();
    $idprof=$current_user->ID;
    $mats=get_user_meta ($idprof,'matiere',true);
	
	global $wpdb;
	$table=$wpdb->prefix.'profmatiere';
	$rows=$wpdb->get_results("SELECT idmatiere FROM $table WHERE idprof=$idprof");
    //$mats=explode(' ',$mats);
?>

<div class="textresult">
	<!-- <h2><?php the_title() ?></h2> -->
	<!--<h3><?php the_excerpt() ?></h3>-->
</div>

<div id="calEventDialog">
    <form action="" method="post" id="frmcours" class="frmagenda">
        <fieldset>
        <label for="eventTitle">Titre *</label>
        <input type="text" name="eventTitle" id="eventTitle" class="title"/><br>
        <label for="eventStart">Début :</label>
        <input type="text" name="eventStart" id="eventStart" disabled /><br>
        <label for="eventEnd">Fin :</label>
        <input type="text" name="eventEnd" id="eventEnd" disabled /><br>
        <label for="matieres" class="matdispo">Matières disponibles :</label> <br/>  
        <ul id="matieres">
        <?php
		//print_r($rows);
			foreach($rows as $row)
            {
                //$idcat=substr($mat,1);
                echo '<li><input type="radio" name="matiere" value="'.$row->idmatiere.'"/>'.get_the_category_by_ID( $row->idmatiere ).'</li>';
            }
			/*
            foreach($mats as $mat)
            {
                $idcat=substr($mat,1);
                echo '<li><input type="radio" name="matiere" value="'.$idcat.'"/>'.get_the_category_by_ID( $idcat ).'</li>';
            }
			*/
        ?>
        </ul>
        <textarea id="comment" name="comment"></textarea>
        <span class="oblig">* champ obligatoire</span>
        <input type="hidden" name="hdeb" id="hdeb" value=""/>
        <input type="hidden" name="hfin" id="hfin" value=""/>
            
        <input type="hidden" name="action" value="addcours"/>
        <?php wp_nonce_field('ajax_addcours_nonce','security'); ?>
        </fieldset>
    </form>
</div>

<div id="calmodifEventDialog">
    <form action="" method="post" id="frmmdfcours" class="frmagenda">
        <fieldset>
        <label for="eventTitlem" class="lablt">Titre</label>
        <span id="eventTitlem" class="content"></span><br>
        <label for="eventStartm" class="lablt">Début :</label>
        <span id="eventStartm" class="content"></span><br>
        <label for="eventEndm" class="lablt">Fin :</label>
        <span id="eventEndm" class="content"></span><br>
        <label for="eventEleve" class="lablt">Elève :</label>
        <span id="eventEleve" class="content"></span><br> 
            
        <label for="adressEleve" class="lablt">Adresse :</label>
        <span id="adressEleve" class="content"></span><br>
        <label for="villeEleve" class="lablt">Ville :</label>
        <span id="villeEleve" class="content"></span><br>    
        <label for="cpEleve" class="lablt">Code postal :</label>
        <span id="cpEleve" class="content"></span><br> 
        <label for="telEleve" class="lablt">Tél. :</label>
        <span id="telEleve" class="content"></span><br>
        <label for="mobileEleve" class="lablt">Mobile :</label>
        <span id="mobileEleve" class="content"></span><br>
    
        <label for="eventMatiere" class="lablt">Matière :</label>
        <span id="eventMatiere" class="content"></span><br>
        <input type="hidden" name="action" value="modifcours"/>
        <?php wp_nonce_field('ajax_modifcours_nonce','security'); ?>
        </fieldset>
    </form>
</div>

<div id="calsupprEventDialog">
    <form action="" method="post" id="frmmdfcours" class="frmagenda">
        <fieldset>
        <label for="eventTitlems" class="lablt">Titre</label>
        <span id="eventTitlems" class="content"></span><br>
        <label for="eventStartms" class="lablt">Début :</label>
        <span id="eventStartms" class="content"></span><br>
        <label for="eventEndms" class="lablt">Fin :</label>
        <span id="eventEndms" class="content"></span><br>
        <label for="eventEleves" class="lablt">Elève :</label>
        <span id="eventEleves" class="content"></span><br> 
        <label for="eventMatieres" class="lablt">Matière :</label>
        <span id="eventMatieres" class="content"></span><br>
        <input type="hidden" name="action" value="modifcours"/>
        <?php wp_nonce_field('ajax_modifcours_nonce','security'); ?>
        </fieldset>
    </form>
</div>

<div id="datenotallowed">
    <h3>Vous devez sélectionner un date supérieur à 48H à la date courante</h3>
</div>

<div id="deplacnotallowed">
    <h3>Ce cours a été validé, vous ne pouvez pas le déplacer</h3>
</div>

<div id="modifnotallowed">
    <h3>Ce cours a été validé, vous ne pouvez pas le modifier</h3>
</div>

<div id='calendar'></div>
<p class="clearboth"> &nbsp;</p>
<a href="#" id="aresume" class="resumehrf">Afficher le résumé de mes cours</a>
<a href="#" id="exprotcsv" class="resumehrf">Exporter en CSV (Excel)</a>

<form style="display:inline" id="csvprof" action="<?php echo get_template_directory_uri(); ?>/inc/exportcsvprof.php" method="post">
    <select name="mois" id="mois" style="width:100px">
        <option value="00"> Mois </option>
        <option value="01">Janvier</option>
        <option value="02">Février</option>
        <option value="03">Mars</option>
        <option value="04">Avril</option>
        <option value="05">Mai</option>
        <option value="06">Juin</option>
        <option value="07">>Juillet</option>
        <option value="08">Août</option>
        <option value="09">Septembre</option>
        <option value="10">Octobre</option>
        <option value="11">Novembre</option>
        <option value="12">Décembre</option>
    </select>
    <select name="annee" id="annee" style="width:100px">
        <option value="00"> Année </option>
        <option value="2014">2014</option>
        <option value="2015">2015</option>
        <option value="2016">2016</option>
        <option value="2017">2017</option>
        <option value="2018">2018</option>
        <option value="2019">2019</option>
        <option value="2020">2020</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
        <option value="2026">2026</option>
        <option value="2027">2027</option>
        <option value="2028">2028</option>
        <option value="2029">2029</option>
        <option value="2030">2030</option>
    </select>
    <input type="hidden" name="prof" value="<?php echo $idprof?>" />
</form>
    
<div id="blocresume" class="blcresume">
    <table class="tbresume headtb">
        <thead>
            <th>Elève</th>
            <th>Matière</th>
            <th>Date & heure</th>
            <th>Durée</th>
            <th style="width:12%">Action</th>
        </thead>
    </table>
    <div id="contentresume" class="cntresume">
    </div>
</div>