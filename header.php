<!DOCTYPE html>
<html>
    <head>
        <title>LO07</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="semantic.min.css">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="ui inverted fixed menu">
            <a class="ui item" href="play.php">Play</a>
            <?php
            if (isset($session_idutilisateur)) {
                ?>
                <div class="item-control lg-screen">
                    <div class="item"><?php echo "$session_prenom $session_nom ($session_pseudo)"; ?></div>
                    <a class="item" href="mygames.php">Mes parties jouées</a>
                    <a class="item" href="inscription.php">Compte</a>
                    <?php
                    if (in_array('modo', $session_permsgroup)) {
                        ?>
                        <a class="item" href="pannel.php">Pannel administrateur</a>
                        <?php
                    }?>
                    <a class="item" href="logout.php">Déconnecter</a>
                </div>
                <div class="dropdown item-control sm-screen">
                    <div class="item item-modified"><?php echo "$session_prenom $session_nom ($session_pseudo)"; ?></div>
                    <button class="dropdown-toggle btn-modified" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></button>
                    <ul class="dropdown-menu" style="width:160px !important;" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="mygames.php">Mes parties jouées</a></li>
                        <li><a class="dropdown-item" href="inscription.php">Compte</a></li>
                        <?php
                        if (in_array('modo', $session_permsgroup)) {
                            ?>
                            <li><a class="dropdown-item" href="pannel.php">Pannel administrateur</a></li>
                            <?php
                        }?>
                        <li><a class="dropdown-item" href="logout.php">Déconnecter</a></li>
                    </ul>
                </div>
                <?php
            } else {
                ?>
                <a class="item right" href="login.php">Connecter</a>
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