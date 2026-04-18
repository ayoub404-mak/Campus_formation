<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/connexion.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$formationsStmt = $pdo->query("SELECT id, titre FROM formations ORDER BY titre ASC");
$formations = $formationsStmt->fetchAll();

$selectedFormation = $_GET['formation'] ?? '';
$inscriptions = [];

if ($selectedFormation !== '' && ctype_digit($selectedFormation)) {
    $stmt = $pdo->prepare(
        "SELECT i.id, i.nom, i.prenom, i.email, i.tel, i.commentaire, i.date_inscription, f.titre
         FROM inscriptions i
         INNER JOIN formations f ON f.id = i.id_formation
         WHERE i.id_formation = :id_formation
         ORDER BY i.date_inscription DESC"
    );
    $stmt->execute(['id_formation' => (int) $selectedFormation]);
    $inscriptions = $stmt->fetchAll();
} else {
    $stmt = $pdo->query(
        "SELECT i.id, i.nom, i.prenom, i.email, i.tel, i.commentaire, i.date_inscription, f.titre
         FROM inscriptions i
         INNER JOIN formations f ON f.id = i.id_formation
         ORDER BY i.date_inscription DESC"
    );
    $inscriptions = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form'Campus - Admin inscriptions</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="admin-page">
  <header>
    <div class="container navbar">
      <a class="brand" href="index.php">Form'Campus</a>
      <nav class="menu">
        <a href="admin_formations.php">Formations</a>
        <a href="admin_inscriptions.php" class="active">Inscriptions</a>
        <a href="logout.php">Deconnexion</a>
      </nav>
    </div>
  </header>

  <main>
    <div class="container">
      <h1>Liste des inscriptions</h1>

      <section class="admin-section">
      <form method="get" action="admin_inscriptions.php" class="admin-filter-form">
        <label for="formation">Filtrer par formation</label>
        <div class="actions">
          <select id="formation" name="formation">
            <option value="">Toutes les formations</option>
            <?php foreach ($formations as $formation): ?>
              <option value="<?php echo (int) $formation['id']; ?>" <?php echo ($selectedFormation === (string) $formation['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($formation['titre']); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <button type="submit" class="btn btn-primary">Filtrer</button>
          <a class="btn btn-secondary" href="admin_inscriptions.php">Reinitialiser</a>
        </div>
      </form>
      </section>

      <section class="admin-section">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom complet</th>
            <th>Email</th>
            <th>Telephone</th>
            <th>Formation</th>
            <th>Commentaire</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($inscriptions)): ?>
            <tr>
              <td colspan="7">Aucune inscription trouvee.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($inscriptions as $item): ?>
              <tr>
                <td><?php echo (int) $item['id']; ?></td>
                <td><?php echo htmlspecialchars($item['prenom'] . ' ' . $item['nom']); ?></td>
                <td><?php echo htmlspecialchars($item['email']); ?></td>
                <td><?php echo htmlspecialchars($item['tel']); ?></td>
                <td><?php echo htmlspecialchars($item['titre']); ?></td>
                <td><?php echo htmlspecialchars($item['commentaire'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($item['date_inscription']); ?></td>
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
