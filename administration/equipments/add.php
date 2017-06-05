<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à choisit un id de compte
if (isset($_POST['add']))
{
    ?>
        <p>Informations de l'équipement</p>
        <form method="POST" action="finalAdd.php">
            Classe
            <select class="form-control" id="adminItemRaceId" name="adminItemRaceId">
            <option value="0">Toutes les classes</option>
            <?php
            //On rempli le menu déroulant avec la liste des classes disponible
            $raceListQuery = $bdd->query("SELECT * FROM car_races");
            //On recherche combien il y a de classes disponible
            $raceList = $raceListQuery->rowCount();
            //Si il y a au moins une classe de disponible on les affiches dans le menu déroulant
            if ($raceList >= 1)
            {
                //On fait une boucle sur tous les résultats
                while ($raceList = $raceListQuery->fetch())
                {
                    //on récupère les valeurs de chaque classes qu'on va ensuite mettre dans le menu déroulant
                    $raceId = stripslashes($raceList['raceId']); 
                    $raceName = stripslashes($raceList['raceName']);
                    ?>
                        <option value="<?php echo $raceId ?>"><?php echo $raceName ?></option>
                    <?php
                }
            }
            //Si il n'y a aucune classe de disponible on ajoute "Aucune classe" dans le menu déroulant
            else
            {
                ?>
                    <option value="0">Aucune classe</option>
                <?php
            }
            $raceListQuery->closeCursor();
            ?>
            </select>
            Image : <br> <input type="mail" name="adminItemPicture" class="form-control" placeholder="Image" required><br />
            Type:
            <select class="form-control" id="adminItemType" name="adminItemType">
                <option value="Armor">Armure</option>
                <option value="Boots">Bottes</option>
                <option value="Gloves">Gants</option>
                <option value="Helmet">Casque</option>
                <option value="Weapon">Arme</option>
            </select>
            Niveau : <br> <input type="mail" name="adminItemLevel" class="form-control" placeholder="Email" required><br /><br />
            Niveau requis : <br> <input type="mail" name="adminItemLevelRequired" class="form-control" placeholder="Niveau requis" required><br /><br />
            Nom : <br> <input type="text" name="adminItemName" class="form-control" placeholder="Nom" required><br /><br />
            Description : <br> <input type="mail" name="adminItemDescription" class="form-control" placeholder="Description" required><br /><br />
            HP Bonus : <br> <input type="mail" name="adminItemHpEffects" class="form-control" placeholder="HP Bonus" required><br /><br />
            MP Bonus : <br> <input type="mail" name="adminItemMpEffect" class="form-control" placeholder="MP Bonus" required><br /><br />
            Force Bonus : <br> <input type="mail" name="adminItemStrengthEffect" class="form-control" placeholder="Force Bonus" required><br /><br />
            Magie Bonus : <br> <input type="mail" name="adminItemMagicEffect" class="form-control" placeholder="Magie Bonus" required><br /><br />
            Agilité Bonus : <br> <input type="mail" name="adminItemAgilityEffect" class="form-control" placeholder="Agilité Bonus" required><br /><br />
            Défense Bonus : <br> <input type="mail" name="adminItemDefenseEffect" class="form-control" placeholder="Défense Bonus" required><br /><br />
            Défense Magique Bonus : <br> <input type="mail" name="adminItemDefenseMagicEffect" class="form-control" placeholder="Défense Magique Bonus" required><br /><br />
            Sagesse Bonus : <br> <input type="mail" name="adminItemWisdomEffect" class="form-control" placeholder="Sagesse Bonus" required><br /><br />
            Prix d'achat : <br> <input type="mail" name="adminItemPurchasePrice" class="form-control" placeholder="Prix d'achat" required><br /><br />
            Prix de vente : <br> <input type="mail" name="adminItemSalePrice" class="form-control" placeholder="Prix de vente" required><br /><br />
            <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
        </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");