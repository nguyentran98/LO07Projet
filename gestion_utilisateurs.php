<?php
require './assets.php';

require './authentification.php';
forcelog('admin');

$liste_deroulante_permsgroup = "";
$liste_deroulante_utilisateurs = "";

require './base_connexion.php';

require './sqlcommands.php';


// Récuperation des joueurs dans une liste
function majplayerlist() {
    global $liste_deroulante_utilisateurs;
    $liste_deroulante_utilisateurs = "";
    $requete = "select IdUtilisateur, Pseudo, permsgroup_tag from utilisateur";
    $resultat = sqlrequest($requete);
    if ($resultat) {
        $nbligne = mysqli_num_rows($resultat);
        if ($nbligne == 0) {
            $liste_deroulante_utilisateurs .= "<option value=\"\">Il n'y a pas d'utilisateur</option>\n";
        } else {
            while ($ligne = mysqli_fetch_assoc($resultat)) {
                $liste_deroulante_utilisateurs .= "<option value=\"" . $ligne['IdUtilisateur'] . "\">"
                        . $ligne['Pseudo']
                        . "</option>\n";
            }
        }
    }
}
majplayerlist();

// Récuperation des groupes de permission dans une liste
$requete = "select * from permsgroup";
$resultat = sqlrequest($requete);
if ($resultat) {
    $nbligne = mysqli_num_rows($resultat);
    if ($nbligne == 0) {
        $liste_deroulante_permsgroup .= "<option value=\"\">Il n'existe pas de groupe de permission</option>\n";
    } else {
        while ($ligne = mysqli_fetch_assoc($resultat)) {
            $liste_deroulante_permsgroup .= "<option value=\"" . $ligne['tag'] . "\">"
                    . $ligne['name']
                    . "</option>\n";
        }
    }
}

// Mise à jour des privilèges d'un utilisateur
if (isset($_POST['maj'])) {
    $idutilisateur = trim(htmlspecialchars($_POST['utilisateurs']));
    $idpermsgroup = trim(htmlspecialchars($_POST['permsgroup']));

    $requete = "UPDATE utilisateur SET permsgroup_tag = '$idpermsgroup' WHERE utilisateur.IdUtilisateur = $idutilisateur;";
    sqlrequest($requete);
}

// Suppression d'un utilisateur
if (isset($_POST['suppr'])) {
    $idutilisateur = trim(htmlspecialchars($_POST['utilisateurs']));

    $requete = "DELETE FROM utilisateur WHERE utilisateur.IdUtilisateur = $idutilisateur";
    sqlrequest($requete);
    
    majplayerlist();
}

require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>


<?php
//<!-- **************************************** -->
//<!-- Panneau de gestion des privilèges        -->
if (true) {
    ?>

    <div class="user-permission">
        <h1>Gestion utilisateurs</h1>
        <form method="POST" action="">
            <h4 class="ui dividing header">Coordonnées</h4>
            <label for="edit-utilisateurs">Pseudo</label>
            <select class="ui dropdown" name="utilisateurs">
                <?php echo $liste_deroulante_utilisateurs; ?>
            </select>

            <label for="edit-permsgroup">Permissions</label>
            <select class="ui dropdown" name="permsgroup">
                <?php echo $liste_deroulante_permsgroup; ?>
            </select>
            
            <button style="width:100px; margin-right:10px;" type="submit" class="btn btn-primary btn-block btn-large" name="maj">MAJ</button>
            <button style="width:100px;" type="submit" class="btn btn-primary btn-block btn-large" name="suppr">Supprimer</button>
        </form>
    </div>
    <?php
}
?>

<?php
// **********************************************
// Ajout pied de page HTML
require './footer.php';
?>