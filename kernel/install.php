<?php
//Si le formulaire de création du fichier config.php n'est pas rempli on l'affiche
if (empty($_POST['install']))
{
    ?>
    <center>
        <p>Afin d'installer Caranille veuillez remplir les informations suivantes</p>
        <form method="POST" action="install.php">
            Nom de la base de donnée<br><input type="text" class="form-control" name="databaseName" required><br>
            Adresse de la base de donnée<br><input type="text" class="form-control" name="databaseHost" required><br>
            Nom de l'utilisateur<br><input type="text" class="form-control" name="databaseUser" required><br>
            Mot de passe<br><input type="password" class="form-control" name="databasePassword"><br>
            <input type="submit" class="btn btn-default form-control" name="install" value="Continuer">
        </form>
    </center>
    <?php
}
//Si le formulaire de création du fichier config.php est rempli on crée le fichier et on installe les exemples
else
{
    $databaseName = htmlspecialchars(addslashes($_POST['databaseName']));
    $databaseHost = htmlspecialchars(addslashes($_POST['databaseHost']));
    $databaseUser = htmlspecialchars(addslashes($_POST['databaseUser']));
    $databasePassword = htmlspecialchars(addslashes($_POST['databasePassword']));

    //On créer le fichier config.php et on y écrit dedans les informations de connexion SQL
    $openSql = fopen('config.php', 'w');
    fwrite($openSql, "
    <?php
    //Version of Caranille
    \$version = \"1.0.0\";
    \$dsn = 'mysql:dbname=$databaseName;host=$databaseHost';
    \$user = '$databaseUser';
    \$password = '$databasePassword';

    //LAUNCH THE CONNECTION
    try 
    {
        \$bdd = new PDO(\$dsn, \$user, \$password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        \$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    catch (PDOException \$e) 
    {
        echo 'An error as occurred, Cannot connect to the database. Error: '.\$e->getMessage();
    }
    ?>");
    fclose($openSql);

    //On inclue le fichier précédement crée
    include("config.php");

    //On injecte dans la base de donnée les exemples et ont affiche à l'utilisateur ses informations de connexion
    $bdd->query(file_get_contents('ddb.sql'));
    ?>
    <center>
        <p>Installation terminée !</p>
        Voici vos identifiants de connexion:<br />
        Identifiant: admin<br />
        Mot de passe: admin<br /><br />

        Une fois connecté veuillez immédiatement changer votre mot de passe !

        <form method="POST" action="../../index.php">
            <input type="submit" class="btn btn-default form-control" name="finish" value="Continuer">
        </form>
    </center>
    <?php
}
?>