<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['add']))
{
    //On vérifie S'il existe au moins un monstre pour créer le monstre du chapitre (le boss)
    $monsterQuery = $bdd->query("SELECT * FROM car_monsters
    ORDER BY monsterName");
    //On recherche combien il y a de monstres disponible
    $monsterRow = $monsterQuery->rowCount();
    //S'il y a au moins un monstre de disponible on peut créer un chapitre
    if ($monsterRow >= 1)
    {
        ?>
        <p>Informations du chapitre</p>
        <form method="POST" action="addChapterEnd.php">
            Monstre du chapitre <br> 
            <select name="adminChapterMonsterId" class="form-control">
                <?php
                //On fait une boucle sur tous les résultats
                while ($monster = $monsterQuery->fetch())
                {
                    //on récupère les valeurs de chaque monstres qu'on va ensuite mettre dans le menu déroulant
                    $adminMonsterId = stripslashes($monster['monsterId']); 
                    $adminMonsterName = stripslashes($monster['monsterName']);
                    ?>
                    <option value="<?php echo $adminMonsterId ?>"><?php echo $adminMonsterName ?></option>
                    <?php
                }
                $monsterQuery->closeCursor();
                ?>
            </select><br /><br />
            Titre : <br> <input type="text" name="adminChapterTitle" class="form-control" placeholder="Titre" required><br /><br />
            Introduction :  <br> <textarea class="form-control" name="adminChapterOpening" id="adminChapterOpening" rows="3" required></textarea><br /><br />
            Conclusion :  <br> <textarea class="form-control" name="adminChapterEnding" id="adminChapterEnding" rows="3" required></textarea><br /><br />
            <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
        </form>
        
        <hr>
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
        <?php
    }
    //S'il n'y a aucun monstre dans le jeu
    else
    {
        echo "Il est impossible de créer un chapitre S'il n'existe aucun monstres dans le jeu";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");