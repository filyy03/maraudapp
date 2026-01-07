<?php
/**
 * ========================================
 * HEADER PRINCIPAL - Maraud'App
 * ========================================
 * Navigation principale pour les pages publiques
 * Gère l'état actif selon la page courante
 * Menu responsive avec hamburger mobile
 * ========================================
 */

// Définir la variable $page pour savoir quelle page est active
// Si $page n'est pas définie, la récupérer automatiquement
$page = $page ?? basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar">
  
  <!-- Logo cliquable -->
  <div class="logo">
    <a href="index.php">
      <img src="includes/images/logo1.png" alt="Logo Maraud'App">
    </a>
  </div>

  <!-- Bouton hamburger pour mobile -->
  <div class="menu-toggle">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <!-- Liens de navigation -->
  <ul class="nav-links">
    <li>
      <a href="index.php" class="<?= ($page == 'index.php') ? 'active' : '' ?>">
        Accueil
      </a>
    </li>
    <li>
      <a href="maraude.php" class="<?= ($page == 'maraude.php') ? 'active' : '' ?>">
        Maraudes
      </a>
    </li>
    <li>
      <a href="don.php" class="<?= ($page == 'don.php') ? 'active' : '' ?>">
        Faire un don
      </a>
    </li>
    <li>
      <a href="benevole.php" class="<?= ($page == 'benevole.php') ? 'active' : '' ?>">
        Devenir bénévole
      </a>
    </li>
    <li>
      <a href="login.php" class="<?= ($page == 'login.php') ? 'active' : '' ?>">
        Espace admin
      </a>
    </li>
    <li>
      <a href="contact.php" class="<?= ($page == 'contact.php') ? 'active' : '' ?>">
        Contact
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