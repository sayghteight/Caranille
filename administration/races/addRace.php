<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['add']))
{
    ?>
    <p>Informations de la classe</p>
    <form method="POST" action="addRaceEnd.php">
        Image : <br> <input type="text" name="adminRacePicture" class="form-control" placeholder="Nom" required autofocus><br /><br />
        Nom : <br> <input type="text" name="adminRaceName" class="form-control" placeholder="Nom" required autofocus><br /><br />
        Description : <br> <textarea class="form-control" name="adminRaceDescription" id="adminRaceDescription" rows="3" required></textarea><br /><br />
        HP par niveau : <br> <input type="number" name="adminRaceHpBonus" class="form-control" placeholder="HP par niveau" required autofocus><br /><br />
        MP par niveau : <br> <input type="number" name="adminRaceMpBonus" class="form-control" placeholder="MP par niveau" required autofocus><br /><br />
        Force par niveau : <br> <input type="number" name="adminRaceStrengthBonus" class="form-control" placeholder="Force par niveau" required autofocus><br /><br />
        Magie par niveau : <br> <input type="number" name="adminRaceMagicBonus" class="form-control" placeholder="Magie par niveau" required autofocus><br /><br />
        Agilité par niveau : <br> <input type="number" name="adminRaceAgilityBonus" class="form-control" placeholder="Agilité par niveau" required autofocus><br /><br />
        Défense par niveau : <br> <input type="number" name="adminRaceDefenseBonus" class="form-control" placeholder="Défense par niveau" required autofocus><br /><br />
        Défense Magique par niveau : <br> <input type="number" name="adminRaceDefenseMagicBonus" class="form-control" placeholder="Défense Magique par niveau" required autofocus><br /><br />
        Sagesse par niveau : <br> <input type="number" name="adminRaceWisdomBonus" class="form-control" placeholder="Sagesse par niveau" required autofocus><br /><br />
        <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
    </form>
    
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");