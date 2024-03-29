<?php

namespace Dagstuhl\DataCite\Metadata;

class RelatedIdentifier
{
    const TYPE_ARK = 'ARK';
    const TYPE_ARXIV = 'arXiv';
    const TYPE_BIBCODE = 'bibcode';
    const TYPE_DOI = 'DOI';
    const TYPE_EAN13 = 'EAN13';
    const TYPE_EISSN = 'EISSN';
    const TYPE_HANDLE = 'Handle';
    const TYPE_IGSN = 'IGSN';
    const TYPE_ISBN = 'ISBN';
    const TYPE_ISSN = 'ISSN';
    const TYPE_ISTC = 'ISTC';
    const TYPE_LISSN = 'LISSN';
    const TYPE_LSID = 'LSID';
    const TYPE_PMID = 'PMID';
    const TYPE_PURL = 'PURL';
    const TYPE_UPC = 'UPC';
    const TYPE_URL = 'URL';
    const TYPE_URN = 'URN';
    const TYPE_W3ID = 'w3id';

    private string $relationType;
    private string $relatedIdentifier;
    private string $relatedIdentifierType;
    private ?string $resourceTypeGeneral;

    // TODO: add relatedMetadataScheme, schemeURI, schemeType

    public function __construct(string $relationType, string $relatedIdentifier, string $relatedIdentifierType, string $resourceTypeGeneral = NULL)
    {
        $this->relationType = $relationType;
        $this->relatedIdentifier = $relatedIdentifier;
        $this->relatedIdentifierType = $relatedIdentifierType;
        $this->resourceTypeGeneral = $resourceTypeGeneral;
    }

    public static function isPartOf(string $relatedIdentifier, string $relatedIdentifierType): static
    {
        return new static('IsPartOf', $relatedIdentifier, $relatedIdentifierType);
    }

    public static function hasPart(string $relatedIdentifier, string $relatedIdentifierType): static
    {
        return new static('HasPart', $relatedIdentifier, $relatedIdentifierType);
    }

    public static function isVersionOf(string $relatedIdentifier, string $relatedIdentifierType, string $resourceTypeGeneral = NULL): static
    {
        return new static('IsVersionOf', $relatedIdentifier, $relatedIdentifierType, $resourceTypeGeneral);
    }

    public static function isSupplementTo(string $relatedIdentifier, string $relatedIdentifierType, string $resourceTypeGeneral = NULL): static
    {
        return new static('IsSupplementTo', $relatedIdentifier, $relatedIdentifierType, $resourceTypeGeneral);
    }

    public static function isSupplementedBy(string $relatedIdentifier, string $relatedIdentifierType, string $resourceTypeGeneral = NULL): static
    {
        return new static('IsSupplementedBy', $relatedIdentifier, $relatedIdentifierType, $resourceTypeGeneral);
    }

    public static function cites(string $relatedIdentifier, string $relatedIdentifierType): static
    {
        return new static('Cites', $relatedIdentifier, $relatedIdentifierType);
    }

    public static function isCitedBy(string $relatedIdentifier, string $relatedIdentifierType): static
    {
        return new static('IsCitedBy', $relatedIdentifier, $relatedIdentifierType);
    }

    public static function parseCites(string $string, string $resourceTypeGeneral = NULL): static
    {
        $related = static::parseFromUrl($string);

        return static::cites($related['relatedIdentifier'], $related['relatedIdentifierType']);
    }

    /**
     * @return static[]
     */
    public static function parseIsSupplementTo(string $string, string $resourceTypeGeneral = NULL): array
    {
        if (empty($string)) {
            return [];
        }

        $related = [];

        foreach(self::parseRelatedIdentifiers($string, 'IsSupplementTo') as $match) {
            $related[] = static::isSupplementTo(
                $match['relatedIdentifier'],
                $match['relatedIdentifierType'],
                $match['resourceTypeGeneral'] ?? $resourceTypeGeneral
            );
        }

        return $related;
    }

