<?php require_once("../../html/header.php"); ?>

<form method="POST" action="completeRegistration.php">
        <label for="example-text-input" class="col-2 col-form-label">Pseudo</label>
        <input class="form-control" type="text" name="accountPseudo" required>
        <label for="example-text-input" class="col-2 col-form-label">Mot de passe</label>
        <input class="form-control" type="password" name="accountPassword" required>
        <label for="example-text-input" class="col-2 col-form-label">Confirmez</label>
        <input class="form-control" type="password" name="accountPasswordConfirm" required>
        <label for="example-text-input" class="col-2 col-form-label">Email</label>
        <input class="form-control" type="email" name="accountEmail" required>
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
            ?>
        
        </select>
        <label for="sexList" class="col-2 col-form-label">Sexe</label>
        <select class="form-control" id="sexList" name="characterSex">
            <option value="1">Homme</option>
            <option value="0">Femme</option>
        </select>
        <label for="example-text-input" class="col-2 col-form-label">Nom du personnage</label>
        <input class="form-control" type="text" name="characterName" required>
    <iframe src="../../licence.txt" width="100%" height="100%"></iframe>
    En vous inscrivant vous acceptez le présent règlement !
    <center><input type="submit" name="Register" class="btn btn-default form-control" value="Je créer mon compte"></center>
</form>

<?php require_once("../../html/footer.php"); ?>