<?php

namespace LZI\DataCite\Metadata;

class Title
{
    private $title;
    private $lang;
    private $titleType;

    public function __construct($title, $language = NULL, $titleType = NULL)
    {
        $this->title = $title;
        $this->lang = $language;
        $this->titleType = $titleType;
    }


    public static function main(string $title, $language = NULL)
    {
        return new static($title, $language);
    }

    public static function alternative(string $title, $language = NULL)
    {
        return new static($title, $language, 'AlternativeTitle');
    }

    public static function subtitle(string $title, $language = NULL)
    {
        return new static($title, $language, 'Subtitle');
    }

    public static function translatedTitle(string $title, $language = NULL)
    {
        return new static($title, $language, 'TranslatedTitle');
    }

    public static function other(string $title, $language = NULL)
    {
        return new static($title, $language, 'Other');
    }


    public function toApiObject()
    {
        $t = [];

        $t['title'] = $this->title;

        foreach([ 'lang', 'titleType' ] as $prop) {
            if($this->{$prop} !== NULL) {
                $t[$prop] = $this->{$prop};
            }
        }

        return $t;
    }
}