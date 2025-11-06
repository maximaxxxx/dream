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

      
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DREAM</title>
    <link rel="icon" type="image/png" href="img/4200111.png">
    <link rel="stylesheet" type="text/css" href="site sql.css">
    
    
</head>

<body>
<div id="connexion">
    <!-- Bouton mode jour/nuit banane-->
    <img src="https://cdn-icons-png.flaticon.com/512/6714/6714978.png" alt="Mode nuit" class="modenuit" id="toggleTheme">

    <?php if (isset($_SESSION['identifiant'])): ?>
        <div class="user-menu">
            <a href="compte.php">
            <img src="img/image_connexion.png" alt="Icône utilisateur">
            <span><?= htmlspecialchars($_SESSION['identifiant']) ?></span></a>
        </div>
    <?php else: ?>
        <a href="connexion.php">
            <img src="img/image_connexion.png" alt="Icône de connexion">Connexion
        </a>
    <?php endif; ?>
</div>



    <div id="header">DREAM</div>

    <div id="titre">
        <h1>Bienvenue dans l’univers des rêves</h1>
        <p>Explorez vos rêves dans cette base de données !!</p>
    </div>

    <section>
        <h2>Quand la nuit nous fait voyager</h2>
        <p>Plongée dans l’univers fascinant des rêves et de leurs secrets.</p>
    </section>



<form id="menuForm" method="post" action="index.php">
  <label for="liste">Liste info <br/>
  <select name="choix" id="liste">
    <option value="REVE">Reve</option>
    <option value="REVEUR">Reveur</option>
    <option value="PEUR">Peur</option>
    <option value="EMOTION">Emotion</option>
    <option value="LANGUE">Langue</option>
    <option value="LIEU">Lieu</option>
    <option value="PERSONNAGE_PRINCIPAL">Personnage</option>
    <option value="TROUBLE_DE_SANTE">Trouble de sante</option>
    <option value="TYPE_ELEMENT_DANS_LE_REVE">Elements</option>
    <option value="TYPE_REVE">Type de reve</option>
  </select>
  <br>
  <input type="submit" value="Valider" name="valider"/>
</form>

    <br>




<script> //cette partie a été corrgié par de l'ia... j'avais oublié comment le lexyque du js 
document.addEventListener("DOMContentLoaded", function() {
  const body = document.body;
  const toggleBtn = document.getElementById("toggleTheme");


  const moonIcon = "https://cdn-icons-png.flaticon.com/512/6714/6714978.png";
  const sunIcon = "https://cdn-icons-png.flaticon.com/512/869/869869.png";

  const savedTheme = localStorage.getItem("theme") || "dark";
  body.classList.add(savedTheme);

  toggleBtn.src = savedTheme === "dark" ? sunIcon : moonIcon;
  toggleBtn.alt = savedTheme === "dark" ? "Mode jour" : "Mode nuit";

  toggleBtn.addEventListener("click", () => {
    const newTheme = body.classList.contains("dark") ? "light" : "dark";
    body.classList.remove("dark", "light");
    body.classList.add(newTheme);
    localStorage.setItem("theme", newTheme);

    toggleBtn.src = newTheme === "dark" ? sunIcon : moonIcon;
    toggleBtn.alt = newTheme === "dark" ? "Mode jour" : "Mode nuit";
  });
});

</script>


