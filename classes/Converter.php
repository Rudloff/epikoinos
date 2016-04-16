<?php

namespace Epíkoinos;

use Stringy\Stringy as S;
use Dicollecte\Lexicon;

class Converter
{

    private $separator;
    private $lexicon;
    private $cache;

    public function __construct($separator = '.')
    {
        $this->separator = $separator;
        $this->lexicon = new Lexicon(__DIR__.'/../lexique-dicollecte-names.txt');
        $this->cache = new \Gilbitron\Util\SimpleCache();
    }

    private function convertWordObject(S $w)
    {
        if ($this->cache->is_cached($w)) {
            return S::create($this->cache->get_cache($w));
        }
        foreach ($this->lexicon->getByInflection($w) as $inflection) {
            if ($inflection->inflection == $w
                && $inflection->hasTag('mas')
            ) {
                $mascInflection = $inflection;
                break;
            }
        }
        if (!isset($mascInflection)) {
            return $w;
        }
        foreach ($this->lexicon->getByLemma($mascInflection->lemma) as $inflection) {
            if (($mascInflection->hasTag('pl') && $inflection->hasTag('pl')
                    || $mascInflection->hasTag('sg') && $inflection->hasTag('sg'))
                && $inflection->hasTag('fem')
            ) {
                $femInflection = $inflection;
                break;
            }
        }
        if (isset($femInflection)) {
            $prefix = $w->longestCommonPrefix($femInflection->inflection);
            $suffix = S::create($femInflection->inflection)->removeLeft($prefix);
            if ($mascInflection->hasTag('pl')) {
                $plural = $w->longestCommonSuffix($femInflection->inflection);
                $w = $w->removeRight($plural);
                $suffix = $suffix->removeRight($plural)->ensureRight($this->separator.$plural);
            }
            $w = $w->ensureRight($this->separator.$suffix);
            $this->cache->set_cache($mascInflection->inflection, $w);
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
