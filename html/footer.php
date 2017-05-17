            </div>
        </div>
        <?php
            $timeEnd = microtime(true);
            $time = $timeEnd - $timeStart;
            $pageLoadTime = number_format($time, 3);
            echo "<center>Temps d'exécution de la page: {$pageLoadTime} seconds</center>";
        ?>
        <script src="../../js/jquery-3.1.1.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
    </body>
</html>

<?php
$bdd = null;
?>