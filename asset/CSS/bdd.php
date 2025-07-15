<?php

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
} catch (PDOException $e) {
    echo 'Connection echouée';
}

?>