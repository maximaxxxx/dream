










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

$nom_page = basename($_SERVER['PHP_SELF']); // on recupere le nom de la page qu'on va charger

try { //on check si la page existe dans la base de donné PAGE
    $stmt = $conn->prepare("SELECT 1 FROM PAGE WHERE nom_page = :nom_page LIMIT 1");
    $stmt->execute(['nom_page' => $nom_page]);
    $page_existe = $stmt->fetch();
} catch (PDOException $e) {
    die("<h1>Erreur lors de la vérification de la page</h1><p>{$e->getMessage()}</p>");
}

if (!$page_existe) { //si la page n'existe pas, on empeche le chargement du reste de la page et on envoie un msg d'erreur
    header("HTTP/1.1 403 Forbidden");
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Accès refusé</title></head><body>";
    echo "<h1>🚫 Accès refusé</h1>";
    echo "<p>Cette page n'est pas enregistrée dans la base de données.</p>";
    echo "</body></html>";
    exit; 
}

?>







<?php
//index
?>





<form id="menuForm" method="post" action="index.php"> 
  <label for="liste">Liste info <br/> <!-- ici, on definit la liste avec du brute force-->
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














<script> //ici c'est le js qui va notemment nous permettre de coder les deux modes (jour et nuit), ainsi
document.addEventListener("DOMContentLoaded", function() {
  const body = document.body;
  const toggleBtn = document.getElementById("toggleTheme");


  const moonIcon = "https://cdn-icons-png.flaticon.com/512/6714/6714978.png";
  const sunIcon = "https://cdn-icons-png.flaticon.com/512/869/869869.png";//on charge les ciones

  const savedTheme = localStorage.getItem("theme") || "dark"; //mode dark de base, mais on check si l'utilisateur a pas deja fait un choix
  body.classList.add(savedTheme);

  toggleBtn.src = savedTheme === "dark" ? sunIcon : moonIcon; //on charge le bon icone en fonction du theme choisi
  toggleBtn.alt = savedTheme === "dark" ? "Mode jour" : "Mode nuit";

  toggleBtn.addEventListener("click", () => { //gestion du click sur le bouton, qui nous fais changer de theme
    const newTheme = body.classList.contains("dark") ? "light" : "dark";
    body.classList.remove("dark", "light");
    body.classList.add(newTheme);
    localStorage.setItem("theme", newTheme);

    toggleBtn.src = newTheme === "dark" ? sunIcon : moonIcon;
    toggleBtn.alt = newTheme === "dark" ? "Mode jour" : "Mode nuit";
  });
});

</script>








