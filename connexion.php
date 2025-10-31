
<?php
session_start();
require_once 'site_sql.php'; // connexion PDO dans $conn

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $motdepasse = trim($_POST['motdepasse'] ?? '');

    if ($identifiant === '' || $motdepasse === '') {
        $error = "Merci de remplir tous les champs.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT motdepasse FROM UTILISATEUR WHERE identifiant = :identifiant");
            $stmt->bindParam(':identifiant', $identifiant);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $motdepasse === $user['motdepasse']) {
                // Connexion OK (EN CLAIR)
                session_regenerate_id(true);
                $_SESSION['identifiant'] = $identifiant;
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Identifiant ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur PDO : " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="img/4200111.png">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="page_de_connexion.css">
    
</head>
<body>
    <img src="https://cdn-icons-png.flaticon.com/512/6714/6714978.png" alt="Mode nuit" class="modenuit" id="toggleTheme">

    <script>
document.addEventListener("DOMContentLoaded", function() {
  const body = document.body;
  const toggleBtn = document.getElementById("toggleTheme");

  const moonIcon = "https://cdn-icons-png.flaticon.com/512/6714/6714978.png";
  const sunIcon = "https://cdn-icons-png.flaticon.com/512/869/869869.png";

  const homeIcon = document.querySelector(".home-icon");
  const homeLight = "https://cdn-icons-png.flaticon.com/512/1/1112.png"; // noire
  const homeDark = "/img/flecheblanche.png";  // blanche

  // Restaure le thème depuis le localStorage
  const savedTheme = localStorage.getItem("theme") || "dark";
  body.classList.add(savedTheme);

  toggleBtn.src = savedTheme === "dark" ? sunIcon : moonIcon;
  toggleBtn.alt = savedTheme === "dark" ? "Mode jour" : "Mode nuit";
  homeIcon.src = savedTheme === "dark" ? homeDark : homeLight;

  toggleBtn.addEventListener("click", () => {
    const newTheme = body.classList.contains("dark") ? "light" : "dark";
    body.classList.remove("dark", "light");
    body.classList.add(newTheme);
    localStorage.setItem("theme", newTheme);

    toggleBtn.src = newTheme === "dark" ? sunIcon : moonIcon;
    toggleBtn.alt = newTheme === "dark" ? "Mode jour" : "Mode nuit";
    homeIcon.src = newTheme === "dark" ? homeDark : homeLight;
  });
});
</script>





    

    <form method="POST" novalidate>
                <h1>
                    <a href="index.php" title="Accueil" aria-label="Accueil">
            <a href="index.php">
                    <img class="home-icon" src="https://cdn-icons-png.flaticon.com/512/1/1112.png" alt="accueil">
                </a>Connexion
                
        </h1>
        <?php if ($error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <label for="identifiant">Identifiant</label>
        <input type="text" id="identifiant" name="identifiant" required value="<?= htmlspecialchars($_POST['identifiant'] ?? '') ?>">
        <label for="motdepasse">Mot de passe</label>
        <input type="password" id="motdepasse" name="motdepasse" required>
        <button type="submit">Se connecter aux Rêves</button>
        
        <a href="inscription.php" class="inscription" title="inscription">Inscription aux Rêves</a>
    </form>
</body>
</html>
