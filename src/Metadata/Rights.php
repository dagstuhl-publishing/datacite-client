<?php

namespace LZI\DataCite\Metadata;

class Rights
{
    private $rights;
    private $rightsIdentifier;
    private $rightsUri;
    private $lang;
    private $rightsIdentifierScheme;
    private $schemeUri;

    const RIGHTS_INFO_EU_SEMANTICS_OPEN_ACCESS = 'info:eu-repo/semantics/openAccess';

    const RIGHTS_IDENTIFIER_SCHEME_SPDX = 'SPDX';
    const SCHEME_URI_SPDX = 'https://spdx.org/licenses/';

    const OPTIONAL_PROPERTIES = [ 'rightsIdentifier', 'rightsUri', 'lang', 'rightsIdentifierScheme', 'schemeUri' ];

    const SPDX_LICENSES = [
        'CC-BY-3.0' => [
            'Creative Commons Attribution 3.0 Unported',
            'https://creativecommons.org/licenses/by/3.0/legalcode',
            'CC-BY-3.0',
        ],
        'CC-BY-SA-3.0' => [
            'Creative Commons Attribution Share Alike 3.0 Unported',
            'https://creativecommons.org/licenses/by-sa/3.0/legalcode',
            'CC-BY-SA-3.0',
        ]
    ];

    public function __construct(
        string $rights,
        $rightsUri = NULL,
        $language = NULL,
        $rightsIdentifier = NULL,
        $rightsIdentifierScheme = NULL,
        $schemeUri = NULL)
    {
        $this->rights = $rights;
        $this->rightsUri = $rightsUri;
        $this->lang = $language;
        $this->rightsIdentifier = $rightsIdentifier;
        $this->rightsIdentifierScheme = $rightsIdentifierScheme;
        $this->schemeUri = $schemeUri;
    }

    public function toApiObject()
    {
        $r = [ 'rights' => $this->rights ];

        foreach(self::OPTIONAL_PROPERTIES as $prop) {
            if ($this->{$prop} !== NULL) {
                $r[$prop] = $this->{$prop};
            }
        }

        return (object) $r;
    }

    public static function infoEuRepoSemanticsOpenAccess()
    {
        return new self(self::RIGHTS_INFO_EU_SEMANTICS_OPEN_ACCESS);
    }

    public static function getLicenseBySpdxIdentifier($rightsIdentifier)
    {
        $license = self::SPDX_LICENSES[$rightsIdentifier];

        return new self(
            $license[0], $license[1], $license[3] ?? 'en', $license[2],
            self::RIGHTS_IDENTIFIER_SCHEME_SPDX, self::SCHEME_URI_SPDX
        );
    }

    public static function license_CC_BY_3_0()
    {
        return self::getLicenseBySpdxIdentifier('CC-BY-3.0');
    }
}