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
        $this->assertEquals('foobar', 'foobar');
        $this->assertEquals('foobar', 'foobar');
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
                "Vous connaissez la teurgoule ? Et le tuteurat ?",
                "Vous connaissez la teurgoule ? Et le tuteurat ?"
            ),
            array(
                'Étudiants et professeurs',
                'Étudiant.e.s et professeur.e.s'
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
            array('administratif', 'administratif.ve'),
            array('créatif', 'créatif.ve'),
            array('sportif', 'sportif.ve'),
            array('administratifs', 'administratif.ve.s'),
            array('créatifs', 'créatif.ve.s'),
            array('sportifs', 'sportif.ve.s'),
        );
    }
}
