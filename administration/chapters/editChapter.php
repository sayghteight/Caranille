<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminChapterId'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminChapterId'])
    && $_POST['adminChapterId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminChapterId = htmlspecialchars(addslashes($_POST['adminChapterId']));

        //On fait une requête pour vérifier si le chapitre choisit existe
        $chapterQuery = $bdd->prepare('SELECT * FROM car_chapters 
        WHERE chapterId = ?');
        $chapterQuery->execute([$adminChapterId]);
        $chapterRow = $chapterQuery->rowCount();

        //Si le chapitre existe
        if ($chapterRow == 1) 
        {
            //On fait une boucle pour récupérer toutes les information
            while ($chapter = $chapterQuery->fetch())
            {
                //On récupère les informations du chapitre
                $adminChapterId = stripslashes($chapter['chapterId']);
                $adminChapterMonsterId = stripslashes($chapter['chapterMonsterId']);
                $adminChapterTitle = stripslashes($chapter['chapterTitle']);
                $adminChapterOpening = stripslashes($chapter['chapterOpening']);
                $adminChapterEnding = stripslashes($chapter['chapterEnding']);
            }

            //On récupère les informations du monstre du chapitre
            $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
            WHERE monsterId = ?');
            $monsterQuery->execute([$adminChapterMonsterId]);
            
            //On fait une boucle pour récupérer toutes les information
            while ($monster = $monsterQuery->fetch())
            {
                $adminMonsterId = stripslashes($monster['monsterId']);
                $adminMonsterName = stripslashes($monster['monsterName']);
            }
            $monsterQuery->closeCursor();
            ?>

            <p>Informations du chapitre</p>
            <form method="POST" action="editChapterEnd.php">
                Monstre du chapitre <br> 
                <select name="adminChapterMonsterId" class="form-control">
                    <option selected="selected" value="<?php echo $adminMonsterId ?>"><?php echo $adminMonsterName ?>
                    <?php
                    //On rempli le menu déroulant avec la liste des monstres disponible sans afficher celui juste au dessus
                    $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters
                    WHERE monsterId != ?
                    ORDER BY monsterName");
                    $monsterQuery->execute([$adminChapterMonsterId]);
                    //On recherche combien il y a de monstres disponible
                    $monsterRow = $monsterQuery->rowCount();
                    //Si il y a au moins un monstre de disponible on les affiches dans le menu déroulant
                    if ($monsterRow >= 1)
                    {
                        //On fait une boucle sur tous les résultats
                        while ($monster = $monsterQuery->fetch())
                        {
                            //on récupère les valeurs de chaque classes qu'on va ensuite mettre dans le menu déroulant
                            $adminMonsterId = stripslashes($monster['monsterId']); 
                            $adminMonsterName = stripslashes($monster['monsterName']);
                            ?>
                            <option value="<?php echo $adminMonsterId ?>"><?php echo $adminMonsterName ?></option>
                            <?php
                        }
                    }
                    $monsterQuery->closeCursor();
                    ?>
                </select><br /><br />
                Titre : <br> <input type="text" name="adminChapterTitle" class="form-control" placeholder="Titre" value="<?php echo $adminChapterTitle; ?>" required><br /><br />
                Introduction :  <br> <textarea class="form-control" name="adminChapterOpening" id="adminChapterOpening" rows="3" required><?php echo $adminChapterOpening; ?></textarea><br /><br />
                Conclusion :  <br> <textarea class="form-control" name="adminChapterEnding" id="adminChapterEnding" rows="3" required><?php echo $adminChapterEnding; ?></textarea><br /><br />
                <input type="hidden" name="adminChapterId" value="<?= $adminChapterId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si le chapitre n'est pas disponible
        else
        {
            echo "Erreur: Chapitre indisponible";
        }
        $chapterQuery->closeCursor();
    }
    //Si le chapitre choisit n'est pas un nombre
    else
    {
        echo "Erreur: Chapitre invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");