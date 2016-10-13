<?php

namespace Epíkoinos\Tests;

use Dicollecte\Lexicon;
use Epíkoinos\Word;
use Stringy\Stringy as S;

class WordTest extends BaseTest
{

    protected static $skip = [
        'le', 'les', 'des', 'ces', 'ce', 'cet', 'ceux', 'tout', 'tous'
    ];

    protected function setUp()
    {
        $this->separator = S::create('.');
        $this->lexicon = new Lexicon(__DIR__.'/../lexique-dicollecte-names.csv');
    }

    /**
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
        $this->assertEquals(
            $result,
            $w->convert()
        );
    }
}
