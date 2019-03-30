<?php
/**
 * FemInflection class.
 */

namespace Epíkoinos;

use Dicollecte\Inflection;
use Stringy\Stringy;

/**
 * Class used to manage feminine inflections.
 * This class extends the Inflection class in order to add some useful functions.
 */
class FemInflection extends Inflection
{
    /**
     * Masculine inflection.
     *
     * @var Inflection
     */
    public $mascInflection;

    /**
     * FemInflection constructor.
     *
     * @param Inflection $inflection     Feminine inflection to extend
     * @param Inflection $mascInflection Corrresponding masculine inflection
     */
    public function __construct(Inflection $inflection, Inflection $mascInflection)
    {
        parent::__construct(0, $inflection->inflection, $inflection->lemma, $inflection->tags);
        $this->mascInflection = $mascInflection;
    }

    /**
     * Get prefix.
     *
     * @return Stringy Prefix
     */
    private function getPrefix()
    {
        $string = new Stringy($this->mascInflection->inflection);

        return $string->toLowerCase()->longestCommonPrefix($this->inflection);
    }

    /**
     * Get plural suffix.
     *
     * @return Stringy Plural suffix
     */
    public function getPlural()
    {
        $string = new Stringy($this->mascInflection->inflection);

        return $string->longestCommonSuffix($this->inflection);
    }

    /**
     * Get suffix.
     *
     * @return Stringy Suffix
     */
    public function getSuffix()
    {
        $suffix = Stringy::create($this->inflection)->removeLeft($this->getPrefix());
        switch ($suffix) {
            case 'se':
                $suffix = Stringy::create('euse');
                break;
        }

        return $suffix;
    }
}
