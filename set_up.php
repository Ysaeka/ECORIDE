<?php
require_once 'config.php'; 

$sqlFile = __DIR__ . '/BDD_ECORIDE.sql';
$mongoCollection = 'avis';
$mongoFile = __DIR__ . '/avis_test.json';

    try {
        $bdd = new PDO("mysql:host=$host;charset=$charset", $user, $password);
        $bdd->exec("CREATE DATABASE `$db`;");
    } catch (\PDOException $e) {
       die('Erreur de connexion à la base');
    }

    if (!file_exists($sqlFile)) die("Erreur : fichier SQL introuvable !");
    
    $sqlContent = file_get_contents($sqlFile);
    $queries = array_filter(array_map('trim', explode(';', $sqlContent)));
    
    try {
        $bdd = new PDO($dsn, $user, $password);
        foreach ($queries as $query) {
            if ($query !== '') $bdd->exec($query.";");
        }
    } catch (\PDOException $e) {
        die("Erreur lors de l'import SQL : " . $e->getMessage());
    }

    unlink($sqlFile);

    function checkMongoDB($mongoDsn, $mongoDb) {
        try {
            $manager = new MongoDB\Driver\Manager($mongoDsn);
            $collections = $manager->executeCommand($mongoDb, new MongoDB\Driver\Command(['listCollections' => 1]));
            $collectionExists = iterator_count($collections->toArray()) > 0;
            return !$collectionExists;
        } catch (MongoDB\Driver\Exception\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    if (checkMongoDB($mongoDsn, $mongoDb)) {
        if (!file_exists($mongoFile)) die("Erreur : fichier JSON Mongo introuvable !");
        
        $avisData = json_decode(file_get_contents($mongoFile), true);
        if ($avisData === null) die("Erreur JSON dans '$mongoFile'");

        $bulk = new MongoDB\Driver\BulkWrite;

        // Test de conversion des dates pour que $date fonctionne 
    foreach ($avisData as &$avis) {
        if (isset($avis['date_creation']['$date'])) {
            $dateString = $avis['date_creation']['$date'];
            $avis['date_creation'] = new MongoDB\BSON\UTCDateTime(strtotime($dateString) * 1000);
        }
        $bulk->insert($avis);
    }

        $manager->executeBulkWrite("$mongoDb.$mongoCollection", $bulk);



        unlink($mongoFile);
    }

    header("Location: index.php");