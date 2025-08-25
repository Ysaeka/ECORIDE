<?php
require_once 'libs/bdd.php';
require_once 'mongo.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $covoiturage_id = $_POST['covoiturage_id'];
    $reviewed_user_id = $_POST['reviewed_user_id'];
    $reviewer_id = $_SESSION['users_id'];
    $statut = $_POST['statut'];
    $note = (int) $_POST['note'];
    $commentaire = $_POST['commentaire'];

    try {
        //Recup données pour MySQL
        $recupAvis = $bdd->prepare("INSERT INTO avis (commentaire, note, statut, reviewer_id, reviewed_user_id, covoiturage_id) VALUES (?, ?, ?, ?, ?, ?)");
        $recupAvis->execute([$commentaire, $note, $statut, $reviewer_id, $reviewed_user_id, $covoiturage_id]);

        $bddInfos = $bdd->prepare(" SELECT u1.first_name AS conducteur_prenom, u1.last_name AS conducteur_nom, u2.first_name AS passager_prenom, u2.last_name AS passager_nom FROM covoiturage c JOIN users u1 ON u1.users_id = c.conducteur_id JOIN users u2 ON u2.users_id = ? WHERE c.covoiturage_id = ?");
        $bddInfos->execute([$reviewer_user_id, $covoiturage_id]);
        $infos = $bddInfos->fetch(PDO::FETCH_ASSOC);

        //Insertion dans Mongo
        $document = [
            'covoiturage_id'   => (int) $covoiturage_id,
            'reviewer_id'      => (int) $reviewer_id,
            'reviewed_user_id' => (int) $reviewed_user_id,
            'statut'           => $statut,
            'note'             => $note,
            'commentaire'      => $commentaire,
            'date_creation'    => new MongoDB\BSON\UTCDateTime(),
            'infos_sql'        => [
                'conducteur_nom'   => $infos['conducteur_nom'] . ' ' . $infos['conducteur_prenom'],
                'passager_nom'     => $infos['passager_nom'] . ' ' . $infos['passager_prenom']
            ]
        ];

        insertAvisMongo($document);

        header("Location: profil.php?success=1");
        exit();

    } catch (Exception $e) {
        error_log("Erreur insertion de l'avis : " . $e->getMessage());
        header("Location: profil.php?error=1");
        exit();
    }
}
?>