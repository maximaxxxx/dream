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
$host = 'mysql-termnsi.alwaysdata.net';
$db   = 'termnsi_reve';
$user = 'termnsi_nino';
$pass = 'passy2025';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}