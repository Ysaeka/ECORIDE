<?php 
  require_once __DIR__ . '/../libs/bdd.php';

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecoride</title>
    <link rel="stylesheet" href="asset/CSS/style.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&family=Happy+Monkey&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/02b9ec3203.js" crossorigin="anonymous"></script>
    <script src="asset/JS/btn_login.js"></script>
</head>

<header>
  <nav class="navbar">
    <div class="logo">
      <a href="index.php">
        <img src="asset/images/Logo ECORIDE.png"> 
      </a>
    </div>
      
    <div class="link">
      <a href="index.php?page=covoiturages"> Covoiturage |</a>
      <a href="index.php?page=proposer_trajet"> Proposer un trajet |</a>
      <a href="index.php?page=contact"> Contact</a>

      <div class="dropdown">
        <button class="avatar-btn" onclick="toggleMenu()">
          <img src="<?= htmlspecialchars($_SESSION['photo'] ?? 'asset/images/icon-park-solid--people.png') ?>" 
               alt="avatar" 
               style="width:50px; height:50px; border-radius:50%; object-fit:cover;" />
          <span class="arrow">&#709;</span> 
        </button>

        <div class="menu" id="dropdownMenu">
          <?php if (isset($_SESSION['users_id'])): ?>
            <a href="index.php?page=profil">Mon profil</a>

            <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] === 2): ?> 
              <a href="index.php?page=admin">Espace administrateur</a>
            <?php endif; ?>

            <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] === 3): ?> 
              <a href="index.php?page=employe">Espace Employé</a>
            <?php endif; ?>

            <a href="index.php?page=deconnexion">Deconnexion</a>
          <?php else: ?>
            <a href="index.php?page=inscription">Inscription</a>
            <a href="index.php?page=connexion">Connexion</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
</header>

