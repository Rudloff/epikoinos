<?php

namespace Epíkoinos\Tests;

use Epíkoinos\Converter;

class ConverterTest extends BaseTest
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

    /**
     * @dataProvider wordProvider
     */
    public function testConvertWord($word, $result)
    {
        if (in_array($word, parent::$skip)) {
            $this->markTestIncomplete();
        }
        $converter = new Converter('.', false);
        $this->assertEquals(
            $result,
            $converter->convertWord($word)
        );
    }

    /**
     * @expectedException Exception
     * @dataProvider wordProviderError
     */
    public function testConvertWordError($word)
    {
        $converter = new Converter('.', false);
        $converter->convertWord($word);
    }
}
