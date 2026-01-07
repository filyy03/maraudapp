<?php
/**
 * ========================================
 * PAGE MARAUDE - Dates
 * ========================================
 * Permet aux utilisateurs de voir les dates des prochaines maraudes :
 * - Dates et informations sur les maraudes
 * - Lien vers 'benevoles'
 * ========================================
 */

// Démarrer la session
session_start();

// Forcer l'encodage UTF-8
header('Content-Type: text/html; charset=utf-8');

// Récupère le nom de la page pour la navbar active
$page = basename($_SERVER['PHP_SELF']);

// ========================================
// TRAITEMENT DU FORMULAIRE
// ========================================

include 'includes/config.php'; // connexion à la base

// On récupère les maraudes triées par date
$sql = "SELECT * FROM maraudes ORDER BY date ASC";
$result = mysqli_query($link, $sql);

$maraudes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $maraudes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maraudes</title>
    <!-- Styles CSS -->
<link rel="stylesheet" href="includes/css/header.css">
<link rel="stylesheet" href="includes/css/pages-principales.css">
<link rel="stylesheet" href="includes/css/maraude.css">
<link rel="stylesheet" href="includes/css/footer.css">

<!-- Icônes Boxicons -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
  <!-- ===========================
       SECTION INTRODUCTION
       =========================== -->
  <div class="intro">
    <h1>Prochaines maraudes</h1>
    <p>
    Découvrez ci-dessous les prochaines maraudes prévues, avec leur date et leur lieu.
    Cliquez sur « Je participe » pour vous engager activement à nos côtés, ou sur « Don » pour soutenir l’action par un don.
    Chaque petit geste compte !
    </p>
  </div>
        
        <div class="maraudes-container">
            <?php if (!empty($maraudes)): ?>
                <ul>
                    <?php 
                    // Tableau des mois en français
                    $mois_fr = [
                        1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril',
                        5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août',
                        9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'
                    ];

                    foreach ($maraudes as $maraude):
                        $date = new DateTime($maraude['date']);
                        $jour = $date->format('d');
                        $mois = $mois_fr[(int)$date->format('m')];
                        $annee = $date->format('Y');
                        $date_formatee = "$jour $mois $annee";
                    ?>
                    <li class="maraude-item">
                        <p><strong>Date :</strong> <?php echo htmlspecialchars($date_formatee); ?></p>
                        <p><strong>Lieu :</strong> <?php echo htmlspecialchars($maraude['lieu']); ?></p>

                        <?php if (!empty($maraude['commentaire'])): ?>
                            <p class="maraude-commentaire"><em><?php echo htmlspecialchars($maraude['commentaire']); ?></em></p>
                        <?php endif; ?>

                        <a href="benevole.php" class="btn btn-primary">Je participe</a>
                        <a href="don.php" class="btn btn-primary">Je fais un don</a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune maraude prévue pour le moment.</p>
            <?php endif; ?>
        </div>
    </main>
<!-- Footer -->
<?php include 'includes/footer.php'; ?>

</body>
</html>
