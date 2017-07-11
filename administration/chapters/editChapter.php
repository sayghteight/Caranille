<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
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
        //On récupère l'id du formulaire précédent
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
                //On récupère les informations du monstre
                $adminMonsterId = stripslashes($monster['monsterId']);
                $adminMonsterName = stripslashes($monster['monsterName']);
            }
            $monsterQuery->closeCursor();
            ?>

            <p>Informations du chapitre</p>
            
            <form method="POST" action="editChapterEnd.php">
                Monstre du chapitre <select name="adminChapterMonsterId" class="form-control">
                    <option selected="selected" value="<?php echo $adminMonsterId ?>"><?php echo $adminMonsterName ?>
                    
                    <?php
                    //On rempli le menu déroulant avec la liste des monstres disponible sans afficher celui juste au dessus
                    $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters
                    WHERE monsterId != ?
                    ORDER BY monsterName");
                    $monsterQuery->execute([$adminChapterMonsterId]);
                    //On recherche combien il y a de monstres disponible
                    $monsterRow = $monsterQuery->rowCount();
                    
                    //S'il y a au moins un monstre de disponible on les affiches dans le menu déroulant
                    if ($monsterRow >= 1)
                    {
                        //On fait une boucle sur tous les résultats
                        while ($monster = $monsterQuery->fetch())
                        {
                            //On récupère les informations du monstre
                            $adminMonsterId = stripslashes($monster['monsterId']); 
                            $adminMonsterName = stripslashes($monster['monsterName']);
                            ?>
                            
                                <option value="<?php echo $adminMonsterId ?>"><?php echo $adminMonsterName ?></option>

                            <?php
                        }
                    }
                    $monsterQuery->closeCursor();
                    ?>
                    
                </select>
                Titre : <input type="text" name="adminChapterTitle" class="form-control" placeholder="Titre" value="<?php echo $adminChapterTitle ?>" required>
                Introduction :  <br> <textarea class="form-control" name="adminChapterOpening" id="adminChapterOpening" rows="3" required><?php echo $adminChapterOpening; ?></textarea>
                Conclusion :  <br> <textarea class="form-control" name="adminChapterEnding" id="adminChapterEnding" rows="3" required><?php echo $adminChapterEnding; ?></textarea>
                <input type="hidden" name="adminChapterId" value="<?= $adminChapterId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le chapitre n'exite pas
        else
        {
            echo "Erreur: Impossible de modifier un chapitre qui n'existe pas";
        }
        $chapterQuery->closeCursor();
    }
   //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");