<?php 
require_once("../html/header.php"); 
include("../../config.php"); ?>
    
<form method="POST" action="step-5.php">
    Pseudo : <input type="text" name="accountPseudo" class="form-control" required>
    Mot de passe : <input type="password" name="accountPassword" class="form-control" required>
    Confirmez : <input type="password" name="accountPasswordConfirm" class="form-control" required>
    Email : <input type="email" name="accountEmail" class="form-control" required>
    Classe <select name="characterRaceId" class="form-control">
        
        <?php
        //On rempli le menu déroulant avec la liste des classes disponible
        $raceListQuery = $bdd->query("SELECT * FROM car_races");
        //On recherche combien il y a de classes disponible
        $raceList = $raceListQuery->rowCount();
        //S'il y a au moins une classe de disponible on les affiches dans le menu déroulant
        if ($raceList >= 1)
        {
            //On fait une boucle sur tous les résultats
            while ($raceList = $raceListQuery->fetch())
            {
                //On récupère les informations de la classe
                $raceId = stripslashes($raceList['raceId']); 
                $raceName = stripslashes($raceList['raceName']);
                ?>
                <option value="<?php echo $raceId ?>"><?php echo $raceName ?></option>
                <?php
            }
        }
        ?>
    
    </select>
    Sexe : <select name="characterSex" class="form-control">
        <option value="1">Homme</option>
        <option value="0">Femme</option>
    </select>
    Nom du personnage : <input class="form-control" type="text" name="characterName" required>
    <iframe src="../../../CGU.txt" width="100%" height="100%"></iframe>
    En vous inscrivant vous acceptez le présent règlement !
    <input type="hidden" name="token" class="btn btn-default form-control" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" name="Register" class="btn btn-default form-control" value="Je créer mon compte">
</form>

<?php require_once("../html/footer.php"); ?>