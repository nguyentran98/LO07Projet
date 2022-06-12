<?php
require './assets.php';

$nbutilisateur = "";
$nbconnexionday = "";
$nbconnexiontotal = "";
$nbpartieday = "";
$nbpartietotal = "";

require './authentification.php';
forcelog('modo');

require './base_connexion.php';

require './sqlcommands.php';

// Récupération du nombre d'utilisateur
$requete = "SELECT IdUtilisateur FROM utilisateur;";
$resultat = sqlrequest($requete);
if ($resultat) {
    $nbutilisateur = mysqli_num_rows($resultat);
}

// Récupération du nombre de connexions aujourd'hui
$requete = "SELECT * FROM connexion_logs WHERE datetime >= CURDATE()
  AND datetime < CURDATE() + INTERVAL 1 DAY";
$resultat = sqlrequest($requete);
if ($resultat) {
    $nbconnexionday = mysqli_num_rows($resultat);
}

// Récupération du nombre de connexions total
$requete = "SELECT id FROM connexion_logs;";
$resultat = sqlrequest($requete);
if ($resultat) {
    $nbconnexiontotal = mysqli_num_rows($resultat);
}

// Récupération du nombre de partie joué aujourd'hui
$requete = "SELECT * FROM play_logs WHERE datetime >= CURDATE()
  AND datetime < CURDATE() + INTERVAL 1 DAY";
$resultat = sqlrequest($requete);
if ($resultat) {
    $nbpartieday = mysqli_num_rows($resultat);
}

// Récupération du nombre de partie joué total
$requete = "SELECT id FROM play_logs;";
$resultat = sqlrequest($requete);
if ($resultat) {
    $nbpartietotal = mysqli_num_rows($resultat);
}

// 
if (isset($_POST['add'])) {
    //***************************
    // Bouton "Ajouter" de valeur name="add"
    // Traitement du formulaire
    $phrase = trim(htmlspecialchars($_POST['phrase']));

    if (empty($phrase)) {
        $message_erreur .= "    Le champ phrase est obligatoire<br>\n";
    } elseif (strlen($phrase) > 255) {
        $message_erreur .= "    La phrase ne doit pas comporter plus de 255 caractères<br>\n";
    }


    $requete = "select * from histoire where phrase = '$phrase'";
    if (!sfwifexist($requete)) {
        $requete = "INSERT INTO histoire (phrase) VALUES ('$phrase');";
        if (sqlrequest($requete)) {
            $message .= "Insertion de <strong>$phrase</strong> réussi <br>";
        } else {
            $message_erreur .= "Insertion de <strong>$phrase</strong> à échoué <br>";
        }
    }
}

require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>

<div class="ui segment">
    <a class="ui button" href="gestion_utilisateurs.php"> Gestion utilisateurs </a>
    <a class="ui button" href="gestion_histoires.php"> Gestion histoires </a>
</div>      
<div class="ui segment">
    <p> Nombres d'utilisateurs : <?php echo $nbutilisateur ?> </p>
    <p> Nombres de connexions aujourd'hui : <?php echo $nbconnexionday ?> </p>
    <p> Nombres de connexions total : <?php echo $nbconnexiontotal ?> </p>
    <p> Nombres de partie(s) jouée(s) aujourd'hui : <?php echo $nbpartieday ?> </p>
    <p> Nombres de partie(s) jouée(s) total : <?php echo $nbpartietotal ?> </p>
</div>
<!-- **************************************** -->     
<?php
require './footer.php';
?>
