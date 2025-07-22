<?php
require_once 'libs/auth_users.php';
require_once 'libs/bdd.php';

if(!isset($userInfo['users_id'])) {
    return;
}


$conducteur_id = $userInfo['users_id'];

$req_moyenne_note = $bdd->prepare ("SELECT ROUND(AVG(note), 1) AS moyenne_note FROM avis WHERE reviewed_user_id = ?");
$req_moyenne_note->execute([$conducteur_id]);

$result = $req_moyenne_note->fetch(PDO::FETCH_ASSOC);
$moyenne = $result['moyenne_note'] ?? 0;
?>

<div class="noteContainer">
    <strong> Note : </strong>
    <div class="etoiles">
        <?php 
        $etoilePleine = floor($moyenne);
        $etoileMoitie = ($moyenne - $etoilePleine) >= 0.5;

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $etoilePleine) {
                echo '<span class="etoilePleine">&#9733;</span>';
            } elseif ($i == $etoilePleine + 1 && $etoileMoitie) {
                echo '<span class="etoileMoitie">&#9733;</span>';
            } else {
                echo '<span class="etoileVide">&#9733;</span>';
            }
        }
        ?>
        <span class="score">(<?=$moyenne ?> /5) </span>
    </div>
</div>