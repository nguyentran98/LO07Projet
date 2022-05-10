<?php
require './assets.php';

$actions = array();

require './authentification.php';
forcelog();

require './base_connexion.php';

require './sqlcommands.php';

$requete = "select * from histoire";
$dataset = sqlrequest($requete);

$nbligne = mysqli_num_rows($dataset);
if ($nbligne == 0) {
    // Pas de phrase
    $message_erreur .= "Aucune phrase !";
} else {
    while ($ligne = mysqli_fetch_assoc($dataset)) {
        $actions .= array(
            $ligne['id'] => array(
            "phrase" => $ligne['phrase']
        ));
    }

    echo "table action : $action";

    /*
      for ($i = 0; $i < mysqli_num_rows( $dataset ); ++$i)
      {
      $line = mysqli_fetch_row($dataset);
      echo( "$line[0] - $line[1]\n");
      }

     * 
     */
}

require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>

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
            <button class="ui button">
                Choix 3
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