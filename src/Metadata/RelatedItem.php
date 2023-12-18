<?php

namespace Dagstuhl\DataCite\Metadata;

class RelatedItem
{
    // cf. https://schema.datacite.org/meta/kernel-4.4/doc/DataCite-MetadataKernel_v4.4.pdf

    const RELATION_TYPE_IS_PART_OF = 'IsPartOf';
    const RELATION_TYPE_IS_PUBLISHED_IN = 'IsPublishedIn';
    const RELATED_IDENTIFIER_TYPE_DOI = 'DOI';
    const RELATED_IDENTIFIER_TYPE_ISSN = 'ISSN';

    const OPTIONAL_STRING_RELATED_PROPERTIES = [
        'publicationYear',
        'volume',
        'issue',
        'firstPage',
        'lastPage',
        'publisher',
        'edition',
        'number'
    ];

    private string $relatedItemType;
    private string $relationType;
    private string $publicationYear;
    private string $volume;
    private string $issue;
    private string $firstPage;
    private string $lastPage;
    private string $publisher;
    private string $edition;
    private string $number;

    /** @var Contributor[] */
    private array $contributors;

    /** @var string */
    private string $relatedItemIdentifier;
    private string $relatedItemIdentifierType;

    /** @var Creator[]  */
    private array $creators = [];

    /** @var Title[] */
    private array $titles = [];

    public function __construct(string $relatedItemType, string $relationType)
    {
        $this->relatedItemType = $relatedItemType;
        $this->relationType = $relationType;
    }

    public function setRelatedItemIdentifier(string $relatedItemIdentifier): void
    {
        $this->relatedItemIdentifier = $relatedItemIdentifier;
    }

    public function setRelatedItemIdentifierType(string $relatedItemIdentifierType): void
    {
        $this->relatedItemIdentifierType = $relatedItemIdentifierType;
    }

    public function addCreator(Creator $creator): void
    {
        $this->creators[] = $creator;
    }

    public function addTitle(Title $title): void
    {
        $this->titles[] = $title;
    }

    public function setPublicationYear(int $year): void
    {
        $this->publicationYear = $year;
    }

    public function setVolume(string $volume): void
    {
        $this->volume = $volume;
    }

    public function setIssue(string $issue): void
    {
        $this->issue = $issue;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function setFirstPage(string $firstPage): void
    {
        $this->firstPage = $firstPage;
    }

    public function setLastPage(string $lastPage): void
    {
        $this->lastPage = $lastPage;
    }

    public function setPublisher(string $publisher): void
    {
        $this->publisher = $publisher;
    }

    public function setEdition(string $edition): void
    {
        $this->edition = $edition;
    }

    public function addContributor(Contributor $contributor): void
    {
        $this->contributors[] = $contributor;
    }

    /**
     * @param Contributor[] $contributors
     */
    public function setContributors(array $contributors): void
    {
        $this->contributors = $contributors;
    }

    public function toApiObject(): object
    {
        // mandatory properties
        $item = [
            'relatedItemType' => $this->relatedItemType,
            'relationType' => $this->relationType,
        ];

        // optional properties of type string
        foreach (self::OPTIONAL_STRING_RELATED_PROPERTIES as $prop) {

            // IMPORTANT: empty(0) is true !!!
            if (isset($this->{$prop}) AND (string)$this->{$prop} !== '') {
                $item[$prop] = $this->{$prop};
            }
        }

        $item['relatedItemIdentifier'] = (object) [
            'relatedItemIdentifier' => $this->relatedItemIdentifier,
            'relatedItemIdentifierType' => $this->relatedItemIdentifierType
        ];

        // properties of type array/object
        if (isset($this->creators) AND count($this->creators) > 0) {

            $item['creators'] = [];
            foreach ($this->creators as $creator) {
                $item['creators'][] = $creator->toApiObject();
            }
        }

        if (isset($this->titles) AND count($this->titles) > 0) {

            $item['titles'] = [];
            foreach ($this->titles as $title) {
                $item['titles'][] = $title->toApiObject();
            }
        }

        if (isset($this->contributors) AND count($this->contributors) > 0) {

            $item['contributors'] = [];
            foreach ($this->contributors as $contributor) {
                $item['contributors'][] = $contributor->toApiObject();
            }
        }

        return (object) $item;
    }
}