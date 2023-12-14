<?php

namespace Dagstuhl\DataCite\Metadata;

class NameIdentifier
{
    private string $nameIdentifier;
    private string $nameIdentifierScheme;
    private ?string $schemeUri;

    const NAME_IDENTIFIER_SCHEME_ORCID = 'ORCID';
    const SCHEME_URI_ORCID = 'https://orcid.org';

    public function __construct(string $nameIdentifier, string $nameIdentifierScheme, string $schemeUri = NULL)
    {
        $this->nameIdentifier = $nameIdentifier;
        $this->nameIdentifierScheme = $nameIdentifierScheme;
        $this->schemeUri = $schemeUri;
    }

    public function toApiObject(): object
    {
        $n['nameIdentifier'] = $this->nameIdentifier;
        $n['nameIdentifierScheme'] = $this->nameIdentifierScheme;

        if ($this->schemeUri !== NULL) {
            $n['schemeUri'] = $this->schemeUri;
        }

        return (object) $n;
    }

    public static function orcid(string $orcid): static
    {
        return new static($orcid, self::NAME_IDENTIFIER_SCHEME_ORCID, self::SCHEME_URI_ORCID);
    }
}