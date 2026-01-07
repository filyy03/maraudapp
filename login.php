<?php
/**
 * ========================================
 * PAGE DE CONNEXION ADMIN - Maraud'App
 * ========================================
 * Authentification des administrateurs :
 * - Vérification email/mot de passe
 * - Gestion de deux types de comptes (admin et demo)
 * - Redirection vers le dashboard en cas de succès
 * - Messages d'erreur en cas d'échec
 * ========================================
 */

// Démarrer la session
session_start();

// Connexion à la base de données
include 'includes/config.php';

// Variable pour les messages d'erreur
$error = '';

// ========================================
// TRAITEMENT DU FORMULAIRE
// ========================================
if (isset($_POST['submit'])) {
    // Récupération et nettoyage des données
    $email = trim($_POST['email']);
    $motdepasse = $_POST['password'];

    // Vérifier que les champs ne sont pas vides
    if (!empty($email) && !empty($motdepasse)) {
        
        // Rechercher l'utilisateur par email
        $sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            // ===== CAS 1 : Admin réel avec mot de passe hashé =====
            if ($user['role'] === 'admin' && password_verify($motdepasse, $user['password'])) {
                // Authentification réussie
                $_SESSION['admin_logged'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_role'] = $user['role'];
                
                // Redirection vers le dashboard
                header("Location: admindashboard.php");
                exit;
            }
            // ===== CAS 2 : Compte demo (mot de passe en clair) =====
            elseif ($user['role'] === 'demo' && $motdepasse === $user['password']) {
                // Authentification demo réussie
                $_SESSION['admin_logged'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_role'] = $user['role'];
                
                // Redirection vers le dashboard
                header("Location: admindashboard.php");
                exit;
            }
            // ===== CAS 3 : Mot de passe incorrect =====
            else {
                $error = "Mot de passe incorrect.";
            }
        } 
        // ===== CAS 4 : Email non trouvé =====
        else {
            $error = "Email incorrect.";
        }

        // Fermer le statement
        mysqli_stmt_close($stmt);
    } 
    // ===== CAS 5 : Champs vides =====
    else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Maraud'App</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="includes/images/favicon.svg">
    
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
        <h1>Espace Admin</h1>
        <p>
            Entrez vos identifiants pour accéder à votre tableau de bord et gérer 
            les dons, bénévoles et stocks. Seuls les administrateurs autorisés peuvent se connecter.
        </p>
    </div>
    
    <!-- ===========================
         FORMULAIRE DE CONNEXION
         =========================== -->
    <div class="form-container">
        <h2>Connexion</h2>

        <form method="post" action="" class="login-form">
            
            <!-- Champ Email -->
            <div class="form-group">
                <label for="email">Email<span class="required">*</span></label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    autocomplete="email"
                    placeholder="admin@maraudapp.fr"
                >
            </div>

            <!-- Champ Mot de passe -->
            <div class="form-group">
                <label for="password">Mot de passe<span class="required">*</span></label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                >
            </div>

            <!-- Message d'erreur -->
            <?php if (!empty($error)): ?>
                <p style="color: red; font-weight: 600; margin-bottom: 15px; text-align: center;">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php endif; ?>

            <!-- Bouton de soumission -->
            <div class="form-actions">
                <button type="submit" name="submit" class="btn btn-primary btn-block">
                    Se connecter
                </button>
            </div>
            
        </form>
    </div>

</main>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

</body>
</html>