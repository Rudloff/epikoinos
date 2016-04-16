<?php

namespace Epíkoinos;

use Stringy\Stringy as S;
use Dicollecte\Lexicon;

class Converter
{

    private $separator;

    public function __construct($separator = '.')
    {
        $this->separator = $separator;
        $this->lexicon = new Lexicon(__DIR__.'/../vendor/dicollecte/lexique/lexique-dicollecte-fr-v5.6.txt');
    }

    private function convertWordObject(S $w)
    {
        $inflections = $this->lexicon->getByLemma($w);
        foreach ($inflections as $inflection) {
            if ($inflection->inflection == $w
                && $inflection->hasTag('nom')
                && $inflection->hasTag('mas')
            ) {
                $isPlural = $inflection->hasTag('pl');
                break;
            }
        }
        if (!isset($isPlural)) {
            return $w;
        }
        foreach ($inflections as $inflection) {
            if ($inflection->hasTag('nom')
                && ($isPlural && $inflection->hasTag('pl') || !$isPlural && $inflection->hasTag('sg'))
                && $inflection->hasTag('fem')
            ) {
                $femInflection = $inflection;
                break;
            }
        }
        if (isset($femInflection)) {
            $prefix = $w->longestCommonPrefix($femInflection->inflection);
            $suffix = S::create($femInflection->inflection)->removeLeft($prefix);
            $w = $w->ensureRight($this->separator.$suffix);
        }
        return $w;
    }

    public function convertWord($word)
    {
        return (string)$this->convertWordObject(S::create($word));
    }

    public function convert($string)
    {
        $s = S::create($string);
        foreach (str_word_count($s, 2, $this->separator) as $i => $word) {
            $w = S::create($word);
            $w->trim($this->separator);
            $newW = $this->convertWordObject($w);
            if ($newW != $w) {
                $s = $s->regexReplace(
                    '\b'.$w.'\b(?!'.$newW->removeLeft($w).')',
                    $newW
                );
            }
        }
        return (string)$s;
    }
}
