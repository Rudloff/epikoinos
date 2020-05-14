<?php
/**
 * WordTest class.
 */

namespace Epikoinos\Tests;

use Dicollecte\Lexicon;
use Epikoinos\Word;
use Exception;
use Stringy\Stringy;

/**
 * Class used to test the Word class.
 */
class WordTest extends BaseTest
{
    /**
     * Separator used in tests.
     *
     * @var Stringy
     */
    private $separator;

    /**
     * Lexicon object used in tests.
     *
     * @var Lexicon
     */
    private $lexicon;

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
        $this->separator = Stringy::create('.');
        $this->lexicon = new Lexicon(__DIR__ . '/../lexique-dicollecte-names.csv');
    }

    /**
     * Test the convert() function.
     *
     * @param string $word Word to convert
     * @param string $result Expected result
     *
     * @return void
     * @dataProvider wordProvider
     * @throws Exception
     */
    public function testConvert($word, $result)
    {
        if (in_array($word, self::$skip)) {
            $this->markTestSkipped("Doesn't work when calling Word::convert directly");
        }
        if (in_array($word, parent::$skip)) {
            $this->markTestIncomplete();
        }
        $w = new Word(Stringy::create($word), $this->lexicon, $this->separator);
        $this->assertArraySubset([$result => ['epicene' => $result]], $w->convert());
    }
}
