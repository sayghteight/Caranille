<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterId'])
&& isset($_POST['delete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminMonsterId'])
    && $_POST['adminMonsterId'] >= 1)
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
            <p>ATTENTION</p> 
            Vous êtes sur le point de supprimer le monstre <em><?php echo $adminMonsterName ?></em><br />
            confirmez-vous la suppression ?

            <hr>
                
            <form method="POST" action="deleteMonsterEnd.php">
                <input type="hidden" class="btn btn-default form-control" name="adminMonsterId" value="<?= $adminMonsterId ?>">
                <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
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
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");