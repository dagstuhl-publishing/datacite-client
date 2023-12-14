<?php

namespace Dagstuhl\DataCite\Metadata;

class NameIdentifier
{
    private $nameIdentifier;
    private $nameIdentifierScheme;
    private $schemeUri;

    const NAME_IDENTIFIER_SCHEME_ORCID = 'ORCID';
    const SCHEME_URI_ORCID = 'https://orcid.org';

    public function __construct(string $nameIdentifier, string $nameIdentifierScheme, $schemeUri = NULL)
    {
        $this->nameIdentifier = $nameIdentifier;
        $this->nameIdentifierScheme = $nameIdentifierScheme;
        $this->schemeUri = $schemeUri;
    }

    public function toApiObject()
    {
        $n['nameIdentifier'] = $this->nameIdentifier;
        $n['nameIdentifierScheme'] = $this->nameIdentifierScheme;

        if ($this->schemeUri !== NULL) {
            $n['schemeUri'] = $this->schemeUri;
        }

        return (object) $n;
    }

    public static function orcid($orcid)
    {
        return new self($orcid, self::NAME_IDENTIFIER_SCHEME_ORCID, self::SCHEME_URI_ORCID);
    }
}