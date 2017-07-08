<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow == 0) { exit(header("Location: ../../modules/main/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['itemId'])
&& isset($_POST['useItem']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if(ctype_digit($_POST['itemId'])
    && $_POST['itemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));
        
        //On fait une requête pour vérifier si l'objet choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId = ?');
        $itemQuery->execute([$itemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'objet existe
        if ($itemRow == 1) 
        {
            //On cherche à savoir si l'objet que le joueur va utiliser lui appartient bien
            $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
            WHERE itemId = inventoryItemId
            AND inventoryCharacterId = ?
            AND itemId = ?");
            $itemQuery->execute([$characterId, $itemId]);
            $itemRow = $itemQuery->rowCount();
    
            //Si le personne possède cet objet
            if ($itemRow == 1) 
            {
                //On récupère les informations de l'objet
                while ($item = $itemQuery->fetch())
                {
                    $inventoryId = stripslashes($item['inventoryId']);
                    $itemId = stripslashes($item['itemId']);
                    $itemName = stripslashes($item['itemName']);
                    $itemDescription = stripslashes($item['itemDescription']);
                    $itemQuantity = stripslashes($item['inventoryQuantity']);
                    $itemHpEffect = stripslashes($item['itemHpEffect']);
                    $itemMpEffect = stripslashes($item['itemMpEffect']);
                }
                $itemQuery->closeCursor();
                
                /*
                VARIABLES GLOBALES
                */
                $characterMinStrength = $characterStrengthTotal / 2;
                $characterMaxStrength = $characterStrengthTotal * 2;    
                $characterMinMagic = $characterMagicTotal / 2;
                $characterMaxMagic = $characterMagicTotal * 4;
            
                $opponentMinStrength = $opponentStrength / 2;
                $opponentMaxStrength = $opponentStrength * 2;    
                $opponentMinMagic = $opponentMagic / 2;
                $opponentMaxMagic = $opponentMagic * 4;
            
                $opponentMinDefense = $opponentDefense / 2;
                $opponentMaxDefense = $opponentDefense * 2;
                $opponentMinDefenseMagic = $opponentDefenseMagic / 2;
                $opponentMaxDefenseMagic = $opponentDefenseMagic * 2;
            
                $characterMinDefense = $characterDefenseTotal / 2;
                $characterMaxDefense = $characterDefenseTotal * 2;
                $characterMinDefenseMagic = $characterDefenseMagicTotal / 2;
                $characterMaxDefenseMagic = $characterDefenseMagicTotal * 2;
                
                echo "$characterName vient d'utiliser l'objet $itemName<br />";
                echo "+ $itemHpEffect HP<br />";
                echo "+ $itemMpEffect MP<br />";
            
                //On met à jour les HP et MP du joueur
                $characterHpMin = $characterHpMin  + $itemHpEffect;
                $characterMpMin = $characterMpMin  + $itemMpEffect;
                
                //Si les HP Minimum sont supérieur au HP Maximum
                if ($characterHpMin > $characterHpMax)
                {
                    //Si c'est le cas $characterHpMin = $characterHpMax
                    $characterHpMin = $characterHpMax;
                }
                
                //Si les MP Minimum sont supérieur au MP Maximum
                if ($characterMpMin > $characterMpMax)
                {
                    //Si c'est le cas $characterMpMin = $characterMpMax
                    $characterMpMin = $characterMpMax;
                }
            
                //Si l'adversaire à plus de puissance physique ou autant que de magique il fera une attaque physique
                if ($opponentStrength >= $opponentMagic)
                {
                    //On calcule les dégats de l'adversaire
                    $positiveDamagesOpponent = mt_rand($opponentMinStrength, $opponentMaxStrength);
                    $negativeDamagesOpponent = mt_rand($characterMinDefense, $characterMaxDefense);
                    $totalDamagesOpponent = $positiveDamagesOpponent - $negativeDamagesOpponent;
                }
                //Sinon il fera une attaque magique
                else
                {
                    //On calcule les dégats de l'adversaire
                    $positiveDamagesOpponent = mt_rand($opponentMinMagic, $opponentMaxMagic);
                    $negativeDamagesOpponent = mt_rand($characterMinDefenseMagic, $characterMaxDefenseMagic);
                    $totalDamagesOpponent = $positiveDamagesOpponent - $negativeDamagesOpponent;
                }
            
                //Si l'adversaire a fait des dégats négatif ont bloque à zéro pour ne pas soigner le personnage (Car moins et moins fait plus)
                if ($totalDamagesOpponent < 0)
                {
                    $totalDamagesOpponent = 0;
                }
            
                //On vérifie si le joueur esquive l'attaque de l'adversaire
                if ($characterAgilityTotal >= $opponentAgility)
                {
                    $totalDifference = $characterAgilityTotal - $opponentAgility;
                    $percentage = $totalDifference/$characterAgilityTotal * 100;
            
                    //Si la différence est de plus de 50% on bloque pour ne pas rendre le joueur intouchable
                    if ($percentage > 50)
                    {
                        $percentage = 50;
                    }
            
                    //On génère un nombre entre 0 et 100 (inclus)
                    $result = mt_rand(0, 101);
            
                    //Si le nombre généré est inférieur ou égal le joueur esquive l'attaque, on met donc $totalDamagesOpponent à 0
                    if ($result <= $percentage)
                    {
                        $totalDamagesOpponent = 0;
                        echo "$characterName a esquivé l'attaque de $opponentName<br />";
                    }
                    //Sinon le joueur subit l'attaque
                    else
                    {
                        echo "$opponentName a fait $totalDamagesOpponent point(s) de dégat à $characterName<br />";
                    }
                }
                //Si le joueur a moins d'agilité que l'adversaire il subit l'attaque
                else
                {
                    echo "$opponentName a fait $totalDamagesOpponent point(s) de dégat à $characterName<br />";
                }
                
                //On met à jour la vie du joueur et de l'adversaire
                $battleOpponentHpRemaining = $battleOpponentHpRemaining - $totalDamagesCharacter;
                $characterHpMin = $characterHpMin - $totalDamagesOpponent;
            
                //On met le personnage à jour dans la base de donnée
                $updateCharacter = $bdd->prepare("UPDATE car_characters
                SET characterHpMin = :characterHpMin,
                characterMpMin = :characterMpMin
                WHERE characterId = :characterId");
                $updateCharacter->execute([
                'characterHpMin' => $characterHpMin,
                'characterMpMin' => $characterMpMin,
                'characterId' => $characterId]);
                $updateCharacter->closeCursor();
                
                //Si le joueur possède plusieurs exemplaire de l'objet utilisé
                if ($itemQuantity > 1)
                {
                    //On met l'inventaire à jour
                    $updateInventory = $bdd->prepare("UPDATE car_inventory SET
                    inventoryQuantity = inventoryQuantity - 1
                    WHERE inventoryId = :inventoryId");
                    $updateInventory->execute(array(
                    'inventoryId' => $inventoryId));
                    $updateInventory->closeCursor();
                }
                //Si le joueur possède l'objet utilisé en un seul exemplaire
                else
                {
                    //On supprime l'objet de l'inventaire
                    $updateInventory = $bdd->prepare("DELETE FROM car_inventory
                    WHERE inventoryId = :inventoryId");
                    $updateInventory->execute(array(
                    'inventoryId' => $inventoryId));
                    $updateInventory->closeCursor();
                }
            
                //On met l'adversaire à jour dans la base de donnée
                $updateBattle = $bdd->prepare("UPDATE car_battles
                SET battleOpponentHpRemaining = :battleOpponentHpRemaining
                WHERE battleId = :battleId");
                $updateBattle->execute([
                'battleOpponentHpRemaining' => $battleOpponentHpRemaining,
                'battleId' => $battleId]);
                $updateBattle->closeCursor();
                
                //Si le joueur ou l'adversaire a moins ou a zéro HP on redirige le joueur vers la page des récompenses
                if ($characterHpMin <= 0 || $battleOpponentHpRemaining <= 0)
                {
                    ?>
                                
                    <hr>
                    
                    <form method="POST" action="rewards.php">
                        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
                    </form>
                    <?php
                }
            
                //Si l'adversaire et le joueur ont plus de zéro HP on continue le combat
                if ($battleOpponentHpRemaining > 0 && $characterHpMin > 0 )
                {
                    ?>
                            
                    <hr>
            
                    <form method="POST" action="index.php">
                        <input type="submit" name="magic" class="btn btn-default form-control" value="Continuer"><br>
                    </form>
                    
                    <?php
                }
            }
            else
            {
                echo "Erreur: Impossible d'utiliser un objet que vous ne possédez pas.";
            }
        }
        //Si l'objet n'existe pas
        else
        {
            echo "Erreur: Objet indisponible";
        }
        $itemQuery->closeCursor();
    }
    //Si l'objet choisit n'est pas un nombre
    else
    {
         echo "L'objet choisit est invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucune attaque de lancée";
}

require_once("../../html/footer.php"); ?>