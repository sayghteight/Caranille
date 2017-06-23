<?php
////On récupère le temps Unix actuel une première fois
$timeStart = microtime(true);
//On démarre le module des sessions de PHP
session_start();
//On inclue le fichier de configuration qui contient les paramètre de connexion SQL ainsi que la création d'un objet $bdd pour les requêtes SQL
require_once("../../kernel/config.php");
//Si le joueur est connecté on va récupérer toutes les informations du joueur (Compte, Personnage, Combat en cours...)
if (isset($_SESSION['account']['id']))
{
    //On récupère les informations de configuration du jeu
    require_once("../../kernel/configuration/index.php");
    //On récupère toutes les informations du compte
    require_once("../../kernel/account/index.php");
    //On récupère toutes les informations du personnage grâce au compte
    require_once("../../kernel/character/index.php");
    //On vérifie si le personnage est actuellement dans un combat de joueur. Si c'est le cas on récupère toutes les informations du personnage
    require_once("../../kernel/battleArena/index.php");
    //On vérifie si le personnage est actuellement dans un combat de monstre. Si c'est le cas on récupère toutes les informations du monstre
    require_once("../../kernel/battleMonster/index.php");
    //On récupère toutes les informations des équipements équipé au personnage
    require_once("../../kernel/equipment/index.php");
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

        <title>Caranille</title>
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
                    <a class="navbar-brand" href="../../modules/main/index.php">Caranille</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Informations<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="../../modules/main/index.php">Accueil</a></li>
                            <li><a href="../../modules/presentation/index.php">Présentation</a></li>
                            <li><a href="../../modules/races/index.php">Les classes</a></li>
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
                                <li><a href="../../modules/character/index.php">Fiche</a></li>
                                <li><a href="../../modules/skillPoints/index.php">Points de compétences</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sac à dos<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../../modules/inventory/index.php">Objets</a></li>
                                <li><a href="../../modules/inventory/equipment.php">Equipment</a></li>
                            </ul>
                        </li>
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
                        <?php
                    }
                    //Sinon on affiche rien
                    else
                    {
                        ?>
                        <?php
                    }
                    ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                    <?php
                    //Si le joueur est connecté on lui donne la possibilité de se déconnecter
                    if (isset($_SESSION['account']['id']))
                    {
                        switch ($accountAccess)
                        {
                            case 0:
                            
                            break;

                            case 1:
                            ?>
                                <li><a href="#">Modération</a></li>
                            <?php
                            break;

                            case 2:
                            ?>
                                <li><a href="#">Modération</a></li>
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
                            <li><a href="../../modules/register/index.php">Inscription</a></li>
                            <li><a href="../../modules/login/index.php">Connexion</a></li>
                        <?php
                    }
                    ?>
                    <li><a href="../../modules/contact/index.php">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="jumbotron">