<?php
require_once 'mongo.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    var_dump($id);
    
    updateAvisStatut($id, 'validé');
    header("Location: acceuil_employe.php?success=1");
    exit();
}else{
    die("ID non trouvé ");
}