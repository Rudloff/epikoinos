<?php
/**
 * Word class.
 */
namespace Epíkoinos;

use Dicollecte\Lexicon;
use Stringy\Stringy as S;

/**
 * Class used to handle words and their inflections.
 */
class Word
{
    /**
     * Base word string.
     *
     * @var S
     */
    public $string;

    /**
     * Lexicon used to look for inflections.
     *
     * @var Lexicon
     */
    private $lexicon;

    /**
     * Masculine inflection.
     *
     * @var \Dicollecte\Inflection[]
     */
    private $mascInflections;

    /**
     * Feminine inflection.
     *
     * @var \Dicollecte\Inflection
     */
    private $femInflection;

    /**
     * Word prefix (common to masculine and feminine forms).
     *
     * @var S
     */
    private $prefix;

    /**
     * Word suffix (added by conversion).
     *
     * @var S
     */
    private $suffix;

    /**
     * Separator used in epicene form.
     *
     * @var S
     */
    private $separator;

    /**
     * Plural suffix (generally "s").
     *
     * @var S
     */
    private $plural;

    /**
     * Word constructor.
     *
     * @param S       $string    Base word string
     * @param Lexicon $lexicon   Lexicon used to look for inflections
     * @param S       $separator Separator used in epicene form
     */
    public function __construct(S $string, Lexicon $lexicon, S $separator)
    {
        $this->string = $string;
        $this->separator = $separator;
        $this->lexicon = $lexicon;
        $this->mascInflections = $this->getMascInflections();
        $this->femInflection = $this->getFemInflection();
        $this->plural = $this->getPlural();
        $this->prefix = $this->getPrefix();
        $this->suffix = $this->getSuffix();
    }

    /**
     * Get masculine inflections.
     *
     * @return \Dicollecte\Inflection[] Masculine inflections
     */
    private function getMascInflections()
    {
        $inflections = $this->lexicon->getByInflection($this->string);
        if (empty($inflections)) {
            throw new \Exception("Can't find this inflection");
        }
        $mascInflections = [];
        foreach ($inflections as $inflection) {
            if ($inflection->inflection == $this->string->toLowerCase()
                && $inflection->hasTag('mas')
            ) {
                $mascInflections[] = $inflection;
            }
        }
        if (!empty($mascInflections)) {
            return $mascInflections;
        }
    }

    /**
     * Get feminine inflection.
     *
     * @return \Dicollecte\Inflection Feminine inflection
     */
    private function getFemInflection()
    {
        if (!empty($this->mascInflections)) {
            foreach ($this->mascInflections as $mascInflection) {
                foreach ($this->lexicon->getByLemma($mascInflection->lemma) as $inflection) {
                    if (($mascInflection->hasTag('inv') ||
                            $mascInflection->hasTag('pl') && $inflection->hasTag('pl')
                            || $mascInflection->hasTag('sg') && $inflection->hasTag('sg'))
                        && ($mascInflection->hasTag('adj') && $inflection->hasTag('adj')
                            || $mascInflection->hasTag('nom') && $inflection->hasTag('nom'))
                        && $inflection->hasTag('fem')
                    ) {
                        $femInflection = $inflection;
                        $femInflection->mascInflections = $mascInflection;
                        break;
                    }
                }
            }
            if (isset($femInflection)) {
                return $femInflection;
            }
        }
    }

    /**
     * Get prefix.
     *
     * @return S Prefix
     */
    private function getPrefix()
    {
        if (isset($this->femInflection)) {
            return $this->string->toLowerCase()->longestCommonPrefix($this->femInflection->inflection);
        }
    }

    /**
     * Get plural suffix.
     *
     * @return S Plural suffix
     */
    private function getPlural()
    {
        if (isset($this->femInflection)) {
            return $this->string->longestCommonSuffix($this->femInflection->inflection);
        }
    }

    /**
     * Get suffix.
     *
     * @return S Suffix
     */
    private function getSuffix()
    {
        if (isset($this->femInflection)) {
            $suffix = S::create($this->femInflection->inflection)->removeLeft($this->prefix);
            switch ($suffix) {
                case 'se':
                    $suffix = S::create('euse');
                    break;
            }
            if ($this->femInflection->mascInflections->hasTag('pl')) {
                if ($this->plural->length() > 0) {
                    $suffix = $suffix->removeRight((string) $this->plural)->ensureRight($this->separator.$this->plural);
                }
                switch ($suffix) {
                    case 'les':
                        $suffix = S::create('ales');
                        break;
                    case 'se.s':
                        $suffix = S::create('euse.s');
                        break;
                }
            }

            return $suffix;
        }
    }

    /**
     * Convert word to its epicene form.
     *
     * @return S Epicene form
     */
    public function convert()
    {
        if (isset($this->femInflection) && isset($this->femInflection->mascInflections)) {
            $return = $this->string;
            if ($this->plural->length() > 0) {
                $return = $return->removeRight((string) $this->plural);
            }

            return $return->ensureRight($this->separator.$this->suffix);
        } else {
            return $this->string;
        }
    }
}
