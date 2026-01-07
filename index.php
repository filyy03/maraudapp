<?php
/**
 * ========================================
 * PAGE D'ACCUEIL - Maraud'App
 * ========================================
 * Présentation de la plateforme et des 3 actions principales :
 * - Faire un don (vêtements, nourriture, hygiène)
 * - Devenir bénévole (inscription aux maraudes)
 * - Partager l'application (réseaux sociaux)
 * ========================================
 */

// Récupère le nom du fichier actuel pour la navbar active
$page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Maraud'App</title>
<link rel="icon" type="image/image/svg+xml" href="includes/images/favicon.svg">

<!-- Styles CSS -->
<link rel="stylesheet" href="includes/css/header.css">
<link rel="stylesheet" href="includes/css/footer.css">
<link rel="stylesheet" href="includes/css/accueil.css">

<!-- Icônes Boxicons -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  
<?php include 'includes/header.php'; ?>

<main>
  
  <!-- ===========================
       SECTION INTRODUCTION
       =========================== -->
  <section class="intro">
    <h1>Bienvenue sur Maraud'App</h1>
    <p>
      Maraud'App est une plateforme qui facilite la solidarité et l'entraide autour des maraudes en France. Notre objectif est simple : mettre en relation les personnes souhaitant aider avec celles qui en ont le plus besoin.
      Que vous soyez un donateur, un bénévole ou un partenaire, vous pouvez agir rapidement et efficacement :
    </p>
  </section>


    <!-- ===========================
       SECTION MARAUDES
       Layout: Image gauche | Texte + Bouton partage droite
       =========================== -->
  <section class="hero-maraude">
    <div class="hero-text">
      <h2>Les prochaines maraudes</h2>
      <p>
      Découvrez les dates des prochaines maraudes et rejoignez nos bénévoles pour faire la différence.
      </p>
      <a href="maraude.php" class="btn-don">Découvrir</a>
    </div>
    <div class="hero-image">
      <img src="includes/images/maraude.webp" alt="Date maraudes">
    </div>
  </section>

  <!-- ===========================
       SECTION DON
       Layout: Image gauche | Texte droite
       =========================== -->
  <section class="hero-don">
    <div class="hero-image">
      <img src="includes/images/don.webp" alt="Faire un don">
    </div>
    <div class="hero-text">
      <h2>Faire un don</h2>
      <p>
        Vêtements chauds, couvertures, nourriture, articles d'hygiène… Chaque don compte et aide directement les personnes en difficulté.
      </p>
      <a href="don.php" class="btn-don">Faire un don maintenant</a>
    </div>
  </section>

  <!-- ===========================
       SECTION BÉNÉVOLAT
       Layout: Texte gauche | Image droite (inversé)
       =========================== -->
  <section class="hero-benevole">
    <div class="hero-text">
      <h2>Devenir bénévole</h2>
      <p>
        Inscrivez-vous pour participer aux maraudes et apporter votre aide sur le terrain. Vous serez recontacté pour planifier votre participation.
      </p>
      <a href="benevole.php" class="btn-don">S'inscrire maintenant</a>
    </div>
    <div class="hero-image">
      <img src="includes/images/benevole.webp" alt="Devenir bénévole">
    </div>
  </section>

  <!-- ===========================
       SECTION PARTAGE
       Layout: Image gauche | Texte + Bouton partage droite
       =========================== -->
  <section class="hero-partage">
    <div class="hero-image">
      <img src="includes/images/partage.webp" alt="Soutenir notre action">
    </div>
    <div class="hero-text">
      <h2>Soutenir notre action</h2>
      <p>
        Même si vous ne pouvez pas donner ou participer sur le terrain, vous pouvez contribuer en parlant de Maraud'App autour de vous. Partagez nos actions sur les réseaux sociaux, invitez vos amis à rejoindre la plateforme et aidez-nous à toucher le plus de personnes possible. Chaque geste compte pour faire grandir la solidarité.
      </p>

      <!-- Bouton de partage avec Web Share API -->
      <a href="#" class="btn-don" id="btn-share">Partager Maraud'App</a>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>

<!-- ===========================
     SCRIPT DE PARTAGE
     Utilise Web Share API (natif) ou fallback
     =========================== -->
<script>
// Sélection du bouton de partage
const btn = document.getElementById('btn-share');

// Événement au clic
btn.addEventListener('click', (event) => {
  event.preventDefault(); // Empêche le scroll en haut de page
  
  // Vérifier si le navigateur supporte l'API de partage natif
  if (navigator.share) {
    // Partage natif (mobile et navigateurs modernes)
    navigator.share({
      title: 'Maraud'App',
      text: 'Partagez Maraud'App avec vos amis !',
      url: window.location.href
    })
    .then(() => console.log('Partagé avec succès !'))
    .catch((error) => console.log('Erreur partage :', error));
  } else {
    // Fallback pour navigateurs non compatibles
    alert('Votre navigateur ne supporte pas le partage automatique. Copiez le lien manuellement : ' + window.location.href);
  }
});
</script>

</body>
</html>