<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "mysql-termnsi.alwaysdata.net";
$username = "termnsi_visite";
$password = "passy2025";
$dbname = "termnsi_reve";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("<h1>Erreur de connexion à la base</h1><p>{$e->getMessage()}</p>");
}

$nom_page = basename($_SERVER['PHP_SELF']);

try {
    $stmt = $conn->prepare("SELECT 1 FROM PAGE WHERE nom_page = :nom_page LIMIT 1");
    $stmt->execute(['nom_page' => $nom_page]);
    $page_existe = $stmt->fetch();
} catch (PDOException $e) {
    die("<h1>Erreur lors de la vérification de la page</h1><p>{$e->getMessage()}</p>");
}

if (!$page_existe) {
    header("HTTP/1.1 403 Forbidden");
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Accès refusé</title></head><body>";
    echo "<h1>🚫 Accès refusé</h1>";
    echo "<p>Cette page n'est pas enregistrée dans la base de données.</p>";
    echo "</body></html>";
    exit; 
}

?>
<?php

require_once 'site_sql.php'; // connexion PDO dans $conn

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['identifiant'])) {
    header('Location: connexion.php');
    exit;
}

// Gestion des actions : supprimer ou ajouter
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $table = $_POST['table'] ?? '';
    
    try {
        if ($action === 'supprimer') {
            $id = $_POST['id'] ?? '';
            $pk = $_POST['pk'] ?? 'id';
            $stmt = $conn->prepare("DELETE FROM `$table` WHERE `$pk` = :id");
            $stmt->execute(['id' => $id]);
            $message = "Élément supprimé avec succès.";
        } elseif ($action === 'ajouter') {
            $columns = $_POST['columns'] ?? [];
            if ($columns) {
                $keys = array_keys($columns);
                $placeholders = array_map(fn($k)=> ":$k", $keys);
                $sql = "INSERT INTO `$table` (".implode(',', $keys).") VALUES (".implode(',', $placeholders).")";
                $stmt = $conn->prepare($sql);
                $stmt->execute($columns);
                $message = "Élément ajouté avec succès.";
            }
        }
    } catch (PDOException $e) {
        $message = "Erreur PDO : " . htmlspecialchars($e->getMessage());
    }
}

// Tables disponibles
$tables = ['EMOTION', 'LANGUE', 'LIAISON_ELEMENT', 'LIAISON_EMOTION', 'LIAISON_LANGUE', 'LIAISON_TROUBLE_DE_SANTE', 'LIEU', 'PERSONNAGE_PRINCIPAL','PAGE', 'PEUR', 'REVE', 'REVEUR', 'TROUBLE_DE_SANTE', 'TYPE_ELEMENT_DANS_LE_REVE', 'TYPE_REVE', 'UTILISATEUR'];
$selectedTable = $_GET['table'] ?? $tables[0];

// Colonnes et données
$columns = [];
$data = [];
try {
    $stmt = $conn->query("SHOW COLUMNS FROM `$selectedTable`");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt = $conn->query("SELECT * FROM `$selectedTable`");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Erreur PDO : " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="icon" type="image/png" href="img/4200111.png">
</head>
<body>
    <img src="https://cdn-icons-png.flaticon.com/512/6714/6714978.png" alt="Mode nuit" class="modenuit" id="toggleTheme">

    <div class="dashboard-container">
        <!-- Flèche retour -->
        <a href="index.php">
            <img src="https://cdn-icons-png.flaticon.com/512/1/1112.png" 
                 alt="Accueil" 
                 class="home-icon-dashboard" id="homeIconDashboard">
        </a>

        <h1>Dashboard - Table : <?= htmlspecialchars($selectedTable) ?></h1>

        <?php if($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <!-- Sélecteur de table -->
        <div class="table-select">
            <form method="GET">
                <label for="table">Choisir une table :</label>
                <select name="table" id="table" onchange="this.form.submit()">
                    <?php foreach($tables as $t): ?>
                        <option value="<?= $t ?>" <?= $t === $selectedTable ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Formulaire ajout -->
        <form method="POST" class="add-form">
            <input type="hidden" name="action" value="ajouter">
            <input type="hidden" name="table" value="<?= $selectedTable ?>">
            <?php foreach($columns as $col): ?>
                <input type="text" name="columns[<?= $col ?>]" placeholder="<?= $col ?>">
            <?php endforeach; ?>
            <button type="submit" class="inscription">Ajouter</button>
        </form>

        <!-- Tableau -->
        <table>
            <thead>
                <tr>
                    <?php foreach($columns as $col): ?>
                        <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                    <tr>
                        <?php foreach($columns as $col): ?>
                            <td><?= htmlspecialchars($row[$col]) ?></td>
                        <?php endforeach; ?>
                        <td>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="table" value="<?= $selectedTable ?>">
                                <input type="hidden" name="id" value="<?= $row[$columns[0]] ?>">
                                <input type="hidden" name="pk" value="<?= $columns[0] ?>">
                                <button type="submit" class="inscription" style="background:#e74c3c">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const body = document.body;
    const html = document.documentElement;
    const toggleBtn = document.getElementById("toggleTheme");
    const homeIcon = document.getElementById("homeIconDashboard");

    const moonIcon = "https://cdn-icons-png.flaticon.com/512/6714/6714978.png";
    const sunIcon = "https://cdn-icons-png.flaticon.com/512/869/869869.png";

    const homeDark = "/img/flecheblanche.png";
    const homeLight = "https://cdn-icons-png.flaticon.com/512/1/1112.png";

    // Thème initial
    const savedTheme = localStorage.getItem("theme") || "dark";
    body.classList.add(savedTheme);
    html.classList.add(savedTheme);
    toggleBtn.src = savedTheme === "dark" ? sunIcon : moonIcon;
    homeIcon.src = savedTheme === "dark" ? homeDark : homeLight;

    toggleBtn.addEventListener("click", () => {
        const newTheme = body.classList.contains("dark") ? "light" : "dark";
        body.classList.remove("dark","light");
        html.classList.remove("dark","light");
        body.classList.add(newTheme);
        html.classList.add(newTheme);
        localStorage.setItem("theme", newTheme);
        toggleBtn.src = newTheme === "dark" ? sunIcon : moonIcon;
        homeIcon.src = newTheme === "dark" ? homeDark : homeLight;
    });
});
</script>
</body>
</html>
