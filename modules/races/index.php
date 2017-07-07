<?php require_once("../../html/header.php");

//On recherche la liste des races dans la base de donnée
$raceQuery = $bdd->query('SELECT * FROM car_races');
$raceRow = $raceQuery->rowCount();

//Pour chaque races trouvées on l'affiche
while ($race = $raceQuery->fetch()) 
{
    ?>
    
    <p><img src="<?php echo stripslashes($race['racePicture']); ?>" height="100" width="100"></p>
    
     <table class="table">
            <tr>
                <td>
                    Nom
                </td>
            
                <td>
                    <?php echo stripslashes($race['raceName']); ?>
                </td>
            </tr>

            <tr>
                <td>
                    Description
                </td>
                
                <td>
                    <?php echo stripslashes(nl2br($race['raceDescription'])); ?>
                </td>
            </tr>
                
            <tr>
                <td>
                    Amélioration par niveau
                </td>
                
                <td>
                    <?php echo '+' .stripslashes($race['raceHpBonus']). ' HP'; ?><br />
                    <?php echo '+' .stripslashes($race['raceMpBonus']). ' MP'; ?><br />
                    <?php echo '+' .stripslashes($race['raceStrengthBonus']). ' Force'; ?><br />
                    <?php echo '+' .stripslashes($race['raceMagicBonus']). ' Magie'; ?><br />
                    <?php echo '+' .stripslashes($race['raceAgilityBonus']). ' Agilité'; ?><br />
                    <?php echo '+' .stripslashes($race['raceDefenseBonus']). ' Défense'; ?><br />
                    <?php echo '+' .stripslashes($race['raceDefenseMagicBonus']). ' Défense magique'; ?><br />
                    <?php echo '+' .stripslashes($race['raceWidsomBonus']). ' Sagesse'; ?>
                </td>
            </tr>
        </table>
        
    <hr>
    
    <?php
}
$raceQuery->closeCursor();

require_once("../../html/footer.php"); ?>