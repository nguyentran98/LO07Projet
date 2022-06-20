<?php
require './assets.php';

require './authentification.php';
forcelog('player');

require './base_connexion.php';

require './sqlcommands.php';

$partie_id = null;
if (isset($_POST['partie_id'])) {
    $_SESSION['session_partie_id'] = trim(htmlspecialchars($_POST['partie_id']));

    // Redirection vers la page recap.php
    header('Location: recap.php');
    exit();
}

if (isset($partie_id) OR TRUE) {
    $requete = "SELECT jeu.IdJeu, jeu.DateTerminer, histoire_type.name "
            . "FROM jeu LEFT JOIN histoire_type ON jeu.histoire_type_id = histoire_type.id "
            . "WHERE IdUtilisateur = $session_idutilisateur "
            . "ORDER BY jeu.DateTerminer DESC";
    $liste_deroulante_parties = "<option value=\"null\" selected>---</option> \n";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        while ($ligne = mysqli_fetch_assoc($resultat)) {
            $id = $ligne['IdJeu'];
            $name = $ligne['name'];
            $DateTerminer = $ligne['DateTerminer'];
            $liste_deroulante_parties .= "<option value=\"$id\">$name ($DateTerminer)</option> \n";
        }
    }
}

// **********************************************
// Construction de la page HTML
require './header.php';
?>


<div class="user-permission">
    <h1>Récapitulation</h1>
    <form method="POST" action="">
        <label for="partie_id">Choix de la partie</label>
        <select name="partie_id" onchange="this.form.submit()">
            <?php
            echo $liste_deroulante_parties;
            ?>
        </select>
    </form>
</div>

<?php
// **********************************************
// Ajout pied de page HTML
require './footer.php';
?>