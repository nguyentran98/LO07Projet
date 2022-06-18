<?php
require './assets.php';

require './authentification.php';
forcelog('player');

$session_partie_id = $_SESSION['session_partie_id'];
$_SESSION['session_partie_id'] = null;

require './base_connexion.php';

require './sqlcommands.php';

$recap = "";
if ($session_partie_id != NULL) {
    $requete = "SELECT play_logs.id, play_logs.histoire_id, play_logs.choix_id, histoire.text, histoire.choix1_text, histoire.choix1_id, histoire.choix2_text, histoire.choix2_id "
            . "FROM play_logs LEFT JOIN histoire ON play_logs.histoire_id = histoire.id "
            . "WHERE jeu_IdJeu = $session_partie_id "
            . "ORDER BY id ASC";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        while ($ligne = mysqli_fetch_assoc($resultat)) {
            $histoire_id = $ligne['histoire_id'];
            $choix_id = $ligne['choix_id'];
            $histoire_text = $ligne['text'];

            $recap .= "<h1>$histoire_text";

            if ($choix_id != NULL) {
                $choix1_id = $ligne['choix1_id'];
                $choix2_id = $ligne['choix2_id'];
                $choix1_text = $ligne['choix1_text'];
                $choix2_text = $ligne['choix2_text'];

                if ($choix_id == $choix1_id) {
                    $recap .= "<br> choix : $choix1_text ";
                } elseif ($choix_id == $choix2_id) {
                    $recap .= "<br> choix : $choix2_text ";
                } else {
                    $recap .= "<br> error not expected ";
                }

                // Satistique
                $erequete = "SELECT id FROM play_logs WHERE histoire_id = $histoire_id AND choix_id = $choix_id";
                $eresultat = sqlrequest($erequete);
                if ($eresultat) {
                    $nbsamechoice = mysqli_num_rows($eresultat);
                    $erequete = "SELECT id FROM play_logs WHERE histoire_id = $histoire_id";
                    $eresultat = sqlrequest($erequete);
                    if ($eresultat) {
                        $nbtotal = mysqli_num_rows($eresultat);
                    }
                }
                $pourcent = ($nbsamechoice / $nbtotal) * 100;
                $recap .= "($pourcent%)";
            }

            $recap .= "</h1>";
            $recap .= "<div class=\"ui divider\"></div>";
        }
    }
} else {
    $recap .= "<h1>Pas de récap déso :p</h1>";
}

require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>

<div class="play">
    <h1>Recap de ta partie</h1>
    <div class="ui divider"></div>
    <?php echo $recap; ?>
</div>


<?php
require './footer.php';
?>