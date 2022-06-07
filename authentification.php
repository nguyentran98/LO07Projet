<?php

// Démarrage d'une session
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_SESSION['session_idutilisateur'])) {

// Enregister les informations sur l'utilisateur dans les variables de session
    $session_idutilisateur = $_SESSION['session_idutilisateur'];
    $session_nom = $_SESSION['session_nom'];
    $session_prenom = $_SESSION['session_prenom'];
    $session_pseudo = $_SESSION['session_pseudo'];
    $session_permsgroup = $_SESSION['session_permsgroup'];
}

function forcelog() {
    if (!isset($_SESSION['session_idutilisateur'])) {
        header('Location: login.php');
        exit();
    }
}

function logged() {
    if (isset($_SESSION['session_idutilisateur'])) {
        return true;
    } else {
        return false;
    }
}

?>