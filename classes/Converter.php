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
     * Check if the word can be converted with a simple switch() rule.
     *
     * @param string $word Word to convert
     *
     * @return string[] Array of converted word possibilities
     */
    private function getSimpleResult($word)
    {
        switch ($word) {
            case 'le':
                return ['la.le'];
            case 'les':
            case 'des':
            case 'ces':
                return [$word];
            case 'ce':
                return ['ce.tte'];
            case 'cet':
                return ['cet.te'];
            case 'ceux':
                return ['ceux.elles'];
            case 'tout':
                return ['tout.e'];
            case 'tous':
                return ['tou.te.s'];
        }

        return [];
    }

    /**
     * Convert a word to its epicene form.
     *
     * @param string $word Word to convert
     *
     * @return string[] Array of converted word possibilities
     */
    public function convertWord($word)
    {
        $simpleResult = $this->getSimpleResult($word);
        if (!empty($simpleResult)) {
            return $simpleResult;
        }

        $separator = rawurlencode($this->separator);
        if ($this->enableCache && !$this->overwriteCache && $this->cache->is_cached($word.$separator)) {
            return json_decode($this->cache->get_cache($word.$separator));
        }
        $w = new Word(S::create($word), $this->lexicon, $this->separator);
        $return = $w->convert();
        $return = array_map('strval', $return);
        if ($this->enableCache) {
            $this->cache->set_cache($word.$separator, json_encode($return));
        }

        return $return;
    }
}
