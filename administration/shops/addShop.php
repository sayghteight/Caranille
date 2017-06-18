<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['add']))
{
    ?>
    <p>Informations du magasin</p>
    <form method="POST" action="addShopEnd.php">
        Image : <br> <input type="text" name="adminShopPicture" class="form-control" placeholder="Image" required><br /><br />
        Nom : <br> <input type="text" name="adminShopName" class="form-control" placeholder="Nom" required><br /><br />
        Description : <br> <textarea class="form-control" name="adminShopDescription" id="adminShopDescription" rows="3" required></textarea><br /><br />
        <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
    </form>
    
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");