<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }
?>

<form method="POST" action="manageAccount.php">
    Liste des joueurs <select name="adminAccountId" class="form-control">
        
        <?php
        //On fait une recherche dans la base de donnée de tous les comptes et personnages
        $accountQuery = $bdd->query("SELECT * FROM car_accounts, car_characters
        WHERE accountId = characterAccountId
        ORDER by characterName");

        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
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
    <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer le compte">
</form>

<?php require_once("../html/footer.php");