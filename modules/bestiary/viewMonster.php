<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['monsterId'])
&& isset($_POST['viewMonster']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if(ctype_digit($_POST['monsterId'])
    && $_POST['monsterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $monsterId = htmlspecialchars(addslashes($_POST['monsterId']));
        
        //On fait une requête pour vérifier si le monstre entré est bien dans le bestiaire du joueur
        $monsterBestiaryQuery = $bdd->prepare("SELECT * FROM car_monsters, car_bestiary 
        WHERE monsterId = bestiaryMonsterId
        AND bestiaryMonsterId = ?
        AND bestiaryCharacterId = ?");
        $monsterBestiaryQuery->execute([$monsterId, $characterId]);
        $monsterBestiaryRow = $monsterBestiaryQuery->rowCount();
        
        //Si un ou plusieurs équipements ont été trouvé
        if ($monsterBestiaryRow == 1)
        {
            //On récupère le nombre de fois que ce monstre a été vaincu
            while ($monsterBestiary = $monsterBestiaryQuery->fetch())
            {
                //On récupère les informations du monstre
                $monsterDefeateQuantity = stripslashes($monsterBestiary['bestiaryMonsterQuantity']);
            }
            
            //On fait une requête pour vérifier si le monstre entré existe
            $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters
            WHERE monsterId = ?");
            $monsterQuery->execute([$monsterId]);
            $monsterRow = $monsterQuery->rowCount();
            
            if ($monsterRow == 1)
            {
                while ($monster = $monsterQuery->fetch())
                {
                    //On récupère les informations du monstre
                    $monsterId = stripslashes($monster['monsterId']);
                    $monsterPicture = stripslashes($monster['monsterPicture']);
                    $monsterName = stripslashes($monster['monsterName']);
                    $monsterDescription = stripslashes($monster['monsterDescription']);
                    $monsterLevel = stripslashes($monster['monsterLevel']);
                    $monsterHp = stripslashes($monster['monsterHp']);
                    $monsterMp = stripslashes($monster['monsterMp']);
                    $monsterStrength = stripslashes($monster['monsterStrength']);
                    $monsterMagic = stripslashes($monster['monsterMagic']);
                    $monsterAgility = stripslashes($monster['monsterAgility']);
                    $monsterDefense = stripslashes($monster['monsterDefense']);
                    $monsterDefenseMagic = stripslashes($monster['monsterDefenseMagic']);
                    $monsterWisdom = stripslashes($monster['monsterWisdom']);
                    $monsterGold = stripslashes($monster['monsterGold']);
                    $monsterExperience = stripslashes($monster['monsterExperience']);
                }
                ?>
                
                <p><img src="<?php echo $monsterPicture ?>" height="100" width="100"></p>
                
                <table class="table">
                    <tr>
                        <td>
                            Nom
                        </td>
                        
                        <td>
                            <?php echo $monsterName; ?>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>
                            Description
                        </td>
                        
                        <td>
                            <?php echo nl2br($monsterDescription); ?>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>
                            Niveau
                        </td>
                        
                        <td>
                            <?php echo $monsterLevel; ?>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>
                            HP
                        </td>
                        
                        <td>
                            <?php echo $monsterHp; ?>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>
                            MP
                        </td>
                        
                        <td>
                            <?php echo $monsterMp; ?>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>
                            Force
                        </td>
                        
                        <td>
                            <?php echo $monsterStrength; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Magie
                        </td>
                        
                        <td>
                            <?php echo $monsterMagic; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Agilité
                        </td>
                        
                        <td>
                            <?php echo $monsterAgility; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Défense
                        </td>
                        
                        <td>
                            <?php echo $monsterDefense; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Défense Magique
                        </td>
                        
                        <td>
                            <?php echo $monsterDefenseMagic; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Sagesse
                        </td>
                        
                        <td>
                            <?php echo $monsterWisdom; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Expérience
                        </td>
                        
                        <td>
                            <?php echo $monsterExperience; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Argent
                        </td>
                        
                        <td>
                            <?php echo $monsterGold; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Localisation
                        </td>
                        
                        <td>
                            
                            <?php
                            //On recherche dans la base de donnée les ville dans lequel ce monstre se trouve
                            $monsterTownQuery = $bdd->prepare("SELECT * FROM car_towns, car_towns_monsters
                            WHERE townMonsterTownId = townId
                            AND townMonsterMonsterId = ?");
                            $monsterTownQuery->execute([$monsterId]);
                            $monsterTownRow = $monsterTownQuery->rowCount();
                        
                            //S'il existe une ou plusieurs ville pour ce monstre
                            if ($monsterTownRow > 0) 
                            {
                                //On va tester si le joueur l'obtient
                                while ($monsterTown = $monsterTownQuery->fetch())
                                {
                                    $monsterTownName = stripslashes($monsterTown['townName']);
                                    $monsterTownChapter = stripslashes($monsterTown['townChapter']);
                                    
                                    //Si le joueur à accès à cette ville on l'affiche
                                    if ($monsterTownChapter <= $characterChapter)
                                    {
                                       echo "$monsterTownName<br />"; 
                                    }
                                    //Si le joueur n'a pas accès à cette ville on cache le nom
                                    else 
                                    {
                                        echo "???<br />";
                                    }
                                }
                            }
                            //Si ce monstre se trouve dans aucune ville
                            else
                            {
                                echo "Lieu inconnu";
                            }
                            $monsterTownQuery->closeCursor();
                            ?>
                            
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Objets (%)
                        </td>
                        
                        <td>
                            
                            <?php
                            //On recherche dans la base de donnée les objets et équipements que ce monstre peut faire gagner
                            $monsterDropQuery = $bdd->prepare("SELECT * FROM car_items, car_monsters_drops
                            WHERE monsterDropItemId = itemId
                            AND monsterDropMonsterId = ?");
                            $monsterDropQuery->execute([$monsterId]);
                            $monsterDropRow = $monsterDropQuery->rowCount();
                        
                            //S'il existe un ou plusieurs objet pour ce monstre
                            if ($monsterDropRow > 0) 
                            {
                                //On va tester si le joueur l'obtient
                                while ($monsterDrop = $monsterDropQuery->fetch())
                                {
                                    $monsterDropItemId = stripslashes($monsterDrop['itemId']);
                                    
                                    $monsterDropItemVisible = stripslashes($monsterDrop['monsterDropItemVisible']);
                                    $monsterDropRateVisible = stripslashes($monsterDrop['monsterDropRateVisible']);
                                    
                                    //Si l'objet est caché
                                    if ($monsterDropItemVisible == "Yes")
                                    {
                                        $monsterDropItemName = stripslashes($monsterDrop['itemName']);
                                    }
                                    else
                                    {
                                        $monsterDropItemName = "???";
                                    }
                                    
                                    //Si le taux d'obtention est caché
                                    if ($monsterDropRateVisible == "Yes")
                                    {
                                        $monsterDropRate = stripslashes($monsterDrop['monsterDropRate']);
                                    }
                                    else
                                    {
                                        $monsterDropRate = "???";
                                    }
                                    
                                    echo "$monsterDropItemName ($monsterDropRate%)<br />";
                                }
                            }
                            //Si aucun objet ne peut être obtenu
                            else
                            {
                                echo "Aucun objet";
                            }
                            $monsterDropQuery->closeCursor();
                            ?>
                            
                        </td>
                    </tr>
                </table>
                            
                <hr>
    
                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" value="Retour">
                </form>
                
                <?php
            }
            //Si le monstre n'existe pas
            else 
            {
                 echo "Ce monstre n'existe pas";
            }
            $monsterQuery->closeCursor();
        }
        else
        {
            echo "Ce monstre ne fait pas parti de votre bestiaire";
        }
        $monsterBestiaryQuery->closeCursor(); 
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