    /**
     * @return static[]
     */
    public static function parseIsVersionOf(string $string, string $resourceTypeGeneral = NULL): array
    {
        if (empty($string)) {
            return [];
        }

        $related = [];

        foreach(self::parseRelatedIdentifiers($string, 'IsVersionOf', '') as $match) {
            $related[] = static::isVersionOf(
                $match['relatedIdentifier'],
                $match['relatedIdentifierType'],
                $match['resourceTypeGeneral'] ?? $resourceTypeGeneral
            );
        }

        return $related;
    }

    /**
     * @return static[]
     */
    public static function parseIsSupplementedBy(string $string, string $resourceTypeGeneral = NULL): array
    {
        if (empty($string)) {
            return [];
        }

        $related = [];

        foreach(self::parseRelatedIdentifiers($string, 'IsSupplementedBy', '') as $match) {
            $related[] = static::isSupplementedBy(
                $match['relatedIdentifier'],
                $match['relatedIdentifierType'],
                $match['resourceTypeGeneral'] ?? $resourceTypeGeneral
            );
        }

        return $related;
    }

    private static function parseRelatedIdentifiers(string $string, string $relationType, $fullURLs = false): array
    {
        $result = [];

        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $matches);

        foreach($matches[0] as $match) {
            $related = self::parseFromUrl($match, $fullURLs);

            $related['relationType'] = $relationType;

            $result[] = $related;
        }

        return $result;
    }

    private static function parseFromUrl(string $string, bool $fullURLs = false): array
    {
        $type = self::TYPE_URL;

        if (str_contains(strtolower($string), 'doi.org')) {

            $type = self::TYPE_DOI;

            if(!$fullURLs) {
                $string = str_replace([
                    'http://dx.doi.org/', 'https://dx.doi.org/',
                    'https://doi.org/', 'https://doi.org/'
                ], '', $string);
            }

        } elseif (str_contains(strtolower($string), 'arxiv')) {

            $type = self::TYPE_ARXIV;

            if (!$fullURLs) {
                $string = str_replace([
                    'http://arxiv.org/abs/', 'https://arxiv.org/abs/',
                    'http://arXiv.org/abs/', 'https://arXiv.org/abs/',
                    'http://arxiv.org/pdf/', 'https://arxiv.org/pdf/',
                    'http://arXiv.org/pdf/', 'https://arXiv.org/pdf/',
                    'http://arxiv.org/ps/', 'https://arxiv.org/ps/',
                    'http://arXiv.org/ps/', 'https://arXiv.org/ps/',
                ], 'arXiv:', $string);
            }
        }

        return [
            'relatedIdentifier' => $string,
            'relatedIdentifierType' => $type
        ];
    }

    // IsContinuedBy
    // Continues
    // IsDescribedBy
    // Describes
    // HasMetadata
    // IsMetadataFor
    // HasVersion
    // IsNewVersionOf
    // IsPreviousVersionOf
    // IsPartOf
    // HasPart
    // IsReferencedBy
    // References
    // IsDocumentedBy
    // Documents
    // IsCompiledBy
    // Compiles
    // IsVariantFormOf
    // IsOriginalFormOf
    // IsIdenticalTo
    // IsReviewedBy
    // Reviews
    // IsDerivedFrom
    // IsSourceOf
    // IsRequiredBy
    // Requires
    // IsObsoletedBy
    // Obsoletes

    public function toApiObject(): object
    {
        $r = [
            'relatedIdentifier' => $this->relatedIdentifier,
            'relatedIdentifierType' => $this->relatedIdentifierType,
            'relationType' => $this->relationType
        ];

        if ($this->resourceTypeGeneral !== NULL) {
            $r['resourceTypeGeneral'] = $this->resourceTypeGeneral;
        }

        return (object) $r;
    }

}