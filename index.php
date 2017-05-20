<?php
$config = 'kernel/config.php';
$size = filesize($config);
if ($size == 0) 
{
    header("Location: kernel/install.php");
}
header("Location: modules/main/index.php");?>