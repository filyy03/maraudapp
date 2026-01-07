<?php
/**
 * ========================================
 * TRAITEMENT FORMULAIRE DE CONTACT
 * ========================================
 * Traite les données du formulaire contact.php :
 * - Validation et nettoyage des champs
 * - Envoi d'email
 * - Messages de retour via $_SESSION
 * - Redirection vers contact.php
 * ========================================
 */

// Démarrer la session (indispensable pour les messages)
session_start();

// ========================================
// VÉRIFICATION DE LA MÉTHODE POST
// ========================================
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Si accès direct sans formulaire, rediriger vers l'accueil
    header("Location: index.php");
    exit;
}

// ========================================
// FONCTION DE NETTOYAGE DES DONNÉES
// ========================================
function clean($data) {
    // Supprime les espaces et échappe les caractères spéciaux
    return htmlspecialchars(trim($data));
}

// ========================================
// RÉCUPÉRATION ET NETTOYAGE DES DONNÉES
// ========================================
$prenom  = clean($_POST["prenom"] ?? "");
$nom     = clean($_POST["nom"] ?? "");
$email   = filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL);
$message = clean($_POST["message"] ?? "");

// ========================================
// VALIDATION DES CHAMPS
// ========================================
if (!$prenom || !$nom || !$email || !$message) {
    // Si un champ est vide ou invalide
    $_SESSION['error'] = "Veuillez remplir correctement tous les champs.";
    header("Location: contact.php");
    exit;
}

// ========================================
// PRÉPARATION DE L'EMAIL
// ========================================

// Destinataire
$to = "tfily954@icloud.com";

// Sujet de l'email
$subject = "Nouveau message depuis Maraud'App";

// Corps du message
$body = "Nouveau message reçu via le formulaire de contact :\n\n"
      . "Prénom : $prenom\n"
      . "Nom : $nom\n"
      . "Email : $email\n\n"
      . "Message :\n"
      . "---\n"
      . "$message\n"
      . "---\n\n"
      . "Envoyé depuis Maraud'App";

// En-têtes de l'email
$headers = "From: contact@maraudapp.fr\r\n"
         . "Reply-To: $email\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n"
         . "X-Mailer: PHP/" . phpversion();

// ========================================
// ENVOI DE L'EMAIL
// ========================================
if (mail($to, $subject, $body, $headers)) {
    // Succès
    $_SESSION['success'] = "Votre message a bien été envoyé ! Nous vous répondrons rapidement.";
} else {
    // Échec
    $_SESSION['error'] = "Une erreur est survenue lors de l'envoi. Veuillez réessayer plus tard.";
    
    // Log l'erreur pour debug (optionnel)
    error_log("Erreur envoi mail contact : De $email - Sujet: $subject");
}

// ========================================
// REDIRECTION VERS CONTACT.PHP
// ========================================
header("Location: contact.php");
exit;