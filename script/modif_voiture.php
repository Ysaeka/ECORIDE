<?php
require_once "./libs/auth_users.php";
require_once "./libs/bdd.php";

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['voiture_id'];
            $marque = htmlspecialchars($_POST['marque']);
            $modele = htmlspecialchars($_POST['modele']);
            $couleur = htmlspecialchars($_POST['couleur']);
            $energie = htmlspecialchars($_POST['energie']);
            $immatriculation = htmlspecialchars($_POST['immatriculation']);
            $datePlaque= htmlspecialchars($_POST['datePlaque']);

            $stmt = $bdd-> prepare('SELECT marque_id FROM marque WHERE libelle = ?');
            $stmt-> execute([$marque]);
            $marqueRow = $stmt-> fetch(PDO::FETCH_ASSOC);

            if($marqueRow) {
                $marque_id = $marqueRow['marque_id'];
            }else{
                die("Erreur : La marque n'existe pas dans la base de donnée");
            }

            $update = $bdd->prepare('UPDATE voiture SET marque_id= ?, modele = ?, couleur = ?, energie = ?, immatriculation = ?, date_premiere_immatriculation = ? WHERE voiture_id = ? AND users_id = ?');
            $update->execute([$marque_id, $modele, $couleur, $energie, $immatriculation, $datePlaque, $id, $_SESSION['users_id']]);
            
            header("Location: index.php?page=profil");
        }
?>