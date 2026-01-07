<?php
/**
 * ========================================
 * PAGE BÉNÉVOLAT - Formulaire d'inscription
 * ========================================
 * Permet aux utilisateurs de s'inscrire comme bénévoles :
 * - Nom, email, date de disponibilité
 * - Message facultatif pour préciser la motivation
 * - Insertion en base de données dans la table 'benevoles'
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
include 'includes/config.php';

// Variables pour les messages
$success = '';
$error = '';

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $date_dispo = $_POST['date_dispo'];
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Rôle vide pour l'instant (sera défini par l'admin plus tard)
    $role = '';

    // Préparation de la requête SQL (protection injection SQL)
    $sql = "INSERT INTO benevoles (nom, contact, date_dispo, role, message) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    
    // Liaison des paramètres
    mysqli_stmt_bind_param($stmt, "sssss", $nom, $email, $date_dispo, $role, $message);
    
    // Exécution et gestion des erreurs
    if (mysqli_stmt_execute($stmt)) {
        $success = "Merci ! Nous vous recontacterons bientôt pour la suite.";
    } else {
        $error = "Erreur lors de l'inscription : " . mysqli_error($link);
    }
    
    // Fermeture du statement
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Devenir Bénévole - Maraud'App</title>
<link rel="icon" type="image/image/svg+xml" href="includes/images/favicon.svg">

<!-- Styles CSS -->
<link rel="stylesheet" href="includes/css/header.css">
<link rel="stylesheet" href="includes/css/pages-principales.css">
<link rel="stylesheet" href="includes/css/forms.css">
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
    <h1>Devenir Bénévole</h1>
    <p>
      <span>Vous voulez participer aux maraudes ?</span> Pour venir en aide aux personnes sans-abri, il vous suffit de remplir ce formulaire. Il vous permet de renseigner vos disponibilités et votre motivation, et les organisateurs des maraudes pourront vous contacter pour vous intégrer aux prochaines actions.
    </p>
  </div>

  <!-- ===========================
       MESSAGES DE RETOUR
       =========================== -->
  <?php if (!empty($success)): ?>
    <p style="color: green; font-weight: 600; margin-bottom: 15px; text-align: center;">
        <?php echo htmlspecialchars($success); ?>
    </p>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <p style="color: red; font-weight: 600; margin-bottom: 15px; text-align: center;">
        <?php echo htmlspecialchars($error); ?>
    </p>
  <?php endif; ?>

  <!-- ===========================
       FORMULAIRE D'INSCRIPTION
       =========================== -->
  <div class="form-container">
    <h2>Remplir le formulaire</h2>

    <form action="benevole.php" method="post">
        
        <!-- Nom complet -->
        <div class="form-group">
            <label for="nom">Nom <span class="required">*</span></label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input type="email" id="email" name="email" placeholder="votre.email@exemple.com" required>
        </div>

       <!-- Date de disponibilité parmi celles ouvertes-->
      <div class="form-group">
          <label for="date_dispo">Date de disponibilité</label>
          <select id="date_dispo" name="date_dispo" required>
              <option value="">Sélectionnez une date</option>

              <?php
              // Tableau des mois en français
              $moisFR = [
                  1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril',
                  5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août',
                  9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'
              ];

              $query = "SELECT id, date FROM maraudes ORDER BY date ASC";
              $result = mysqli_query($link, $query);

              if ($result && mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      if (!empty($row['date'])) {
                          $dateObj = new DateTime($row['date']);
                          $jour = $dateObj->format('d');
                          $mois = $moisFR[(int)$dateObj->format('m')];
                          $annee = $dateObj->format('Y');
                          $dateFormatee = "$jour $mois $annee";

                          echo "<option value='{$row['date']}'>{$dateFormatee}</option>";
                      }
                  }
              } else {
                  echo "<option value=''>Aucune maraude disponible</option>";
              }
              ?>
          </select>
      </div>

        <!-- Message facultatif -->
        <div class="form-group">
            <label for="message">Message (facultatif)</label>
            <textarea id="message" name="message" placeholder="Message supplémentaire..."></textarea>
        </div>

        <!-- Bouton d'envoi -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </div>
        
    </form>
  </div>

</main>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

</body>
</html>