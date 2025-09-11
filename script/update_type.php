<?php
require_once __DIR__ . '/../libs/bdd.php';
require_once __DIR__ . '/../libs/auth_users.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$users_id = $_SESSION['users_id'] ?? null;
if (!$users_id) {
    http_response_code(403);
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

$userType = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);

if (!in_array($userType, ['chauffeur', 'passager', 'les_deux'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Type invalide.']);
    exit;
}

try {
    $updateType = $bdd->prepare("UPDATE users SET user_type = :user_type WHERE users_id = :users_id");
    $updateType->bindParam(':user_type', $userType);
    $updateType->bindParam(':users_id', $users_id, PDO::PARAM_INT);
    $updateType->execute();

    $_SESSION['user_type'] = $userType;

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500); 
    echo json_encode(['error' => 'Échec de la mise à jour']);
}
?>
