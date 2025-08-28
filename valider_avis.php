<?php
require_once 'mongo.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    updateAvisStatut($id, 'validé');
    header("Location: acceuil_employe.php?success=1");
    exit();
}else{
    die("ID non trouvé ");
}