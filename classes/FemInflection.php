<?php
/**
 * FemInflection class.
 */
namespace Epíkoinos;

use Dicollecte\Inflection;
use Stringy\Stringy as S;

/**
 * Class used to manage feminine inflections.
 * This class extends the Inflection class in order to add some useful functions.
 */
class FemInflection extends Inflection
{

    /**
     * Masculine inflection.
     * @var Inflection
     */
    public $mascInflection;

    /**
     * FemInflection constructor
     * @param Inflection $inflection     Feminine inflection to extend
     * @param Inflection $mascInflection Corrresponding masculine inflection
     */
    public function __construct(Inflection $inflection, Inflection $mascInflection)
    {
        $this->inflection = $inflection->inflection;
        $this->lemma = $inflection->lemma;
        $this->tags = $inflection->tags;
        $this->mascInflection = $mascInflection;
    }

    /**
     * Get prefix.
     *
     * @return S Prefix
     */
    private function getPrefix()
    {
        $string =  new S($this->mascInflection->inflection);
        return $string->toLowerCase()->longestCommonPrefix($this->inflection);
    }

    /**
     * Get plural suffix.
     *
     * @return S Plural suffix
     */
    public function getPlural()
    {
        $string =  new S($this->mascInflection->inflection);
        return $string->longestCommonSuffix($this->inflection);
    }

    /**
     * Get suffix.
     *
     * @return S Suffix
     */
    public function getSuffix()
    {
        $suffix = S::create($this->inflection)->removeLeft($this->getPrefix());
        switch ($suffix) {
            case 'se':
                $suffix = S::create('euse');
                break;
        }

        return $suffix;
    }
}
