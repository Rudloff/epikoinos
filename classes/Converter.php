<?php
/**
 * Converter class.
 */

namespace Epíkoinos;

use Dicollecte\Lexicon;
use Gilbitron\Util\SimpleCache;
use Stringy\Stringy;

/**
 * Class used to convert words.
 */
class Converter
{
    /**
     * Separator character to use in epicene forms.
     *
     * @var Stringy
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
     * List of predefined results.
     *
     * @var array
     */
    private $simpleResults = [
        'le' => ['la.le' => [
            'masculine' => 'le',
            'feminine'  => 'la',
            'epicene'   => 'la.le',
        ]],
        'ce' => ['ce.tte' => [
            'masculine' => 'ce',
            'feminine'  => 'cette',
            'epicene'   => 'ce.tte',
        ]],
        'cet' => ['cet.te' => [
            'masculine' => 'cet',
            'feminine'  => 'cette',
            'epicene'   => 'cet.te',
        ]],
        'ceux' => ['ceux.elles' => [
            'masculine' => 'ceux',
            'feminine'  => 'celles',
            'epicene'   => 'ceux.elles',
        ]],
        'tout' => ['tout.e' => [
            'masculine' => 'tout',
            'feminine'  => 'toute',
            'epicene'   => 'tout.e',
        ]],
        'tous' => ['tou.te.s' => [
            'masculine' => 'tous',
            'feminine'  => 'toutes',
            'epicene'   => 'tou.te.s',
        ]],
    ];

    /**
     * Converter constructor.
     *
     * @param string $separator      Separator character to use in epicene forms
     * @param bool   $enableCache    Enable cache?
     * @param bool   $overwriteCache Force refreshing of cache?
     */
    public function __construct($separator = '.', $enableCache = true, $overwriteCache = false)
    {
        $this->separator = Stringy::create($separator);
        $this->lexicon = new Lexicon(__DIR__.'/../lexique-dicollecte-names.csv');
        $this->cache = new SimpleCache();
        $this->enableCache = $enableCache;
        $this->overwriteCache = $overwriteCache;
    }

    /**
     * Check if the word can be converted with a simple rule.
     *
     * @param string $word Word to convert
     *
     * @return array Array of converted word possibilities
     */
    private function getSimpleResult($word)
    {
        switch ($word) {
            case 'les':
            case 'des':
            case 'ces':
                return [$word => [
                    'masculine' => $word,
                    'feminine'  => $word,
                    'epicene'   => $word,
                ]];
        }
        if (isset($this->simpleResults[$word])) {
            return $this->simpleResults[$word];
        } else {
            return [];
        }
    }

    /**
     * Convert a word to its epicene form.
     *
     * @param string $word Word to convert
     *
     * @return array[] Array of converted word possibilities
     */
    public function convertWord($word)
    {
        $word = trim($word);
        $simpleResult = $this->getSimpleResult($word);
        if (!empty($simpleResult)) {
            return $simpleResult;
        }

        $separator = rawurlencode($this->separator);
        if ($this->enableCache && !$this->overwriteCache && $this->cache->is_cached($word.$separator)) {
            return json_decode($this->cache->get_cache($word.$separator), true);
        }
        $w = new Word(Stringy::create($word), $this->lexicon, $this->separator);
        $return = $w->convert();
        if ($this->enableCache) {
            $this->cache->set_cache($word.$separator, json_encode($return));
        }

        return $return;
    }
}
