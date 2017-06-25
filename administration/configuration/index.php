<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }
        
$configurationQuery = $bdd->query("SELECT * FROM car_configuration");

//On fait une boucle pour récupérer toutes les information
while ($configuration = $configurationQuery->fetch())
{
    //On récupère les informations du jeu
    $adminGameId = stripslashes($configuration['configurationId']);
    $adminGameName = stripslashes($configuration['configurationGameName']);
    $adminGamePresentation = stripslashes($configuration['configurationPresentation']);   
    $adminGameSkillPoint = stripslashes($configuration['configurationSkillPoint']);
    $adminGameAccess = stripslashes($configuration['configurationAccess']);
}
$configurationQuery->closeCursor();
?>

<p>Configuration du jeu</p>
<form method="POST" action="editConfiguration.php">
    Nom du jeu : <br> <input type="text" name="adminGameName" class="form-control" placeholder="Nom du jeu" value="<?php echo $adminGameName; ?>" required><br /><br />
    Présentation : <br> <textarea class="form-control" name="adminGamePresentation" id="adminGamePresentation" rows="3" required><?php echo $adminGamePresentation; ?></textarea><br /><br />
    PC par niveau : <br> <input type="text" name="adminGameSkillPoint" class="form-control" placeholder="PC par niveau" value="<?php echo $adminGameSkillPoint; ?>" required><br /><br />
    Jeu ouvert : <br> <select name="adminGameAccess" class="form-control">
    <?php
    switch ($adminGameAccess)
    {
        case "Opened":
        ?>
        <option selected="selected" value="Opened">Ouvert</option>
        <option value="Closed">Fermé</option>
        <?php
        break;

        case "Closed":
        ?>
        <option value="Opened">Ouvert</option>
        <option selected="selected" value="Closed">Fermé</option>
        <?php
        break;
    }
    ?>
    </select><br /><br />
    <input name="edit" class="btn btn-default form-control" type="submit" value="Modifier">
</form>

<?php require_once("../html/footer.php");