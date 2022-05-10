<?php

function sqlrequest($requete) {
    global $connexion;
    global $message;
    global $message_erreur;

    try {
        $resultat = mysqli_query($connexion, $requete);
    } catch (mysqli_sql_exception) {
        $resultat = false;
    }

    if ($resultat) {
        $message .= "Requête réussi <br>";
        return $resultat;
    } else {
        $message_erreur .= "Erreur de la requête $requete<br>";
        $message_erreur .= "  Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>";
        return false;
    }
}

?>
