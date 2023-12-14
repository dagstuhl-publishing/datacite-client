<?php

namespace Dagstuhl\DataCite\Metadata;

class Affiliation
{
    private string $name;
    private ?string $affiliationIdentifier;
    private ?string $affiliationIdentifierScheme;
    private ?string $schemeUri;

    public function __construct(string $name, string $affiliationIdentifier = NULL, string $affiliationIdentifierScheme = NULL, string $schemeUri = NULL)
    {
        $this->name = $name;
        $this->affiliationIdentifier = $affiliationIdentifier;
        $this->affiliationIdentifierScheme = $affiliationIdentifierScheme;
        $this->schemeUri = $schemeUri;
    }

    public static function ror(string $affiliationIdentifier, string $name = NULL): static
    {
        if ($name === NULL) {
            $apiObject = file_get_contents('https://api.ror.org/organizations/' . $affiliationIdentifier);

            $apiObject = json_decode($apiObject);

            $name = $apiObject->name ?? $affiliationIdentifier;
        }

        return new static($name, $affiliationIdentifier, 'ROR', 'https://ror.org');
    }

    public function toApiObject(): object
    {
        $a = [];

        $a['name'] = $this->name;

        foreach([ 'affiliationIdentifier', 'affiliationIdentifierScheme', 'schemeUri' ] as $prop) {
            if ($this->{$prop} !== NULL) {
                $a[$prop] = $this->{$prop};
            }
        }

        return (object) $a;
    }
}