<?php
declare(strict_types=1);
require_once __DIR__ . '/connexion.php';

$formationsStmt = $pdo->query("SELECT id, titre FROM formations ORDER BY titre ASC");
$formations = $formationsStmt->fetchAll();

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['tel'] ?? '');
    $idFormation = $_POST['id_formation'] ?? '';
    $commentaire = trim($_POST['commentaire'] ?? '');

    if (
        $nom === '' || $prenom === '' || $email === '' || $tel === '' || $idFormation === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) || !ctype_digit($idFormation)
    ) {
        $errorMessage = "Veuillez remplir correctement tous les champs obligatoires.";
    } else {
        $formationCheck = $pdo->prepare("SELECT id FROM formations WHERE id = :id");
        $formationCheck->execute(['id' => (int) $idFormation]);
        $exists = $formationCheck->fetchColumn();

        if (!$exists) {
            $errorMessage = "La formation selectionnee est introuvable.";
        } else {
            $insert = $pdo->prepare(
                "INSERT INTO inscriptions (nom, prenom, email, tel, id_formation, commentaire, date_inscription)
                 VALUES (:nom, :prenom, :email, :tel, :id_formation, :commentaire, NOW())"
            );
            $insert->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'tel' => $tel,
                'id_formation' => (int) $idFormation,
                'commentaire' => $commentaire,
            ]);
            $successMessage = "Votre inscription a bien ete enregistree.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form'Campus - Inscription</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <div class="container navbar">
      <a class="brand" href="index.php">Form'Campus</a>
      <nav class="menu">
        <a href="index.php">Accueil</a>
        <a href="formations.php">Formations</a>
        <a class="active" href="inscription.php">S'inscrire</a>
        <a href="login.php">Admin</a>
      </nav>
    </div>
  </header>

  <main>
    <div class="container">
      <h1>Formulaire d'inscription</h1>

      <?php if ($successMessage !== ''): ?>
        <p class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></p>
      <?php endif; ?>

      <?php if ($errorMessage !== ''): ?>
        <p class="alert alert-error"><?php echo htmlspecialchars($errorMessage); ?></p>
      <?php endif; ?>

      <form id="inscriptionForm" method="post" action="inscription.php" novalidate>
        <div class="form-grid">
          <div>
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" required>
          </div>
          <div>
            <label for="prenom">Prenom *</label>
            <input type="text" id="prenom" name="prenom" required>
          </div>
          <div>
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
          </div>
          <div>
            <label for="tel">Telephone *</label>
            <input type="text" id="tel" name="tel" required>
          </div>
        </div>

        <div style="margin-top:14px;">
          <label for="id_formation">Formation *</label>
          <select id="id_formation" name="id_formation" required>
            <option value="">-- Selectionnez une formation --</option>
            <?php foreach ($formations as $formation): ?>
              <option value="<?php echo (int) $formation['id']; ?>">
                <?php echo htmlspecialchars($formation['titre']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="margin-top:14px;">
          <label for="commentaire">Commentaire</label>
          <textarea id="commentaire" name="commentaire" placeholder="Votre message (optionnel)"></textarea>
        </div>

        <div style="margin-top:16px;" class="actions">
          <button type="submit" class="btn btn-primary">Valider l'inscription</button>
        </div>
      </form>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; <?php echo date('Y'); ?> Form'Campus</p>
    </div>
  </footer>

  <script src="validation.js"></script>
</body>
</html>
