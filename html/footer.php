            </div>
        </div>
        <?php
            //On récupère une seconde fois le temps Unix actuel
            $timeEnd = microtime(true);
            //On soustrait le temps Unix obtenu dans le header pour le soustraire avec celui eu juste au dessus pour obtenir le temps d'execution de la page'
            $time = $timeEnd - $timeStart;
            //On formate le résultat en secondes
            $pageLoadTime = number_format($time, 3);
            //On affiche le résultat en bas de la page
            echo "<center>Temps d'exécution de la page: {$pageLoadTime} seconds</center>";
        ?>
        <script src="../../js/jquery-3.2.1.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
    </body>
</html>

<?php
//On ferme la connexion à la base de donnée
$bdd = null;
?>