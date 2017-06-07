<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton manage
if (isset($_POST['adminMonsterId'])
&& isset($_POST['manage']))
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
            //On fait une recherche dans la base de donnée de tous les comptes
            $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters
            WHERE monsterId = ?");
            $monsterQuery->execute([$adminMonsterId]);
            while ($monster = $monsterQuery->fetch())
            {
                $adminMonsterName = stripslashes($monster['monsterName']);
            }
            $monsterQuery->closeCursor();

            ?>
            Que souhaitez-vous faire du monstre <em><?php echo $adminMonsterName ?></em><br />

            <hr>
                
            <form method="POST" action="editMonster.php">
                <input type="hidden" class="btn btn-default form-control" name="adminMonsterId" value="<?= $adminMonsterId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier le monstre">
            </form>
            <form method="POST" action="deleteMonster.php">
                <input type="hidden" class="btn btn-default form-control" name="adminMonsterId" value="<?= $adminMonsterId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer le monstre">
            </form>

            <hr>

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
//Si l'utilisateur n'a pas cliqué sur le bouton manage
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");