<?php

$mongoClient = new MongoDB\Driver\Manager("mongodb://192.168.2.110:27017");

$dbName = "ecoride_nosql";
$collectionName = "avis";

$query = new MongoDB\Driver\Query([]);
$cursor = $mongoClient->executeQuery("$dbName.$collectionName", $query);

foreach ($cursor as $document) {
    print_r($document);
}
?>