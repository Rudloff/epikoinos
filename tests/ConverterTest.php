<?php
namespace Epíkoinos\Tests;

use Epíkoinos\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvert()
    {
        $converter = new Converter();
        $this->assertEquals(
            "Devenez formateur.rice, c'est bien d'être formateur.rice",
            $converter->convert("Devenez formateur, c'est bien d'être formateur")
        );
    }

    /**
     * @dataProvider wordProvider
     */
    public function testConvertWord($word, $result)
    {
        $converter = new Converter();
        $this->assertEquals(
            $result,
            $converter->convertWord($word)
        );
    }

    public function wordProvider()
    {
        return array(
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
        );
    }
}
