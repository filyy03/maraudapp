<?php
/**
 * ========================================
 * DÉCONNEXION ADMIN - Maraud'App
 * ========================================
 * Déconnecte l'administrateur en détruisant la session
 * et redirige vers la page de connexion
 * ========================================
 */

// Démarrer la session pour pouvoir la détruire
session_start();

// Vider toutes les variables de session
session_unset();

// Détruire complètement la session
session_destroy();

// Rediriger vers la page de connexion
header("Location: login.php");
exit;