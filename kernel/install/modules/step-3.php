<?php 
require_once("../html/header.php"); 
include("../../config.php"); 
?>
    
<form method="POST" action="step-4.php">
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Pseudo</label>
        <input class="form-control" type="text" name="accountPseudo" required>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Mot de passe</label>
        <input class="form-control" type="password" name="accountPassword" required>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Confirmez</label>
        <input class="form-control" type="password" name="accountPasswordConfirm" required>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Email</label>
        <input class="form-control" type="email" name="accountEmail" required>
    </div>
    <div class="form-group row">
        <label for="raceList" class="col-2 col-form-label">Classe</label>
        <select class="form-control" id="raceList" name="characterRaceId">
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
                //on récupère les valeurs de chaque classes qu'on va ensuite mettre dans le menu déroulant
                $raceId = stripslashes($raceList['raceId']); 
                $raceName = stripslashes($raceList['raceName']);
                ?>
                    <option value="<?php echo $raceId ?>"><?php echo $raceName ?></option>
                <?php
            }
        }
        //S'il n'y a aucune classe de disponible on ajoute "Aucune classe" dans le menu déroulant
        else
        {
            ?>
                <option value="0">Aucune classe</option>
            <?php
        }
        $raceListQuery->closeCursor();
        ?>
        </select>
    </div>
    <div class="form-group row">
        <label for="sexList" class="col-2 col-form-label">Sexe</label>
        <select class="form-control" id="sexList" name="characterSex">
            <option value="1">Homme</option>
            <option value="0">Femme</option>
        </select>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Nom du personnage</label>
        <input class="form-control" type="text" name="characterName" required>
    </div>
    <center><iframe src="../../../licence.txt" width="100%" height="100%"></iframe></center>
    <div class="form-group row">
        <center>En vous inscrivant vous acceptez le présent règlement !</center>
    </div>
    <center><input type="submit" name="Register" class="btn btn-default form-control" value="Je créer mon compte"></center>
</form>

<?php require_once("../html/footer.php"); ?>