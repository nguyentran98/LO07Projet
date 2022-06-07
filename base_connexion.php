<?php

// Connexion à la base de données cuicui du serveur localhost
$connexion = mysqli_connect("localhost", "root", "", "projet_lo07");
if ($connexion) {
    // Changement du jeu de caractères pour utf-8                    
    mysqli_set_charset($connexion, "utf8");
    $logs_message .= "Connexion db réussi <br>";
} else {
    $message_erreur .= "Erreur de connexion<br>\n";
    $message_erreur .= "  Erreur n° " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "<br>\n";
}

?>