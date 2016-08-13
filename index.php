<?php
require_once __DIR__.'/vendor/autoload.php';
use Epíkoinos\Converter;

setlocale(LC_CTYPE, 'fr_FR.utf8');
$converter = new Converter('.', false);
?>
<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>Epíkoinos</title>
        <link rel="stylesheet" href="bower_components/furtive/css/furtive.min.css" />
    </head>
    <body class="p1 bg--white measure">
        <h1><a href="?">Epíkoinos</a></h1>
        <p>
            Ce site vous permet de convertir un mot masculin en écriture épicène.
            <br/>
            Il se base sur les <a target="_blank" href="http://www.haut-conseil-egalite.gouv.fr/IMG/pdf/hcefh__guide_pratique_com_sans_stereo-_vf-_2015_11_05.pdf">
            recommandations du Haut Conseil à l'égalité entre les femmes et les hommes</a>.
        </p>
        <form class="py1" itemprop="potentialAction" action="">
            <label for="query">Mot à convertir</label>
            <input type="text" name="query" id="query"
            <?php
            if (isset($_GET['query'])) {
                echo 'value="'.$_GET['query'].'"';
            }
            ?>
            />
            <input type="submit" value="Convertir" />
        </form>
        <?php
        if (isset($_GET['query']) && !empty($_GET['query'])) {
            ?>
            <div class="brdr--light-gray p1">
            <?php
            echo $converter->convert($_GET['query']);
            ?>
            </div>
            <?php
        }
        ?>
    </body>
</html>