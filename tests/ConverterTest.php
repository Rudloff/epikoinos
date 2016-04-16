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
        $converter = new Converter('foo', true, true);
        $this->assertEquals('formateurfoorice', $converter->convert('formateur'));
        $converter = new Converter('foo', true, false);
        $this->assertEquals('formateurfoorice', $converter->convert('formateur'));
        $converter = new Converter('bar', true, true);
        $this->assertEquals('formateurbarrice', $converter->convert('formateur'));
        $converter = new Converter('bar', true, false);
        $this->assertEquals('formateurbarrice', $converter->convert('formateur'));

        $converter = new Converter('éà');
        $this->assertEquals('formateuréàrice', $converter->convert('formateur'));
        $converter = new Converter('·');
        $this->assertEquals('formateur·rice', $converter->convert('formateur'));
        $converter = new Converter(' ');
        $this->assertEquals('formateur rice', $converter->convert('formateur'));
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
            ),
            array(
                "un étudiant et un meuble",
                "un.e étudiant.e et un meuble"
            ),
            array(
                "cet étudiant",
                "cet.te étudiant.e"
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
            //array('local', 'local.e'),
            array('médical', 'médical.e'),
            array('municipal', 'municipal.e'),
            array('préfectoral', 'préfectoral.e'),
            array('régional', 'régional.e'),
            array('social', 'social.e'),
            array('syndical', 'syndical.e'),
            array('territorial', 'territorial.e'),
            //
            array('départementaux', 'départementaux.ales'),
            //array('locaux', 'locaux.ales'),
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

            array('citoyen', 'citoyen.ne'),
            array('gardien', 'gardien.ne'),
            array('lycéen', 'lycéen.ne'),
            array('technicien', 'technicien.ne'),
            //
            array('citoyens', 'citoyen.ne.s'),
            array('gardiens', 'gardien.ne.s'),
            array('lycéens', 'lycéen.ne.s'),
            array('techniciens', 'technicien.ne.s'),

            array('banquier', 'banquier.ère'),
            array('conseiller', 'conseiller.ère'),
            array('écolier', 'écolier.ère'),
            array('hospitalier', 'hospitalier.ère'),
            array('officier', 'officier.ère'),
            array('premier', 'premier.ère'),
            array('policier', 'policier.ère'),
            array('usager', 'usager.ère'),
            //
            array('banquiers', 'banquier.ère.s'),
            array('conseillers', 'conseiller.ère.s'),
            array('écoliers', 'écolier.ère.s'),
            array('hospitaliers', 'hospitalier.ère.s'),
            array('officiers', 'officier.ère.s'),
            array('premiers', 'premier.ère.s'),
            array('policiers', 'policier.ère.s'),
            array('usagers', 'usager.ère.s'),

            array('chroniqueur', 'chroniqueur.euse'),
            array('entraineur', 'entraineur.euse'),
            array('footballeur', 'footballeur.euse'),
            array('programmeur', 'programmeur.euse'),
            array('travailleur', 'travailleur.euse'),
            //
            array('chroniqueurs', 'chroniqueur.euse.s'),
            array('entraineurs', 'entraineur.euse.s'),
            array('footballeurs', 'footballeur.euse.s'),
            array('programmeurs', 'programmeur.euse.s'),
            array('travailleurs', 'travailleur.euse.s'),

            //array('chercheur', 'chercheur.e'),
            array('gouverneur', 'gouverneur.e'),
            //array('ingénieur', 'ingénieur.e'),
            //array('entrepreneur', 'entrepreneur.e'),
            array('professeur', 'professeur.e'),
            //
            //array('chercheurs', 'chercheur.e.s'),
            array('gouverneurs', 'gouverneur.e.s'),
            //array('ingénieurs', 'ingénieur.e.s'),
            //array('entrepreneurs', 'entrepreneur.e.s'),
            array('professeurs', 'professeur.e.s'),

            array('ambassadeur', 'ambassadeur.rice'),
            //
            array('ambassadeurs', 'ambassadeur.rice.s'),

            array('acheteur', 'acheteur.euse'),
            array('transporteur', 'transporteur.euse'),
            //
            array('acheteurs', 'acheteur.euse.s'),
            array('transporteurs', 'transporteur.euse.s'),

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

            array('adhérent', 'adhérent.e'),
            array('adjoint', 'adjoint.e'),
            array('agent', 'agent.e'),
            array('avocat', 'avocat.e'),
            array('assistant', 'assistant.e'),
            array('consultant', 'consultant.e'),
            array('étudiant', 'étudiant.e'),
            array('habitant', 'habitant.e'),
            array('président', 'président.e'),
            array('remplaçant', 'remplaçant.e'),
            array('suppléant', 'suppléant.e'),
            //
            array('adhérents', 'adhérent.e.s'),
            array('adjoints', 'adjoint.e.s'),
            array('agents', 'agent.e.s'),
            array('avocats', 'avocat.e.s'),
            array('assistants', 'assistant.e.s'),
            array('consultants', 'consultant.e.s'),
            array('étudiants', 'étudiant.e.s'),
            array('habitants', 'habitant.e.s'),
            array('présidents', 'président.e.s'),
            array('remplaçants', 'remplaçant.e.s'),
            array('suppléants', 'suppléant.e.s'),

            array('artisan', 'artisan.e'),
            //array('commis', 'commis.e'),
            //array('sénior', 'sénior.e'),
            //
            array('artisans', 'artisan.e.s'),
            //array('commis', 'commis.e.s'),
            //array('séniors', 'sénior.e.s'),
            array('nombreux', 'nombreux.ses')
        );
    }
}
