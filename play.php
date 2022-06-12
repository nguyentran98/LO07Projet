<?php
require './assets.php';

$text = "";
$choix1text = "";
$choix1id = null;
$choix2text = "";
$choix2id = null;

require './authentification.php';
forcelog('player');

require './base_connexion.php';

require './sqlcommands.php';

$requete = "select * from histoire";
$resultat = sqlrequest($requete);
if ($resultat) {
    while ($ligne = mysqli_fetch_assoc($resultat)) {
        $text = $ligne['text'];
        $choix1text = $ligne['choix1_text'];
        $choix1id = $ligne['choix1_id'];
        $choix2text = $ligne['choix2_text'];
        $choix2id = $ligne['choix2_id'];
    }
}

require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>
<div class="ui main text container">
    <div class="ui segment">   
        <div class="two fields">
            <button class="ui button">
                Choix 1
            </button>
            <button class="ui button">
                Choix 2
            </button>
        </div>
    </div>
</div>

<?php
//<!-- **************************************** -->
//<!-- Liste des derniers messages              -->
if (true) {
    ?>



    <div class="ui grid">
        <div class="four wide column">
            <p>1</p>
        </div>
        <div class="four wide column">
            <button class="ui button">
                Choix 1
            </button>
            <button class="ui button">
                Choix 2
            </button>
        </div>
        <div class="four wide column">
            <h1 class="ui header">Phrase avec choix</h1>
        </div>
        <div class="four wide column">
            <p>4</p>
        </div>
    </div>

    <?php
}
?>

<?php
require './footer.php';
?>