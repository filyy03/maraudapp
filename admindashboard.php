<?php
/**
 * ========================================
 * DASHBOARD ADMIN - Maraud'App
 * ========================================
 * Page principale de gestion :
 * - Dons : validation/refus des propositions
 * - Bénévoles : liste des inscriptions
 * - Stocks : gestion de l'inventaire
 * Accessible uniquement aux administrateurs connectés
 * ========================================
 */

// Démarrer la session
session_start();

// ========================================
// VÉRIFICATION AUTHENTIFICATION ADMIN
// ========================================
if (!isset($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

// Récupération du rôle (admin ou demo)
$role = $_SESSION['admin_role'] ?? 'demo';

// Connexion à la base de données
include 'includes/config.php';

// ========================================
// GESTION DES ACTIONS SUR LES DONS
// ========================================
if (isset($_GET['action'], $_GET['id']) && $role !== 'demo') {
    $id = intval($_GET['id']); // Sécurisation de l'ID
    $action = $_GET['action'];
    
    // Définir le statut selon l'action
    $statut = '';
    if ($action === 'valider') {
        $statut = 'valide';
    } elseif ($action === 'refuser') {
        $statut = 'refuse';
    } elseif ($action === 'en_attente') {
        $statut = 'en_attente';
    }
    
    // Si statut valide, exécuter la requête sécurisée
    if (!empty($statut)) {
        $stmt = mysqli_prepare($link, "UPDATE dons SET statut=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "si", $statut, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Redirection pour éviter le rafraîchissement du GET
    header("Location: admindashboard.php");
    exit;
}

// ========================================
// GESTION DES ACTIONS SUR LES STOCKS
// ========================================
if (isset($_GET['action_stock'], $_GET['id']) && $role !== 'demo') {
    $stock_id = intval($_GET['id']); // Sécurisation de l'ID
    $action = $_GET['action_stock'];
    
    if ($action === 'supprimer') {
        // Supprimer le stock (requête sécurisée)
        $stmt = mysqli_prepare($link, "DELETE FROM stocks WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $stock_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    if ($action === 'modifier') {
        // Modifier l'état et la priorité (requête sécurisée)
        $stmt = mysqli_prepare($link, "UPDATE stocks SET etat='Bon', priorite='ok' WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $stock_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Redirection pour éviter le rafraîchissement
    header("Location: admindashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Maraud'App</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/image/svg+xml" href="includes/images/favicon.svg">
    
    <!-- Styles CSS -->
    <link rel="stylesheet" href="includes/css/header.css">
    <link rel="stylesheet" href="includes/css/dashboard.css">
    <link rel="stylesheet" href="includes/css/footer.css">
</head>

<body>

<?php include 'includes/headeradmin.php'; ?>

<!-- ===========================
     BANNIÈRE MODE DÉMO
     =========================== -->
<?php if($_SESSION['admin_role'] === 'demo'): ?>
    <div class="demo-banner" style="background: #fffae6; color: #333; padding: 10px; text-align: center; border: 1px solid #f0e68c; margin-top: 100px">
        ⚠️ Mode démonstration — aucune donnée réelle n'est modifiée
    </div>
<?php endif; ?>

<main>
    <div class="dashboard-container">
        
        <!-- En-tête du dashboard -->
        <h1>Dashboard Admin - Maraud'App</h1>
        <p class="dashboard-intro">
            Bienvenue sur votre tableau de bord. Ici, vous pouvez gérer les dons, les bénévoles et les stocks.
        </p>

<!-- ===========================
     SECTION DONS
     =========================== -->
<section class="dashboard-section">
    <h2>Gestion des Dons</h2>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Catégorie</th>
                    <th>Type</th>
                    <th>Quantité</th>
                    <th>Message</th>
                    <th>Statut</th>
                    <th>Priorité</th>
                    <th>Modifications</th>
                </tr>
            </thead>
            <tbody>

<?php
// Colonnes à afficher
$colonnes = ['id', 'nom_donateur', 'telephone', 'categorie', 'type_don', 'quantite', 'message', 'statut', 'priorite'];

// Récupération de tous les dons
$sql = "SELECT * FROM dons ORDER BY created_at DESC";
$result = mysqli_query($link, $sql);

// Affichage de chaque don
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    
    // Boucle automatique sur toutes les colonnes
    foreach ($colonnes as $col) {
        $valeur = htmlspecialchars($row[$col] ?? '');
        
        // Badge pour statut et priorité
        if ($col === 'statut' || $col === 'priorite') {
            echo "<td><span class='badge badge-{$valeur}'>{$valeur}</span></td>";
        } else {
            echo "<td>{$valeur}</td>";
        }
    }
    
    // Cellule pour les actions
    echo "<td class='actions'><div class='actions-buttons'>";
    
    if ($role === 'demo') {
        // Mode démo : aucun bouton actif
        echo "<button disabled class='btn btn-edit' title='Mode démo — action désactivée'>Modifier</button>";
    } else {
        // Boutons selon le statut
        switch ($row['statut'] ?? '') {
            case 'en_attente':
                echo "<a href='admindashboard.php?action=valider&id={$row['id']}' class='btn btn-valide'>Valider</a> ";
                echo "<a href='admindashboard.php?action=refuser&id={$row['id']}' class='btn btn-refuse'>Refuser</a>";
                break;
            case 'valide':
                echo "<a href='admindashboard.php?action=refuser&id={$row['id']}' class='btn btn-refuse'>Refuser</a> ";
                echo "<a href='admindashboard.php?action=en_attente&id={$row['id']}' class='btn btn-attente'>Attente</a>";
                break;
            case 'refuse':
                echo "<a href='admindashboard.php?action=valider&id={$row['id']}' class='btn btn-valide'>Valider</a> ";
                echo "<a href='admindashboard.php?action=en_attente&id={$row['id']}' class='btn btn-attente'>Attente</a>";
                break;
            default:
                // Aucun bouton si statut inconnu
                break;
        }
    }

    echo "</div></td></tr>";
}
?>

            </tbody>
        </table>
    </div>
</section>


        <!-- ===========================
             SECTION BÉNÉVOLES
             =========================== -->
        <section class="dashboard-section">
            <h2>Gestion des Bénévoles</h2>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Contact</th>
                            <th>Date dispo</th>
                            <th>Rôle</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Récupération de tous les bénévoles
                        $sql2 = "SELECT * FROM benevoles ORDER BY created_at DESC";
                        $result2 = mysqli_query($link, $sql2);
                        
                        // Affichage de chaque bénévole
                        while ($row = mysqli_fetch_assoc($result2)) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>" . htmlspecialchars($row['nom']) . "</td>
                                <td>" . htmlspecialchars($row['contact']) . "</td>
                                <td>" . htmlspecialchars($row['date_dispo']) . "</td>
                                <td>" . htmlspecialchars($row['role']) . "</td>
                                <td>" . htmlspecialchars($row['message']) . "</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ===========================
             SECTION STOCKS
             =========================== -->
        <section class="dashboard-section">
            <h2>Gestion des Stocks</h2>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Catégorie</th>
                            <th>Type</th>
                            <th>Taille</th>
                            <th>Quantité</th>
                            <th>État</th>
                            <th>Priorité</th>
                            <th>Localisation</th>
                            <th>Note</th>
                            <th>Modifications</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Récupération de tous les stocks
                        $sql3 = "SELECT * FROM stocks ORDER BY created_at DESC";
                        $result3 = mysqli_query($link, $sql3);

                        // Affichage de chaque stock
                        while ($row = mysqli_fetch_assoc($result3)) {

                            // Définir les couleurs de l'état
                            $etatColor = match($row['etat']) {
                                'Bon' => '#2ecc71',
                                'Moyen' => '#f1c40f',
                                'Usé' => '#e74c3c',
                                default => '#999'
                            };

                            // Définir les couleurs de la priorité
                            $prioriteColor = match($row['priorite']) {
                                'urgent' => '#e74c3c',
                                'important' => '#f39c12',
                                'ok' => '#3498db',
                                default => '#999'
                            };

                          echo "<tr>
                            <td>{$row['id']}</td>
                            <td>" . htmlspecialchars($row['categorie'] ?? '') . "</td>
                            <td>" . htmlspecialchars($row['type_article'] ?? '') . "</td>
                            <td>" . htmlspecialchars($row['taille'] ?? '') . "</td>
                            <td>{$row['quantite']}</td>
                            <td><span class='badge' style='background-color: {$etatColor};'>" . htmlspecialchars($row['etat'] ?? '') . "</span></td>
                            <td><span class='badge' style='background-color: {$prioriteColor};'>" . htmlspecialchars($row['priorite'] ?? '') . "</span></td>
                            <td>" . htmlspecialchars($row['localisation'] ?? '') . "</td>
                            <td>" . htmlspecialchars($row['note'] ?? '') . "</td>
                            <td>";


                            // Boutons d'action selon le rôle
                            if ($role === 'demo') {
                                echo "<button disabled class='btn btn-delete' title='Mode démo'>Supprimer</button>";
                            } else {
                                echo "<a href='admindashboard.php?action_stock=supprimer&id={$row['id']}' class='btn btn-delete' onclick='return confirm(\"Supprimer cet article ?\")'>Supprimer</a>";
                            }

                            echo "  </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
        
    </div>
</main>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

</body>
</html>