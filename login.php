<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/connexion.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_formations.php');
    exit;
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $errorMessage = "Veuillez renseigner le login et le mot de passe.";
    } else {
        $stmt = $pdo->prepare("SELECT id, login, password FROM users WHERE login = :login LIMIT 1");
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = (int) $user['id'];
            $_SESSION['admin_login'] = $user['login'];
            header('Location: admin_formations.php');
            exit;
        }

        $errorMessage = "Identifiants invalides.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form'Campus - Connexion admin</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <div class="container navbar">
      <a class="brand" href="index.php">Form'Campus</a>
      <nav class="menu">
        <a href="index.php">Accueil</a>
        <a href="formations.php">Formations</a>
        <a href="inscription.php">S'inscrire</a>
        <a class="active" href="login.php">Admin</a>
      </nav>
    </div>
  </header>

  <main>
    <div class="container">
      <h1>Connexion administrateur</h1>

      <?php if ($errorMessage !== ''): ?>
        <p class="alert alert-error"><?php echo htmlspecialchars($errorMessage); ?></p>
      <?php endif; ?>

      <form method="post" action="login.php" style="max-width: 450px;">
        <div>
          <label for="login">Login</label>
          <input type="text" id="login" name="login" required>
        </div>
        <div style="margin-top: 14px;">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div style="margin-top: 16px;">
          <button type="submit" class="btn btn-primary">Se connecter</button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
