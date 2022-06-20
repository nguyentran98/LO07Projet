<?php
require './assets.php';

$nbutilisateur = "";
$nbconnexionday = "";
$nbconnexiontotal = "";
$nbpartieday = "";
$nbpartietotal = "";
$nbpartieauj = "";

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

// Récupération du nombre de partie joué total
$requete = "SELECT IdJeu FROM jeu WHERE DateTerminer >= CURDATE() AND DateTerminer < CURDATE() + INTERVAL 1 DAY";
$resultat = sqlrequest($requete);
if ($resultat) {
    $nbpartieauj = mysqli_num_rows($resultat);
}


// Récupération du nombre de partie joué total
$requete = "SELECT IdJeu FROM jeu;";
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

<div class="pannel">
    <div class="pannel-btn-container">
        <a class="btn btn-primary btn-block btn-large" style="width:180px; font-size: 18px; padding:10px" href="gestion_utilisateurs.php"> Gestion utilisateurs </a>
        <a class="btn btn-primary btn-block btn-large" style="width:180px; font-size: 18px; padding:10px" href="add_histoires.php"> Ajouter histoire </a>
    </div>
    <div class="pannel-btn-container">
        <a class="btn btn-primary btn-block btn-large" style="width:180px; font-size: 18px; padding:10px" href="modify_histoires.php"> Modifier histoire </a>
        <a class="btn btn-primary btn-block btn-large" style="width:180px; font-size: 18px; padding:10px" href="allgames.php"> Récapitulatif partie tous les joueurs</a>
    </div>
    <h4 class="ui header">Nombres d'utilisateurs : <?php echo $nbutilisateur ?> </h4>
    <h4 class="ui header">Nombres de connexions aujourd'hui : <?php echo $nbconnexionday ?></h4>
    <h4 class="ui header">Nombres de connexions total : <?php echo $nbconnexiontotal ?></h4>
    <h4 class="ui header">Nombres de partie(s) jouée(s) aujourd'hui : <?php echo $nbpartieauj ?></h4>
    <h4 class="ui header">Nombres de partie(s) jouée(s) total : <?php echo $nbpartietotal ?></h4>
</div>
<!-- **************************************** -->
<?php
require './footer.php';
?>
