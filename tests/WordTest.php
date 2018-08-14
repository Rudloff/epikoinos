<?php
/**
 * WordTest class.
 */

namespace Epíkoinos\Tests;

use Dicollecte\Lexicon;
use Epíkoinos\Word;
use Stringy\Stringy as S;

/**
 * Class used to test the Word class.
 */
class WordTest extends BaseTest
{
    /**
     * List of words to skip because we know they're not relevant here.
     *
     * @var string[]
     */
    protected static $skip = [
        'le', 'les', 'des', 'ces', 'ce', 'cet', 'ceux', 'tout', 'tous',
    ];

    /**
     * Initialize objects needed for tests.
     */
    protected function setUp()
    {
        $this->separator = S::create('.');
        $this->lexicon = new Lexicon(__DIR__.'/../lexique-dicollecte-names.csv');
    }

    /**
     * Test the convert() function.
     *
     * @param string $word   Word to convert
     * @param string $result Expected result
     *
     * @return void
     * @dataProvider wordProvider
     */
    public function testConvert($word, $result)
    {
        if (in_array($word, self::$skip)) {
            $this->markTestSkipped("Doesn't work when calling Word::convert directly");
        }
        if (in_array($word, parent::$skip)) {
            $this->markTestIncomplete();
        }
        $w = new Word(S::create($word), $this->lexicon, $this->separator);
        $this->assertArraySubset([$result => ['epicene' => $result]], $w->convert());
    }
}
