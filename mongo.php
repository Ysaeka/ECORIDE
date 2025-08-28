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

function getAllAvis() : array {
    global $mongoClient;

    $query = new MongoDB\Driver\Query([], ['sort' => ['date_creation' => -1]]);
    $cursor = $mongoClient->executeQuery('ecoride_nosql.avis', $query);

    $results = [];
    foreach ($cursor as $document) {
        $doc = (array) $document;

        if(isset($doc['_id'])) {
            $doc['_id'] = (string) $doc['_id'];
        }

        if (isset($doc['date_creation']) && $doc['date_creation'] instanceof MongoDB\BSON\UTCDateTime) {
            $doc['date_creation'] = $doc['date_creation']->toDateTime()->format('Y-m-d H:i:s');
        }

        if (isset($doc['infos_sql'])) {
            $doc['conducteur_nom'] = $doc['infos_sql']->conducteur_nom ?? '';
            $doc['passager_nom'] = $doc['infos_sql']->passager_nom ?? '';
        }

        if (!isset($doc['conducteur_nom'])) {
            $doc['conducteur_nom'] = $doc['reviewed_user_id'] ?? '';
        }
        if (!isset($doc['passager_nom'])) {
            $doc['passager_nom'] = $doc['reviewer_id'] ?? '';
        }

        $results[] = $doc;
    }
    return $results;
}

function updateAvisStatut(string $id, string $status) {
    global $mongoClient;

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$set' => ['statut' => $statut]]
    );

    $mongoClient->executeBulkWrite('ecoride_nosql.avis', $bulk);
}

?>


