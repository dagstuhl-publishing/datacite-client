<?php

namespace Dagstuhl\DataCite\Metadata;

use Dagstuhl\DataCite\DataCiteDataProvider;
use stdClass;

class DataCiteRecord
{
    const STATE_FINDABLE = 'findable';
    const STATE_REGISTERED = 'registered';

    const SCHEMA_VERSION_4 = 'http://datacite.org/schema/kernel-4';

    private object $attributes;

    /**
     * DataCiteRecord constructor.
     */
    public function __construct(object|array|string|null $attributes = NULL)
    {
        if (is_array($attributes)) {
            $this->attributes = (object) $attributes;
        }
        elseif (is_string($attributes)) {
            $this->attributes = json_decode($attributes);
        }
        elseif (is_object($attributes)) {
            $this->attributes = $attributes;

            // read data from API response
            if (isset($this->attributes->data->attributes)) {
                $this->attributes = $this->attributes->data->attributes;
            }
        }
        elseif ($attributes === NULL) {
            $this->attributes = new stdClass();
        }
    }

    public static function fromDataProvider(DataCiteDataProvider $dataCiteInterface): DataCiteRecord
    {
        $dataCiteRecord = new static();

        foreach(DataCiteDataProvider::PROPERTIES as $prop) {
            $prop = ucfirst($prop);
            $getter = 'get'.$prop;
            $setter = 'set'.$prop;

            $dataCiteRecord->{$setter}($dataCiteInterface->{$getter}());
        }

        return $dataCiteRecord;
    }

    // ---- creators ---------------------------------

    public function addCreator(Creator $creator): void
    {
        if (!isset($this->attributes->creators)) {
            $this->attributes->creators = [];
        }

        $this->attributes->creators[] = $creator->toApiObject();
    }

    /**
     * @param Creator[] $creators
     */
    public function setCreators(array $creators): void
    {
        $this->attributes->creators = [];

        foreach($creators as $creator) {
            $this->addCreator($creator);
        }
    }

    // ---- contributors -----------------------------

    public function addContributor(Contributor $contributor): void
    {
        if(!isset($this->attributes->contributors)) {
            $this->attributes->contributors = [];
        }

        $this->attributes->contributors[] = $contributor->toApiObject();
    }

    /**
     * @param Contributor[] $contributors
     */
    public function setContributors(array $contributors): void
    {
        $this->attributes->contributors = [];

        foreach($contributors as $contributor) {
            $this->addContributor($contributor);
        }
    }

    // ---- dates ------------------------------------

    public function addDate(Date $date): void
    {
        if (!isset($this->attributes->dates)) {
            $this->attributes->dates = [];
        }

        $this->attributes->dates[] = $date->toApiObject();
    }

    /**
     * @param Date[] $dates
     */
    public function setDates(array $dates): void
    {
        $this->attributes->dates = [];

        foreach ($dates as $date) {
            $this->addDate($date);
        }
    }

    // ---- description ------------------------------

    public function addDescription(Description $description): void
    {
        if (!isset($this->attributes->descriptions)) {
            $this->attributes->descriptions = [];
        }

        $this->attributes->descriptions[] = $description->toApiObject();
    }

    /**
     * @param Description[] $descriptions
     */
    public function setDescriptions(array $descriptions): void
    {
        $this->attributes->descriptions = [];

        foreach($descriptions as $description) {
            $this->addDescription($description);
        }
    }

    // ---- related identifiers ----------------------

    public function addRelatedIdentifier(RelatedIdentifier $relatedIdentifier): void
    {
        if(!isset($this->attributes->relatedIdentifiers)) {
            $this->attributes->relatedIdentifiers = [];
        }

        $this->attributes->relatedIdentifiers[] = $relatedIdentifier->toApiObject();
    }

    public function setRelatedIdentifiers(array $relatedIdentifiers): void
    {
        $this->attributes->relatedIdentifiers = [];

        foreach($relatedIdentifiers as $relatedIdentifier) {
            $this->addRelatedIdentifier($relatedIdentifier);
        }
    }

    // ---- related items ----------------------------

    public function addRelatedItem(RelatedItem $relatedItem): void
    {
        if(!isset($this->attributes->relatedItems)) {
            $this->attributes->relatedItems = [];
        }

        $this->attributes->relatedItems[] = $relatedItem->toApiObject();
    }

    public function setRelatedItems(array $relatedItems): void
    {
        $this->attributes->relatedItems = [];

        foreach($relatedItems as $relatedItem) {
            $this->addRelatedItem($relatedItem);
        }
    }

    // ---- alternate identifiers --------------------

    public function addAlternateIdentifier(AlternateIdentifier $alternateIdentifier): void
    {
        if (!isset($this->attributes->alternateIdentifiers)) {
            $this->attributes->alternateIdentifiers = [];
        }

        $this->attributes->alternateIdentifiers[] = $alternateIdentifier->toApiObject();
    }

    /**
     * @param AlternateIdentifier[] $alternateIdentifiers
     */
    public function setAlternateIdentifiers(array $alternateIdentifiers): void
    {
        $this->attributes->alternateIdentifiers = [];

        foreach($alternateIdentifiers as $alternateIdentifier) {
            $this->addAlternateIdentifier($alternateIdentifier);
        }
    }

    // ---- doi --------------------------------------

    public function setDoi(string $doi): void
    {
        $this->attributes->doi = $doi;
    }

