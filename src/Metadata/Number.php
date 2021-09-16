<?php

namespace LZI\DataCite\Metadata;

class Number
{
    private $number;
    private $numberType;

    public function __construct($number, $numberType = NULL)
    {
        $this->number = $number;
        $this->numberType = $numberType;
    }


    public static function main(string $number)
    {
        return new static($number);
    }

    public static function article(string $number)
    {
        return new static($number, 'Article');
    }

    public static function chapter(string $number)
    {
        return new static($number, 'Chapter');
    }

    public static function report(string $number)
    {
        return new static($number, 'Report');
    }

    public static function other(string $number)
    {
        return new static($number, 'Other');
    }


    public function toApiObject(): object
    {
        $t = [];

        $t['number'] = $this->number;

        if($this->{'numberType'} !== NULL) {
            $t['numberType'] = $this->{'numberType'};
        }

        return (object) $t;
    }
}