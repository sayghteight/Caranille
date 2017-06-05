<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à choisit un id de compte
if (isset($_POST['adminMonsterId']))
{
    //On vérifie si l'id du compte choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['adminMonsterId']))
    {
        //On récupère l'Id du formulaire précédent
        $adminMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterId']));

        //On fait une requête pour vérifier si le monstre choisit existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId= ?');
        $monsterQuery->execute([$adminMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si l'équippement est disponible
        if ($monsterRow == 1) 
        {
            while ($monster = $monsterQuery->fetch())
            {
                //On récupère les informations du monstre
                $adminMonsterId = stripslashes($monster['monsterId']);
                $adminMonsterPicture = stripslashes($monster['monsterPicture']);
                $adminMonsterName = stripslashes($monster['monsterName']);
                $adminMonsterLevel = stripslashes($monster['monsterLevel']);
                $adminMonsterDescription = stripslashes($monster['monsterDescription']);
                $adminMonsterHp = stripslashes($monster['monsterHp']);
                $adminMonsterMp = stripslashes($monster['monsterMp']);
                $adminMonsterStrength = stripslashes($monster['monsterStrength']);
                $adminMonsterMagic = stripslashes($monster['monsterMagic']);
                $adminMonsterAgility = stripslashes($monster['monsterAgility']);
                $adminMonsterDefense = stripslashes($monster['monsterDefense']);
                $adminMonsterDefenseMagic = stripslashes($monster['monsterDefenseMagic']);
                $adminMonsterWisdom = stripslashes($monster['monsterWisdom']);  
                $adminMonsterExperience = stripslashes($monster['monsterExperience']);              
                $adminMonsterGold = stripslashes($monster['monsterGold']);
            }
            ?>

            <p>Informations du monstre</p>
            <form method="POST" action="finalEdit.php">
                Image : <br> <input type="mail" name="adminMonsterPicture" class="form-control" placeholder="Image" value="<?php echo $adminMonsterPicture; ?>" required><br /><br />
                Nom : <br> <input type="text" name="adminMonsterName" class="form-control" placeholder="Nom" value="<?php echo $adminMonsterName; ?>" required><br /><br />
                Niveau : <br> <input type="mail" name="adminMonsterLevel" class="form-control" placeholder="Niveau" value="<?php echo $adminMonsterLevel; ?>" required><br /><br />
                Description : <br> <input type="mail" name="adminMonsterDescription" class="form-control" placeholder="Description" value="<?php echo $adminMonsterDescription; ?>" required><br /><br />
                HP : <br> <input type="mail" name="adminMonsterHp" class="form-control" placeholder="HP" value="<?php echo $adminMonsterHp; ?>" required><br /><br />
                MP : <br> <input type="mail" name="adminMonsterMp" class="form-control" placeholder="MP" value="<?php echo $adminMonsterMp; ?>" required><br /><br />
                Force : <br> <input type="mail" name="adminMonsterStrength" class="form-control" placeholder="Force" value="<?php echo $adminMonsterStrength; ?>" required><br /><br />
                Magie : <br> <input type="mail" name="adminMonsterMagic" class="form-control" placeholder="Magie" value="<?php echo $adminMonsterMagic; ?>" required><br /><br />
                Agilité : <br> <input type="mail" name="adminMonsterAgility" class="form-control" placeholder="Agilité" value="<?php echo $adminMonsterAgility; ?>" required><br /><br />
                Défense : <br> <input type="mail" name="adminMonsterDefense" class="form-control" placeholder="Défense" value="<?php echo $adminMonsterDefense; ?>" required><br /><br />
                Défense Magique : <br> <input type="mail" name="adminMonsterDefenseMagic" class="form-control" placeholder="Défense Magique" value="<?php echo $adminMonsterDefenseMagic; ?>" required><br /><br />
                Sagesse : <br> <input type="mail" name="adminMonsterWisdom" class="form-control" placeholder="Sagesse" value="<?php echo $adminMonsterWisdom; ?>" required><br /><br />
                Experience : <br> <input type="mail" name="adminMonsterExperience" class="form-control" placeholder="Expérience" value="<?php echo $adminMonsterExperience; ?>" required><br /><br />
                Argent : <br> <input type="mail" name="adminMonsterGold" class="form-control" placeholder="Argent" value="<?php echo $adminMonsterGold; ?>" required><br /><br />
                <input type="hidden" name="adminMonsterId" value="<?= $adminMonsterId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>

            <hr>

            Autres options
            <form method="POST" action="delete.php">
                <input type="hidden" class="btn btn-default form-control" name="adminMonsterId" value="<?= $adminMonsterId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer le monstre">
            </form>
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si le monstre n'est pas disponible
        else
        {
            echo "Erreur: Monstre indisponible";
        }
        $monsterQuery->closeCursor();
    }
    //Si le monstre choisit n'est pas un nombre
    else
    {
        echo "Erreur: Monstre invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");