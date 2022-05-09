<?php

if ($connexion) {
    $deconnexion_reussie = mysqli_close($connexion);
    if (!$deconnexion_reussie) {
        $message_erreur .= "Erreur de déconnexion<br>\n";
    } else {
        $logs_message .= "Déconnexion db réussi";
    }
}
?>