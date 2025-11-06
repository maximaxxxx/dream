<?php
#Permet le connexion à la base de donnée
require_once 'site_sql.php';

#On définit l'identifiant de l'utilisateur
$identifiant = 'admin';
#On définit le mot de passe de l'utilisateur
$motdepasse = 'admin ';
#On crée un hash sécurisé du mot de passe pour le stocker dans la base de données
$hash = password_hash($motdepasse, PASSWORD_DEFAULT);

try {
    
    #On prépare une requête pour vérifier si un utilisateur avec cet identifiant existe déjà
    $stmt = $conn->prepare("SELECT identifiant FROM UTILISATEUR WHERE identifiant = :identifiant");
    #On lie la variable $identifiant au paramètre SQL : identifiant
    $stmt->bindParam(':identifiant', $identifiant);
    #On exécute la requête
    $stmt->execute();
    #On récupère le résultat, si il existe
    $exists = $stmt->fetch();   

    #Si l'utilisateur existe déjà dans la base de donnée
    if ($exists) {
        
        #On prépare une requête pour mettre à jour son mot de passe
        $stmt = $conn->prepare("UPDATE UTILISATEUR SET motdepasse = :motdepasse WHERE identifiant = :identifiant");
        #On lie les paramètres à leurs valeurs respectives
        $stmt->bindParam(':motdepasse', $hash);
        $stmt->bindParam(':identifiant', $identifiant);
        #On exécute la requête
        $stmt->execute();
        #On affiche un message indiquant que le mot de passe a été mis à jour
        echo "Mot de passe mis à jour pour l'utilisateur $identifiant.";
    } else {
        
        #Si l’utilisateur n’existe pas, on l’insère dans la table
        $stmt = $conn->prepare("INSERT INTO UTILISATEUR (identifiant, motdepasse) VALUES (:identifiant, :motdepasse)");
        $stmt->bindParam(':identifiant', $identifiant);
        #On lie les paramètres
        $stmt->bindParam(':motdepasse', $hash);
        #On exécute l’insertion
        $stmt->execute();
        #On affiche un message confirmant la création du compte
        echo "Utilisateur $identifiant créé avec succès.";
    }
#Si une erreur se produit lors de la connexion ou de l’exécution SQL    
} catch (PDOException $e) {
    #On affiche un message d’erreur sécurisé (grâce à htmlspecialchars)
    echo "Erreur : " . htmlspecialchars($e->getMessage());
}
?>