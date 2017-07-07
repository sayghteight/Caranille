<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }
?>

<form method="POST" action="offerExperience.php">
    Liste des personnages <select name="adminCharacterId" class="form-control">
        <option value="0">Tous les joueurs</option>
        
        <?php
        //On fait une recherche dans la base de donnée tous les personnages
        $characterQuery = $bdd->query("SELECT * FROM car_characters
        ORDER by characterName");
        
        while ($character = $characterQuery->fetch())
        {
            $adminCharacterId = stripslashes($character['characterId']);
            $adminCharacterName =  stripslashes($character['characterName']); ?>
            ?>
                <option value="<?php echo $adminCharacterId ?>"><?php echo "$adminCharacterName"; ?></option>
            <?php
        }
        $characterQuery->closeCursor();
        ?>
    
    </select>
    Expérience à offrir : <input type="number" name="adminOfferExperience" class="form-control" placeholder="Experience" required>
    <input type="submit" name="manage" class="btn btn-default form-control" value="Offrir">
</form>

<?php require_once("../html/footer.php");