<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownShopTownId'])
&& isset($_POST['adminTownShopShopId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownShopTownId'])
    && ctype_digit($_POST['adminTownShopShopId'])
    && $_POST['adminTownShopTownId'] >= 1
    && $_POST['adminTownShopShopId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminTownShopTownId = htmlspecialchars(addslashes($_POST['adminTownShopTownId']));
        $adminTownShopShopId = htmlspecialchars(addslashes($_POST['adminTownShopShopId']));

        //On fait une requête pour vérifier si la ville choisie existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId= ?');
        $townQuery->execute([$adminTownShopTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville est disponible
        if ($townRow == 1) 
        {
            //On fait une requête pour vérifier si le magasin choisit existe
            $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
            WHERE shopId= ?');
            $shopQuery->execute([$adminTownShopShopId]);
            $shopRow = $shopQuery->rowCount();

            //Si le magasin est disponible
            if ($shopRow == 1) 
            {
                //On fait une requête pour vérifier si le magasin n'est pas déjà dans cette ville
                $townShopQuery = $bdd->prepare('SELECT * FROM car_towns_shops 
                WHERE townShopTownId = ?
                AND townShopShopId = ?');
                $townShopQuery->execute([$adminTownShopTownId, $adminTownShopShopId]);
                $townShopRow = $townShopQuery->rowCount();

                //Si le magasin est dans la ville
                if ($townShopRow == 1) 
                {
                    //On supprime l'équippement de la base de donnée
                    $townShopDeleteQuery = $bdd->prepare("DELETE FROM car_towns_shops
                    WHERE townShopShopId = ?");
                    $townShopDeleteQuery->execute([$adminTownShopShopId]);
                    $townShopDeleteQuery->closeCursor();
                    ?>

                    Le magasin a bien été retiré de la ville

                    <hr>
                        
                    <form method="POST" action="manageTownShop.php">
                        <input type="hidden" name="adminTownShopTownId" value="<?= $adminTownShopTownId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
                    </form>
                    <?php
                }
                //Si le magasin n'est pas dans la ville disponible
                else
                {
                    echo "Erreur: Ce magasin n'est pas dans cette ville";
                }
                $townShopQuery->closeCursor();
            }
            //Si le magasin existe pas
            else
            {
                echo "Erreur: Magasin indisponible";
            }
            $shopQuery->closeCursor();
        }
        //Si la ville existe pas
        else
        {
            echo "Erreur: Ville indisponible";
        }
        $townQuery->closeCursor();
    }
    //Si le monstre choisit n'est pas un nombre
    else
    {
        echo "Erreur: Equippement invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");