<?php

function sqlrequest($requete) {
    global $connexion;
    global $logs_message;
    global $logs_message_erreur;

    try {
        $resultat = mysqli_query($connexion, $requete);
    } catch (mysqli_sql_exception) {
        $resultat = false;
    }

    if ($resultat) {
        $logs_message .= "Requête réussi <br>";
        return $resultat;
    } else {
        $logs_message_erreur .= "Erreur de la requête $requete<br>";
        $logs_message_erreur .= "  Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>";
        return false;
    }
}

function sfwifexist($requetesfw) {
    global $message_erreur;

    $resultat = sqlrequest($requetesfw);

    $nbligne = mysqli_num_rows($resultat);
    if ($nbligne != 0) {
        $message_erreur .= "La phrase existe déjà <br>";
        return true;
    }
}


?>
