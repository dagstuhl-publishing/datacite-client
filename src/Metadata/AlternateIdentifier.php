<?php

namespace Dagstuhl\DataCite\Metadata;

class AlternateIdentifier
{
    private string $alternateIdentifier;
    private string $alternateIdentifierType;

    public function __construct(string $alternateIdentifier, string $alternateIdentifierType)
    {
        $this->alternateIdentifier = $alternateIdentifier;
        $this->alternateIdentifierType = $alternateIdentifierType;
    }

    public static function urn(string $urn): static
    {
        return new static($urn, 'URN');
    }

    public static function isbn(string $isbn): static
    {
        return new static($isbn, 'ISBN');
    }

    public function toApiObject(): object
    {
        return (object) [
            'alternateIdentifier' => $this->alternateIdentifier,
            'alternateIdentifierType' => $this->alternateIdentifierType
        ];
    }
}