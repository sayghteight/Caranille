<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownId'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownId'])
    && $_POST['adminTownId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminTownId = htmlspecialchars(addslashes($_POST['adminTownId']));

        //On fait une requête pour vérifier si la ville choisit existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId = ?');
        $townQuery->execute([$adminTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville est disponible
        if ($townRow == 1) 
        {
            //On fait une recherche dans la base de donnée de tous les comptes
            while ($town = $townQuery->fetch())
            {
                //On récupère les informations de l'objet
                $adminTownPicture = stripslashes($town['townPicture']);
                $adminTownName = stripslashes($town['townName']);
                $adminTownDescription = stripslashes($town['townDescription']);
                $adminTownPriceInn = stripslashes($town['townPriceInn']);
                $adminTownChapter = stripslashes($town['townChapter']);
            }
            ?>

            <p>Informations de la ville</p>
            <form method="POST" action="editTownEnd.php">
                Image : <br> <input type="text" name="adminTownPicture" class="form-control" placeholder="Image" value="<?php echo $adminTownPicture; ?>" required><br /><br />
                Nom : <br> <input type="text" name="adminTownName" class="form-control" placeholder="Nom" value="<?php echo $adminTownName; ?>" required><br /><br />
                Description : <br> <textarea class="form-control" name="adminTownDescription" id="adminTownDescription" rows="3"><?php echo $adminTownDescription; ?></textarea><br /><br />
                Prix de l'auberge : <br> <input type="number" name="adminTownPriceInn" class="form-control" placeholder="Prix de l'auberge" value="<?php echo $adminTownPriceInn; ?>" required><br /><br />
                Ville disponible au chapitre : <br> <input type="number" name="adminTownChapter" class="form-control" placeholder="Ville disponible au chapitre" value="<?php echo $adminTownChapter; ?>" required><br /><br />
                <input type="hidden" name="adminTownId" value="<?= $adminTownId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si la ville n'est pas disponible
        else
        {
            echo "Erreur: Ville indisponible";
        }
        $townQuery->closeCursor();
    }
    //Si la ville choisit n'est pas un nombre
    else
    {
        echo "Erreur: Ville invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");