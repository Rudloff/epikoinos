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
        $this->assertEquals(['formateurfoorice'], $converter->convertWord('formateur'));
        $converter = new Converter('foo', true, false);
        $this->assertEquals(['formateurfoorice'], $converter->convertWord('formateur'));
        $converter = new Converter('bar', true, true);
        $this->assertEquals(['formateurbarrice'], $converter->convertWord('formateur'));
        $converter = new Converter('bar', true, false);
        $this->assertEquals(['formateurbarrice'], $converter->convertWord('formateur'));

        $converter = new Converter('éà');
        $this->assertEquals(['formateuréàrice'], $converter->convertWord('formateur'));
        $converter = new Converter('·');
        $this->assertEquals(['formateur·rice'], $converter->convertWord('formateur'));
        $converter = new Converter(' ');
        $this->assertEquals(['formateur rice'], $converter->convertWord('formateur'));
    }

    /**
     * Test separator handling.
     *
     * @return void
     */
    public function testSeparator()
    {
        $converter = new Converter('-', false);
        $this->assertEquals(['formateur-rice'], $converter->convertWord('formateur'));
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
        $this->assertContains(
            $result,
            $converter->convertWord($word)
        );
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
