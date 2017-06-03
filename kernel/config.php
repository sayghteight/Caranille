
    <?php
    //Version of Caranille
    $version = "1.0.0";
    $dsn = 'mysql:dbname=caranille;host=localhost';
    $user = 'root';
    $password = '';

    //LAUNCH THE CONNECTION
    try 
    {
        $bdd = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    catch (PDOException $e) 
    {
        echo 'An error as occurred, Cannot connect to the database. Error: '.$e->getMessage();
        exit();
    }
    ?>