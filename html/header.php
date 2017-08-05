<?php
ob_start();
//On démarre le module des sessions de PHP
session_start();
//On récupère le temps Unix actuel une première fois
$timeStart = microtime(true);
//On inclue le fichier de configuration qui contient les paramètre de connexion SQL ainsi que la création d'un objet $bdd pour les requêtes SQL
require_once("../../kernel/config.php");
//On récupère les informations de configuration du jeu
require_once("../../kernel/configuration/index.php");
//Si le joueur est connecté on va récupérer toutes les informations du joueur (Compte, Personnage, Combat en cours...)
if (isset($_SESSION['account']['id']))
{
    //On récupère toutes les informations du compte
    require_once("../../kernel/account/index.php");
    //On récupère toutes les informations du personnage grâce au compte
    require_once("../../kernel/character/index.php");
    //On vérifie si le personnage est actuellement dans un combat de monstre. Si c'est le cas on récupère toutes les informations du monstre
    require_once("../../kernel/battle/index.php");
    //On récupère toutes les informations des équipements équipé au personnage
    require_once("../../kernel/equipment/index.php");
    //On récupère toutes les informations des type d'équipement
    require_once("../../kernel/equipmentType/index.php");
    //On vérifie le nombre d'offre dans le marché
    require_once("../../kernel/market/index.php");
    //On vérifie le nombre de message de conversation privée non lu
    require_once("../../kernel/privateConversation/index.php");
    //On vérifie si le personnage est actuellement dans une ville. Si c'est le cas on récupère toutes les informations de la ville
    require_once("../../kernel/town/index.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title><?php echo $gameName ?></title>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/navbar-fixed-top.css" rel="stylesheet">
    </head>

    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="container-fluid">
                    <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Menu de naviguation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../../modules/main/index.php"><?php echo $gameName ?></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Informations<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../../modules/main/index.php">Actualité</a></li>
                                <li><a href="../../modules/presentation/index.php">Présentation</a></li>
                                <li><a href="../../modules/races/index.php">Les classes</a></li>
                                <li><a href="../../modules/contact/index.php">Contact</a></li>
                            </ul>
                        </li>
                        
                        <?php
                        //Si le joueur est connecté on affiche le menu du jeu
                        if (isset($_SESSION['account']['id']))
                        {
                            ?>
                            
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Personnage<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="../../modules/character/index.php">Fiche complète</a></li>
                                    <li><a href="../../modules/skillPoints/index.php">Points de compétences</a></li>
                                    <li><a href="../../modules/inventory/index.php">Inventaire</a></li>
                                </ul>
                            </li>
                            
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Aventure<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="../../modules/story/index.php">Continuer l'aventure</a></li>
                                    <?php
                                    //Si characterTownId est supérieur ou égal à un le joueur est dans une ville. On met le raccourcit vers la ville
                                    if($characterTownId >= 1)
                                    {
                                        ?>
                                        
                                            <li><a href="../../modules/town/index.php">Retourner en ville</a></li>
                                            
                                        <?php
                                    }
                                    //Si characterTownId n'est pas supérieur ou égal à un le joueur est dans aucune ville. On met le raccourcit vers la carte du monde
                                    else
                                    {
                                        ?>
                                        
                                            <li><a href="../../modules/map/index.php">Carte du monde</a></li>
                                            
                                        <?php
                                    }
                                    ?>
                                    <li><a href="../../modules/bestiary/index.php">Bestiaire</a></li>
                                </ul>
                            </li>
                            
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Communauté<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="../../modules/chat/index.php">Chat</a></li>
                                    <li><a href="../../modules/privateConversation/index.php">Messagerie privée (<?php echo $privateConversationNumberRow ?>)</a></li>
                                    <li><a href="../../modules/market/index.php">Le marché (<?php echo $marketOfferQuantityRow ?>)</a></li>
                                </ul>
                            </li>
                            
                            <?php
                        }
                        ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mon compte<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php
                                //Si le joueur est connecté on lui donne la possibilité de se déconnecter
                                if (isset($_SESSION['account']['id']))
                                {
                                    ?>
                                    
                                    <li><a href="../../modules/account/index.php">Informations</a></li>
                                    
                                    <?php
                                    switch ($accountAccess)
                                    {
                                        case 0:
                                        
                                        break;
            
                                        case 1:
                                        ?>
                                        <?php
                                        break;
            
                                        case 2:
                                        ?>
                                            <li><a href="../../administration/main/index.php">Administration</a></li>
                                        <?php
                                        break;
                                    }
                                    ?>
                                    
                                        <li><a href="../../modules/logout/index.php">Déconnexion</a></li>
                                        
                                    <?php
                                }
                                //Sinon on propose au joueur de s'inscrire ou se connecter
                                else
                                {
                                    ?>
                        
                                        <li><a href="../../modules/login/index.php">Connexion</a></li>
                                        <li><a href="../../modules/register/index.php">Inscription</a></li>
                                        
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="jumbotron">