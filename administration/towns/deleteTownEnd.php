<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownId'])
    && $_POST['adminTownId'] >= 1)
    {
        $adminTownId = htmlspecialchars(addslashes($_POST['adminTownId']));

        //On fait une requête pour vérifier si la ville choisi existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns
        WHERE townId= ?');
        $townQuery->execute([$adminTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville existe
        if ($townRow == 1) 
        {
            //On supprime la ville de la base de donnée
            $townDeleteQuery = $bdd->prepare("DELETE FROM car_towns
            WHERE townId = ?");
            $townDeleteQuery->execute([$adminTownId]);
            $townDeleteQuery->closeCursor();

            //On supprime aussi les monstres de la ville
            $townMonsterDeleteQuery = $bdd->prepare("DELETE FROM car_towns_monsters
            WHERE townMonsterTownId = ?");
            $townMonsterDeleteQuery->execute([$adminTownId]);
            $townMonsterDeleteQuery->closeCursor();
            
            //On supprime aussi les magasins de la ville
            $townShopDeleteQuery = $bdd->prepare("DELETE FROM car_towns_shops
            WHERE townShopTownId = ?");
            $townShopDeleteQuery->execute([$adminTownId]);
            $townShopDeleteQuery->closeCursor();
            
            //On recherche tous les joueurs qui sont dans cette ville et ont les met dans la carte du monde
            $characterTownQuery = $bdd->prepare('SELECT * FROM car_characters 
            WHERE characterTownId = ?');
            $characterTownQuery->execute([$adminTownId]);

            //Pour chaque joueur trouvé
            while ($characterTown = $characterTownQuery->fetch())
            {
                $adminCharacterId = stripslashes($characterTown['characterId']);
                $adminCharacterName = stripslashes($characterTown['characterName']);
                
                echo "Le joueur $adminCharacterName était dans cette ville et a été téléporté à la carte du monde<br />";
                
                //On met à jour les personnages
                $updateCharacter = $bdd->prepare("UPDATE car_characters SET
                characterTownId = 0
                WHERE characterId= :adminCharacterId");
                
                $updateCharacter->execute(array(
                'adminCharacterId' => $adminCharacterId));
                $updateCharacter->closeCursor();
            }
            ?>

            La ville a bien été supprimée

            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
                
            <?php
        }
        //Si la ville n'exite pas
        else
        {
            echo "Erreur: Ville indisponible";
        }
        $townQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");