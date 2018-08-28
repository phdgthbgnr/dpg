<div class="textresult">
	<!--<h2><?php the_title() ?></h2>-->
	<!--<h3><?php the_excerpt() ?></h3>-->
</div>

<div id="calEventDialog">
    <form action="" method="post" id="frmreserve">
        <fieldset>
        <label for="eventMatiere" class="labeltxt">Matière : </label>
        <span id="eventMatiere" class="eventtxt"></span><br>
        <label for="eventTitle" class="labeltxt">Titre : </label>
        <span id="eventTitle" class="eventtxt"></span><br>
        <label for="eventStart" class="labeltxt">Début : </label>
        <span id="eventStart" class="eventtxt"></span> <br>
        <label for="eventEnd" class="labeltxt">Fin : </label>
        <span id="eventEnd" class="eventtxt"></span><br>
        <span id="eventComment" class="bloccomment"></span>
        <input type="hidden" name="hdeb" id="hdeb" value=""/>
        <input type="hidden" name="hfin" id="hfin" value=""/>
            
        <input type="hidden" name="action" value="reserve"/>
        <?php wp_nonce_field('ajax_reserve_nonce','security'); ?>
        </fieldset>
    </form>
</div>

<div id="calEventDeprog">
    <form action="" method="post" id="frmdeprog">
        <fieldset>
        <label for="eventMatieredpgr" class="labeltxt">Matière : </label>
        <span id="eventMatieredpgr"></span><br>
            <?php
            if(is_page('planning-eleve'))
            {
            ?>
        <label for="eventProfdpgr" class="labeltxt">Professeur : </label>
        <span id="eventProfdpgr"></span><br>
            <?php
            }
            ?>
        <label for="eventTitledpgr" class="labeltxt">Titre : </label>
        <span id="eventTitledpgr"></span><br>
        <label for="eventStartdpgr" class="labeltxt">Début : </label>
        <span id="eventStartdpgr"></span> <br>
        <label for="eventEnddpgr" class="labeltxt">Fin : </label>
        <span id="eventEnddpgr"></span><br>          
        <input type="hidden" name="action" value="deprogramm"/>
        <?php wp_nonce_field('ajax_deprogramm_nonce','security'); ?>
        </fieldset>
    </form>
</div>

<div id="calDureeDialog">
    <h3>Vous ne pouvez plus réserver.<br/><br/>Votre compte d'heures est dépassé</h3>
</div>


<div id="calEventnotallow">
    <h3 class="alerte">Vous devez sélectionner un cours dont la date est supérieure de 3 jours à la date courante.<br/><br/>Si cours a déjà été retenu il sera validé automatiquement<br/></h3>
</div>

<div id="calendar"></div>
<p class="clearboth"> &nbsp;</p>
<?php
    if($_GET)
    {
        if(isset($_GET['id']) && isset($_GET['matiere']))
        {
            $id=$_GET['id'];
            $prof=get_user_by( 'id', $id );
            if(is_object($prof)) echo '<p><strong>Professeur : '.$prof->first_name.' '.$prof->last_name.'</strong></p>';
        }
    }
?>

<a href="#" id="aresume" class="resumehrf">Résumé de mes cours</a>
    
<div id="blocresume" class="blcresume">
    <table class="tbresume headtb">
        <thead>
            <th>Professeur</th>
            <th>Matière</th>
            <th>Date & heure</th>
            <th>Durée</th>
            <th style="width:12%">Action</th>
        </thead>
    </table>
    <div id="contentresume" class="cntresume">
    </div>
</div>