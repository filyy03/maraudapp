<?php
/**
 * ========================================
 * HEADER ADMIN - Maraud'App
 * ========================================
 * Navigation pour les pages d'administration
 * Gère l'état actif selon la page courante
 * Liens : Dashboard, Ajout Stock, Retour site, Déconnexion
 * ========================================
 */

// Définir la variable $page pour savoir quelle page est active
// Si $page n'est pas définie, la récupérer automatiquement
$page = $page ?? basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar">
  
  <!-- Logo cliquable -->
  <div class="logo">
    <a href="admindashboard.php">
      <img src="includes/images/logo1.png" alt="Logo Maraud'App">
    </a>
  </div>

  <!-- Bouton hamburger pour mobile -->
  <div class="menu-toggle">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <!-- Liens de navigation admin -->
  <ul class="nav-links">
    <li>
      <a href="admindashboard.php" class="<?= ($page == 'admindashboard.php') ? 'active' : '' ?>">
        Dashboard
      </a>
    </li>
    <li>
      <a href="ajoutstock.php" class="<?= ($page == 'ajoutstock.php') ? 'active' : '' ?>">
        Ajout de Stock
      </a>
    </li>
    <li>
      <a href="index.php" class="<?= ($page == 'index.php') ? 'active' : '' ?>">
        Retour à Maraud'App
      </a>
    </li>
    <li>
      <a href="logout.php">
        Déconnexion
      </a>
    </li>
  </ul>
  
</nav>

<!-- ===========================
     SCRIPT MENU MOBILE
     =========================== -->
<script>
// Toggle du menu hamburger
const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');

if (menuToggle) {
  // Ouvrir/fermer le menu au clic sur le hamburger
  menuToggle.addEventListener('click', () => {
    menuToggle.classList.toggle('active');
    navLinks.classList.toggle('active');
  });

  // Fermer le menu quand on clique sur un lien
  const links = navLinks.querySelectorAll('a');
  links.forEach(link => {
    link.addEventListener('click', () => {
      menuToggle.classList.remove('active');
      navLinks.classList.remove('active');
    });
  });
}
</script>