<?php
$timeStart = microtime(true);
session_start();
require_once("../../kernel/config.php");

//On récupère toutes les informations du compte
require_once("../../kernel/account/index.php");
//On récupère toutes les informations du personnage grâce au compte
require_once("../../kernel/character/index.php");
//On vérifie si le personnage est actuellement dans un combat de joueur. Si c'est le cas on récupère toutes les informations du personnage
require_once("../../kernel/battleArena/index.php");
//On vérifie si le personnage est actuellement dans un combat de monstre. Si c'est le cas on récupère toutes les informations du monstre
require_once("../../kernel/battleMonster/index.php");
//On vérifie si le personnage est actuellement dans une ville. Si c'est le cas on récupère toutes les informations de la ville
require_once("../../kernel/town/index.php");
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

        <title>Administration</title>
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
                    <a class="navbar-brand" href="../../administration/main/index.php">Administration</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gestion de la communauté<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../../administration/accounts/index.php">Joueurs</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gestion des ressources<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../../administration/monsters/index.php">Monstres</a></li>
                                <li><a href="../../administration/equipments/index.php">Equipements</a></li>
                                <li><a href="../../administration/items/index.php">Objets</a></li>
                                <li><a href="../../administration/towns/index.php">Villes</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gestion du scénario<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../../administration/story/index.php">Histoire</a></li>
                                <li><a href="../../administration/missions/index.php">Missions</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gestion de la configuration<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../../administration/configuration/index.php">Configuration</a></li>
                                <li><a href="../../administration/updates/index.php">Mises à jour</a></li>
                            </ul>
                        </li>
                        <li><a href="../../modules/main/index.php">Retour au jeu</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="jumbotron">