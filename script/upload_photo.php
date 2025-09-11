<?php
session_start();
require_once '../libs/bdd.php';

if (!isset($_SESSION['users_id'])) {
    header('Location: connexion.php');
    exit;
}

$userId = $_SESSION['users_id'];
$maxSize = 2 * 1024 * 1024;
$allowedTypes = ['image/jpeg', 'image/png'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];

    if ($photo['error'] === 0) {
        if ($photo['size'] > $maxSize) {
            die("Fichier trop volumineux. Max 2 Mo.");
        }

        if (!in_array($photo['type'], $allowedTypes)) {
            die("Format non autorisé. JPEG et PNG uniquement.");
        }

        $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
        $newName = 'profile_' . $userId . '.' . $ext;

        $destination = __DIR__ . '/../uploads/profils/' . $newName;

        $dbPath = 'uploads/profils/' . $newName;

        if ($photo['type'] === 'image/jpeg') {
            $srcImage = imagecreatefromjpeg($photo['tmp_name']);
        } else {
            $srcImage = imagecreatefrompng($photo['tmp_name']);
        }

        list($width, $height) = getimagesize($photo['tmp_name']);
        $targetWidth = 150;
        $targetHeight = 150;

        $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($photo['type'] === 'image/png') {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
        }

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, 
            $targetWidth, $targetHeight, $width, $height);

        if ($photo['type'] === 'image/jpeg') {
            imagejpeg($dstImage, $destination, 90);
        } else {
            imagepng($dstImage, $destination);
        }

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        $majPhoto = $bdd->prepare("UPDATE users SET photo = :photo WHERE users_id = :id");
        $majPhoto->execute(['photo' => $dbPath, 'id' => $userId]);

        $_SESSION['photo'] = $dbPath;
    } else {
        error_log("Erreur upload : " . $photo['error']);
    }
}

header('Location: ../index.php?page=profil');
exit;
