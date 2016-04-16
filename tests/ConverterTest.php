<?php
namespace Epíkoinos\Tests;

use Epíkoinos\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider sentenceProvider
     */
    public function testConvert($sentence, $result)
    {
        $converter = new Converter('.', false);
        $this->assertEquals(
            $result,
            $converter->convert($sentence)
        );
    }

    public function testCache()
    {
        $converter = new Converter();
        $this->assertEquals('foobar', $converter->convert('foobar'));
        $this->assertEquals('foobar', $converter->convert('foobar'));
    }

    public function testDelimiter()
    {
        $converter = new Converter('-', false);
        $this->assertEquals('formateur-rice', $converter->convertWord('formateur'));
        $this->assertEquals(
            "Devenez formateur-rice, c'est bien d'être formateur-rice",
            $converter->convert("Devenez formateur, c'est bien d'être formateur")
        );
    }

    public function sentenceProvider()
    {
        return array(
            array(
                "Devenez formateur, c'est bien d'être formateur",
                "Devenez formateur.rice, c'est bien d'être formateur.rice"
            ),
            array(
                "Devenez formateur, c'est bien d'être formateur.rice",
                "Devenez formateur.rice, c'est bien d'être formateur.rice"
            ),
            array(
                "Devenez formateur, c'est bien d'être formateur/tuteur",
                "Devenez formateur.rice, c'est bien d'être formateur.rice/tuteur.rice"
            ),
            array(
                "teurgoule et tuteurat",
                "teurgoule et tuteurat"
            ),
            array(
                'Étudiants et professeurs',
                'Étudiant.e.s et professeur.e.s'
            ),
            array(
                "l'étudiant et le professeur",
                "l'étudiant.e et la.le professeur.e"
            ),
            array(
                "L'étudiant et le professeur",
                "L'étudiant.e et la.le professeur.e"
            )
        );
    }

    /**
     * @dataProvider wordProvider
     */
    public function testConvertWord($word, $result)
    {
        $converter = new Converter('.', false);
        $this->assertEquals(
            $result,
            $converter->convertWord($word)
        );
    }

    public function wordProvider()
    {
        return array(
            array('foobar', 'foobar'),

            array('le', 'la.le'),
            array('un', 'un.e'),
            array('ce', 'ce.tte'),
            array('celui.elle', 'celui.elle'),
            //
            array('les', 'les'),
            array('des', 'des'),
            array('ces', 'ces'),
            array('ceux', 'ceux.elles'),

            array('tout', 'tout.e'),
            //
            array('tous', 'tou.te.s'),

            array('artiste', 'artiste'),
            array('bénévole', 'bénévole'),
            array('cadre', 'cadre'),
            array('capitaine', 'capitaine'),
            array('diplomate', 'diplomate'),
            array('fonctionnaire', 'fonctionnaire'),
            array('gendarme', 'gendarme'),
            array('guide', 'guide'),
            array('interprète', 'interprète'),
            array('juriste', 'juriste'),
            array('maire', 'maire'),
            array('membre', 'membre'),
            array('propriétaire', 'propriétaire'),
            array('secrétaire', 'secrétaire'),
            //
            array('artistes', 'artistes'),
            array('bénévoles', 'bénévoles'),
            array('cadres', 'cadres'),
            array('capitaines', 'capitaines'),
            array('diplomates', 'diplomates'),
            array('fonctionnaires', 'fonctionnaires'),
            array('gendarmes', 'gendarmes'),
            array('guides', 'guides'),
            array('interprètes', 'interprètes'),
            array('juristes', 'juristes'),
            array('maires', 'maires'),
            array('membres', 'membres'),
            array('propriétaires', 'propriétaires'),
            array('secrétaires', 'secrétaires'),

            array('administré', 'administré.e'),
            array('apprenti', 'apprenti.e'),
            array('attaché', 'attaché.e'),
            array('chargé', 'chargé.e'),
            array('délégué', 'délégué.e'),
            array('député', 'député.e'),
            //array('diplomé', 'diplomé.e'),
            array('retraité', 'retraité.e'),
            //
            array('administrés', 'administré.e.s'),
            array('apprentis', 'apprenti.e.s'),
            array('attachés', 'attaché.e.s'),
            array('chargés', 'chargé.e.s'),
            array('délégués', 'délégué.e.s'),
            array('députés', 'député.e.s'),
            //array('diplomés', 'diplomé.e.s'),
            array('retraités', 'retraité.e.s'),

            array('élu', 'élu.e'),
            //
            array('élus', 'élu.e.s'),

            array('départemental', 'départemental.e'),
            array('local', 'local.e'),
            array('médical', 'médical.e'),
            array('municipal', 'municipal.e'),
            array('préfectoral', 'préfectoral.e'),
            array('régional', 'régional.e'),
            array('social', 'social.e'),
            array('syndical', 'syndical.e'),
            array('territorial', 'territorial.e'),
            //
            array('départementaux', 'départementaux.ales'),
            array('locaux', 'locaux.ales'),
            array('médicaux', 'médicaux.ales'),
            array('municipaux', 'municipaux.ales'),
            array('préfectoraux', 'préfectoraux.ales'),
            array('régionaux', 'régionaux.ales'),
            array('sociaux', 'sociaux.ales'),
            array('syndicaux', 'syndicaux.ales'),
            array('territoriaux', 'territoriaux.ales'),

            //array('chef', 'chef.fe'),
            //
            //array('chefs', 'chef.fe.s'),

            array('intellectuel', 'intellectuel.le'),
            array('maternel', 'maternel.le'),
            array('professionnel', 'professionnel.le'),
            //
            array('intellectuels', 'intellectuel.le.s'),
            array('maternels', 'maternel.le.s'),
            array('professionnels', 'professionnel.le.s'),

            array('acteur', 'acteur.rice'),
            array('administrateur', 'administrateur.rice'),
            array('animateur', 'animateur.rice'),
            array('agriculteur', 'agriculteur.rice'),
            array('consommateur', 'consommateur.rice'),
            array('directeur', 'directeur.rice'),
            array('éducateur', 'éducateur.rice'),
            array('électeur', 'électeur.rice'),
            array('instituteur', 'instituteur.rice'),
            array('inspecteur', 'inspecteur.rice'),
            array('médiateur', 'médiateur.rice'),
            array('modérateur', 'modérateur.rice'),
            array('sénateur', 'sénateur.rice'),
            array('recteur', 'recteur.rice'),
            //
            array('acteurs', 'acteur.rice.s'),
            array('administrateurs', 'administrateur.rice.s'),
            array('animateurs', 'animateur.rice.s'),
            array('agriculteurs', 'agriculteur.rice.s'),
            array('consommateurs', 'consommateur.rice.s'),
            array('directeurs', 'directeur.rice.s'),
            array('éducateurs', 'éducateur.rice.s'),
            array('électeurs', 'électeur.rice.s'),
            array('instituteurs', 'instituteur.rice.s'),
            array('inspecteurs', 'inspecteur.rice.s'),
            array('médiateurs', 'médiateur.rice.s'),
            array('modérateurs', 'modérateur.rice.s'),
            array('sénateurs', 'sénateur.rice.s'),
            array('recteurs', 'recteur.rice.s'),

            array('auteur', 'auteur.e'),
            array('auteurs', 'auteur.e.s'),

            array('administratif', 'administratif.ve'),
            array('créatif', 'créatif.ve'),
            array('sportif', 'sportif.ve'),
            //
            array('administratifs', 'administratif.ve.s'),
            array('créatifs', 'créatif.ve.s'),
            array('sportifs', 'sportif.ve.s'),
        );
    }
}
