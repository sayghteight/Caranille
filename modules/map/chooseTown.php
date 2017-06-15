<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur est déjà dans une ville on le redirige vers la ville
if ($characterTownId >= 1) { exit(header("Location: ../../modules/town/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['townId'])
&& isset($_POST['enter']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['townId'])
    && $_POST['townId'] >= 1)
    {
        //On récupère les valeurs du formulaire dans une variable
        $townId = htmlspecialchars(addslashes($_POST['townId']));

        //On fait une requête pour vérifier si le joueur peut accèder à la ville choisie
        $townQuery = $bdd->prepare('SELECT * FROM car_towns
        WHERE townChapter <= ?
        AND townId = ?');
        $townQuery->execute([$characterChapter, $townId]);
        $townRow = $townQuery->rowCount();
        $townQuery->closeCursor();

        //Si la ville est disponible pour le joueur il y entre
        if ($townRow >= 1) 
        {
            //On met le personnage à jour
            $updatecharacter = $bdd->prepare("UPDATE car_characters SET
            characterTownId = :characterTownId
            WHERE characterId= :characterId");

            $updatecharacter->execute(array(
            'characterTownId' => $townId, 
            'characterId' => $characterId));
            $updatecharacter->closeCursor();

            header("Location: ../../modules/town/index.php");
        }
        //Si la ville n'est pas disponible pour le joueur on le prévient
        else
        {
            echo "La ville choisie est invalide";
        }
    }
    //Si la ville choisit n'est pas un nombre
    else
    {
         echo "La ville choisit est invalide";
    }
}
//Si tous les champs n'ont pas été rmepli
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>