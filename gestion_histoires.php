<?php
require './assets.php';

$history_type_id = null;
if (isset($_POST['history_type'])) {
    $history_type_id = trim(htmlspecialchars($_POST['history_type']));
}

$history_cat_id = null;
if (isset($_POST['history_cat'])) {
    $history_cat_id = trim(htmlspecialchars($_POST['history_cat']));
}

require './authentification.php';
forcelog('admin');

require './base_connexion.php';

require './sqlcommands.php';

// Listage des types choisie de d'histoires
if (isset($history_type_id) OR TRUE) {
    $requete = "SELECT * FROM histoire_type";
    $liste_deroulante_history_type = "<option value=\"null\" selected>---</option> \n";
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
        global $liste_deroulante_history_after;
        global $history_type_id;
        $liste_deroulante_history_after = "";
        $requete = "SELECT * FROM histoire WHERE category_tag = 'end' OR category_tag = 'middle' AND histoire_type_id = $history_type_id";
        $resultat = sqlrequest($requete);
        if ($resultat) {
            while ($ligne = mysqli_fetch_assoc($resultat)) {
                $id = $ligne['id'];
                $text = $ligne['text'];
                $liste_deroulante_history_after .= "<option value=\"$id\" selected>$text</option> \n";
            }
        }
    }
}

// Listage est maintien de la categorie séléctionnée
if (isset($history_cat_id) OR TRUE) {
    $liste_deroulante_category = "<option value=\"null\" selected>---</option> \n";
    $requete = "SELECT * FROM category";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        while ($ligne = mysqli_fetch_assoc($resultat)) {
            $tag = $ligne['tag'];
            if ($tag == $history_cat_id) {
                $liste_deroulante_category .= "<option value=\"$tag\" selected>$tag</option> \n";
            } else {
                $liste_deroulante_category .= "<option value=\"$tag\">$tag</option> \n";
            }
        }
    }
}

// Connexion à la base de données cuicui du serveur localhost

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
    } elseif (strlen($phrase) > 255) {
        $message_erreur .= "    La phrase ne doit pas comporter plus de 255 caractères<br>\n";
    }

    if (!isset($history_cat_id)) {
        $message_erreur .= "    Le choix de la categorie est pas obligatoire<br>\n";
    }

    if (empty($message_erreur)) {
        // Insertion phrase
        sqlrequest("INSERT INTO histoire (text, choix1_text, choix1_id, choix2_text, choix2_id, category_tag, histoire_type_id) "
                . "VALUES ('$text', '$textchoix1', $choix1id, '$textchoix2', $choix2id, '$history_cat_id', '$history_type_id');");
    }
}


require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>

<div class="ui segment">
    <h1 class="ui header">Ajouter une histoire</h1>
    <div class="ui divider"></div>
    <form class="ui form" method="POST" action="">
        <div class="field">
            <label for="history_type">Choix type d'histoire</label>
            <select name="history_type" onchange="this.form.submit()">
                <?php
                echo $liste_deroulante_history_type;
                ?>
            </select>
        </div>
        <div class="field">
            <label for="history_cat">Choix categorie</label>
            <select name="history_cat" onchange="this.form.submit()">
                <?php
                echo $liste_deroulante_category;
                ?>
            </select>
        </div>
        <div class="field">
            <label for="edt-text">Text</label>
            <input type="text" id="edt-text" name="text" placeholder="truc " value="" maxlength=255 required>
        </div> 
        <div class="two fields">
            <div class="field">
                <label for="edt-text_choix_1">Text choix 1</label>
                <input type="text" id="edt-text_choix_1" name="text_choix_1" placeholder="truc " value="" maxlength=255 required>
            </div>  
            <div class="field">
                <label for="edt-text_choix_1_id">Suite choix 1 text</label>
                <select name="text_choix_1_id" onchange="this.form.submit()">
                    <?php
                    if (isset($_POST['history_type'])) {
                        echo $liste_deroulante_history_after;
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="two fields">
            <div class="field">
                <label for="edt-text_choix_2">Text choix 2</label>
                <input type="text" id="edt-text_choix_2" name="text_choix_2" placeholder="truc " value="" maxlength=255 required>
            </div>  
            <div class="field">
                <label for="edt-text_choix_2_id">Suite choix 2 text</label>
                <select name="text_choix_2_id" onchange="this.form.submit()">
                    <?php
                    if (isset($_POST['history_type'])) {
                        echo $liste_deroulante_history_after;
                    }
                    ?>
                </select>
            </div>
        </div>
        <button class="ui button" type="submit" name="add"> Ajouter </button>
    </form>
</div>                
<!-- **************************************** -->     
<?php
require './footer.php';
?>
