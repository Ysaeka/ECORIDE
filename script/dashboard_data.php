<?php 
require_once '../libs/bdd.php';
header('Content-Type: application/json');

try {
    $totalUsers = $bdd->query("SELECT COUNT(*) FROM users")->fetchColumn();

    $totalCovoit = $bdd->query("SELECT COUNT(*) FROM covoiturage WHERE statut = 'terminé'")->fetchColumn();

    $revenusTotal = $bdd->query("SELECT COUNT(*) * 2 AS revenu_total FROM reservation r JOIN covoiturage c ON r.covoiturage_id = c.covoiturage_id WHERE c.statut = 'terminé'")->fetchColumn();

    $covoitJour = $bdd->query("SELECT DATE(date_depart) AS jour, COUNT(*) AS nombre FROM covoiturage GROUP By jour ORDER by jour")->fetchAll(PDO::FETCH_ASSOC);

    $revenusJour = $bdd->query("SELECT DATE(r.reservation_date) AS jour, COUNT(*) * 2 AS revenu FROM reservation r JOIN covoiturage c ON r.covoiturage_id = c.covoiturage_id WHERE c.statut = 'terminé' GROUP BY jour ORDER BY jour")->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'total_users' => $totalUsers,
        'total_covoiturages' => $totalCovoit,
        'revenu_total' => $revenusTotal,
        'covoits_jour' => $covoitJour,
        'revenus_jour' => $revenusJour
    ]);   
} catch (Exception $e) {
    echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
    http_response_code(500);
}
?>