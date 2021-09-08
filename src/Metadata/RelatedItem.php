<?php

namespace LZI\DataCite\Metadata;

class RelatedItem
{
    // TODO: check relatedItemType, relationTyoe
    // for paper: Conference Proceeding, isPartOf (or isPublishedIn ?)
    // for collection: Series, isPartOf

    // TODO: contants for relationTypes
    // see https://schema.datacite.org/meta/kernel-4.4/doc/DataCite-MetadataKernel_v4.4.pdf
    // page 25, section 12.b

    const RELATION_TYPE_IS_PART_OF = 'IsPartOf';

    private string $relatedItemType;
    private string $relationType;

    /** @var Creator[]  */
    private array $creators = [];

    public function __construct(string $relatedItemType, string $relationType)
    {

    }

    // TODO: optional (0-1) relatedItemIdentifier, relatedItemIdentifierType
    // Series -> ISBN

    public function addRelatedIdentifier(string $relatedIdentifier, string $relatedIdentifierType)
    {

    }

    public function addCreator(Creator $creator)
    {
        $this->creators[] = $creator;
    }

    public function addTitle(Title $title)
    {

    }

    public function setPublicationYear(int $year)
    {

    }

    public function setVolume(string $volume)
    {

    }

    public function setIssue(string $issue)
    {

    }

    // $numberType controlled values. "Article", "Chapter", "Report", "Other"
    // TODO: constants for these values
    public function setNumber(string $number, string $numberType = NULL)
    {

    }

    public function setFirstPage(string $firstPage)
    {

    }

    public function setLastPage(string $lastPage)
    {

    }

    public function setPublisher(string $publisher)
    {

    }

    public function setEdition(string $edition)
    {

    }

    public function addContributor(Contributor $contributor)
    {

    }

    public function toApiObject()
    {
        // mandatory properties
        $item = [
            'relatedItemType' => $this->relatedItemType,
            'relationType' => $this->relationType,
        ];

        // optional properties of type string
        foreach ([ 'firstPage', 'lastPage' ] as $prop) {

            // IMPORTANT: empty(0) is true !!!
            if ((string)$this->{$prop} !== '') {
                $item[$prop] = $this->{$prop};
            }
        }

        // properties of type array/object
        if (count($this->creators) > 0) {

           $item['creators'] = [];
           foreach ($this->creators as $creator) {
                $item['creators'][] = $creator->toApiObject();
           }
        }

        return (object) $item;
    }
}