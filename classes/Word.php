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
     * Feminine inflections.
     *
     * @var \Dicollecte\Inflection[]
     */
    private $femInflections;

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
        $this->femInflections = $this->getFemInflections();
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
    private function getFemInflections()
    {
        if (!empty($this->mascInflections)) {
            $femInflections = [];
            foreach ($this->mascInflections as $mascInflection) {
                foreach ($this->lexicon->getByLemma($mascInflection->lemma) as $inflection) {
                    if (($mascInflection->hasTag('inv') ||
                            $mascInflection->hasTag('pl') && $inflection->hasTag('pl')
                            || $mascInflection->hasTag('sg') && $inflection->hasTag('sg'))
                        && ($mascInflection->hasTag('adj') && $inflection->hasTag('adj')
                            || $mascInflection->hasTag('nom') && $inflection->hasTag('nom'))
                        && $inflection->hasTag('fem')
                    ) {
                        $femInflections[] = new FemInflection($inflection, $mascInflection);
                    }
                }
            }
            if (!empty($femInflections)) {
                return $femInflections;
            }
        }
    }




    /**
     * Convert word to its epicene form.
     *
     * @return S Epicene form
     */
    public function convert()
    {
        if (!empty($this->femInflections)) {
            $return = [];
            foreach ($this->femInflections as $femInflection) {
                if (isset($femInflection->mascInflection)) {
                    $word = $this->string;
                    $plural = $femInflection->getPlural($femInflection);
                    $suffix = $femInflection->getSuffix();
                    if ($plural->length() > 0) {
                        $suffix = $suffix->removeRight((string) $plural)->ensureRight($this->separator.$plural);
                        $word = $word->removeRight((string) $plural);
                    }
                    $return[] =  $word->ensureRight($this->separator.$suffix);
                }
            }
            return array_unique($return);
        } else {
            return [$this->string];
        }
    }
}
