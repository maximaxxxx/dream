
<?php
require_once 'site_sql.php';

$identifiant = 'admin';
$motdepasse = 'admin ';
$hash = password_hash($motdepasse, PASSWORD_DEFAULT);

try {
    
    $stmt = $conn->prepare("SELECT identifiant FROM UTILISATEUR WHERE identifiant = :identifiant");
    $stmt->bindParam(':identifiant', $identifiant);
    $stmt->execute();
    $exists = $stmt->fetch();

    if ($exists) {
        
        $stmt = $conn->prepare("UPDATE UTILISATEUR SET motdepasse = :motdepasse WHERE identifiant = :identifiant");
        $stmt->bindParam(':motdepasse', $hash);
        $stmt->bindParam(':identifiant', $identifiant);
        $stmt->execute();
        echo "Mot de passe mis à jour pour l'utilisateur $identifiant.";
    } else {
        
        $stmt = $conn->prepare("INSERT INTO UTILISATEUR (identifiant, motdepasse) VALUES (:identifiant, :motdepasse)");
        $stmt->bindParam(':identifiant', $identifiant);
        $stmt->bindParam(':motdepasse', $hash);
        $stmt->execute();
        echo "Utilisateur $identifiant créé avec succès.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . htmlspecialchars($e->getMessage());
}
?>