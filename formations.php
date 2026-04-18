<?php
declare(strict_types=1);
require_once __DIR__ . '/connexion.php';

$stmt = $pdo->query("SELECT id, titre, categorie, description, duree, prix FROM formations ORDER BY id DESC");
$formations = $stmt->fetchAll();

$formationDetails = null;
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $detailStmt = $pdo->prepare("SELECT id, titre, categorie, description, duree, prix FROM formations WHERE id = :id");
    $detailStmt->execute(['id' => (int) $_GET['id']]);
    $formationDetails = $detailStmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form'Campus - Formations</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <div class="container navbar">
      <a class="brand" href="index.php">Form'Campus</a>
      <nav class="menu">
        <a href="index.php">Accueil</a>
        <a class="active" href="formations.php">Formations</a>
        <a href="inscription.php">S'inscrire</a>
        <a href="login.php">Admin</a>
      </nav>
    </div>
  </header>

  <main>
    <div class="container">
      <h1>Nos formations</h1>
      <?php if (empty($formations)): ?>
        <p class="alert alert-error">Aucune formation n'est disponible pour le moment.</p>
      <?php else: ?>
        <section class="grid">
          <?php foreach ($formations as $formation): ?>
            <article class="card">
              <h3><?php echo htmlspecialchars($formation['titre']); ?></h3>
              <p><span class="badge"><?php echo htmlspecialchars($formation['categorie']); ?></span></p>
              <p class="muted"><?php echo htmlspecialchars(strlen($formation['description']) > 120 ? substr($formation['description'], 0, 120) . '...' : $formation['description']); ?></p>
              <p><strong>Duree:</strong> <?php echo htmlspecialchars($formation['duree']); ?></p>
              <p class="price"><?php echo number_format((float) $formation['prix'], 2, ',', ' '); ?> EUR</p>
              <a class="btn btn-primary btn-small" href="formations.php?id=<?php echo (int) $formation['id']; ?>">Voir details</a>
            </article>
          <?php endforeach; ?>
        </section>
      <?php endif; ?>

      <?php if ($formationDetails): ?>
        <section class="card" style="margin-top: 24px;">
          <h2><?php echo htmlspecialchars($formationDetails['titre']); ?></h2>
          <p><span class="badge"><?php echo htmlspecialchars($formationDetails['categorie']); ?></span></p>
          <p><?php echo nl2br(htmlspecialchars($formationDetails['description'])); ?></p>
          <p><strong>Duree:</strong> <?php echo htmlspecialchars($formationDetails['duree']); ?></p>
          <p class="price"><?php echo number_format((float) $formationDetails['prix'], 2, ',', ' '); ?> EUR</p>
          <a class="btn btn-secondary" href="inscription.php">S'inscrire a cette formation</a>
        </section>
      <?php endif; ?>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; <?php echo date('Y'); ?> Form'Campus</p>
    </div>
  </footer>
</body>
</html>
