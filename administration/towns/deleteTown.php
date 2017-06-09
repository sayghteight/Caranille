<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton delete
if (isset($_POST['adminTownId'])
&& isset($_POST['delete']))
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
                $adminTownName = stripslashes($town['townName']);
            }
            $townQuery->closeCursor();

            ?>
            <p>ATTENTION</p> 
            Vous êtes sur le point de supprimer la ville <em><?php echo $adminTownName ?></em><br />
            confirmez-vous la suppression ?

            <hr>
                
            <form method="POST" action="deleteTownEnd.php">
                <input type="hidden" class="btn btn-default form-control" name="adminTownId" value="<?= $adminTownId ?>">
                <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
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
//Si l'utilisateur n'a pas cliqué sur le bouton delete
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");