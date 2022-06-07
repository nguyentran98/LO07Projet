<?php
require './assets.php';

require './authentification.php';
forcelog();

$liste_deroulante_permsgroup = "";

require './base_connexion.php';

require './sqlcommands.php';

$requete = "select * from permsgroup";
$resultat = sqlrequest($requete);
if ($resultat) {
    $nbligne = mysqli_num_rows($resultat);
    if ($nbligne == 0) {
        $liste_deroulante_permsgroup .= "<option value=\"\">Il n'existe pas de groupe de permission</option>\n";
    } else {
        while ($ligne = mysqli_fetch_assoc($resultat)) {
            $liste_deroulante_permsgroup .= "<option value=\"" . $ligne['tag'] . "\">"
                    . $ligne['tag']
                    . "</option>\n";
        }
    }
}

// **********************************************
// Construction de la page HTML
require './header.php';
?>


<?php
//<!-- **************************************** -->
//<!-- Liste des derniers messages              -->
if (true) {
    ?>

    <div class="ui segment">
        <div class="field">
            <label>Permission</label>
            <select class="ui dropdown" name="permsgroup">
                <?php echo $liste_deroulante_permsgroup; ?>
            </select>
        </div>
    </div>
    <?php
}
?>



<?php
// Ajout formulaire envoi de message
//include './envoi_message.php';
?>              
<!-- **************************************** -->      



<?php
if (false) {
    ?>

    <div class="ui segment">
        <h1 class="ui header"> Derniers messages </h1>
        <div class="ui segment">
            <h4 class="ui header">
                De admin à faceless, le 2022-04-05 13:27:42              </h4>
            <p>John DOE (johndoe) aimerait être ami avec vous.</p>
        </div>  
        <div class="ui segment">
            <h4 class="ui header">
                De faceless à bartsim, le 2022-03-15 14:53:00              </h4>
            <p>Super tout fonctionne ! Enfin je parle de la partie corrigée par nos enseignants ! </p>
        </div>  
        <div class="ui segment">
            <h4 class="ui header">
                De bartsim à faceless, le 2022-03-15 14:50:00              </h4>
            <p>Bonjour, je tente de faire fonctionner cette super application que nous développons en LO07. </p>
        </div>  
    </div>
    <!-- **************************************** -->                
    <?php
}
?>


<?php
//<!-- **************************************** -->                 
//<!-- Formulaire d'acceptation d'un nouvel ami -->
if (false) {
    ?>
    <div class="ui segment">
        <h1 class="ui header"> Liste des demandes </h1>
        <div class="ui segment">
            <form class="ui form" action="index.php" method="POST"> 
                John DOE (johndoe) vous demande en ami. 
                <input type="hidden" name="id_relation" value="15">                  
                <input type="hidden" name="pseudo_demandeur" value="johndoe">
                <input type="hidden" name="id_demandeur" value="9">
                <button class="ui button right floated" name="accepter">Accepter</button>
                <button class="ui button right floated" name="refuser">Refuser</button>
            </form>
        </div>
    </div>
    <?php
}
?>
<?php
//<!-- **************************************** -->                 
//<!-- Liste des invitations d'ami              -->
if (false) {
    ?>
    <div class="ui segment">
        <h1 class="ui header"> Invitation </h1>

        <form class="ui form" action="index.php" method="POST">
            <div class="field">
                <select class="ui dropdown" name="id_invite">
                    <option value=6>WHITE Walter</option>
                </select>
            </div>
            <button class="ui button" name="inviter"> Inviter </button>
        </form>
    </div>
    <!-- **************************************** -->

    <?php
}
?>
<?php
// **********************************************
// Ajout pied de page HTML
require './footer.php';
?>