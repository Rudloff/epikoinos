<?php
/**
 * Converter class.
 */
namespace Epíkoinos;

use Dicollecte\Lexicon;
use Gilbitron\Util\SimpleCache;
use Stringy\Stringy as S;

/**
 * Class used to convert words.
 */
class Converter
{
    /**
     * Separator character to use in epicene forms.
     *
     * @var S
     */
    private $separator = '.';

    /**
     * Lexicon used to find word inflections.
     *
     * @var Lexicon
     */
    private $lexicon;

    /**
     * Cache.
     *
     * @var SimpleCache
     */
    private $cache;

    /**
     * Enable cache?
     *
     * @var bool
     */
    private $enableCache = true;

    /**
     * Force refreshing of cache?
     *
     * @var bool
     */
    private $overwriteCache = false;

    /**
     * String containing all the letters that are considred diacritics.
     * This is used in order to make str_word_count() work correctly with French words.
     *
     * @var string
     */
    private $diacritics = 'ÀàÂâÆæÇçÈèÉéÊêËëÎîÏïÔôŒœÙùÛûÜü';

    /**
     * List of French articles.
     * This is used in order to convert each word along with its article.
     *
     * @var string[]
     */
    private $articles = ['un', 'le', 'ce', 'cet', 'tout', 'tous'];

    /**
     * Converter constructor.
     *
     * @param string $separator      Separator character to use in epicene forms
     * @param bool   $enableCache    Enable cache?
     * @param bool   $overwriteCache Force refreshing of cache?
     */
    public function __construct($separator = '.', $enableCache = true, $overwriteCache = false)
    {
        $this->separator = S::create($separator);
        $this->lexicon = new Lexicon(__DIR__.'/../lexique-dicollecte-names.csv');
        $this->cache = new SimpleCache();
        $this->enableCache = $enableCache;
        $this->overwriteCache = $overwriteCache;
    }

    /**
     * Convert a Stringy object to its epicene form.
     *
     * @param S $word Word to convert
     *
     * @return S Converted word
     */
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
            return S::create($this->cache->get_cache($word.$separator));
        }
        $return = $w->convert();
        if ($this->enableCache) {
            $this->cache->set_cache($word.$separator, $return);
        }

        return $return;
    }

    /**
     * Convert a word to its epicene form.
     *
     * @param string $word Word to convert
     *
     * @return string Converted word
     */
    public function convertWord($word)
    {
        return (string) $this->convertWordObject(S::create($word));
    }

    /**
     * Update words position in string after a word has been replaced.
     *
     * @param array  $words   Words in string
     * @param int    $i       Index of the word we're currently processing
     * @param string $newWord Converted word
     * @param string $oldWord Word to be replace
     *
     * @return array Words in string with updated position
     */
    private function updateWordsPosition($words, $i, $newWord, $oldWord)
    {
        foreach ($words as $j => $word) {
            if ($j > $i) {
                $words[$j]['pos'] += strlen($newWord) - strlen($oldWord);
            }
        }

        return $words;
    }

    /**
     * Convert words in a string to their epicene form.
     *
     * @param string $string String to parse
     *
     * @return string String with converted words
     */
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
