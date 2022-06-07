<!DOCTYPE html>
<html>
    <head>
        <title>LO07</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="semantic.min.css">
        <style>
            .main.container {
                margin-top: 4em;
            }
        </style>
    </head>
    <body>
        <div class="ui inverted fixed menu">
            <a class="ui item" href="index.php">HOME</a> 
            <?php
            if (isset($session_idutilisateur)) {
                ?>
                <div class="item right"><?php echo "$session_prenom $session_nom ($session_pseudo)"; ?></div>
                <a class="item" href="logout.php">Déconnecter</a>
                <a class="item" href="inscription.php">Compte</a>
                <?php
                if (strspn($session_permsgroup, "admin")) {
                    ?>
                    <a class="item" href="pannel.php">Pannel administrateur</a>
                    <?php
                }
            } else {
                ?>
                <div class="item right">- - ( - )</div>
                <a class="item" href="login.php">Connecter</a>
                <a class="item" href="inscription.php">S'inscrire</a>
            <?php } ?>
        </div>
        <div class="ui main text container">
            <?php
            if ($logs) {
                if (!empty($logs_message) || !empty($logs_message_erreur)) {
                    ?>
                    <div class="ui segment">
                        <h1 class="ui header"> Logs admins </h1>
                        <div id="logs">
                            <?php
                            if (!empty($logs_message_erreur)) {
                                echo '<div class="ui red message">' . $logs_message_erreur . "</div>\n";
                            }
                            if (!empty($logs_message)) {
                                echo '<div class="ui green message">' . $logs_message . "</div>\n";
                            }
                            ?>
                        </div>                
                    </div>          
                    <?php
                }
            }
            ?>

            <?php
            if (!empty($message_erreur) || !empty($message)) {
                ?>
                <div class="ui segment">
                    <h1 class="ui header"> Logs </h1>
                    <div id="logs">
                        <?php
                        if (!empty($message_erreur)) {
                            echo '<div class="ui red message">' . $message_erreur . "</div>\n";
                        }
                        if (!empty($message)) {
                            echo '<div class="ui green message">' . $message . "</div>\n";
                        }
                        ?>
                    </div>                
                </div>    
                <?php
            }
            ?>

        </div> 