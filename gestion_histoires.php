<?php
require './assets.php';

require './authentification.php';
forcelog();

require './base_connexion.php';

require './sqlcommands.php';

function sfwifexist($requetesfw) {
    global $message_erreur;

    $resultat = sqlrequest($requetesfw);

    $nbligne = mysqli_num_rows($resultat);
    if ($nbligne != 0) {
        $message_erreur .= "La phrase existe déjà <br>";
        return true;
    }
}

// Connexion à la base de données cuicui du serveur localhost

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
    <h1 class="ui header">Ajouter une phrase</h1>
    <div class="ui divider"></div>
    <form class="ui form" action="pannel.php" method="POST">
        <div class="field">
            <label>Table</label>
            <select class="ui dropdown" name="id_destinataire">
                <option value=0>Phrase</option>
            </select>
        </div>
        <div class="ui field">
            <label>Phrase</label>
            <textarea rows="1" name="phrase"></textarea>
        </div>
        <button class="ui button" type="submit" name="add"> Ajouter </button>
    </form>
</div>                
<!-- **************************************** -->     
<?php
require './footer.php';
?>
