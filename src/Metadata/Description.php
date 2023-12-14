<?php

namespace Dagstuhl\DataCite\Metadata;

class Description
{
    private string $description;
    private string $descriptionType;
    private ?string $language;

    public function __construct($description, $descriptionType, $language = NULL)
    {
        $this->description = $description;
        $this->descriptionType = $descriptionType;
        $this->language = $language;
    }

    public static function abstract(string $description, $language = NULL): static
    {
        return new static($description, 'Abstract', $language);
    }

    public static function methods(string $description): static
    {
        return new static($description, 'Methods');
    }

    public static function seriesInformation(string $description, $language = NULL): static
    {
        return new static($description, 'SeriesInformation', $language);
    }

    public static function tableOfContents(string $description, $language = NULL): static
    {
        return new static($description, 'TableOfContents', $language);
    }

    public static function technicalInfo(string $description, $language = NULL): static
    {
        return new static($description, 'TechnicalInfo', $language);
    }

    public static function other(string $description, $language = NULL): static
    {
        return new static($description, 'Other', $language);
    }

    public function toApiObject(): object
    {
        $result = [
            'description' => $this->description,
            'descriptionType' => $this->descriptionType
        ];

        if ($this->language !== NULL) {
            $result['lang'] = $this->language;
        }

        return (object) $result;
    }
}