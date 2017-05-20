<?php
$timeStart = microtime(true);
session_start();
require_once("../../kernel/config.php");
if (isset($_SESSION['account']['id']))
{
    require_once("../../kernel/account/index.php");
    require_once("../../kernel/character/index.php");
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
                    <li class="active"><a href="../../modules/main/index.php">Accueil</a></li>
                    <?php
                    if (isset($_SESSION['account']['id']))
                    {
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Personnage<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../../modules/character/index.php">Fiche</a></li>
                                <li><a href="../../modules/character/stats.php">Statistiques</a></li>
                                <li><a href="../../modules/skillPoints/index.php">Points de compétences</a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    else
                    {
                        ?>
                        <?php
                    }
                    ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (isset($_SESSION['account']['id']))
                    {
                        ?>
                            <li><a href="../../modules/logout/index.php">Déconnexion</a></li>
                        <?php
                    }
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