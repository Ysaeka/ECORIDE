<?php

$mongoClient = new MongoDB\Driver\Manager($mongoDsn);
$mongoCollection = 'avis';

function insertAvisMongo(array $document) {
    global $mongoClient;

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($document);

    $mongoClient->executeBulkWrite("$mongoDb.$mongoCollection", $bulk);
}

function getAvisMongo(int $covoiturage_id): array {
    global $mongoClient;

    $filtre = [ 'covoiturage_id' => $covoiturage_id ];
    $options = [
        'sort' => [ 'date_creation' => -1 ]
    ];

    $query = new MongoDB\Driver\Query($filtre, $options);
    $cursor = $mongoClient->executeQuery("$mongoDb.$mongoCollection", $query);

    $results = [];
    foreach ($cursor as $document) {
        $results[] = (array) $document;
    }

    return $results;
}

function getAllAvis(): array {
    global $mongoClient;

    $query = new MongoDB\Driver\Query([], ['sort' => ['date_creation' => -1]]);
    $cursor = $mongoClient->executeQuery("$mongoDb.$mongoCollection", $query);

    $results = [];

    foreach ($cursor as $document) {
        $doc = [];
        $doc['_id'] = isset($document->_id) ? (string)$document->_id : '';
        $doc['statut'] = $document->statut ?? 'en attente';

        if (isset($document->date_creation) && $document->date_creation instanceof MongoDB\BSON\UTCDateTime) {
            $doc['date_creation'] = $document->date_creation->toDateTime()->format('Y-m-d H:i:s');
        } else {
            $doc['date_creation'] = '';
        }

        if (isset($document->infos_sql)) {
            $infos = is_object($document->infos_sql) ? (array)$document->infos_sql : (array)$document->infos_sql;

            $doc['conducteur_nom'] = $infos['conducteur_nom'] ?? '';
            $doc['conducteur_email'] = $infos['conducteur_email'] ?? '';
            $doc['passager_nom'] = $infos['passager_nom'] ?? '';
            $doc['passager_email'] = $infos['passager_email'] ?? '';
        } else {
            $doc['conducteur_nom'] = $document->reviewed_user_id ?? '';
            $doc['conducteur_email'] = '';
            $doc['passager_nom'] = $document->reviewer_id ?? '';
            $doc['passager_email'] = '';
        }

        $doc['bien_passe'] = $document->bien_passe ?? '';
        $doc['note'] = $document->note ?? '';
        $doc['commentaire'] = $document->commentaire ?? '';
        $doc['covoiturage_id'] = $document->covoiturage_id ?? '';

        $results[] = $doc;
    }

    return $results;
}

function updateAvisStatut(string $id, string $status) {
    global $mongoClient;

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$set' => ['statut' => $status]]
    );

    $mongoClient->executeBulkWrite("$mongoDb.$mongoCollection", $bulk);
}

function getAvisValide(int $conducteur_id): array {
    global $mongoClient;

    $filtre = [ 
        'reviewed_user_id' => $conducteur_id,
        'statut' => 'validé' 
    ];
    $options = [
        'sort' => [ 'date_creation' => -1 ]
    ];

    $query = new MongoDB\Driver\Query($filtre, $options);
    $cursor = $mongoClient->executeQuery("$mongoDb.$mongoCollection", $query);

    $results = [];
    foreach ($cursor as $document) {
        $doc = (array) $document;

        if (isset($doc['_id'])) $doc['_id'] = (string) $doc['_id'];
        if (isset($doc['date_creation']) && $doc['date_creation'] instanceof MongoDB\BSON\UTCDateTime) {
            $doc['date_creation'] = $doc['date_creation']->toDateTime()->format('Y-m-d H:i:s');
        }

        if (isset($doc['infos_sql']) && is_array($doc['infos_sql'])) {
            $doc['conducteur_nom'] = $doc['infos_sql']['conducteur_nom'] ?? '';
            $doc['passager_nom']   = $doc['infos_sql']['passager_nom'] ?? '';
        } else {
            $doc['conducteur_nom'] = $doc['conducteur_nom'] ?? '';
            $doc['passager_nom']   = $doc['passager_nom'] ?? '';
        }

                
        $results[] = $doc;
    }

    return $results;
}

?>


