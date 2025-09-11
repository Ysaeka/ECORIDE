<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/bdd.php';

    if(isset($_SESSION['users_id']) AND $_SESSION['users_id'] > 0){
        $getId = intval($_SESSION['users_id']);
        
        $reqUser = $bdd -> prepare('SELECT * FROM users WHERE users_id = ?');
        $reqUser-> execute([$getId]);
        $userInfo = $reqUser -> fetch();

        $recupVoiture = $bdd->prepare('SELECT v.*, m.libelle AS marque FROM voiture v LEFT JOIN marque m ON v.marque_id = m.marque_id WHERE v.users_id = ?');
        $recupVoiture->execute([$getId]);
        $voitures = $recupVoiture->fetchAll();

            if(!$userInfo){
                $_SESSION['redirection'] = $_SERVER['REQUEST_URI'];
                session_destroy();
                header('Location: index.php?page=connexion');
                exit();
            }
            
    } else {
        $_SESSION['redirection'] = $_SERVER['REQUEST_URI'];
        header('Location: index.php?page=connexion');
        exit();
    }
?>