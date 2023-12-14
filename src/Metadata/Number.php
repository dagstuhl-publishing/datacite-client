<?php

namespace Dagstuhl\DataCite\Metadata;

class Number
{
    private string $number;
    private ?string $numberType;

    public function __construct(string $number, string $numberType = NULL)
    {
        $this->number = $number;
        $this->numberType = $numberType;
    }


    public static function main(string $number): static
    {
        return new static($number);
    }

    public static function article(string $number): static
    {
        return new static($number, 'Article');
    }

    public static function chapter(string $number): static
    {
        return new static($number, 'Chapter');
    }

    public static function report(string $number): static
    {
        return new static($number, 'Report');
    }

    public static function other(string $number): static
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