<?php

namespace Epíkoinos;

use Dicollecte\Lexicon;
use Stringy\Stringy as S;

class Converter
{
    private $separator;
    private $lexicon;
    private $cache;
    private $enableCache = true;
    private $overwriteCache = false;
    private $diacritics = 'ÀàÂâÆæÇçÈèÉéÊêËëÎîÏïÔôŒœÙùÛûÜü';
    private $articles = ['un', 'le', 'ce', 'cet', 'tout', 'tous'];

    public function __construct($separator = '.', $enableCache = true, $overwriteCache = false)
    {
        $this->separator = $separator;
        $this->lexicon = new Lexicon(__DIR__.'/../lexique-dicollecte-names.csv');
        $this->cache = new \Gilbitron\Util\SimpleCache();
        $this->enableCache = $enableCache;
        $this->overwriteCache = $overwriteCache;
    }

    private function convertWordObject(S $w)
    {
        $safeSeparator = rawurlencode($this->separator);
        switch ($w) {
            case 'le':
                return S::create('la.le');
            break;
            case 'les':
            case 'des':
            case 'ces':
                return $w;
            break;
            case 'ce':
                return S::create('ce.tte');
            break;
            case 'cet':
                return S::create('cet.te');
            break;
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
        if ($this->enableCache && !$this->overwriteCache && $this->cache->is_cached($w.$safeSeparator)) {
            return S::create($this->cache->get_cache($w.$safeSeparator));
        }
        $w = $w->removeLeft("l'")->removeLeft("L'");
        $inflections = $this->lexicon->getByInflection($w);
        if (empty($inflections)) {
            throw new \Exception("Can't find this inflection");
        }
        foreach ($inflections as $inflection) {
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
            $this->cache->set_cache($origW.$safeSeparator, $w);
        }

        return $w;
    }

    public function convertWord($word)
    {
        return (string) $this->convertWordObject(S::create($word, 'UTF-8'));
    }

    public function convert($string)
    {
        $s = S::create($string);
        foreach (str_word_count($s, 2, $this->separator.$this->diacritics) as $i => $word) {
            $words[] = [
                'word' => $word,
                'pos'  => $i,
            ];
        }
        foreach ($words as $i => &$word) {
            $w = S::create($word['word'], 'UTF-8');
            if (!in_array($w, $this->articles)) {
                $w->trim($this->separator);
                try {
                    $newW = $this->convertWordObject($w);
                } catch (\Exception $e) {
                    $newW = $w;
                }
                if ($newW != $w) {
                    $s = S::create(substr_replace($s, $newW, $word['pos'], strlen($w)));
                    foreach ($words as $j => $word) {
                        if ($j > $i) {
                            $words[$j]['pos'] += strlen($newW) - strlen($w);
                        }
                    }
                    if (isset($words[$i - 1]) && in_array($words[$i - 1]['word'], $this->articles)) {
                        $w = S::create($words[$i - 1]['word'], 'UTF-8');
                        $newW = $this->convertWordObject($w);
                        if ($newW != $w) {
                            $s = S::create(substr_replace($s, $newW, $words[$i - 1]['pos'], strlen($w)));
                            foreach ($words as $j => $word) {
                                if ($j > $i) {
                                    $words[$j]['pos'] += strlen($newW) - strlen($w);
                                }
                            }
                        }
                    }
                }
            }
        }

        return (string) $s;
    }
}
