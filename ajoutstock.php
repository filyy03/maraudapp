<?php
/**
 * ========================================
 * PAGE AJOUT STOCK - Admin
 * ========================================
 * Permet aux administrateurs d'ajouter des articles au stock :
 * - Vérification connexion admin
 * - Protection mode demo (lecture seule)
 * - Formulaire avec catégories et types dynamiques
 * - Insertion en base de données
 * ========================================
 */

// Démarrer la session
session_start();

// Connexion à la base de données
include 'includes/config.php';

// ========================================
// VÉRIFICATION CONNEXION ADMIN
// ========================================
if (!isset($_SESSION['admin_logged'])) {
    // Si non connecté, rediriger vers login
    header("Location: login.php");
    exit;
}

// Variables pour les messages
$message = '';
$message_type = '';

// ========================================
// MODE DEMO - LECTURE SEULE
// ========================================
if ($_SESSION['admin_role'] === 'demo') {
    $message = "⚠️ Mode démo : vous ne pouvez pas ajouter de stock.";
    $message_type = 'info';
}

// ========================================
// TRAITEMENT DU FORMULAIRE
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['admin_role'] !== 'demo') {
    
    // Récupération et nettoyage des données
    $categorie = isset($_POST['categorie']) ? trim($_POST['categorie']) : '';
    $type_article = isset($_POST['type_article']) ? trim($_POST['type_article']) : '';
    $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 0;
    $taille = isset($_POST['taille']) ? trim($_POST['taille']) : '';
    $etat = isset($_POST['etat']) ? trim($_POST['etat']) : 'Bon';
    $priorite = isset($_POST['priorite']) ? trim($_POST['priorite']) : 'ok';
    $localisation = isset($_POST['localisation']) ? trim($_POST['localisation']) : '';
    $note = isset($_POST['note']) ? trim($_POST['note']) : '';

    // Validation des champs obligatoires
    if (!empty($categorie) && !empty($type_article) && $quantite > 0) {
        
        // Préparation de la requête SQL
        $sql = "INSERT INTO stocks (categorie, type_article, taille, quantite, etat, priorite, localisation, note) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        
        // Liaison des paramètres
        mysqli_stmt_bind_param($stmt, "sssissss", $categorie, $type_article, $taille, $quantite, $etat, $priorite, $localisation, $note);

        // Exécution et gestion du résultat
        if (mysqli_stmt_execute($stmt)) {
            $message = "✓ Stock ajouté avec succès !";
            $message_type = 'success';
        } else {
            $message = "✗ Erreur lors de l'ajout du stock : " . mysqli_error($link);
            $message_type = 'error';
        }
        
        // Fermeture du statement
        mysqli_stmt_close($stmt);
    } else {
        // Champs obligatoires manquants
        $message = "✗ Veuillez remplir tous les champs obligatoires.";
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajout Stock - Maraud'App</title>

<!-- Favicon -->
<link rel="icon" type="image/image/svg+xml" href="includes/images/favicon.svg">

<!-- Styles CSS -->
<link rel="stylesheet" href="includes/css/header.css">
<link rel="stylesheet" href="includes/css/ajoutstock.css">
<link rel="stylesheet" href="includes/css/footer.css">

<!-- Icônes Boxicons -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

<?php include 'includes/headeradmin.php'; ?>

<!-- ===========================
     MESSAGE DE RETOUR
     =========================== -->
<?php if ($message): ?>
    <div class="message <?php echo $message_type; ?>" style="text-align:center; margin:20px 0; padding:15px; border-radius:8px;">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<main>

    <!-- ===========================
         SECTION INTRODUCTION
         =========================== -->
    <div class="intro">
        <h1>Ajouter du stock</h1>
        <p>
            Ce formulaire permet d'enregistrer les dons disponibles dans l'inventaire de Maraud'App.
            Les informations saisies ici seront utilisées pour organiser la distribution, prioriser les besoins
            et assurer un suivi clair des stocks.
        </p>
    </div>

    <!-- ===========================
         FORMULAIRE D'AJOUT STOCK
         =========================== -->
    <div class="form-container">
        <h1>Remplir le formulaire</h1>

        <form action="ajoutstock.php" method="post" class="stock-form">
            
            <!-- Catégorie (obligatoire) -->
            <div class="form-group">
                <label for="categorie">Catégorie<span class="required">*</span></label>
                <input 
                    type="text" 
                    id="categorie" 
                    name="categorie" 
                    list="categories" 
                    required 
                    placeholder="Sélectionnez ou tapez une catégorie"
                    <?php echo ($_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
                >
                <datalist id="categories">
                    <option value="Vêtements">
                    <option value="Couvertures">
                    <option value="Nourriture">
                    <option value="Hygiène">
                    <option value="Accessoires">
                </datalist>
            </div>

            <!-- Type de produit (obligatoire, dépend de la catégorie) -->
            <div class="form-group">
                <label for="type_article">Type de produit<span class="required">*</span></label>
                <input 
                    type="text" 
                    id="type_article" 
                    name="type_article" 
                    list="types" 
                    required 
                    placeholder="Sélectionnez ou tapez un type"
                    <?php echo ($_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
                >
                <datalist id="types"></datalist>
            </div>

            <!-- Quantité (obligatoire) -->
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
                    <?php echo ($_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
                >
            </div>

            <!-- Taille (facultatif) -->
            <div class="form-group">
                <label for="taille">Taille</label>
                <input 
                    type="text" 
                    id="taille" 
                    name="taille" 
                    placeholder="Ex: M, L, XL"
                    <?php echo ($_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
                >
            </div>

            <!-- État (facultatif) -->
            <div class="form-group">
                <label for="etat">État</label>
                <select 
                    id="etat" 
                    name="etat"
                    <?php echo ($_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
                >
                    <option value="Bon" selected>Bon</option>
                    <option value="Moyen">Moyen</option>
                    <option value="Usé">Usé</option>
                </select>
            </div>

            <!-- Priorité (facultatif) -->
            <div class="form-group">
                <label for="priorite">Priorité</label>
                <select 
                    id="priorite" 
                    name="priorite"
                    <?php echo ($_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
                >
                    <option value="urgent">Urgent</option>
                    <option value="important">Important</option>
                    <option value="ok" selected>Ok</option>
                </select>
            </div>

            <!-- Localisation (facultatif) -->
            <div class="form-group">
                <label for="localisation">Localisation</label>
                <input 
                    type="text" 
                    id="localisation" 
                    name="localisation" 
                    placeholder="Ex: Entrepôt A, Étagère 3"
                    <?php echo ($_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
                >
            </div>

            <!-- Note/Commentaires (facultatif) -->
            <div class="form-group">
                <label for="note">Commentaires</label>
                <textarea 
                    id="note" 
                    name="note" 
                    rows="4" 
                    placeholder="Notes additionnelles sur cet article..."
                    <?php echo ($_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
                ></textarea>
            </div>

            <!-- Boutons d'action -->
            <div class="form-actions">
                <button 
                    type="submit" 
                    class="btn btn-primary"
                    <?php echo ($_SESSION['admin_role']==='demo') ? 'disabled' : ''; ?>
                >
                    Ajouter au stock
                </button>
                <a href="admindashboard.php" class="btn btn-secondary">Retour</a>
            </div>

        </form>
    </div>

</main>

<!-- ===========================
     SCRIPT - SÉLECTION DYNAMIQUE
     Met à jour les types selon la catégorie
     =========================== -->
<script>
// Produits disponibles par catégorie
const productsByCategory = {
    "Vêtements": ["Doudoune Homme","Pull Homme","Pantalon Homme","Doudoune Femme","Pull Femme","Pantalon Femme","Vêtements Enfants"],
    "Couvertures": ["Couverture","Couverture de survie","Plaid"],
    "Nourriture": ["Conserves","Repas","Barres de céréales","Eau","Fruits"],
    "Hygiène": ["Savon","Dentifrice","Brosse à dents","Protections hygiéniques", "Couches"],
    "Accessoires": ["Gants","Bonnet","Écharpe"]
};

// Sélection des éléments
const categorySelect = document.getElementById('categorie');
const typeInput = document.getElementById('type_article');
const typeDatalist = document.getElementById('types');

// Écouter les changements de catégorie
categorySelect.addEventListener('input', () => {
    const selectedCategory = categorySelect.value;
    const products = productsByCategory[selectedCategory] || [];

    // Vider la liste des types
    typeDatalist.innerHTML = '';
    
    // Ajouter les nouveaux types
    products.forEach(prod => {
        const option = document.createElement('option');
        option.value = prod;
        typeDatalist.appendChild(option);
    });

    // Réinitialiser le champ type
    typeInput.value = '';
});
</script>

<!-- Footer admin -->
<?php include 'includes/footeradmin.php'; ?>

</body>
</html>