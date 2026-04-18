<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/connexion.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$successMessage = '';
$errorMessage = '';
$editFormation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $id = $_POST['id'] ?? '';
        $titre = trim($_POST['titre'] ?? '');
        $categorie = trim($_POST['categorie'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duree = trim($_POST['duree'] ?? '');
        $prix = trim($_POST['prix'] ?? '');

        if ($titre === '' || $categorie === '' || $description === '' || $duree === '' || $prix === '' || !is_numeric($prix)) {
            $errorMessage = "Tous les champs sont obligatoires et le prix doit etre numerique.";
        } else {
            if ($action === 'add') {
                $stmt = $pdo->prepare(
                    "INSERT INTO formations (titre, categorie, description, duree, prix)
                     VALUES (:titre, :categorie, :description, :duree, :prix)"
                );
                $stmt->execute([
                    'titre' => $titre,
                    'categorie' => $categorie,
                    'description' => $description,
                    'duree' => $duree,
                    'prix' => (float) $prix,
                ]);
                $successMessage = "Formation ajoutee avec succes.";
            } elseif ($action === 'edit' && ctype_digit((string) $id)) {
                $stmt = $pdo->prepare(
                    "UPDATE formations
                     SET titre = :titre, categorie = :categorie, description = :description, duree = :duree, prix = :prix
                     WHERE id = :id"
                );
                $stmt->execute([
                    'titre' => $titre,
                    'categorie' => $categorie,
                    'description' => $description,
                    'duree' => $duree,
                    'prix' => (float) $prix,
                    'id' => (int) $id,
                ]);
                $successMessage = "Formation modifiee avec succes.";
            }
        }
    }
}

if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    try {
        $deleteStmt = $pdo->prepare("DELETE FROM formations WHERE id = :id");
        $deleteStmt->execute(['id' => (int) $_GET['delete']]);
        $successMessage = "Formation supprimee avec succes.";
    } catch (PDOException $e) {
        $errorMessage = "Suppression impossible. Cette formation est probablement liee a des inscriptions.";
    }
}

if (isset($_GET['edit']) && ctype_digit($_GET['edit'])) {
    $editStmt = $pdo->prepare("SELECT id, titre, categorie, description, duree, prix FROM formations WHERE id = :id");
    $editStmt->execute(['id' => (int) $_GET['edit']]);
    $editFormation = $editStmt->fetch();
}

$formationsStmt = $pdo->query("SELECT id, titre, categorie, description, duree, prix FROM formations ORDER BY id DESC");
$formations = $formationsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form'Campus - Admin formations</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="admin-page">
  <header>
    <div class="container navbar">
      <a class="brand" href="index.php">Form'Campus</a>
      <nav class="menu">
        <a href="admin_formations.php" class="active">Formations</a>
        <a href="admin_inscriptions.php">Inscriptions</a>
        <a href="logout.php">Deconnexion</a>
      </nav>
    </div>
  </header>

  <main>
    <div class="container">
      <h1>Gestion des formations</h1>

      <?php if ($successMessage !== ''): ?>
        <p class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></p>
      <?php endif; ?>
      <?php if ($errorMessage !== ''): ?>
        <p class="alert alert-error"><?php echo htmlspecialchars($errorMessage); ?></p>
      <?php endif; ?>

      <section class="admin-section">
      <h2><?php echo $editFormation ? 'Modifier une formation' : 'Ajouter une formation'; ?></h2>
      <form method="post" action="admin_formations.php">
        <input type="hidden" name="action" value="<?php echo $editFormation ? 'edit' : 'add'; ?>">
        <input type="hidden" name="id" value="<?php echo $editFormation ? (int) $editFormation['id'] : ''; ?>">

        <div class="form-grid">
          <div>
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" required value="<?php echo htmlspecialchars($editFormation['titre'] ?? ''); ?>">
          </div>
          <div>
            <label for="categorie">Categorie</label>
            <input type="text" id="categorie" name="categorie" required value="<?php echo htmlspecialchars($editFormation['categorie'] ?? ''); ?>">
          </div>
          <div>
            <label for="duree">Duree</label>
            <input type="text" id="duree" name="duree" required value="<?php echo htmlspecialchars($editFormation['duree'] ?? ''); ?>">
          </div>
          <div>
            <label for="prix">Prix (EUR)</label>
            <input type="number" step="0.01" min="0" id="prix" name="prix" required value="<?php echo htmlspecialchars((string) ($editFormation['prix'] ?? '')); ?>">
          </div>
        </div>
        <div class="field-spacer">
          <label for="description">Description</label>
          <textarea id="description" name="description" required><?php echo htmlspecialchars($editFormation['description'] ?? ''); ?></textarea>
        </div>
        <div class="actions field-actions">
          <button type="submit" class="btn btn-primary"><?php echo $editFormation ? 'Modifier' : 'Ajouter'; ?></button>
          <?php if ($editFormation): ?>
            <a href="admin_formations.php" class="btn btn-secondary">Annuler</a>
          <?php endif; ?>
        </div>
      </form>
      </section>

      <section class="admin-section">
      <h2>Liste des formations</h2>
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Categorie</th>
            <th>Duree</th>
            <th>Prix</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($formations)): ?>
            <tr><td colspan="6">Aucune formation enregistree.</td></tr>
          <?php else: ?>
            <?php foreach ($formations as $formation): ?>
              <tr>
                <td><?php echo (int) $formation['id']; ?></td>
                <td><?php echo htmlspecialchars($formation['titre']); ?></td>
                <td><?php echo htmlspecialchars($formation['categorie']); ?></td>
                <td><?php echo htmlspecialchars($formation['duree']); ?></td>
                <td><?php echo number_format((float) $formation['prix'], 2, ',', ' '); ?> EUR</td>
                <td class="actions">
                  <a class="btn btn-secondary btn-small" href="admin_formations.php?edit=<?php echo (int) $formation['id']; ?>">Modifier</a>
                  <a class="btn btn-danger btn-small" href="admin_formations.php?delete=<?php echo (int) $formation['id']; ?>" onclick="return confirm('Supprimer cette formation ?');">Supprimer</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      </section>
    </div>
  </main>
</body>
</html>
