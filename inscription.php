<?php
session_start();
require_once 'site_sql.php'; // connexion PDO dans $conn

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $motdepasse = trim($_POST['motdepasse'] ?? '');
    $motdepasse_confirm = trim($_POST['motdepasse_confirm'] ?? '');

    // Vérification des champs
    if ($identifiant === '' || $motdepasse === '' || $motdepasse_confirm === '') {
        $error = "Merci de remplir tous les champs.";
    } elseif ($motdepasse !== $motdepasse_confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        try {
            // Vérifie si l'identifiant existe déjà
            $stmt = $conn->prepare("SELECT COUNT(*) FROM UTILISATEUR WHERE identifiant = :identifiant");
            $stmt->execute(['identifiant' => $identifiant]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $error = "Identifiant déjà utilisé.";
            } else {
                // Insérer l'utilisateur en clair
                $stmt = $conn->prepare("INSERT INTO UTILISATEUR (identifiant, motdepasse) VALUES (:identifiant, :motdepasse)");
                $stmt->execute([
                    'identifiant' => $identifiant,
                    'motdepasse' => $motdepasse
                ]);

                // Connexion automatique
                session_regenerate_id(true);
                $_SESSION['identifiant'] = $identifiant;

                // Redirection vers le dashboard
                header('Location: dashboard.php');
                exit;
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
    <title>Inscription</title>
    <link rel="stylesheet" href="inscription.css">
    <link rel="icon" type="image/png" href="img/4200111.png">
</head>
<body>
    <img src="https://cdn-icons-png.flaticon.com/512/6714/6714978.png" alt="Mode nuit" class="modenuit" id="toggleTheme">

    <form method="POST" novalidate>
        <h1>
            <a href="connexion.php">
                <img class="home-icon" src="https://cdn-icons-png.flaticon.com/512/1/1112.png" alt="accueil">
            </a>
            Inscription
        </h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <label for="identifiant">Identifiant</label>
        <input type="text" id="identifiant" name="identifiant" required value="<?= htmlspecialchars($_POST['identifiant'] ?? '') ?>">

        <label for="motdepasse">Mot de passe</label>
        <input type="password" id="motdepasse" name="motdepasse" required>

        <label for="motdepasse_confirm">Confirmer le mot de passe</label>
        <input type="password" id="motdepasse_confirm" name="motdepasse_confirm" required>

        <button type="submit" class="inscription">S'inscrire</button>
    </form>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const body = document.body;
    const toggleBtn = document.getElementById("toggleTheme");
    const homeIcon = document.querySelector(".home-icon");

    // Icônes
    const moonIcon = "https://cdn-icons-png.flaticon.com/512/6714/6714978.png";
    const sunIcon = "https://cdn-icons-png.flaticon.com/512/869/869869.png";
    const homeLight = "https://cdn-icons-png.flaticon.com/512/1/1112.png";
    const homeDark = "/img/flecheblanche.png";

    // Applique le thème
    function applyTheme(theme) {
        body.classList.remove("dark", "light");
        body.classList.add(theme);

        // Animation fade sur les icônes
        homeIcon.style.opacity = 0;
        toggleBtn.style.opacity = 0;
        setTimeout(() => {
            toggleBtn.src = theme === "dark" ? sunIcon : moonIcon;
            toggleBtn.alt = theme === "dark" ? "Mode jour" : "Mode nuit";
            homeIcon.src = theme === "dark" ? homeDark : homeLight;
            homeIcon.style.opacity = 1;
            toggleBtn.style.opacity = 1;
        }, 150);
    }

    // Restaure le thème depuis localStorage
    const savedTheme = localStorage.getItem("theme") || "dark";
    applyTheme(savedTheme);

    // Toggle clic
    toggleBtn.addEventListener("click", () => {
        const newTheme = body.classList.contains("dark") ? "light" : "dark";
        applyTheme(newTheme);
        localStorage.setItem("theme", newTheme);
    });
});
</script>
</body>
</html>
