<?php
require './assets.php';

$history_type_id = null;
if (isset($_POST['history_type'])) {
    $history_type_id = trim(htmlspecialchars($_POST['history_type']));
}

require './authentification.php';
forcelog('modo');

require './base_connexion.php';

require './sqlcommands.php';

// Listage des types choisie de d'histoires
if (isset($history_type_id) OR TRUE) {
    $requete = "SELECT * FROM histoire_type";
    $liste_deroulante_history_type = "<option value=\"null\" selected>Nouveau type d'histoire</option> \n";
    $liste_deroulante_history_after = "<option value=\"null\" selected>---</option> \n";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        while ($ligne = mysqli_fetch_assoc($resultat)) {
            $id = $ligne['id'];
            $name = $ligne['name'];
            if ($id == $history_type_id) {
                $liste_deroulante_history_type .= "<option value=\"$id\" selected>$name</option> \n";
            } else {
                $liste_deroulante_history_type .= "<option value=\"$id\">$name</option> \n";
            }
        }
    }
    if (isset($history_type_id)) {
        $requete = "SELECT * FROM histoire WHERE histoire_type_id = $history_type_id";
        $resultat = sqlrequest($requete);
        if ($resultat) {
            while ($ligne = mysqli_fetch_assoc($resultat)) {
                $id = $ligne['id'];
                $text = $ligne['text'];
                $liste_deroulante_history_after .= "<option value=\"$id\">$text</option> \n";
            }
        }
    }
}

if (isset($_POST['add'])) {
    //***************************
    // Bouton "Ajouter" de valeur name="add"
    // Traitement du formulaire
    $newhistoiretype = trim(htmlspecialchars($_POST['histoiretype']));
    $text = trim(htmlspecialchars($_POST['text']));
    $textchoix1 = trim(htmlspecialchars($_POST['text_choix_1']));
    $choix1id = trim(htmlspecialchars($_POST['text_choix_1_id']));
    $textchoix2 = trim(htmlspecialchars($_POST['text_choix_2']));
    $choix2id = trim(htmlspecialchars($_POST['text_choix_2_id']));

    if (empty($text)) {
        $message_erreur .= "    Le champ phrase est obligatoire<br>\n";
    } elseif (strlen($text) > 255) {
        $message_erreur .= "    La phrase ne doit pas comporter plus de 255 caract??res<br>\n";
    }

    if (empty($textchoix1)) {
        $choix1id = "NULL";
    } elseif (strlen($textchoix1) > 255) {
        $message_erreur .= "    La phrase ne doit pas comporter plus de 255 caract??res<br>\n";
    }

    if (empty($textchoix2)) {
        $choix2id = "NULL";
    } elseif (strlen($textchoix2) > 255) {
        $message_erreur .= "    La phrase ne doit pas comporter plus de 255 caract??res<br>\n";
    }


    if (empty($message_erreur)) {
        if (!empty($newhistoiretype)) {
            $requete = "INSERT INTO histoire_type (name) VALUES ('$newhistoiretype')";
            sqlrequest($requete);

            $requete = "SELECT id FROM histoire_type WHERE name = '$newhistoiretype'";
            $resultat = sqlrequest($requete);
            if ($resultat) {
                $ligne = mysqli_fetch_assoc($resultat);
                $history_type_id = $ligne['id'];
            }
        }
        // Insertion phrase
        sqlrequest("INSERT INTO histoire (text, choix1_text, choix1_id, choix2_text, choix2_id, histoire_type_id) "
                . "VALUES ('$text', '$textchoix1', $choix1id, '$textchoix2', $choix2id, '$history_type_id');");

        // Mise ?? jour phrase de d??part
        if (!empty($newhistoiretype)) {
            $requete = "SELECT id FROM histoire WHERE text = '$text' ORDER BY id DESC";
            $resultat = sqlrequest($requete);
            if ($resultat) {
                $ligne = mysqli_fetch_assoc($resultat);
                $history_id = $ligne['id'];
            }

            $requete = "UPDATE histoire_type SET histoire_start_id = $history_id WHERE histoire_type.id = $history_type_id";
            sqlrequest($requete);
        }
    }
}


require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>

<div class="history">
    <h1>Ajouter une histoire</h1>
    <form method="POST" action="">
        <label for="history_type">Choix type d'histoire</label>
        <select name="history_type" onchange="this.form.submit()">
            <?php
            echo $liste_deroulante_history_type;
            ?>
        </select>
        <?php if (empty($history_type_id) OR $history_type_id == "null") { ?>
            <label for="edt-text">Nouvelle type histoire</label>
            <input type="text" id="edt-text" name="histoiretype" placeholder="..." value="" maxlength=255 required>
        <?php } ?>

        <label for="edt-text">Text</label>
        <input type="text" id="edt-text" name="text" placeholder="..." value="" maxlength=255 required>

        <div class="two-fields">
            <div class="field">
                <label for="edt-text_choix_1">Text choix 1</label>
                <input type="text" id="edt-text_choix_1" name="text_choix_1" placeholder="..." value="" maxlength=255>
            </div>
            <div class="field">
                <label for="edt-text_choix_1_id">Suite choix 1 text</label>
                <select name="text_choix_1_id">
                    <?php
                    echo $liste_deroulante_history_after;
                    ?>
                </select>
            </div>
        </div>

        <div class="two-fields">
            <div class="field">
                <label for="edt-text_choix_2">Text choix 2</label>
                <input type="text" id="edt-text_choix_2" name="text_choix_2" placeholder="..." value="" maxlength=255>
            </div>
            <div class="field">
                <label for="edt-text_choix_2_id">Suite choix 2 text</label>
                <select name="text_choix_2_id">
                    <?php
                    echo $liste_deroulante_history_after;
                    ?>
                </select>
            </div>
        </div>
        <button style="width:100px;" type="submit" class="btn btn-primary btn-block btn-large" name="add">Ajouter</button>
    </form>
</div>
<!-- **************************************** -->     
<?php
require './footer.php';
?>
