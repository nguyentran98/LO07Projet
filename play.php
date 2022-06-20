<?php
require './assets.php';

$text = "";
$choix1text = "";
$choix1id = null;
$choix2text = "";
$choix2id = null;
$finjeu = false;

require './authentification.php';
forcelog('player');

require './base_connexion.php';

require './sqlcommands.php';

// Fin de partie
if (isset($_POST['findepartie'])) {
    $session_partie_id = $_SESSION['session_partie_id'];

    $requete = "UPDATE jeu SET DateTerminer = current_timestamp() WHERE jeu.IdJeu = $session_partie_id";
    sqlrequest($requete);

    $_SESSION['session_choix1id'] = null;
    $_SESSION['session_choix2id'] = null;
    header('Location: recap.php');
    exit();
}

// Initialisation de la partie avec sécurité contre refresh page
if (isset($_POST['history_type']) AND!isset($_SESSION['session_partie_id'])) {
    $history_type_id = trim(htmlspecialchars($_POST['history_type']));

    // Création d'un partie
    $requete = "INSERT INTO jeu (DateCommencer, IdUtilisateur, histoire_type_id) VALUES (current_timestamp(), $session_idutilisateur, $history_type_id)";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        $requete = "SELECT jeu.IdJeu, histoire_type.histoire_start_id FROM jeu LEFT JOIN histoire_type ON jeu.histoire_type_id = histoire_type.id WHERE jeu.IdUtilisateur = $session_idutilisateur AND jeu.DateTerminer IS NULL";
        $resultat = sqlrequest($requete);
        if ($resultat) {
            $ligne = mysqli_fetch_assoc($resultat);
            $session_partie_id = $_SESSION['session_partie_id'] = $ligne['IdJeu'];
            $histoire_start_id = $ligne['histoire_start_id'];

            // Création d'un play_logs
            $requete = "INSERT INTO play_logs (datetime, jeu_IdJeu, histoire_id) VALUES (current_timestamp(), $session_partie_id, $histoire_start_id)";
            sqlrequest($requete);
        }
    }
}

// Récupération de la partie en cours sinon choix de l'histoire
if (isset($_SESSION['session_partie_id'])) {
    $session_partie_id = $_SESSION['session_partie_id'];
} else {
    // Vérification si une partie n'est pas déjà commencé
    $requete = "SELECT IdJeu FROM jeu WHERE IdUtilisateur = $session_idutilisateur AND DateTerminer IS NULL";
    $resultat = sqlrequest($requete);
    $nbligne = mysqli_num_rows($resultat);
    if ($nbligne == 0) {
        // Affichage des choix de jeu possible
        $requete = "SELECT * FROM histoire_type";
        $liste_deroulante_history_type = "<option value=\"null\" selected>---</option> \n";
        $resultat = sqlrequest($requete);
        if ($resultat) {
            while ($ligne = mysqli_fetch_assoc($resultat)) {
                $id = $ligne['id'];
                $name = $ligne['name'];
                $liste_deroulante_history_type .= "<option value=\"$id\">$name</option> \n";
            }
        }
    } else {
        $ligne = mysqli_fetch_assoc($resultat);
        $_SESSION['session_partie_id'] = $ligne['IdJeu'];
        $session_partie_id = $_SESSION['session_partie_id'];
    }
}

// Traitement des boutons choix
if (isset($_POST['choix1']) OR isset($_POST['choix2'])) {
    if (isset($_POST['choix1'])) {
        $histoire_id = $_SESSION['session_choix1id'];
    } elseif (isset($_POST['choix2'])) {
        $histoire_id = $_SESSION['session_choix2id'];
    }

    $requete = "SELECT id FROM play_logs WHERE jeu_IdJeu = $session_partie_id AND choix_id IS NULL";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        $ligne = mysqli_fetch_assoc($resultat);
        $play_logs_id = $ligne['id'];

        $requete = "UPDATE play_logs SET choix_id = $histoire_id WHERE play_logs.id = $play_logs_id";
        sqlrequest($requete);

        $requete = "INSERT INTO play_logs (datetime, jeu_IdJeu, histoire_id) VALUES (current_timestamp(), $session_partie_id, $histoire_id)";
        sqlrequest($requete);
    }
}
// Récupération des infos de la dernière manche
if (isset($session_partie_id)) {
    $requete = "SELECT histoire_id FROM play_logs WHERE jeu_IdJeu = $session_partie_id ORDER BY datetime DESC LIMIT 1";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        $ligne = mysqli_fetch_assoc($resultat);
        $histoire_id = $ligne['histoire_id'];
    }

    $requete = "SELECT * FROM histoire WHERE id = $histoire_id";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        $ligne = mysqli_fetch_assoc($resultat);
        $text = $ligne['text'];
        $choix1text = $ligne['choix1_text'];
        $choix1id = $_SESSION['session_choix1id'] = $ligne['choix1_id'];
        $choix2text = $ligne['choix2_text'];
        $choix2id = $_SESSION['session_choix2id'] = $ligne['choix2_id'];

        
        // Correcion bug
        if (empty($choix1text)and !empty($choix1id)) {
            $choix1text = "not set";
        }
        if (empty($choix2text) and !empty($choix2id)) {
            $choix2text = "not set";
        }
        
        // Vérifie si c'est la dernière partie de l'histoire
        if (empty($choix1id) and empty($choix2id)) {
            $finjeu = true;
        }
    }
}

require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>

<?php if (!isset($session_partie_id)) {
    ?>
    <div class="play">
        <h1>Choisie ton histoire !</h1>
        <form method="POST" action="play.php">
            <label for="history_type">Choix categorie</label>
            <select name="history_type" onchange="this.form.submit()">
                <?php
                echo $liste_deroulante_history_type;
                ?>
            </select>
        </form>
    </div>
    <?php
}
?>


<?php if (isset($session_partie_id)) {
    ?>
    <div class="play">
        <h1><?php echo $text; ?></h1>
        <form method="POST" class="play-form" action="play.php">
            <?php if (isset($choix1text) AND isset($choix1id) AND!$finjeu) { ?>
                <button class="btn btn-primary btn-block btn-large" style="width:45%; margin-top:10px; font-size:20px;" type="submit" name="choix1"> <?php echo $choix1text; ?> </button>
            <?php } if (isset($choix2text) AND isset($choix2id) AND!$finjeu) { ?>
                <button class="btn btn-primary btn-block btn-large" style="width:45%; margin-top:10px; font-size:20px;" type="submit" name="choix2"> <?php echo $choix2text; ?> </button>
            <?php } if ($finjeu) { ?>
                <button class="btn btn-primary btn-block btn-large" style="width:45%; margin-top:10px; font-size:20px;" type="submit" name="findepartie"> Finir la partie </button>
            <?php } ?>
        </form>
    </div>
    <?php
}
?>
<?php
require './footer.php';
?>