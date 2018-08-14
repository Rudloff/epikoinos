<?php
/**
 * ConverterTest class.
 */

namespace Epíkoinos\Tests;

use Epíkoinos\Converter;

/**
 * Class used to test the Converter class.
 */
class ConverterTest extends BaseTest
{
    /**
     * Test the cache system.
     *
     * @return void
     */
    public function testCache()
    {
        $converter = new Converter('foo', true, true);
        $result = $converter->convertWord('formateur');
        $this->assertEquals('formateurfoorice', $result['formateurfoorice']['epicene']);

        $converter = new Converter('foo', true, false);
        $result = $converter->convertWord('formateur');
        $this->assertEquals('formateurfoorice', $result['formateurfoorice']['epicene']);

        $converter = new Converter('bar', true, true);
        $result = $converter->convertWord('formateur');
        $this->assertEquals('formateurbarrice', $result['formateurbarrice']['epicene']);

        $converter = new Converter('bar', true, false);
        $result = $converter->convertWord('formateur');
        $this->assertEquals('formateurbarrice', $result['formateurbarrice']['epicene']);

        $converter = new Converter('éà');
        $result = $converter->convertWord('formateur');
        $this->assertEquals('formateuréàrice', $result['formateuréàrice']['epicene']);

        $converter = new Converter('·');
        $result = $converter->convertWord('formateur');
        $this->assertEquals('formateur·rice', $result['formateur·rice']['epicene']);

        $converter = new Converter(' ');
        $result = $converter->convertWord('formateur');
        $this->assertEquals('formateur rice', $result['formateur rice']['epicene']);
    }

    /**
     * Test separator handling.
     *
     * @return void
     */
    public function testSeparator()
    {
        $converter = new Converter('-', false);
        $result = $converter->convertWord('formateur');
        $this->assertEquals('formateur-rice', $result['formateur-rice']['epicene']);
    }

    /**
     * Test that words are correctly trimmed.
     *
     * @return void
     */
    public function testTrim()
    {
        $converter = new Converter('-', false);
        $result = $converter->convertWord('formateur ');
        $this->assertEquals('formateur-rice', $result['formateur-rice']['epicene']);
    }

    /**
     * Test the convertWord() function.
     *
     * @param string $word   Word to convert
     * @param string $result Expected result
     *
     * @return void
     * @dataProvider wordProvider
     */
    public function testConvertWord($word, $result)
    {
        if (in_array($word, parent::$skip)) {
            $this->markTestIncomplete();
        }
        $converter = new Converter('.', false);
        $this->assertArraySubset([$result => ['epicene' => $result]], $converter->convertWord($word));
    }

    /**
     * Test the convertWord() function with an unknown word.
     *
     * @param string $word Word to convert
     *
     * @return void
     * @expectedException Exception
     * @dataProvider wordProviderError
     */
    public function testConvertWordError($word)
    {
        $converter = new Converter('.', false);
        $converter->convertWord($word);
    }
}
