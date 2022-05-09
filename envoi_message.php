<?php
if (session_status() == PHP_SESSION_ACTIVE) {

// Identifiant de l'expéditeur du message à envoyer
    $id_expediteur = $_SESSION['session_idutilisateur'];

// Initialisation de la variable contenant la liste déroulante des destinataires du message à anvoyer
    $liste_deroulante_destinataires = "";

    require './base_connexion.php';

// Si aucun message d'erreur
    if (empty($message_erreur)) {
        //*******************************************
        //  Construction de la liste déroulante
        //  des destinataires possibles du message à envoyer
        //  = listes des "amis" de l'expéditeur
        // Requete d'extraction de la liste des "amis" de l'expéditeur
        $requete = "select IdAmi, Nom, Prenom
              from relation inner join utilisateur on IdUtilisateur = IdAmi
              where IdDemandeur = $id_expediteur and RelationAccepte = true
              order by Nom, Prenom;";

        // Exécution de la requête
        $resultat = mysqli_query($connexion, $requete);
        if ($resultat) {
            $nbligne = mysqli_num_rows($resultat);
            if ($nbligne == 0) {
                // Pas d'ami !
                $liste_deroulante_destinataires .= "<option value=\"\">Vous n'avez pas encore d'ami !</option>\n";
            } else {
                while ($ligne = mysqli_fetch_assoc($resultat)) {
                    $liste_deroulante_destinataires .= "<option value=\"" . $ligne['IdAmi'] . "\">"
                            . strtoupper($ligne['Nom']) . " " . $ligne['Prenom']
                            . "</option>\n";
                }
            }
        } else {
            $message_erreur .= "Erreur de la requête $requete<br>\n";
            $message_erreur .= "  Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
        }
        if (empty($message_erreur)) {
            if (isset($_POST['envoyer'])) {
                //***************************
                // Clic sur le bouton "Envoyer" de valeur name="envoyer"
                // Traitement du formulaire d'envoi de message
                $id_destinataire = htmlspecialchars($_POST['id_destinataire']);
                $message_a_envoyer = htmlspecialchars($_POST['message_a_envoyer']);

                // Vérification des valeurs saisies
                if (empty($id_destinataire)) {
                    // La liste déroulante des amis est vide                          
                    $message_erreur .= "    Vous n'avez pas d'ami<br>\n";
                } else {
                    // Requête d'insertion du message dans la table message
                    $requete = "insert into message (IdExpediteur, IdDestinataire, DateMessage, Message) values
                    ($id_expediteur, $id_destinataire, CURRENT_TIMESTAMP,
                     '$message_a_envoyer');";
                    // Exécution de la requête
                    $resultat = mysqli_query($connexion, $requete);
                    if ($resultat) {
                        $message .= "Message envoyé<br>\n";
                    } else {
                        $message_erreur .= "Erreur de la requête $requete<br>\n";
                        $message_erreur .= "  Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
                    }
                }
            }
        }
    }
    require './base_deconnexion.php';
    ?> 
    <!-- **************************************** -->                 
    <!-- Formulaire d'envoi de message            -->
    <div class="ui segment">
        <h1 class="ui header">Envoyer un message</h1>
        <div class="ui divider"></div>
        <form class="ui form" action="" method="POST">
            <div class="field">
                <label>Destinataire</label>
                <select class="ui dropdown" name="id_destinataire">
                    <?php echo $liste_deroulante_destinataires; ?>
                </select>
            </div>
            <div class="ui field">
                <label>Message</label>
                <textarea rows="4" name="message_a_envoyer"></textarea>
            </div>
            <button class="ui button" type="submit" name="envoyer"> Envoyer </button>
        </form>
    </div>
    <?php
} else {
    header('Location: index.php');
    exit();
}
?>