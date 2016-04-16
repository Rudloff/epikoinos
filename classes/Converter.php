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
    private $overwriteCache = false;
    private $diacritics = 'ÀàÂâÆæÇçÈèÉéÊêËëÎîÏïÔôŒœÙùÛûÜü';

    public function __construct($separator = '.', $enableCache = true, $overwriteCache = false)
    {
        $this->separator = $separator;
        $this->lexicon = new Lexicon(__DIR__.'/../lexique-dicollecte-names.txt');
        $this->cache = new \Gilbitron\Util\SimpleCache();
        $this->enableCache = $enableCache;
        $this->overwriteCache = $overwriteCache;
    }

    private function convertWordObject(S $w)
    {
        switch ($w) {
            case 'le':
                return S::create('la.le');
            break;
            case 'ce':
                return S::create('ce.tte');
            case 'ceux':
                return S::create('ceux.elles');
            break;
            case 'tout':
                return S::create('tout.e');
            break;
            case 'tous':
                return S::create('tou.te.s');
            break;
        }
        $origW = $w;
        if ($this->enableCache && !$this->overwriteCache && $this->cache->is_cached($w.$this->separator)) {
            return S::create($this->cache->get_cache($w.$this->separator));
        }
        $w = $w->removeLeft("l'")->removeLeft("L'");
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
                if (($mascInflection->hasTag('inv') ||
                        $mascInflection->hasTag('pl') && $inflection->hasTag('pl')
                        || $mascInflection->hasTag('sg') && $inflection->hasTag('sg'))
                    && ($mascInflection->hasTag('adj') && $inflection->hasTag('adj')
                        || $mascInflection->hasTag('nom') && $inflection->hasTag('nom'))
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
                switch ($suffix) {
                    case 'se':
                        $suffix = 'euse';
                        break;
                }
                if ($mascInflection->hasTag('pl')) {
                    $plural = $w->longestCommonSuffix($femInflection->inflection);
                    if ($plural->length() > 0) {
                        $baseW = $baseW->removeRight($plural);
                        $suffix = $suffix->removeRight($plural)->ensureRight($this->separator.$plural);
                    }
                    switch ($suffix) {
                        case 'les':
                            $suffix = 'ales';
                            break;
                        case 'se.s':
                            $suffix = 'euse.s';
                            break;
                    }
                }
                $w = $baseW->ensureRight($this->separator.$suffix);
            }
        }
        if ($this->enableCache) {
            $this->cache->set_cache($origW.$this->separator, $w);
        }
        return $w;
    }

    public function convertWord($word)
    {
        return (string)$this->convertWordObject(S::create($word, 'UTF-8'));
    }

    public function convert($string)
    {
        $s = S::create($string);
        foreach (str_word_count($s, 2, $this->separator.$this->diacritics) as $i => $word) {
            $w = S::create($word, 'UTF-8');
            $w->trim($this->separator);
            $newW = $this->convertWordObject($w);
            if ($newW != $w) {
                $s = $s->regexReplace(
                    '\b'.preg_quote($w).'\b(?!'.preg_quote($newW->removeLeft($w)).')',
                    $newW
                );
            }
        }
        return (string)$s;
    }
}
