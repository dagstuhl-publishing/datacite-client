<?php

namespace Dagstuhl\DataCite\Metadata;

class Title
{
    private string $title;
    private ?string $lang;
    private ?string $titleType;

    public function __construct(string $title, string $language = NULL, string $titleType = NULL)
    {
        $this->title = $title;
        $this->lang = $language;
        $this->titleType = $titleType;
    }


    public static function main(string $title, string $language = NULL): static
    {
        return new static($title, $language);
    }

    public static function alternative(string $title, string $language = NULL): static
    {
        return new static($title, $language, 'AlternativeTitle');
    }

    public static function subtitle(string $title, string $language = NULL): static
    {
        return new static($title, $language, 'Subtitle');
    }

    public static function translatedTitle(string $title, string $language = NULL): static
    {
        return new static($title, $language, 'TranslatedTitle');
    }

    public static function other(string $title, string $language = NULL): static
    {
        return new static($title, $language, 'Other');
    }


    public function toApiObject(): object
    {
        $t = [];

        $t['title'] = $this->title;

        foreach([ 'lang', 'titleType' ] as $prop) {
            if($this->{$prop} !== NULL) {
                $t[$prop] = $this->{$prop};
            }
        }

        return (object) $t;
    }
}