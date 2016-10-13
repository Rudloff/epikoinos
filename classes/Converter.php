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
        $this->separator = S::create($separator);
        $this->lexicon = new Lexicon(__DIR__.'/../lexique-dicollecte-names.csv');
        $this->cache = new \Gilbitron\Util\SimpleCache();
        $this->enableCache = $enableCache;
        $this->overwriteCache = $overwriteCache;
    }

    private function convertWordObject(S $word)
    {
        switch ($word) {
            case 'le':
                return S::create('la.le');
            case 'les':
            case 'des':
            case 'ces':
                return $word;
            case 'ce':
                return S::create('ce.tte');
            case 'cet':
                return S::create('cet.te');
            case 'ceux':
                return S::create('ceux.elles');
            case 'tout':
                return S::create('tout.e');
            case 'tous':
                return S::create('tou.te.s');
        }

        $w = new Word($word, $this->lexicon, $this->separator);
        $separator = rawurlencode($this->separator);
        if ($this->enableCache && !$this->overwriteCache && $this->cache->is_cached($word.$separator)) {
            return $this->cache->get_cache($word.$separator);
        }
        $return = $w->convert();
        if ($this->enableCache) {
            $this->cache->set_cache($word.$separator, $return);
        }

        return $return;
    }

    public function convertWord($word)
    {
        return (string) $this->convertWordObject(S::create($word));
    }

    private function updateWordsPosition($words, $i, $newWord, $oldWord)
    {
        foreach ($words as $j => $word) {
            if ($j > $i) {
                $words[$j]['pos'] += strlen($newWord) - strlen($oldWord);
            }
        }

        return $words;
    }

    public function convert($string)
    {
        $s = S::create($string);
        $words = [];
        foreach (str_word_count($s, 2, $this->separator.$this->diacritics) as $i => $word) {
            $word = S::create($word, 'UTF-8');
            $pos = $i;
            if ($word->endsWith($this->separator)) {
                $word = $word->removeRight($this->separator);
            }
            if ($word->startsWith("l'") || $word->startsWith("L'")) {
                $word = $word->removeLeft("l'")->removeLeft("L'");
                $pos = +2;
            }
            $words[] = [
                'word' => $word,
                'pos'  => $pos,
            ];
        }
        foreach ($words as $i => &$word) {
            if (!in_array($word['word'], $this->articles)) {
                try {
                    $newWord = $this->convertWordObject($word['word']);
                } catch (\Exception $e) {
                    $newWord = S::create($word['word']);
                }
                if ($newWord != $word['word']) {
                    $s = S::create(substr_replace($s, $newWord, $word['pos'], strlen($word['word'])));
                    $words = $this->updateWordsPosition($words, $i, $newWord, $word['word']);
                    if (isset($words[$i - 1]) && in_array($words[$i - 1]['word'], $this->articles)) {
                        $newWord = $this->convertWordObject($words[$i - 1]['word']);
                        if ($newWord != $words[$i - 1]['word']) {
                            $s = S::create(
                                substr_replace(
                                    $s,
                                    $newWord,
                                    $words[$i - 1]['pos'],
                                    strlen($words[$i - 1]['word'])
                                )
                            );
                            $words = $this->updateWordsPosition($words, $i, $newWord, $words[$i - 1]['word']);
                        }
                    }
                }
            }
        }

        return (string) $s;
    }
}
