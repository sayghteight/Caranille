<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }
?>

<form method="POST" action="manageAccount.php">
    <div class="form-group row">
        <label for="equipmentList" class="col-2 col-form-label">Liste des joueurs</label>
        <select class="form-control" id="adminAccountId" name="adminAccountId">
        <?php
        //on récupère les valeurs de chaque compte et personnage qu'on va ensuite mettre dans le menu déroulant
        //On fait une recherche dans la base de donnée de tous les comptes et personnage
        $accountQuery = $bdd->query("SELECT * FROM car_accounts, car_characters
        WHERE accountId = characterAccountId
        ORDER by characterName");
        while ($account = $accountQuery->fetch())
        {
            $adminAccountId = stripslashes($account['accountId']);
            $adminAccountPseudo = stripslashes($account['accountPseudo']);
            $adminAccountCharacterName =  stripslashes($account['characterName']); ?>
            ?>
                <option value="<?php echo $adminAccountId ?>"><?php echo "$adminAccountCharacterName ($adminAccountPseudo)"; ?></option>
            <?php
        }
        $accountQuery->closeCursor();
        ?>
        </select>
    </div>
    <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer">
</form>

<?php require_once("../html/footer.php");