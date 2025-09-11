<?php

try
{
    $bdd = new PDO($dsn, $user, $password);
}
catch (Exception $e)
{
    die('Connection echouée' . $e->getMessage());
}
?>
