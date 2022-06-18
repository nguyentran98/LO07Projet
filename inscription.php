<?php
require './assets.php';

require './authentification.php';

// Initialisation des variables contenant les données saisies dans le formulaire
$nom = "";
$prenom = "";
$Pseudo = "";
$passe1 = "";
$passe2 = "";

require './base_connexion.php';

require './sqlcommands.php';

// Si aucun message d'erreur
if (empty($message_erreur)) {
    if (isset($_POST['inscrire'])) {
        //***************************
        // Clic sur le bouton "S'inscrire" de valeur Nom="inscrire"
        // Traitement du formulaire
        // 
        // Filtrage du contenu de $_POST et assignation à des variables locales
        // htmlspecialchars() : Convertit les caractères spéciaux en entités HTML
        // trim() : Supprime les espaces (ou d'autres caractères) en début et fin de chaîne
        $nom = trim(htmlspecialchars($_POST['nom']));
        $prenom = trim(htmlspecialchars($_POST['prenom']));
        $Pseudo = htmlspecialchars($_POST['Pseudo']);
        $passe1 = trim(htmlspecialchars($_POST['passe1']));
        $passe2 = trim(htmlspecialchars($_POST['passe2']));

        // Vérification de toutes les valeurs saisies

        if (empty($nom)) {
            $message_erreur .= "    Le champ nom est obligatoire<br>\n";
        } elseif (strlen($nom) > 100) {
            $message_erreur .= "    Le nom ne doit pas comporter plus de 100 caractères<br>\n";
        } elseif (!preg_match('/^([[:alpha:]]|-|[[:space:]]|\')*$/u', $nom)) {
            // [[:alpha:]] : caractères alphabétique
            // [[:space:]] : espace blanc
            $message_erreur .= "    Le nom ne doit comporter que des lettres<br>\n";
        }

        if (empty($prenom)) {
            $message_erreur .= "    Le champ prenom est obligatoire<br>\n";
        } elseif (strlen($prenom) > 100) {
            $message_erreur .= "    Le prénom ne doit pas comporter plus de 100 caractères<br>\n";
        } elseif (!preg_match('/^([[:alpha:]]|-|[[:space:]]|\')*$/u', $prenom)) {
            $message_erreur .= "    Le prénom ne doit comporter que des lettres<br>\n";
        }

        if (empty($Pseudo)) {
            $message_erreur .= "    Le champ pseudo est obligatoire<br>\n";
        } elseif (strlen($Pseudo) > 15 || strlen($Pseudo) < 3) {
            $message_erreur .= "    Le pseudo doit être composé de 3 à 15 caractères<br>\n";
        } elseif (!preg_match('/^[a-zA-Z0-9]*$/u', $Pseudo)) {
            $message_erreur .= "    Le pseudo ne doit comporter que des lettres non accentuées ou des chiffres et pas d'espaces<br>\n";
        }

        if (empty($passe1)) {
            $message_erreur .= "    Le mot de passe est obligatoire<br>\n";
        } elseif (strlen($passe1) < 6) {
            $message_erreur .= "    Le mot de passe doit contenir au moins 6 caractères<br>\n";
        } elseif (!preg_match('/^[[:graph:]]*$/u', $passe1)) {
            // [[:graph:]] : tous les caractères imprimables sauf l'espace
            $message_erreur .= "    Le mot de passe ne doit pas comporter d'espaces<br>\n";
        }

        if (strcmp($passe1, $passe2) != 0) {
            $message_erreur .= "    Les mots de passe sont différents<br>\n";
        }

        // Cryptage du mot de passe
        $passe_chiffre = password_hash($passe1, PASSWORD_DEFAULT);

        // Si aucun message d'erreur
        if (empty($message_erreur)) {
            //*******************************************
            // Saisie des données du formulaire dans la table utilisateur
            // après verification que le pseudo et le mail n'existent 
            // pas déjà dans la table
            // 
            // Vérification que le pseudo n'existe pas dans la table utilisateur
            $requete = "select * from utilisateur where Pseudo = '$Pseudo'";
            $resultat = mysqli_query($connexion, $requete);
            if ($resultat) {
                $nbligne = mysqli_num_rows($resultat);
                if ($nbligne != 0) {
                    // Le pseudo existe déjà
                    $message_erreur .= "Le pseudo $Pseudo existe déjà<br>\n";
                }
            } else {
                $message_erreur .= "Erreur de la requête $requete<br>\n";
                $message_erreur .= "  Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
            }

            // Si aucun message d'erreur
            if (empty($message_erreur)) {
                // Requête d'insertion de l'utilisateur dans la table utilisateur                
                $requete = "INSERT INTO utilisateur (nom, prenom, Pseudo, password, create_time) "
                        . "VALUES ('$nom', '$prenom', '$Pseudo', '$passe_chiffre', current_timestamp());";
                // Exécution de la requête
                $resultat = mysqli_query($connexion, $requete);
                if (!$resultat) {
                    $message_erreur .= "Erreur de la requête $requete<br>\n";
                    $message_erreur .= "  Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
                }
            }
        }

        // Si aucun message d'erreur
        if (empty($message_erreur)) {
            // Affiche un message de confirmation ainsi que les valeurs saisies
            $message .= "    <p>Nous avons pris en compte votre inscription.</p>\n";
            $message .= "    Voici les données saisies :\n    <ul>\n";
            // $message .= "      <li>Civilité : " . $civilite . "</li>\n";
            $message .= "      <li>Nom : " . $nom . "</li>\n";
            $message .= "      <li>Prénom : " . $prenom . "</li>\n";
            $message .= "      <li>Pseudo : " . $Pseudo . "</li>\n";
            //$message .= "      <li>Mot de passe 1 : " . $passe1 . "</li>\n";
            //$message .= "      <li>Mot de passe 2 : " . $passe2 . "</li>\n";
            $message .= "      <li>Mot de passe chiffré : " . $passe_chiffre . "</li>\n";
            $message .= "    </ul>\n";
        }
    }
}

