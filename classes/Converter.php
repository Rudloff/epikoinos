<?php

namespace Epíkoinos;

use Stringy\Stringy as S;
use Dicollecte\Lexicon;

class Converter
{

    private $separator;
    private $lexicon;
    private $cache;
    private $enableCache = true;
    private $diacritics = 'ÀàÂâÆæÇçÈèÉéÊêËëÎîÏïÔôŒœÙùÛûÜü';

    public function __construct($separator = '.', $enableCache = true)
    {
        $this->separator = $separator;
        $this->lexicon = new Lexicon(__DIR__.'/../lexique-dicollecte-names.txt');
        $this->cache = new \Gilbitron\Util\SimpleCache();
        $this->enableCache = $enableCache;
    }

    private function convertWordObject(S $w)
    {
        $origW = $w;
        if ($this->enableCache && $this->cache->is_cached($w)) {
            return S::create($this->cache->get_cache($w));
        }
        $w = $w->removeLeft("l'");
        foreach ($this->lexicon->getByInflection($w) as $inflection) {
            if ($inflection->inflection == $w->toLowerCase()
                && $inflection->hasTag('mas')
            ) {
                $mascInflection = $inflection;
                break;
            }
        }
        if (isset($mascInflection)) {
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
                $prefix = $w->toLowerCase()->longestCommonPrefix($femInflection->inflection);
                $suffix = S::create($femInflection->inflection)->removeLeft($prefix);
                $baseW = $origW;
                if ($mascInflection->hasTag('pl')) {
                    $plural = $w->longestCommonSuffix($femInflection->inflection);
                    $baseW = $origW->removeRight($plural);
                    $suffix = $suffix->removeRight($plural)->ensureRight($this->separator.$plural);
                }
                $w = $baseW->ensureRight($this->separator.$suffix);
            }
        }
        if ($this->enableCache) {
            $this->cache->set_cache($origW, $w);
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
        foreach (str_word_count($s, 2, $this->separator.$this->diacritics) as $i => $word) {
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
