<?php
require './assets.php';

require './authentification.php';
forcelog('player');

$session_partie_id = $_SESSION['session_partie_id'];

require './base_connexion.php';

require './sqlcommands.php';




require './base_deconnexion.php';

// **********************************************
// Construction de la page HTML
require './header.php';
?>



<?php
require './footer.php';
?>