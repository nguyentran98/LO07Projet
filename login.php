<?php
require './assets.php';

require './authentification.php';
if (logged()) {
    header('Location: index.php');
    exit();
}

// Initialisation des variables contenant les données saisies dans le formulaire
$pseudo = "";
$passe1 = "";

require './base_connexion.php';

require './sqlcommands.php';

// Si aucun message d'erreur
if (empty($message_erreur)) {
    if (isset($_POST['connexion'])) {
        //***************************
        // Clic sur le bouton "S'inscrire" de valeur name="inscrire"
        // Traitement du formulaire
        // 
        // Filtrage du contenu de $_POST et assignation à des variables locales
        // htmlspecialchars() : Convertit les caractères spéciaux en entités HTML
        // trim() : Supprime les espaces (ou d'autres caractères) en début et fin de chaîne
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $passe1 = trim(htmlspecialchars($_POST['password']));

        // Vérification de toutes les valeurs saisies
        if (empty($pseudo)) {
            $message_erreur .= "    Le champ pseudo est obligatoire<br>\n";
        }

        if (empty($passe1)) {
            $message_erreur .= "    Le mot de passe est obligatoire<br>\n";
        }

        // Si aucun message d'erreur
        if (empty($message_erreur)) {
            //*******************************************
            // Saisie des données du formulaire dans la table utilisateur
            // après verification que le pseudo et le mail n'existent 
            // pas déjà dans la table
            // 
            // Vérification que le pseudo n'existe pas dans la table utilisateur
            $requete = "select * from utilisateur where Pseudo = '$pseudo'";
            $resultat = mysqli_query($connexion, $requete);
            $logs_message .= "user : $pseudo <br> mdp : $passe1 <br>";
            if ($resultat) {
                $nbligne = mysqli_num_rows($resultat);
                if ($nbligne == 0) {
                    $logs_message_erreur .= "Le pseudo n'existe pas <br>";
                    $message_erreur .= "Connexion impossible<br>\n";
                } elseif ($nbligne > 1) {
                    $message_erreur .= "Erreur : Plusieurs comptes<br>\n";
                } else {
                    $utilisateur = mysqli_fetch_assoc($resultat);
                    if (password_verify($passe1, $utilisateur['Password'])) {
                        // Mot de passe saisie valide
                        
                        // Ajout de ocnnexion logs
                        $idutilisateur = $utilisateur['IdUtilisateur'];
                        $requete = "INSERT INTO connexion_logs (datetime, utilisateur_IdUtilisateur) VALUES (current_timestamp(), '$idutilisateur');";
                        sqlrequest($requete);
                        
                        $message .= "Bienvenue {$utilisateur['Prenom']} <br>";

                        $permsgroup_tag = $utilisateur['permsgroup_tag'];
                        $requete = "select perms_tag from permsgroup_has_perms where permsgroup_tag = '$permsgroup_tag'";
                        $permsgroup = array();
                        $resultat = sqlrequest($requete);
                        if ($resultat) {
                            while ($ligne = mysqli_fetch_assoc($resultat)) {
                                array_push($permsgroup, $ligne['perms_tag']);
                            }
                        }

                        // Démarrage d'une session
                        if (session_status() != PHP_SESSION_ACTIVE) {
                            session_start();
                        }

                        // Enregister les informations sur l'utilisateur dans les variables de session
                        $_SESSION['session_idutilisateur'] = $utilisateur['IdUtilisateur'];
                        $_SESSION['session_nom'] = $utilisateur['Nom'];
                        $_SESSION['session_prenom'] = $utilisateur['Prenom'];
                        $_SESSION['session_pseudo'] = $utilisateur['Pseudo'];
                        $_SESSION['session_permsgroup'] = $permsgroup;

                        // Redirection vers la page index.php
                        header('Location: index.php');
                        exit();
                    } else {
                        $logs_message_erreur .= "Le mdp n'est pas correct <br>";
                        $message_erreur .= "Connexion impossible<br>\n";
                    }
                }
            } else {
                $message_erreur .= "Erreur de la requête $requete<br>\n";
                $message_erreur .= "  Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
            }
        }
    }
}

// Déconnexion de la base de données cuicui
require './base_deconnexion.php';

require './header.php';
?>


<?php
if (isset($_POST['connexion'])) {
    echo "connexion...";
}
?>

<div class="login">
    <h1>Connexion</h1>
    <form method="POST" action="login.php">
        <input type="text" name="pseudo" placeholder="Votre pseudo" required="required" />
        <input type="password" name="password" placeholder="Votre mot de passe" required="required" />
        <button type="submit" class="btn btn-primary btn-block btn-large" name="connexion">Se connecter</button>
    </form>
</div>
<?php
require './footer.php';
?>
