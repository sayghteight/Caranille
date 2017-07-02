<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['add']))
{
    ?>
    <p>Informations du monstre</p>
    <form method="POST" action="addMonsterEnd.php">
        Image : <br> <input type="text" name="adminMonsterPicture" class="form-control" placeholder="Image" required><br /><br />
        Nom : <br> <input type="text" name="adminMonsterName" class="form-control" placeholder="Nom" required><br /><br />
        Niveau : <br> <input type="number" name="adminMonsterLevel" class="form-control" placeholder="Niveau" required><br /><br />
        Description : <br> <textarea class="form-control" name="adminMonsterDescription" id="adminMonsterDescription" rows="3" required></textarea><br /><br />
        HP : <br> <input type="number" name="adminMonsterHp" class="form-control" placeholder="HP" required><br /><br />
        MP : <br> <input type="number" name="adminMonsterMp" class="form-control" placeholder="MP" required><br /><br />
        Force : <br> <input type="number" name="adminMonsterStrength" class="form-control" placeholder="Force" required><br /><br />
        Magie : <br> <input type="number" name="adminMonsterMagic" class="form-control" placeholder="Magie" required><br /><br />
        Agilité : <br> <input type="number" name="adminMonsterAgility" class="form-control" placeholder="Agilité" required><br /><br />
        Défense : <br> <input type="number" name="adminMonsterDefense" class="form-control" placeholder="Défense" required><br /><br />
        Défense Magique : <br> <input type="number" name="adminMonsterDefenseMagic" class="form-control" placeholder="Défense Magique" required><br /><br />
        Sagesse : <br> <input type="number" name="adminMonsterWisdom" class="form-control" placeholder="Sagesse" required><br /><br />
        Experience : <br> <input type="number" name="adminMonsterExperience" class="form-control" placeholder="Expérience" required><br /><br />
        Argent : <br> <input type="number" name="adminMonsterGold" class="form-control" placeholder="Argent" required><br /><br />
        <input type="hidden" name="adminMonsterId" value="<?= $adminMonsterId ?>">
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
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");