<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownId'])
    && $_POST['adminTownId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminTownId = htmlspecialchars(addslashes($_POST['adminTownId']));

        //On fait une requête pour vérifier si l'objet choisi existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId= ?');
        $townQuery->execute([$adminTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville existe
        if ($townRow == 1) 
        {
            //On fait une recherche dans la base de donnée de toutes les villes
            while ($town = $townQuery->fetch())
            {
                $adminTownName = stripslashes($town['townName']);
            }
            ?>
            Que souhaitez-vous faire de la ville <em><?php echo $adminTownName ?></em> ?<br />

            <hr>
                
            <form method="POST" action="editTown.php">
                <input type="hidden" class="btn btn-default form-control" name="adminTownId" value="<?= $adminTownId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier la ville">
            </form>
            <form method="POST" action="../townsShops/manageTownShop.php">
                <input type="hidden" class="btn btn-default form-control" name="adminTownShopTownId" value="<?= $adminTownId ?>">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Magasins de la ville">
            </form>
            <form method="POST" action="../townsMonsters/manageTownMonster.php">
                <input type="hidden" class="btn btn-default form-control" name="adminTownMonsterTownId" value="<?= $adminTownId ?>">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Monstres de la ville">
            </form>
            <form method="POST" action="deleteTown.php">
                <input type="hidden" class="btn btn-default form-control" name="adminTownId" value="<?= $adminTownId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer la ville">
            </form>
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si la ville n'exite pas
        else
        {
            echo "Erreur: Ville indisponible";
        }
        $townQuery->closeCursor();
    }
    //Si la ville choisi n'est pas un nombre
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