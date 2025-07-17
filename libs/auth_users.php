<?php
session_start();

require_once "libs/bdd.php";

if(isset($_SESSION['users_id']) AND $_SESSION['users_id'] > 0){
    $getId = intval($_SESSION['users_id']);
    $reqUser = $bdd -> prepare('SELECT * FROM users WHERE users_id = ?');
    $reqUser-> execute([$getId]);
    $userInfo = $reqUser -> fetch();

        if(!$userInfo){
            session_destroy();
            $_SESSION['redirection'] = $_SERVER['REQUEST_URI'];
            header('Location: connexion.php');
            exit();
        }

} else {
    $_SESSION['redirection'] = $_SERVER['REQUEST_URI'];
    header('Location; connexion.php');
    exit();
}
?>