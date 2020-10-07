<?php

namespace LZI\DataCite\Metadata;

class Affiliation
{
    private $name;
    private $affiliationIdentifier;
    private $affiliationIdentifierScheme;
    private $schemeUri;

    public function __construct(string $name, $affiliationIdentifier = NULL, $affiliationIdentifierScheme = NULL, $schemeUri = NULL)
    {
        $this->name = $name;
        $this->affiliationIdentifier = $affiliationIdentifier;
        $this->affiliationIdentifierScheme = $affiliationIdentifierScheme;
        $this->schemeUri = $schemeUri;
    }

    public static function ror($affiliationIdentifier, $name = NULL)
    {
        if ($name === NULL) {
            $apiObject = file_get_contents('https://api.ror.org/organizations/' . $affiliationIdentifier);

            $apiObject = json_decode($apiObject);

            $name = $apiObject->name ?? $affiliationIdentifier;
        }

        return new static($name, $affiliationIdentifier, 'ROR', 'https://ror.org');
    }

    public function toApiObject()
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