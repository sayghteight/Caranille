<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

$fileUpdate = '../../kernel/update/update.php';

if (file_exists($fileUpdate)) 
{
    ?>
    
    Le fichier <?php echo $fileUpdate ?> existe.
    
    <?php
} 
else 
{
    ?>
    
    Aucun fichier de mise à jour trouvé.<br />
    Pour mettre Caranille à jour veuiller copier le fichier update.php dans le dossier suivant:<br />
    kernel/update<br />
    
    <?php
}

require_once("../html/footer.php");