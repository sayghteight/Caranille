<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['marketId'])
&& isset($_POST['viewOffer']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['marketId'])
    && $_POST['marketId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $marketId = htmlspecialchars(addslashes($_POST['marketId']));

        //On fait une requête pour vérifier si l'offre choisit existe
        $marketQuery = $bdd->prepare('SELECT * FROM car_market, car_characters, car_items
        WHERE marketCharacterId = characterId
        AND marketItemId = itemId
        AND marketId = ?');
        $marketQuery->execute([$marketId]);
        $marketRow = $marketQuery->rowCount();

        //Si l'offre existe
        if ($marketRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($market = $marketQuery->fetch())
            {
                //On récupère toutes les informations de l'offre
                $marketId = stripslashes($market['marketId']);
                $marketCharacterName = stripslashes($market['characterName']);
                $marketItemId = stripslashes($market['itemId']);
                $marketItemName = stripslashes($market['itemName']);
                $marketSalePrice = stripslashes($market['marketSalePrice']);
                $marketItemRaceId = stripslashes($market['itemRaceId']);
                $marketItemType = stripslashes($market['itemType']);
                $marketItemLevel = stripslashes($market['itemLevel']);
                $marketItemLevelRequired = stripslashes($market['itemLevelRequired']);
                $marketItemName = stripslashes($market['itemName']);
                $marketItemDescription = stripslashes($market['itemDescription']);
                $marketItemHpEffect = stripslashes($market['itemHpEffect']);
                $marketItemMpEffect = stripslashes($market['itemMpEffect']);
                $marketItemStrengthEffect = stripslashes($market['itemStrengthEffect']);
                $marketItemMagicEffect = stripslashes($market['itemMagicEffect']);
                $marketItemAgilityEffect = stripslashes($market['itemAgilityEffect']);
                $marketItemDefenseEffect = stripslashes($market['itemDefenseEffect']);
                $marketItemDefenseMagicEffect = stripslashes($market['itemDefenseMagicEffect']);
                $marketItemWisdomEffect = stripslashes($market['itemWisdomEffect']);
            }

            //Si la race de l'équipement est supérieur à 1 c'est qu'il est attitré à une classe
            if ($marketItemRaceId >= 1)
            {
                //On récupère la classe de l'équipement
                $raceQuery = $bdd->prepare("SELECT * FROM car_races
                WHERE raceId = ?");
                $raceQuery->execute([$marketItemRaceId]);
                while ($race = $raceQuery->fetch())
                {
                    //On récupère le nom de la classe
                    $marketItemRaceName = stripslashes($race['raceName']);
                }
                $raceQuery->closeCursor();
            }
            //Si la race de l'équipement est égal à 0 c'est qu'il est disponible pour toutes les classes
            else
            {
                $marketItemRaceName = "Toutes les classes";
            }
            ?>
            
            <p>Offre de <?php echo $marketCharacterName ?></p>

            <table class="table">
                
                <?php
                //S'il s'agit d'un équipement on affiche la race de celui-ci ainsi que son niveu requis
                if ($marketItemType != "Item")
                {
                    ?>
                    <tr>
                        <td>
                            Classe requise
                        </td>
                    
                        <td>
                            <?php echo $marketItemRaceName ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Niveau de l'objet
                        </td>

                        <td>
                            <?php echo $marketItemLevel ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Niveau requis
                        </td>
                        
                        <td>
                            <?php echo $marketItemLevelRequired ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td>
                        Nom
                    </td>
                    
                    <td>
                        <?php echo $marketItemName ?>
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        Description
                    </td>
                    
                    <td>
                        <?php echo nl2br($marketItemDescription) ?>
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        Effet(s)
                    </td>
                    
                    <td>
                        <?php
                        //S'il s'agit d'un équipement on affiche toutes les stats concernée
                        if ($marketItemType != "Item")
                        {
                            ?>
                            
                            <?php echo '+' .$marketItemHpEffect. ' HP' ?><br />
                            <?php echo '+' .$marketItemMpEffect. ' MP' ?><br />
                            <?php echo '+' .$marketItemStrengthEffect. ' Force' ?><br />
                            <?php echo '+' .$marketItemMagicEffect. ' Magie' ?><br />
                            <?php echo '+' .$marketItemAgilityEffect. ' Agilité' ?><br />
                            <?php echo '+' .$marketItemDefenseEffect. ' Défense' ?><br />
                            <?php echo '+' .$marketItemDefenseMagicEffect. ' Défense magique' ?><br />
                            <?php echo '+' .$marketItemWisdomEffect. ' Sagesse'; ?>
                            
                            <?php
                        }
                        //S'il s'agit d'un objet on affiche que les stats HP et MP qui sont concernée
                        else
                        {
                            ?>
                            
                            <?php echo '+' .$marketItemHpEffect. ' HP' ?><br />
                            <?php echo '+' .$marketItemMpEffect. ' MP' ?><br />
                            
                            <?php
                        }
                        ?>                            
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        Prix de vente
                    </td>
                    
                    <td>
                        <?php echo $marketSalePrice ?>
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        Actions
                    </td>
                    
                    <td>
                        <form method="POST" action="buyOffer.php">
                            <input type="hidden" class="btn btn-default form-control" name="marketId" value="<?= $marketId ?>">
                            <input type="submit" class="btn btn-default form-control" name="buyOffer" value="Acheter">
                        </form>
                    </td>
                </tr>
            </table>
            
            <?php
        }
        //Si l'offre n'exite pas
        else
        {
            echo "Erreur: Cette offre n'existe pas";
        }
        $marketQuery->closeCursor();
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
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>