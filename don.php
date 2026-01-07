<?php
/**
 * ========================================
 * PAGE DON - Formulaire de don
 * ========================================
 * Permet aux utilisateurs de proposer des dons :
 * - Vêtements, couvertures, nourriture, hygiène, accessoires
 * - Formulaire avec sélection dynamique des types de produits
 * - Validation et insertion en base de données
 * ========================================
 */

// Démarrer la session (nécessaire pour $_SESSION)
session_start();

// Forcer l'encodage UTF-8
header('Content-Type: text/html; charset=utf-8');

// Récupère le nom de la page pour la navbar active
$page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Faire un don - Maraud'App</title>
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

<?php
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
    $nom = trim($_POST['nom_donateur']);
    $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';
    $categorie = trim($_POST['categorie']);
    $type = isset($_POST['type_article']) ? trim($_POST['type_article']) : '';
    $quantite = (int)$_POST['quantite'];
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Préparation de la requête SQL (protection injection SQL)
    $sql = "INSERT INTO dons (nom_donateur, telephone, categorie, type_don, quantite, message) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    
    // Liaison des paramètres
    mysqli_stmt_bind_param($stmt, "ssssis", $nom, $telephone, $categorie, $type, $quantite, $message);
    
    // Exécution et gestion des erreurs
    if (mysqli_stmt_execute($stmt)) {
        $success = "Merci pour votre don ! Notre équipe étudiera votre proposition.";
    } else {
        $error = "Erreur lors de l'envoi : " . mysqli_error($link);
    }
    
    // Fermeture du statement
    mysqli_stmt_close($stmt);
}
?>

<main>
  
  <!-- ===========================
       SECTION INTRODUCTION
       =========================== -->
  <section class="intro">
    <h1>Faire un don</h1>
    <p>
      <span>Vous souhaitez soutenir notre association ?</span> Vous pouvez contribuer en faisant un don de vêtements, produits d'hygiène, nourriture ou tout autre article utile. Remplissez le formulaire ci-dessous pour nous proposer votre don. Une fois votre proposition envoyée, notre équipe étudiera vos besoins et décidera si le don peut être accepté en fonction des priorités et des besoins de nos bénéficiaires. Chaque don compte et aide directement ceux qui en ont le plus besoin. Merci pour votre générosité !
    </p>
  </section>

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
       FORMULAIRE DE DON
       =========================== -->
  <div class="form-container">
    <h2>Remplir le formulaire</h2>

    <form action="don.php" method="post">
        
        <!-- Nom du donateur -->
        <div class="form-group">
            <label for="nom_donateur">Nom</label>
            <input type="text" id="nom_donateur" name="nom_donateur" placeholder="Votre nom">
        </div>

        <!-- Téléphone (obligatoire) -->
        <div class="form-group">
            <label for="telephone">Téléphone<span class="required">*</span></label>
            <input type="tel" id="telephone" name="telephone" placeholder="06XXXXXXXX" required>
        </div>

        <!-- Catégorie avec datalist (obligatoire) -->
        <div class="form-group">
            <label for="categorie">Catégorie<span class="required">*</span></label>
            <input 
                type="text" 
                id="categorie" 
                name="categorie" 
                list="categories" 
                required 
                placeholder="Sélectionnez ou tapez une catégorie"
                <?php echo (isset($_SESSION['admin_role']) && $_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
            >
            <datalist id="categories">
                <option value="Vêtements">
                <option value="Couvertures">
                <option value="Nourriture">
                <option value="Hygiène">
                <option value="Accessoires">
            </datalist>
        </div>

        <!-- Type de produit (dépend de la catégorie) -->
        <div class="form-group">
            <label for="type_article">Type de produit<span class="required">*</span></label>
            <input 
                type="text" 
                id="type_article" 
                name="type_article" 
                list="types" 
                required 
                placeholder="Sélectionnez ou tapez un type"
                <?php echo (isset($_SESSION['admin_role']) && $_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
            >
            <datalist id="types"></datalist>
        </div>

        <!-- Quantité -->
        <div class="form-group">
            <label for="quantite">Quantité<span class="required">*</span></label>
            <input 
                type="number" 
                id="quantite" 
                name="quantite" 
                min="1" 
                max="1000" 
                required 
                placeholder="Ex: 10"
                <?php echo (isset($_SESSION['admin_role']) && $_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
            >
        </div>

        <!-- Message facultatif -->
        <div class="form-group">
            <label for="message">Message (facultatif)</label>
            <textarea id="message" name="message" placeholder="Message supplémentaire..."></textarea>
        </div>

        <!-- Bouton d'envoi -->
        <div class="form-actions">
            <button 
                type="submit" 
                class="btn btn-primary"
                <?php echo (isset($_SESSION['admin_role']) && $_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
            >
                Envoyer
            </button>
        </div>

    </form>
  </div>

</main>

<!-- ===========================
     SCRIPT - SÉLECTION DYNAMIQUE
     Met à jour les types de produits selon la catégorie
     =========================== -->
<script>
// Produits disponibles par catégorie
const productsByCategory = {
    "Vêtements": ["Doudoune Homme","Pull Homme","Pantalon Homme","Doudoune Femme","Pull Femme","Pantalon Femme","Vêtements Enfants"],
    "Couvertures": ["Couverture","Couverture de survie","Plaid"],
    "Nourriture": ["Conserves","Repas","Barres de céréales","Eau","Fruits"],
    "Hygiène": ["Savon","Dentifrice","Brosse à dents","Protections hygièniques", "Couches"],
    "Accessoires": ["Gants","Bonnet","Écharpe"]
};

// Sélection des éléments du formulaire
const categorySelect = document.getElementById('categorie');
const typeInput = document.getElementById('type_article');
const typeDatalist = document.getElementById('types');

// Écouter les changements de catégorie
categorySelect.addEventListener('input', () => {
    const selectedCategory = categorySelect.value;
    const products = productsByCategory[selectedCategory] || [];

    // Vider la liste des types
    typeDatalist.innerHTML = '';
    
    // Ajouter les nouveaux types selon la catégorie
    products.forEach(prod => {
        const option = document.createElement('option');
        option.value = prod;
        typeDatalist.appendChild(option);
    });

    // Réinitialiser le champ type
    typeInput.value = '';
});
</script>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

</body>
</html>