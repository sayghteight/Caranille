<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['monsterId'])
&& isset($_POST['viewMonster']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if(ctype_digit($_POST['monsterId'])
    && $_POST['monsterId'] >= 1)
    {
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
                $monsterDefeateQuantity = stripslashes($monsterBestiary['bestiaryMonsterQuantity']);
            }
            
            //On fait une requête pour vérifier si le monstre entré est bien dans le bestiaire du joueur
            $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters
            WHERE monsterId = ?");
            $monsterQuery->execute([$monsterId]);
            $monsterRow = $monsterQuery->rowCount();
            
            while ($monster = $monsterQuery->fetch())
            {
                $monsterId = stripslashes($monster['monsterId']);
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
            </table>
                        
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" value="Retour">
            </form>
            <?php
            
        }
        else
        {
            echo "Ce monstre ne fait pas parti de votre bestiaire";
        }
        $monsterBestiaryQuery->closeCursor();
    }
    //Si le monstre choisit n'est pas un nombre
    else
    {
         echo "Le monstre choisit est invalid";
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>