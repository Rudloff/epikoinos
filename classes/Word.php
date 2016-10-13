<?php

namespace Epíkoinos;

use Dicollecte\Lexicon;
use Stringy\Stringy as S;

class Word
{
    public $string;
    private $lexicon;
    private $mascInflection;
    private $femInflection;
    private $prefix;
    private $suffix;
    private $separator;
    private $plural;

    public function __construct(S $string, Lexicon $lexicon, S $separator)
    {
        $this->string = $string;
        $this->separator = $separator;
        $this->lexicon = $lexicon;
        $this->mascInflection = $this->getMascInflection();
        $this->femInflection = $this->getFemInflection();
        $this->plural = $this->getPlural();
        $this->prefix = $this->getPrefix();
        $this->suffix = $this->getSuffix();
    }

    private function getMascInflection()
    {
        $inflections = $this->lexicon->getByInflection($this->string);
        if (empty($inflections)) {
            throw new \Exception("Can't find this inflection");
        }
        foreach ($inflections as $inflection) {
            if ($inflection->inflection == $this->string->toLowerCase()
                && $inflection->hasTag('mas')
            ) {
                $mascInflection = $inflection;
                break;
            }
        }
        if (isset($mascInflection)) {
            return $mascInflection;
        }
    }

    private function getFemInflection()
    {
        if (isset($this->mascInflection)) {
            foreach ($this->lexicon->getByLemma($this->mascInflection->lemma) as $inflection) {
                if (($this->mascInflection->hasTag('inv') ||
                        $this->mascInflection->hasTag('pl') && $inflection->hasTag('pl')
                        || $this->mascInflection->hasTag('sg') && $inflection->hasTag('sg'))
                    && ($this->mascInflection->hasTag('adj') && $inflection->hasTag('adj')
                        || $this->mascInflection->hasTag('nom') && $inflection->hasTag('nom'))
                    && $inflection->hasTag('fem')
                ) {
                    $femInflection = $inflection;
                    break;
                }
            }
            if (isset($femInflection)) {
                return $femInflection;
            }
        }
    }

    private function getPrefix()
    {
        if (isset($this->femInflection)) {
            return $this->string->toLowerCase()->longestCommonPrefix($this->femInflection->inflection);
        }
    }

    private function getPlural()
    {
        if (isset($this->femInflection)) {
            return $this->string->longestCommonSuffix($this->femInflection->inflection);
        }
    }

    private function getSuffix()
    {
        if (isset($this->femInflection)) {
            $suffix = S::create($this->femInflection->inflection)->removeLeft($this->prefix);
            switch ($suffix) {
                case 'se':
                    $suffix = S::create('euse');
                    break;
            }
            if ($this->mascInflection->hasTag('pl')) {
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

    public function convert()
    {
        if (isset($this->mascInflection) && isset($this->femInflection)) {
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
