<?php
session_start();
require_once 'site_sql.php'; // connexion PDO dans $conn

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['identifiant'])) {
    header('Location: connexion.php');
    exit;
}

// Si l'utilisateur choisit une action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Déconnexion
    if ($action === 'deconnexion') {
        session_destroy();
        header('Location: connexion.php');
        exit;
    }
    // Suppression du compte
    if ($action === 'supprimer') {
        try {
            $stmt = $conn->prepare("DELETE FROM UTILISATEUR WHERE identifiant = :id");
            $stmt->execute(['id' => $_SESSION['identifiant']]);
            session_destroy();
            header('Location: connexion.php?message=compte_supprime');
            exit;
        } catch (PDOException $e) {
            $erreur = "Erreur lors de la suppression : " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion du compte</title>
    <link rel="stylesheet" href="compte.css">
    <link rel="icon" type="image/png" href="img/4200111.png">
</head>
<body>
    <!-- Bouton mode nuit -->
    <img src="https://cdn-icons-png.flaticon.com/512/6714/6714978.png" 
         alt="Mode nuit" 
         class="modenuit" 
         id="toggleTheme">

    <!-- Formulaire centré -->
    <form method="POST">
        <h1>Mon compte</h1>
        <p style="font-weight:600; text-align:center;">Bonjour, 
            <span style="color:#007bff;">
                <?= htmlspecialchars($_SESSION['identifiant']) ?>
            </span> 👋
        </p>

        <?php if (isset($erreur)): ?>
            <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>
        <a href="dashboard.php" class="dashboard-btn">Aller au Dashboard</a>
        <button type="submit" name="action" value="deconnexion" style="background-color:#007bff;">
            Se déconnecter
        </button>
        <button type="submit" name="action" value="supprimer" style="background-color:#e74c3c;"
                onclick="return confirm('⚠️ Voulez-vous vraiment supprimer votre compte ? Cette action est irréversible.')">
            Supprimer mon compte
        </button>
        

        <a href="index.php" class="inscription">Retour à l'accueil</a>
    </form>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const body = document.body;
        const toggleBtn = document.getElementById("toggleTheme");

        const moonIcon = "https://cdn-icons-png.flaticon.com/512/6714/6714978.png";
        const sunIcon = "https://cdn-icons-png.flaticon.com/512/869/869869.png";

        const savedTheme = localStorage.getItem("theme") || "dark";
        body.classList.add(savedTheme);
        toggleBtn.src = savedTheme === "dark" ? sunIcon : moonIcon;

        toggleBtn.addEventListener("click", () => {
            const newTheme = body.classList.contains("dark") ? "light" : "dark";
            body.classList.remove("dark", "light");
            body.classList.add(newTheme);
            localStorage.setItem("theme", newTheme);
            toggleBtn.src = newTheme === "dark" ? sunIcon : moonIcon;
        });
    });
    </script>
</body>
</html>
