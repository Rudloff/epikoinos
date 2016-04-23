# Epíkoinos
Convert French words to their epicene form

##Usage

###Setup

    require_once __DIR__.'/vendor/autoload.php';
    use Epíkoinos\Converter;
    $converter = new Converter();

###Convert a single word

    $converter->convertWord('étudiante'); //étudiant.e

###Convert a text

    $converter->convert('Étudiants et professeurs'); //Étudiant.e.s et professeur.e.s

###Use a custom delimiter

    $converter = new Converter('-');
    $converter->convertWord('étudiante'); //étudiant-e
