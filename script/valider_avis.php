<?php
require_once './libs/mongo.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    updateAvisStatut($id, 'validé');
    header("Location: ./index.php?page=employe&success=1");
    exit();
}else{
    die("ID non trouvé ");
}