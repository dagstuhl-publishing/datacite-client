<?php

namespace LZI\DataCite\Metadata;

class AlternateIdentifier
{
    private $alternateIdentifier;
    private $alternateIdentifierType;

    public function __construct(string $alternateIdentifier, string $alternateIdentifierType)
    {
        $this->alternateIdentifier = $alternateIdentifier;
        $this->alternateIdentifierType = $alternateIdentifierType;
    }

    public static function urn($urn)
    {
        return new static($urn, 'URN');
    }

    public static function isbn($isbn)
    {
        return new static($isbn, 'ISBN');
    }

    public function toApiObject()
    {
        return (object) [
            'alternateIdentifier' => $this->alternateIdentifier,
            'alternateIdentifierType' => $this->alternateIdentifierType
        ];
    }
}