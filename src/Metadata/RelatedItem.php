<?php

namespace LZI\DataCite\Metadata;

class RelatedItem
{
    // TODO: check relatedItemType, relationType
    // for paper: Conference Proceeding, isPartOf (or isPublishedIn ?)
    // for collection: Series, isPartOf

    // TODO: constants for relationTypes
    // see https://schema.datacite.org/meta/kernel-4.4/doc/DataCite-MetadataKernel_v4.4.pdf
    // page 25, section 12.b

    const RELATION_TYPE_IS_PART_OF = 'IsPartOf';

    const OPTIONAL_STRING_RELATED_PROPERTIES = [
        //'relatedItemIdentifier',
        'PublicationYear',
        'volume',
        'issue',
        'firstPage',
        'lastPage',
        'Publisher',
        'edition'
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

    /** @var Contributor[] */
    private array $contributors;

    /** @var RelatedIdentifier  */
    private RelatedIdentifier $relatedItemIdentifier;

    /** @var Creator[]  */
    private array $creators = [];

    /** @var Title[] */
    private array $titles = [];

    /** @var Number[] */
    private array $numbers = [];

    public function __construct(string $relatedItemType, string $relationType)
    {
        $this->relatedItemType = $relatedItemType;
        $this->relationType = $relationType;
    }

    // TODO: optional (0-1) relatedItemIdentifier, relatedItemIdentifierType
    // Series -> ISBN

    public function setRelatedItemIdentifier(RelatedIdentifier $relatedItemIdentifier)
    {
        $this->relatedItemIdentifier = $relatedItemIdentifier;
    }

    public function addCreator(Creator $creator)
    {
        $this->creators[] = $creator;
    }

    public function addTitle(Title $title)
    {
        $this->titles[] = $title;
    }

    public function addNumber(Number $number)
    {
        $this->numbers[] = $number;
    }

    public function setPublicationYear(int $year)
    {
        $this->publicationYear = $year;
    }

    public function setVolume(string $volume)
    {
        $this->volume = $volume;
    }

    public function setIssue(string $issue)
    {
        $this->issue = $issue;
    }

    public function setNumber(Number $number)
    {
        $this->number = $number;
    }

    public function setFirstPage(string $firstPage)
    {
        $this->firstPage = $firstPage;
    }

    public function setLastPage(string $lastPage)
    {
        $this->lastPage = $lastPage;
    }

    public function setPublisher(string $publisher)
    {
        $this->publisher = $publisher;
    }

    public function setEdition(string $edition)
    {
        $this->edition = $edition;
    }

    public function addContributor(Contributor $contributor)
    {
        $this->contributors[] = $contributor;
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

        // properties of type array/object
        if (isset($this->creators) AND count($this->creators) > 0) {

           $item['creators'] = [];
           foreach ($this->creators as $creator) {
                $item['creators'][] = $creator->toApiObject();
           }
        }

        if (isset($this->numbers) AND count($this->numbers) > 0) {

            $item['numbers'] = [];
            foreach ($this->numbers as $number) {
                $item['numbers'][] = $number->toApiObject();
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