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
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Epíkoinos, convertisseur et générateur d'écriture épicène</title>
        <meta name="description" content="Ce convertisseur d'écriture épicène permet de convertir un mot masculin en écriture épicène" />
        <link rel="stylesheet" href="bower_components/furtive/css/furtive.min.css" />
        <link rel="manifest" href="manifest.json" />
        <!-- Tu veux contribuer ? Ça se passe ici : https://github.com/Rudloff/epikoinos -->
    </head>
    <body class="p1 bg--white measure">
        <h1 class="txt--center"><a href="?">Epíkoinos</a><br/><span class="small">Convertisseur d'écriture épicène</span></h1>
        <p>
            Ce site vous permet de convertir un mot masculin en
            <a target="_blank" href="https://fr.wikipedia.org/wiki/Langage_non_sexiste">écriture épicène</a>.
            Cela peut être utile si vous avez un doute concernant la forme correcte d'un mot.
            <br/>
            Il se base sur les <a target="_blank" href="http://www.haut-conseil-egalite.gouv.fr/IMG/pdf/hcefh__guide_pratique_com_sans_stereo-_vf-_2015_11_05.pdf">
            recommandations du Haut Conseil à l'égalité entre les femmes et les hommes</a>.
        </p>
        <form class="py1">
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
        if (isset($_GET['query']) && !empty($_GET['query'])) :
            ?>
            <div class="brdr--light-gray p1">
            <?php
            try {
                echo implode(' ou ', $converter->convertWord($_GET['query']));
            } catch (\Exception $e) {
                echo '<span class="fnt--red">Mot inconnu&nbsp;: '.$_GET['query'].'</span>';
            } ?>
            </div>
            <?php
        endif;
        ?>
    </body>
</html>