<?php
if ($nom_table == "EMOTION") 
            {
                //requete sql
            $query = $conn->query("SELECT * FROM EMOTION ORDER BY nom_emotion ASC;");
            $resultat_emotion = $query->fetchAll();

            //option pouyr rechercher toute les personnes avec les dit troubles
            print('<form method="post" action="index.php">
                    <label for="liste"> <b><u> Recherche type de trouble de sante:</u></b><br>
                    <select name="choix1" id="liste">');
            foreach ($resultat_emotion as $key => $variable)//ici, on crée la liste pour le nouveau formulaire
            {
                print("<option value='".$resultat_emotion[$key]["id_emotion"]."'>".$resultat_emotion[$key]["nom_emotion"]."</option>");
            }
            print('</select> <br>
                    <input type="submit" value="Ok" name="valider3"/>
                    </form>');

            print("<table border=5>");//on affiche les colonnes du tableau
            print("<tr>");
            print("<th>Emotion</th>");
            print("<tr>"); 
            foreach ($resultat_emotion as $key => $variable)//boucle pour afficher les donné, car cette fois
            {
                print("<tr>");
                print("<td>".$resultat_emotion[$key]['nom_emotion']."</td>");
                print("<tr>");
            }
            print("</table>");
        }








elseif (isset($_POST["valider2"]) && $_POST["valider2"] == "Ok")
                    {
                    echo "<br>Ok !<br>"; 
                    $numero2 = $_POST["choix1"]; //représente l’ID d’un trouble de santé choisi par l’utilisateur.
                    $query = $conn->query("SELECT TROUBLE_DE_SANTE.nom AS trouble_nom, REVEUR.nom AS reveur_nom, REVEUR.prenom AS reveur_prenom 
                    FROM REVEUR 
                    JOIN LIAISON_TROUBLE_DE_SANTE ON REVEUR.id_reveur = LIAISON_TROUBLE_DE_SANTE.id_reveur 
                    JOIN TROUBLE_DE_SANTE ON LIAISON_TROUBLE_DE_SANTE.id_trouble_de_sante = TROUBLE_DE_SANTE.id_trouble_de_sante  
                    WHERE TROUBLE_DE_SANTE.id_trouble_de_sante ORDER BY reveur_nom ASC= ".$numero2.";");//on a fait les jointure pour recup les don,né neccessaire au noueau table
                    $resultat_nom_prenom_trouble = $query->fetchAll();//on affiche les colonne du tableau 
                    print("<table border=5>");
                    print("<tr>");
                    print("<th>Nom du trouble</th>");
                    print("<th>Nom</th>");
                    print("<th>Prenom</th>");
                    print("<tr>"); 
                    foreach ($resultat_nom_prenom_trouble as $key => $variable) //on met ici la boucles qui va afficher les donnés
                {
                    print("<tr>");
                    print("<td>".$resultat_nom_prenom_trouble[$key]['trouble_nom']."</td>");
                    print("<td>".$resultat_nom_prenom_trouble[$key]['reveur_nom']."</td>");
                    print("<td>".$resultat_nom_prenom_trouble[$key]['reveur_prenom']."</td>");
                    print("<tr>");
                }
                print("</table>");
                    }










//dashboard.php





// Vérifier si l'utilisateur est connecté, si 
if (!isset($_SESSION['identifiant'])) {
    header('Location: connexion.php');
    exit;
}



// Tables disponibles
$tables = ['EMOTION', 'LANGUE', 'LIAISON_ELEMENT', 'LIAISON_EMOTION', 'LIAISON_LANGUE', 'LIAISON_TROUBLE_DE_SANTE', 'LIEU', 'PERSONNAGE_PRINCIPAL','PAGE', 'PEUR', 'REVE', 'REVEUR', 'TROUBLE_DE_SANTE', 'TYPE_ELEMENT_DANS_LE_REVE', 'TYPE_REVE', 'UTILISATEUR'];
$selectedTable = $_GET['table'] ?? $tables[0];


        <!-- Sélecteur de table -->
        <div class="table-select">
            <form method="GET">
                <label for="table"></label><!--Choisir une table -->
                <select name="table" id="table" onchange="this.form.submit()">//le onchange fait que y a pas besoin de bouton valider
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
            <?php foreach($columns as $col): ?>// pour chaque element, on met une petite case pour pouvoir rentrer la donné
                <input type="text" name="columns[<?= $col ?>]" placeholder="<?= $col ?>">
            <?php endforeach; ?>
            <button type="submit" class="inscription">Ajouter</button>//bouton valider
        </form>







        <!-- Tableau -->
        <table>
            <thead>
                <tr>
                    <?php foreach($columns as $col): ?><!-- on creer les collonnes -->
                        <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th><!-- on rajoute une colonne pour le bouton supprimer -->
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                    <tr>
                        <?php foreach($columns as $col): ?> <!-- on met les donnee dans le tableau -->
                            <td><?= htmlspecialchars($row[$col]) ?></td>
                        <?php endforeach; ?>
                        <td><!-- on creer un formulaire invisible pour ppouvoir supprimer chaque donné -->
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











<?php
//site_sql.css
?>








<style>





/* --- Thème sombre (dark mode) --- */
      body.dark {
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);  /* dégradé sombre bleu-gris */
        color: #f0e6f6; /* texte clair */
      }

/* --- Effet d’étoiles dans le fond en mode sombre --- */
      body.dark::before {
        content: '';
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
/* plusieurs petits points blancs pour simuler des étoiles */
        background:
          radial-gradient(2px 2px at 20% 30%, #fff, transparent),
          radial-gradient(2px 2px at 50% 10%, #fff, transparent),
          radial-gradient(2px 2px at 80% 25%, #fff, transparent),
          radial-gradient(2px 2px at 10% 60%, #fff, transparent),
          radial-gradient(2px 2px at 60% 80%, #fff, transparent),
          radial-gradient(2px 2px at 90% 70%, #fff, transparent);
      }</style>