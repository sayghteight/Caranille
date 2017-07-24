<?php require_once("../html/header.php");

    //On récupère les valeurs du formulaire dans une variable
    $databaseName = htmlspecialchars(addslashes($_POST['databaseName']));
    $databaseHost = htmlspecialchars(addslashes($_POST['databaseHost']));
    $databaseUser = htmlspecialchars(addslashes($_POST['databaseUser']));
    $databasePassword = htmlspecialchars(addslashes($_POST['databasePassword']));

    //On créer le fichier config.php et on y écrit dedans les informations de connexion SQL
    $openSql = fopen('../../config.php', 'w');
    fwrite($openSql, "
    <?php
    //Version of Caranille
    \$version = \"1.2.2\";
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
        exit();
    }
    ?>");
    fclose($openSql);

    //On inclue le fichier précédement crée
    include("../../config.php");

    $bdd->query(file_get_contents('../ddb.sql'));
    ?>
    
    Création de la base de donnée terminée...
    <form method="POST" action="step-3.php">
        <input type="submit" class="btn btn-default form-control" name="continuer" value="Continuer">
    </form>
    
<?php require_once("../html/footer.php"); ?>