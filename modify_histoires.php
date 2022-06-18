<?php
require './assets.php';

$history_id = null;
if (isset($_POST['history_type'])) {
    $history_id = trim(htmlspecialchars($_POST['history_type']));
}

$liste_deroulante_history_after1 = "<option value=\"null\" selected>---</option> \n";
$liste_deroulante_history_after2 = "<option value=\"null\" selected>---</option> \n";
$textchoix1 = "";
$textchoix2 = "";

require './authentification.php';
forcelog('modo');

require './base_connexion.php';

require './sqlcommands.php';

// Listage des types choisie de d'histoires
if (isset($history_id) OR TRUE) {
    $requete = "SELECT histoire.*, histoire_type.name  FROM histoire LEFT JOIN histoire_type ON histoire.histoire_type_id = histoire_type.id";
    $liste_deroulante_history_type = "<option value=\"null\" selected>---</option> \n";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        while ($ligne = mysqli_fetch_assoc($resultat)) {
            $id = $ligne['id'];
            $text = $ligne['text'];
            $name = $ligne['name'];
            $histoire_type_id = $ligne['histoire_type_id'];
            if ($id == $history_id) {
                $liste_deroulante_history_type .= "<option value=\"$id\" selected>($name) $text</option> \n";
                $textchoix1 = $ligne['choix1_text'];
                $choix1id = $ligne['choix1_id'];
                $textchoix2 = $ligne['choix2_text'];
                $choix2id = $ligne['choix2_id'];
            } else {
                $liste_deroulante_history_type .= "<option value=\"$id\">($name) $text</option> \n";
            }
        }
    }
    if (isset($history_id)) {
        $requete = "SELECT * FROM histoire WHERE histoire_type_id = $histoire_type_id";
        $resultat = sqlrequest($requete);
        if ($resultat) {
            while ($ligne = mysqli_fetch_assoc($resultat)) {
                $id = $ligne['id'];
                $text = $ligne['text'];
                if ($id == $choix1id) {
                    $liste_deroulante_history_after1 .= "<option value=\"$id\" selected>$text</option> \n";
                } elseif ($id == $choix2id) {
                    $liste_deroulante_history_after2 .= "<option value=\"$id\" selected>$text</option> \n";
                } else {
                    $liste_deroulante_history_after1 .= "<option value=\"$id\">$text</option> \n";
                    $liste_deroulante_history_after2 .= "<option value=\"$id\">$text</option> \n";
                }
            }
        }
    }
}

if (isset($_POST['add'])) {
    //***************************
    // Bouton "Ajouter" de valeur name="add"
    // Traitement du formulaire
    $text = trim(htmlspecialchars($_POST['text']));
    $textchoix1 = trim(htmlspecialchars($_POST['text_choix_1']));
    $choix1id = trim(htmlspecialchars($_POST['text_choix_1_id']));
    $textchoix2 = trim(htmlspecialchars($_POST['text_choix_2']));
    $choix2id = trim(htmlspecialchars($_POST['text_choix_2_id']));

    if (empty($text)) {
        $message_erreur .= "    Le champ phrase est obligatoire<br>\n";
    } elseif (strlen($text) > 255) {
        $message_erreur .= "    La phrase ne doit pas comporter plus de 255 caractères<br>\n";
    }

    if (empty($textchoix1)) {
        $choix1id = "NULL";
    } elseif (strlen($textchoix1) > 255) {
        $message_erreur .= "    La phrase ne doit pas comporter plus de 255 caractères<br>\n";
    }

    if (empty($textchoix2)) {
        $choix2id = "NULL";
    } elseif (strlen($textchoix2) > 255) {
        $message_erreur .= "    La phrase ne doit pas comporter plus de 255 caractères<br>\n";
    }


    if (empty($message_erreur)) {
        // Modification
        $requete = "UPDATE histoire "
                . "SET text = '$text', choix1_text = '$textchoix1', choix1_id = $choix1id, choix2_text = '$textchoix2', choix2_id = $choix2id "
                . "WHERE histoire.id = $history_id";
        sqlrequest($requete);
    }
}


require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>

<div class="history">
    <h1>Modifier une histoire</h1>
    <form method="POST" action="">
        <label for="history_type">Choix type d'histoire</label>
        <select name="history_type" onchange="this.form.submit()">
            <?php
            echo $liste_deroulante_history_type;
            ?>
        </select>

        <label for="edt-text">Text</label>
        <input type="text" id="edt-text" name="text" placeholder="truc " value="<?php echo $text ?>" maxlength=255 required>

        <div class="two-fields">
            <div class="field">
                <label for="edt-text_choix_1">Text choix 1</label>
                <input type="text" id="edt-text_choix_1" name="text_choix_1" placeholder="truc " value="<?php echo $textchoix1 ?>" maxlength=255>
            </div>
            <div class="field">
                <label for="edt-text_choix_1_id">Suite choix 1 text</label>
                <select name="text_choix_1_id">
                    <?php
                    if (isset($_POST['history_type'])) {
                        echo $liste_deroulante_history_after1;
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="two-fields">
            <div class="field">
                <label for="edt-text_choix_2">Text choix 2</label>
                <input type="text" id="edt-text_choix_2" name="text_choix_2" placeholder="truc " value="<?php echo $textchoix2 ?>" maxlength=255>
            </div>
            <div class="field">
                <label for="edt-text_choix_2_id">Suite choix 2 text</label>
                <select name="text_choix_2_id">
                    <?php
                    if (isset($_POST['history_type'])) {
                        echo $liste_deroulante_history_after2;
                    }
                    ?>
                </select>
            </div>
        </div>
        <button style="width:100px;" type="submit" class="btn btn-primary btn-block btn-large" name="add">Modifier</button>
    </form>
</div>
<!-- **************************************** -->     
<?php
require './footer.php';
?>
