<?php

namespace Epíkoinos;

use Stringy\Stringy as S;

class Converter
{

    private $separator;
    private $suffixes = array(
        'teur'=>'rice',
        'if'=>'ive',
    );

    public function __construct($separator = '.')
    {
        $this->separator = $separator;
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');
    }

    private function convertWordObject(S $w)
    {
        foreach ($this->suffixes as $suffix => $add) {
            if ($w->endsWith($suffix)) {
                $w = $w->ensureRight($this->separator.$add);
            }
        }
        return $w;
    }

    public function convertWord($word)
    {
        return (string)$this->convertWordObject(S::create($word));
    }

    public function convert($string)
    {
        $s = S::create($string);
        foreach (str_word_count($s, 2, $this->separator) as $i => $word) {
            $w = S::create($word);
            $w->trim($this->separator);
            $newW = $this->convertWordObject($w);
            if ($newW != $w) {
                $s = $s->regexReplace(
                    $w.'(?!'.$newW->removeLeft($w).')',
                    $newW
                );
            }
        }
        return (string)$s;
    }
}
