<?php

$mongoClient = new MongoDB\Driver\Manager("mongodb://root:example@192.168.2.110:27017/");

function insertAvisMongo(array $document) {
    global $mongoClient;

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($document);

    $mongoClient->executeBulkWrite('ecoride_nosql.avis', $bulk);
}

function getAvisMongo(int $covoiturage_id): array {
    global $mongoClient;

    $filtre = [ 'covoiturage_id' => $covoiturage_id ];
    $options = [
        'sort' => [ 'date_creation' => -1 ]
    ];

    $query = new MongoDB\Driver\Query($filtre, $options);
    $cursor = $mongoClient->executeQuery('ecoride_nosql.avis', $query);

    $results = [];
    foreach ($cursor as $document) {
        $results[] = (array) $document;
    }

    return $results;
}
?>


