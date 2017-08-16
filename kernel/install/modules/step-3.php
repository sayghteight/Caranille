<?php require_once("../html/header.php");

if (isset($_POST['databaseName'])
&& isset($_POST['databaseHost'])
&& isset($_POST['databaseUser'])
&& isset($_POST['databasePassword'])
&& isset($_POST['databasePort']))
{
	//Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        $_SESSION['token'] = NULL;
        
        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['databasePort'])
        && $_POST['databasePort'] >= 0)
        {
			//On récupère les valeurs du formulaire dans une variable
		    $databaseName = htmlspecialchars(addslashes($_POST['databaseName']));
		    $databaseHost = htmlspecialchars(addslashes($_POST['databaseHost']));
		    $databaseUser = htmlspecialchars(addslashes($_POST['databaseUser']));
		    $databasePassword = htmlspecialchars(addslashes($_POST['databasePassword']));
		    $databasePort = htmlspecialchars(addslashes($_POST['databasePort']));
		
		    //On créer le fichier config.php et on y écrit dedans les informations de connexion SQL
		    $openSql = fopen('../../config.php', 'w');
		    fwrite($openSql, "
		    <?php
		    //Version of Caranille
		    \$version = \"1.6.3\";
		    \$dsn = 'mysql:dbname=$databaseName;host=$databaseHost;port=$databasePort';
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
		    <form method="POST" action="step-4.php">
		        <input type="submit" class="btn btn-default form-control" name="continuer" value="Continuer">
		    </form>
		    
		    <?php
        }
        //Si tous les champs numérique ne contiennent pas un nombre
        else
        {
            echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
        }
    }
    //Si le token de sécurité n'est pas correct
    else
    {
        echo "Erreur: Token invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php"); ?>