<?php
/**
 * ========================================
 * PAGE CONTACT - Formulaire de contact
 * ========================================
 * Permet aux utilisateurs de nous contacter :
 * - Formulaire avec nom, prénom, email, message
 * - Messages flash (succès/erreur) via $_SESSION
 * - Traitement dans traitement.php
 * ========================================
 */

// Démarrer la session pour les messages flash
session_start();


// Récupérer les valeurs sauvegardées après soumission
$form_data = $_SESSION['form_data'] ?? [];

// Nettoyer la session pour la prochaine soumission
unset($_SESSION['form_data']);

// Récupérer messages d'erreur / succès
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';

// Supprimer les messages pour ne pas les afficher à nouveau après
unset($_SESSION['success'], $_SESSION['error']);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Maraud'App</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/image/svg+xml" href="includes/images/favicon.svg">
    
    <!-- Styles CSS -->
    <link rel="stylesheet" href="includes/css/header.css">
    <link rel="stylesheet" href="includes/css/contact.css">
    <link rel="stylesheet" href="includes/css/footer.css">
    
    <!-- Icônes Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

<?php include 'includes/header.php'; ?>

<main id="contact">

    <!-- ===========================
         MESSAGES FLASH (succès / erreur)
         =========================== -->
 <div class="message-container">
    <?php if ($success): ?>
        <p class="message success" id="flash-message">
            <?= htmlspecialchars($success) ?>
        </p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="message error" id="flash-message">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>
</div>

    <!-- ===========================
         SECTION INTRODUCTION
         =========================== -->
    <div class="intro">
        <h1>Contact</h1>
        <p>
            Utilisez ce formulaire pour nous envoyer vos questions ou vos remarques. Nous vous répondrons rapidement.
        </p>
    </div>

    <!-- ===========================
     FORMULAIRE DE CONTACT
     Traitement: traitement.php
     =========================== -->
<form action="traitement.php" method="POST">

    <!-- Champ Nom -->
    <div class="input-box">
        <label for="nom">Nom<span class="required">*</span></label>
        <input 
            type="text" 
            id="nom" 
            name="nom" 
            placeholder="Entrez votre nom" 
            required
            value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>"
        >
    </div>

    <!-- Champ Prénom -->
    <div class="input-box">
        <label for="prenom">Prénom<span class="required">*</span></label>
        <input 
            type="text" 
            id="prenom" 
            name="prenom" 
            placeholder="Entrez votre prénom" 
            required
            value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>"
        >
    </div>

    <!-- Champ Email -->
    <div class="input-box">
        <label for="email">Adresse mail<span class="required">*</span></label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            placeholder="votre.email@exemple.com" 
            required
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
        >
    </div>

    <!-- Champ Message -->
    <div class="input-box">
        <label for="message">Message<span class="required">*</span></label>
        <textarea 
            id="message" 
            name="message" 
            placeholder="Entrez votre message" 
            required
        ><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
    </div>
    
    <!-- Bouton d'envoi -->
    <button type="submit" class="bouton">Soumettre</button>

</form>

</main>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Sélectionne tous les messages flash
    const messages = document.querySelectorAll(".message");

    messages.forEach(msg => {
        // Fais disparaître le message après 3 secondes
        setTimeout(() => {
            msg.style.transition = "opacity 0.5s"; // transition douce
            msg.style.opacity = 0; // on rend le message transparent
            setTimeout(() => msg.remove(), 500); // on supprime l'élément du DOM après la transition
        }, 3000);
    });
});
</script>


</body>
</html>