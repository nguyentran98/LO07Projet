<?php
// **********************************************
// Traitement du formulaire                    
// La variable $message contiendra les messages à afficher
$message = "";

// La variable $message_erreur contiendra les éventuels messages d'erreur à afficher
$message_erreur = "";

// La variable $tableau_messages contiendra les éventuels messages envoyés par les utilisateurs
$tableau_message = "";

// Connexion à la base de données cuicui du serveur localhost
$connexion = mysqli_connect("localhost", "root", "", "cuicui");

if ($connexion) {
    // Changement du jeu de caractères pour utf-8                    
    mysqli_set_charset($connexion, "utf8");

    $message .= "Connexion établie :";
    $message .= "<ul><li>" . mysqli_get_host_info($connexion) . "</li>";
    $message .= "<li>" . mysqli_get_server_info($connexion) . "</li></ul>";
} else {
    $message_erreur .= "Erreur de connexion<br>";
    $message_erreur .= "  Erreur n° " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "<br>";
}

if (empty($message_erreur)) {
    // Affichage du contenu de la table message
    // Requête d'affichage de la table message
    $requete = "select IdMessage, concat(ex.Prenom, ' ', ex.Nom) as 'Expediteur',
                            concat(dest.Prenom, ' ', dest.Nom) as 'Destinataire',
                            DateMessage, Message
                            from message as m 
                            inner join utilisateur as ex on ex.IdUtilisateur = m.IdExpediteur
                            inner join utilisateur as dest on dest.IdUtilisateur = m.IdDestinataire
                            order by DateMessage desc;";

    // Exécution de la requête
    $resultat = mysqli_query($connexion, $requete);

    if ($resultat) {
        $nbligne = mysqli_num_rows($resultat);
        if ($nbligne == 0) {
            // Pas de messages !
            $tableau_message .= "Aucun message<br>";
        } else {
            $tableau_message .= "<table class=\"ui celled table\">\n";
            $tableau_message .= "<tr><td>Date</td><td>Expéditeur</td><td>Destinataire</td><td>Message</td></tr>\n";
            while ($ligne = mysqli_fetch_assoc($resultat)) {
                $tableau_message .= "<tr><td>" . $ligne['DateMessage'] . " </td>"
                        . "<td>" . $ligne['Expediteur'] . "</td>"
                        . "<td>" . $ligne['Destinataire'] . "</td>"
                        . "<td>" . $ligne['Message'] . "</td></tr>";
            }
            $tableau_message .= "</table>\n";
        }
    } else {
        $message_erreur .= "Erreur de la requête $requete<br>";
        $message_erreur .= "  Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>";
    }
}

// Déconnexion de la base de données cuicui
if ($connexion) {
    $deconnexion_reussie = mysqli_close($connexion);
    if (!$deconnexion_reussie) {
        $message_erreur .= "Erreur de déconnexion<br>";
    } else {
        $message .= "Déconnexion réussie<br>";
    }
}

// **********************************************
// Construction de la page HTML
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Cuicui entre amis</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="semantic.min.css">
        <style>
            .main.container {
                margin-top: 4em;
            }
        </style>
    </head>
    <body>
        <div class="ui main text container">
            <!-- **************************************** -->
            <!-- Messages éventuels de l'application      -->
            <?php
            if (!empty($message_erreur) || !empty($message)) {
                ?>
                <div class="ui segment">
                    <h1 class="ui header"> Logs </h1>
                    <div id="logs">
                        <?php
                        if (!empty($message_erreur)) {
                            echo '<div class="ui red message">' . $message_erreur . '</div>';
                        }
                        if (!empty($message)) {
                            echo '<div class="ui green message">' . $message . '</div>';
                        }
                        ?>
                    </div>                
                </div>          
                <?php
            }
            ?>
            <!-- **************************************** -->
            <!-- Affichage des messages                   -->
            <?php
            if (!empty($tableau_message)) {
                ?>
                <div class="ui segment">
                    <h1 class="ui header"> Messages </h1>
                    <div id="messages">
                        <?php
                        echo $tableau_message;
                        ?>
                    </div>                
                </div>          
                <?php
            }
            ?>  
        </div>
    </body>
</html>