    public function getDoi() : ?string
    {
        return $this->attributes->doi ?? '';
    }

    // ---- formats ----------------------------------

    /**
     * @param string[] $formats
     */
    public function setFormats(array $formats): void
    {
        $this->attributes->formats = $formats;
    }

    /**
     * @return string[]
     */
    public function getFormats() : array
    {
        return $this->attributes->formats ?? [];
    }

    // ---- sizes ------------------------------------

    public function setSizes(array $sizes): void
    {
        $this->attributes->sizes = $sizes;
    }

    public function getSizes() : array
    {
        return $this->attributes->sizes ?? [];
    }

    // ---- titles -----------------------------------

    public function addTitle(Title $title): void
    {
        if (!isset($this->attributes->titles)) {
            $this->attributes->titles = [];
        }

        /*
        // if only one title is provided, api returns no array but stdClass
        // so, when adding another title, convert to array
        if (!is_array($this->attributes->titles)) {
            $this->attributes->titles = [ $this->attributes->titles ];
        }
*/
        $this->attributes->titles[] = $title->toApiObject();
    }

    /**
     * @param Title[] $titles
     */
    public function setTitles(array $titles): void
    {
        $this->attributes->titles = [];

        foreach($titles as $title) {
            $this->attributes->titles[] = $title->toApiObject();
        }
    }

    public function getTitles() : array
    {
        $t = [];

        foreach($this->attributes->titles as $title) {
            $t[] = new Title($title->title, $title->lang ?? NULL, $title->titleType ?? NULL);
        }

        return $t;
    }

    // ---- url --------------------------------------

    public function setUrl(string $url): void
    {
        $this->attributes->url = $url;
    }

    public function getUrl() : ?string
    {
        return $this->attributes->url ?? NULL;
    }

    // ---- publisher --------------------------------

    public function setPublisher(string $publisher): void
    {
        $this->attributes->publisher = $publisher;
    }

    public function getPublisher() : ?string
    {
        return $this->attributes->publisher ?? NULL;
    }

    // ---- publication year -------------------------

    public function setPublicationYear(int $year): void
    {
        $this->attributes->publicationYear = $year;
    }

    public function getPublicationYear() : ?int
    {
        return $this->attributes->publicationYear;
    }

    // ---- language ---------------------------------

    public function setLanguage(string $languageCode): void
    {
        $this->attributes->language = $languageCode;
    }

    public function getLanguage() : ?string
    {
        return $this->attributes->language ?? NULL;
    }

    // ---- rights -----------------------------------

    public function addRights(Rights $rights): void
    {
        if (!isset($this->attributes->rightsList)) {
            $this->attributes->rightsList = [];
        }

        $this->attributes->rightsList[] = $rights->toApiObject();
    }

    /**
     * @param Rights[] $rightsList
     */
    public function setRightsList(array $rightsList): void
    {
        $this->attributes->rightsList = [];

        foreach($rightsList as $rights) {
            $this->addRights($rights);
        }
    }

    /**
     * @return Rights[]
     */
    public function getRightsList() : array
    {
        $rightsList = [];
        foreach($this->attributes->rightsList as $rights) {
            $rightsList[] = new Rights(
                $rights->rights,
                $rights->rightsUri ?? NULL,
                $rights->lang ?? NULL,
                $rights->rightsIdentifier ?? NULL,
                $rights->rightsIDentifierScheme ?? NULL,
                $rights->schemeURI ?? NULL
            );
        }

        return $rightsList;
    }

    // ---- subjects ---------------------------------

    /**
     * @param Subject $subject
     */
    public function addSubject(Subject $subject): void
    {
        if (!isset($this->attributes->subjects)) {
            $this->attributes->subjects = [];
        }

        $this->attributes->subjects[] = $subject->toApiObject();
    }

    /**
     * @param Subject[] $subjects
     */
    public function setSubjects(array $subjects): void
    {
        $this->attributes->subjects = [];

        foreach($subjects as $subject) {
            $this->addSubject($subject);
        }
    }

    /**
     * @return Subject[]
     */
    public function getSubjects() : array
    {
        $subjects = [];

        if (!isset($this->attributes->subjects)) {
            return [];
        }

        foreach($this->attributes->subjects as $subject) {
            $subjects[] = new Subject(
                $subject->subject,
                $subject->lang ?? NULL,
                $subject->subjectScheme ?? NULL
            );
        }

        return $subjects;
    }

    // ---- types ------------------------------------

    public function setType(Type $type): void
    {
        $this->attributes->types = $type->toApiObject();
    }

    public function getType() : ?Type
    {
        return isset($this->attributes->types)
            ? new Type(
                $this->attributes->types->resourceType,
                $this->attributes->types->resourceTypeGeneral,
                $this->attributes->types->bibtex ?? NULL
            )
            : NULL;
    }

    public function getState() : ?string
    {
        return $this->attributes->state ?? NULL;
    }

    public function toApiJson() : string
    {
        $this->attributes->schemaVersion = static::SCHEMA_VERSION_4;

        $data = (object) [
            'data' => [
                'id' => $this->getDoi(),
                'type' => 'dois',
                'attributes' => $this->attributes
            ]
        ];

        return json_encode($data);
    }

    public function getAttributes() : array
    {
        return json_decode(json_encode((object) $this->attributes), true);
    }

}