// Déconnexion de la base de données cuicui
require './base_deconnexion.php';
// **********************************************
// Construction de la page HTML
require './header.php';
?>
<!-- **************************************** -->
<!-- Affichage du formulaire                  -->
<?php
// S'il y a eu des erreurs ou si aucun appui sur le bouton "S'incrire"
if (!empty($message_erreur) || !(isset($_POST['inscrire']))) {
    ?>
    <!-- <div class="ui segment">     
        <h1 class="ui header">Inscription</h1>
        <form class="ui form" method="POST" action="">
            <h4 class="ui dividing header">Coordonnées</h4>
            <div class="two fields">
                <div class="field">
                    <label for="edit-nom">Nom</label>
                    <input type="text" id="edit-nom" name="nom" placeholder="Nom" value="<?php echo $nom ?>" maxlength=100 required>
                </div>
                <div class="field">
                    <label for="edit-prenom">Prénom</label>
                    <input type="text" id="edit-prenom" name="prenom" placeholder="Prénom" value="<?php echo $prenom ?>" maxlength=100 required>
                </div>
            </div>
            <h4 class="ui dividing header">Informations de connexion</h4>
            <div class="field">
                <label for="edit-pseudo">Pseudo</label>
                <input type="text" id="edit-pseudo" name="Pseudo" placeholder="Pseudo" value="<?php echo $Pseudo ?>" minlength="5" maxlength=10 required>
            </div>  
            <div class="two fields">
                <div class="field">
                    <label for="edit-passe1">Mot de passe</label>
                    <input type="password" id="edit-passe1" name="passe1" placeholder="Mot de passe" value="" minlength="6" required>
                </div>  
                <div class="field">
                    <label for="edit-passe2">Confirmer le mot de passe</label>
                    <input type="password" id="edit-passe2" name="passe2" placeholder="Mot de passe" value="" minlength="6" required>
                </div>
            </div>
            <button class="ui button" type="submit" name="inscrire"> S'inscrire </button>
        </form>
    </div> -->
    <div class="register">
        <h1>Inscription</h1>
        <form method="POST" action="">
            <h4 class="ui dividing header">Coordonnées</h4>
            <input type="text" id="edit-nom" name="nom" placeholder="Nom" value="<?php echo $nom ?>" maxlength=100 required>
            <input type="text" id="edit-prenom" name="prenom" placeholder="Prénom" value="<?php echo $prenom ?>" maxlength=100 required>
            <h4 class="ui dividing header">Informations de connexion</h4>
            <input type="text" id="edit-pseudo" name="Pseudo" placeholder="Pseudo" value="<?php echo $Pseudo ?>" minlength="5" maxlength=10 required>
            <input type="password" id="edit-passe1" name="passe1" placeholder="Mot de passe" value="" minlength="6" required>
            <input type="password" id="edit-passe2" name="passe2" placeholder="Mot de passe" value="" minlength="6" required>
            <button type="submit" class="btn btn-primary btn-block btn-large" name="inscrire">S'inscrire</button>
        </form>
    </div>
    <?php
}

require './footer.php'
?>