</body>
</html>



    <?php
    // Si on a cliqué sur [Valider]
    if (isset($_POST["valider"]) && $_POST["valider"] == "Valider") {
        
        $nom_table = $_POST["choix"];
        

        // Requêtes SQL
        if ($nom_table == "PEUR")           
            {
            $query = $conn->query("SELECT * FROM PEUR ORDER BY nom_peur ASC;");
            $resultat_peur = $query->fetchAll();

            // Afficher le résultat dans un tableau
            print('<form method="post" action="index.php">
                    <label for="liste"> <b><u> Recherche:</u></b><br>
                    <select name="choix1" id="liste">');
            foreach ($resultat_peur as $key => $variable)
            
            {
                print("<option value='".$resultat_peur[$key]["id_peur"]."'>".$resultat_peur[$key]["nom_peur"]."</option>");
            }
            print('</select> <br>
                    <input type="submit" value="Ok" name="valider1"/>
                    </form>');
                
            print("<table border=2>");
            print("<tr>");
            print("<th>Nom</th>");
            print("<th>Theme</th>");
            print("<tr>");
            foreach ($resultat_peur as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_peur[$key]['nom_peur']."</td>");
                print("<td>".$resultat_peur[$key]['lieu']."</td>");
                print("<tr>");
            }
            print("</table>");
        
            
            
        }
        
                
            
        
    
        elseif ($nom_table == "REVE") 
            {
            $query = $conn->query("SELECT * FROM REVE ORDER BY style_visuel ASC;");
            $resultat_reve = $query->fetchAll();

            // Afficher le résultat dans un tableau

            print("<table border=5>");
            print("<tr>");
            print("<th>Score bizzarerie</th>");
            print("<th>Date du reve</th>");
            print("<th>Style Visuel</th>");
            print("<th>Temps dans la Réalité</th>");
            print("<th>Duree de ressenti</th>");
            print("<th>Interpretation</th>");
            print("<th>Note du reve</th>");
            print("<th>Commentaire du reveur</th>");
            print("<tr>");
            foreach ($resultat_reve as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_reve[$key]['score_bizzarerie']."</td>");
                print("<td>".$resultat_reve[$key]['date_reve']."</td>");
                print("<td>".$resultat_reve[$key]['style_visuel']."</td>");
                print("<td>".$resultat_reve[$key]['duree_reel']."</td>");
                print("<td>".$resultat_reve[$key]['duree_ressenti']."</td>");
                print("<td>".$resultat_reve[$key]['type_interpretation']."</td>");
                print("<td>".$resultat_reve[$key]['score']."</td>");
                print("<td>".$resultat_reve[$key]['commentaire']."</td>");
                print("<tr>");
            }
            print("</table>");
        }
        elseif ($nom_table == "REVEUR") 
            {
            $query = $conn->query("SELECT * FROM REVEUR ORDER BY nom ASC;");
            $resultat_reveur = $query->fetchAll();
           


            // Afficher le résultat dans un tableau

            print("<table border=5>");
            print("<tr>");
            print("<th>Nom</th>");
            print("<th>Prenom</th>");
            print("<th>Date de Naissance</th>");
            print("<th>Age mental</th>");
            print("<th>Genre</th>");
            print("<th>pays</th>");
            print("<th>Niveau de Peur</th>");
            print("<tr>"); 
            foreach ($resultat_reveur as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_reveur[$key]['nom']."</td>");
                print("<td>".$resultat_reveur[$key]['prenom']."</td>");
                print("<td>".$resultat_reveur[$key]['date_de_naissance']."</td>");
                print("<td>".$resultat_reveur[$key]['age_mental']."</td>");
                print("<td>".$resultat_reveur[$key]['genre_de_la_personne']."</td>");
                print("<td>".$resultat_reveur[$key]['pays']."</td>");
                print("<td>".$resultat_reveur[$key]['niveau_peur']."</td>");
                print("<tr>");
            }
            print("</table>");
        }
        elseif ($nom_table == "EMOTION") 
            {
            $query = $conn->query("SELECT * FROM EMOTION ORDER BY nom_emotion ASC;");
            $resultat_emotion = $query->fetchAll();

            // Afficher le résultat dans un tableau
            print('<form method="post" action="index.php">
                    <label for="liste"> <b><u> Recherche type de trouble de sante:</u></b><br>
                    <select name="choix1" id="liste">');
            foreach ($resultat_emotion as $key => $variable)
            {
                print("<option value='".$resultat_emotion[$key]["id_emotion"]."'>".$resultat_emotion[$key]["nom_emotion"]."</option>");
            }
            print('</select> <br>
                    <input type="submit" value="Ok" name="valider3"/>
                    </form>');

            print("<table border=5>");
            print("<tr>");
            print("<th>Emotion</th>");
            print("<tr>"); 
            foreach ($resultat_emotion as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_emotion[$key]['nom_emotion']."</td>");
                print("<tr>");
            }
            print("</table>");
        }
        elseif ($nom_table == "LANGUE") 
            {
            $query = $conn->query("SELECT * FROM LANGUE ORDER BY langue ASC;");
            $resultat_langue = $query->fetchAll();

            // Afficher le résultat dans un tableau
            print('<form method="post" action="index.php">
                    <label for="liste"> <b><u> Recherche de langue:</u></b><br>
                    <select name="choix1" id="liste">');
            foreach ($resultat_langue as $key => $variable)
            {
                print("<option value='".$resultat_langue[$key]["id_langue"]."'>".$resultat_langue[$key]["langue"]."</option>");
            }
            print('</select> <br>
                    <input type="submit" value="Ok" name="valider4"/>
                    </form>');

            print("<table border=5>");
            print("<tr>");
            print("<th>Langue</th>");
            print("<tr>"); 
            foreach ($resultat_langue as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_langue[$key]['langue']."</td>");
                print("<tr>");
            }
            print("</table>");
        }
        elseif ($nom_table == "LIEU") 
            {
            $query = $conn->query("SELECT * FROM LIEU ORDER BY nom_lieu ASC;");
            $resultat_lieu = $query->fetchAll();

            // Afficher le résultat dans un tableau

            print("<table border=5>");
            print("<tr>");
            print("<th>Type de lieu du reve</th>");
            print("<th>Nom du lieu</th>");
            print("<th>Taille du lieu</th>");
            print("<th>Niveau de diversité</th>");
            print("<tr>"); 
            foreach ($resultat_lieu as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_lieu[$key]['type_lieu']."</td>");
                print("<td>".$resultat_lieu[$key]['nom_lieu']."</td>");
                print("<td>".$resultat_lieu[$key]['taille_lieu']."</td>");
                print("<td>".$resultat_lieu[$key]['niveau_diversite']."</td>");
                print("<tr>");
            }
            print("</table>");
        }
        elseif ($nom_table == "PERSONNAGE_PRINCIPAL") 
            {
            $query = $conn->query("SELECT * FROM PERSONNAGE_PRINCIPAL ORDER BY type_de_personnage ASC;");
            $resultat_personnage = $query->fetchAll();

            // Afficher le résultat dans un tableau

            print("<table border=5>");
            print("<tr>");
            print("<th>Type de personnage</th>");
            print("<th>Nom du personnage</th>");
            print("<th>La relation avec le reveur</th>");
            print("<th>Personnage fictif</th>");
            print("<th>Personnage réel</th>");
            print("<tr>"); 
            foreach ($resultat_personnage as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_personnage[$key]['type_personnage']."</td>");
                print("<td>".$resultat_personnage[$key]['nom_personnage']."</td>");
                print("<td>".$resultat_personnage[$key]['relation_reveur']."</td>");
                print("<td>".$resultat_personnage[$key]['personnage_fictif']."</td>");
                print("<td>".$resultat_personnage[$key]['personnage_réel']."</td>");
                print("<tr>");
            }
            print("</table>");
        }
                elseif ($nom_table == "TROUBLE_DE_SANTE") 
            {
            $query = $conn->query("SELECT * FROM TROUBLE_DE_SANTE ORDER BY type_trouble_de_sante ASC;");
            $resultat_trouble = $query->fetchAll();

            // Afficher le résultat dans un tableau
            print('<form method="post" action="index.php">
                    <label for="liste"> <b><u> Recherche type de trouble de sante:</u></b><br>
                    <select name="choix1" id="liste">');
            foreach ($resultat_trouble as $key => $variable)
            {
                print("<option value='".$resultat_trouble[$key]["id_trouble_de_sante"]."'>".$resultat_trouble[$key]["nom"]."</option>");
            }
            print('</select> <br>
                    <input type="submit" value="Ok" name="valider2"/>
                    </form>');
        
            print("<table border=5>");
            print("<tr>");
            print("<th>Type de trouble</th>");
            print("<th>Nom du trouble</th>");
            print("<tr>"); 
            foreach ($resultat_trouble as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_trouble[$key]['type_trouble_de_sante']."</td>");
                print("<td>".$resultat_trouble[$key]['nom']."</td>");
                print("<tr>");
            }
            print("</table>");
            
        }
                elseif ($nom_table == "TYPE_ELEMENT_DANS_LE_REVE") 
            {
            $query = $conn->query("SELECT * FROM TYPE_ELEMENT_DANS_LE_REVE ORDER BY nom_element ASC;");
            $resultat_element = $query->fetchAll();

            // Afficher le résultat dans un tableau

            print("<table border=5>");
            print("<tr>");
            print("<th>Type d'éléments dans le rêve</th>");
            print("<th>Nom des éléments</th>");
            print("<th>Taille des éléments</th>");
            print("<th>Couleur principal</th>");
            print("<tr>"); 
            foreach ($resultat_element as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_element[$key]['type_element']."</td>");
                print("<td>".$resultat_element[$key]['nom_element']."</td>");
                print("<td>".$resultat_element[$key]['taille_element']."</td>");
                print("<td>".$resultat_element[$key]['couleur_element']."</td>");
                print("<tr>");
            }
            print("</table>");
        }
                elseif ($nom_table == "TYPE_REVE") 
            {
            $query = $conn->query("SELECT * FROM TYPE_REVE ORDER BY type_reve ASC;");
            $resultat_type_reve= $query->fetchAll();

            // Afficher le résultat dans un tableau

            print("<table border=5>");
            print("<tr>");
            print("<th>Type du rêve</th>");
            print("<th>Nom</th>");
            print("<tr>"); 
            foreach ($resultat_type_reve as $key => $variable)
            {
                print("<tr>");
                print("<td>".$resultat_type_reve[$key]['type_reve']."</td>");
                print("<td>".$resultat_type_reve[$key]['nom']."</td>");
                print("<tr>");
            }
            print("</table>");
        }
    }
        if (isset($_POST["valider1"]) && $_POST["valider1"] == "Ok")
                    {
                    echo "<br>Ok !<br>";
                    $numero1 = $_POST["choix1"];
                    $query = $conn->query("SELECT nom_peur, nom, prenom FROM REVEUR JOIN PEUR ON REVEUR.id_peur = PEUR.id_peur WHERE REVEUR.id_peur = ".$numero1.";");
                    $resultat_nom_prenom_peur = $query->fetchAll();
                    print("<table border=5>");
                    print("<tr>");
                    print("<th>La peur</th>");
                    print("<th>Nom</th>");
                    print("<th>Prenom</th>");
                    print("<tr>"); 
                    foreach ($resultat_nom_prenom_peur as $key => $variable)
                {
                    print("<tr>");
                    print("<td>".$resultat_nom_prenom_peur[$key]['nom_peur']."</td>");
                    print("<td>".$resultat_nom_prenom_peur[$key]['nom']."</td>");
                    print("<td>".$resultat_nom_prenom_peur[$key]['prenom']."</td>");
                    print("<tr>");
                }
                print("</table>");
                    }
        elseif (isset($_POST["valider2"]) && $_POST["valider2"] == "Ok")
                    {
                    echo "<br>Ok !<br>";
                    $numero2 = $_POST["choix1"];
                    $query = $conn->query("SELECT TROUBLE_DE_SANTE.nom AS trouble_nom, REVEUR.nom AS reveur_nom, REVEUR.prenom AS reveur_prenom 
                    FROM REVEUR 
                    JOIN LIAISON_TROUBLE_DE_SANTE ON REVEUR.id_reveur = LIAISON_TROUBLE_DE_SANTE.id_reveur 
                    JOIN TROUBLE_DE_SANTE ON LIAISON_TROUBLE_DE_SANTE.id_trouble_de_sante = TROUBLE_DE_SANTE.id_trouble_de_sante  
                    WHERE TROUBLE_DE_SANTE.id_trouble_de_sante ORDER BY reveur_nom ASC= ".$numero2.";");
                    $resultat_nom_prenom_trouble = $query->fetchAll();
                    print("<table border=5>");
                    print("<tr>");
                    print("<th>Nom du trouble</th>");
                    print("<th>Nom</th>");
                    print("<th>Prenom</th>");
                    print("<tr>"); 
                    foreach ($resultat_nom_prenom_trouble as $key => $variable)
                {
                    print("<tr>");
                    print("<td>".$resultat_nom_prenom_trouble[$key]['trouble_nom']."</td>");
                    print("<td>".$resultat_nom_prenom_trouble[$key]['reveur_nom']."</td>");
                    print("<td>".$resultat_nom_prenom_trouble[$key]['reveur_prenom']."</td>");
                    print("<tr>");
                }
                print("</table>");
            
                    }
                elseif (isset($_POST["valider3"]) && $_POST["valider3"] == "Ok")
                    {
                    echo "<br>Ok !<br>";
                    $numero3 = $_POST["choix1"];
                    $query = $conn->query("
                        SELECT EMOTION.nom_emotion, REVEUR.nom AS reveur_nom, REVEUR.prenom AS reveur_prenom
                        FROM REVE
                        JOIN LIAISON_EMOTION ON REVE.id_reve = LIAISON_EMOTION.id_reve
                        JOIN EMOTION ON LIAISON_EMOTION.id_emotion = EMOTION.id_emotion
                        JOIN REVEUR ON REVE.id_reveur = REVEUR.id_reveur
                        WHERE EMOTION.id_emotion = ".$numero3."
                    ");
                    $resultat_emotion = $query->fetchAll();

                    print("<table border=5>");
                    print("<tr>");
                    print("<th>Nom de l'émotion</th>");
                    print("<th>Nom du rêveur</th>");
                    print("<th>Prénom du rêveur</th>");
                    print("</tr>");

                    foreach ($resultat_emotion as $key => $variable) {
                        print("<tr>");
                        print("<td>".$resultat_emotion[$key]['nom_emotion']."</td>");
                        print("<td>".$resultat_emotion[$key]['reveur_nom']."</td>");
                        print("<td>".$resultat_emotion[$key]['reveur_prenom']."</td>");
                        print("</tr>");
                    }
                    print("</table>");
                }
                
                elseif (isset($_POST["valider4"]) && $_POST["valider4"] == "Ok")
                    {
                    echo "<br>Ok !<br>";
                    $numero4 = $_POST["choix1"];
                    $query = $conn->query("SELECT langue, nom, prenom FROM REVEUR JOIN LIAISON_LANGUE ON REVEUR.id_reveur = LIAISON_LANGUE.id_reveur JOIN LANGUE ON LIAISON_LANGUE.id_langue = LANGUE.id_langue  WHERE LANGUE.id_langue = ".$numero4.";");
                    $resultat_langue = $query->fetchAll();
                    print("<table border=5>");
                    print("<tr>");
                    print("<th>Langue de la personne</th>");
                    print("<th>Nom</th>");
                    print("<th>Prenom</th>");
                    print("<tr>"); 
                    foreach ($resultat_langue as $key => $variable)
                {
                    print("<tr>");
                    print("<td>".$resultat_langue[$key]['langue']."</td>");
                    print("<td>".$resultat_langue[$key]['nom']."</td>");
                    print("<td>".$resultat_langue[$key]['prenom']."</td>");
                    print("<tr>");
                }
                print("</table>");
                }
    
    
    ?>
        </body>
</html>

