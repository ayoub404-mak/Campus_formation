<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formations Campus - Accueil</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <div class="container navbar">
      <a class="brand" href="index.php">Formations Campus</a>
      <nav class="menu">
        <a class="active" href="index.php">Accueil</a>
        <a href="formations.php">Formations</a>
        <a href="inscription.php">S'inscrire</a>
        <a href="login.php">Admin</a>
      </nav>
    </div>
  </header>

  <main class="home-page">
    <div class="container">
      <section class="hero">
        <p class="hero-kicker">Plateforme de formation professionnelle</p>
        <h1>Bienvenue sur Formations Campus</h1>
        <p>
          Formations Campus est une plateforme d'inscription a des formations professionnelles.
          Decouvrez nos programmes, comparez les categories, puis inscrivez-vous en quelques clics.
        </p>
        <div class="actions">
          <a class="btn btn-primary" href="formations.php">Voir les formations</a>
          <a class="btn btn-secondary" href="inscription.php">Demarrer une inscription</a>
        </div>
      </section>

      <section class="stats-grid">
        <article class="stat-card">
          <p class="stat-value">4+</p>
          <p class="stat-label">Domaines de formation</p>
        </article>
        <article class="stat-card">
          <p class="stat-value">100%</p>
          <p class="stat-label">Inscription en ligne</p>
        </article>
        <article class="stat-card">
          <p class="stat-value">24/7</p>
          <p class="stat-label">Acces au catalogue</p>
        </article>
      </section>

      <section class="grid home-features">
        <article class="card">
          <p class="badge">Catalogue</p>
          <h3>Formations variees</h3>
          <p class="muted">
            Retrouvez plusieurs domaines pour monter en competence selon votre projet.
          </p>
          <a class="btn btn-secondary btn-small" href="formations.php">Explorer</a>
        </article>

        <article class="card">
          <p class="badge">Inscription</p>
          <h3>Simple et rapide</h3>
          <p class="muted">
            Completez le formulaire en quelques minutes et recevez un suivi immediat.
          </p>
          <a class="btn btn-secondary btn-small" href="inscription.php">S'inscrire</a>
        </article>

        <article class="card">
          <p class="badge">Administration</p>
          <h3>Gestion centralisee</h3>
          <p class="muted">
            Les responsables peuvent piloter les formations et les inscriptions depuis l'espace admin.
          </p>
          <a class="btn btn-secondary btn-small" href="login.php">Acceder</a>
        </article>
      </section>

      <section class="card home-why">
        <h2>Pourquoi choisir Formations Campus ?</h2>
        <p class="muted">
          Une experience moderne pour simplifier l'inscription des apprenants et la gestion pour votre equipe.
        </p>
        <div class="grid why-grid">
          <div class="why-item">
            <h3>Interface claire</h3>
            <p class="muted">Navigation fluide entre les pages publiques et l'espace administratif.</p>
          </div>
          <div class="why-item">
            <h3>Plateforme securisee</h3>
            <p class="muted">Connexion admin protegee avec sessions PHP et mots de passe verifies.</p>
          </div>
          <div class="why-item">
            <h3>Design responsive</h3>
            <p class="muted">Experience optimale sur ordinateur, tablette et mobile.</p>
          </div>
        </div>
      </section>

      <section class="home-cta">
        <div class="home-cta-content">
          <h2>Pret a rejoindre une formation ?</h2>
          <p>Consultez les programmes disponibles et finalisez votre inscription en quelques etapes.</p>
        </div>
        <div class="actions">
          <a class="btn btn-primary" href="inscription.php">Je m'inscris maintenant</a>
          <a class="btn btn-secondary" href="formations.php">Comparer les formations</a>
        </div>
      </section>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; <?php echo date('Y'); ?> Formations Campus - Tous droits reserves.</p>
    </div>
  </footer>
</body>
</html>
