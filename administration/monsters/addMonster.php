<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton add
if (isset($_POST['add']))
{
    ?>
    <p>Informations du monstre</p>
    <form method="POST" action="addMonsterEnd.php">
        Image : <br> <input type="mail" name="adminMonsterPicture" class="form-control" placeholder="Image" required><br /><br />
        Nom : <br> <input type="text" name="adminMonsterName" class="form-control" placeholder="Nom" required><br /><br />
        Niveau : <br> <input type="mail" name="adminMonsterLevel" class="form-control" placeholder="Niveau" required><br /><br />
        Description : <br> <input type="mail" name="adminMonsterDescription" class="form-control" placeholder="Description" required><br /><br />
        HP : <br> <input type="mail" name="adminMonsterHp" class="form-control" placeholder="HP" required><br /><br />
        MP : <br> <input type="mail" name="adminMonsterMp" class="form-control" placeholder="MP" required><br /><br />
        Force : <br> <input type="mail" name="adminMonsterStrength" class="form-control" placeholder="Force" required><br /><br />
        Magie : <br> <input type="mail" name="adminMonsterMagic" class="form-control" placeholder="Magie" required><br /><br />
        Agilité : <br> <input type="mail" name="adminMonsterAgility" class="form-control" placeholder="Agilité" required><br /><br />
        Défense : <br> <input type="mail" name="adminMonsterDefense" class="form-control" placeholder="Défense" required><br /><br />
        Défense Magique : <br> <input type="mail" name="adminMonsterDefenseMagic" class="form-control" placeholder="Défense Magique" required><br /><br />
        Sagesse : <br> <input type="mail" name="adminMonsterWisdom" class="form-control" placeholder="Sagesse" required><br /><br />
        Experience : <br> <input type="mail" name="adminMonsterExperience" class="form-control" placeholder="Expérience" required><br /><br />
        Argent : <br> <input type="mail" name="adminMonsterGold" class="form-control" placeholder="Argent" required><br /><br />
        <input type="hidden" name="adminMonsterId" value="<?= $adminMonsterId ?>">
        <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
    </form>
    
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton add
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");