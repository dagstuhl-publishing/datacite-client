<?php

namespace LZI\DataCite\Metadata;

class Type
{
    const BIBTEX_TYPE_PROCEEDINGS = 'proceedings';
    const BIBTEX_TYPE_IN_PROCEEDINGS = 'inproceedings';
    const BIBTEX_TYPE_ARTICLE = 'article';
    const BIBTEX_TYPE_BOOK = 'book';

    const RESOURCE_TYPE_ARTICLE = 'Article';
    const RESOURCE_TYPE_BOOK = 'Book';
    const RESOURCE_TYPE_CONFERENCE_PAPER = 'Conference Paper';
    const RESOURCE_TYPE_CONFERENCE_PROCEEDINGS = 'Conference Proceedings';
    const RESOURCE_TYPE_SEMINAR_REPORT = 'Seminar Report';
    const RESOURCE_TYPE_SEMINAR_REPORTS = 'Seminar Reports';

    const RESOURCE_TYPE_GENERAL_AUDIOVISUAL = 'Audiovisual';
    const RESOURCE_TYPE_GENERAL_COLLECTION = 'Collection';
    const RESOURCE_TYPE_GENERAL_DATA_PAPER = 'DataPaper';
    const RESOURCE_TYPE_GENERAL_DATASET = 'Dataset';
    const RESOURCE_TYPE_GENERAL_EVENT = 'Event';
    const RESOURCE_TYPE_GENERAL_IMAGE = 'Image';
    const RESOURCE_TYPE_GENERAL_INTERACTIVE_RESOURCE = 'InteractiveResource';
    const RESOURCE_TYPE_GENERAL_MODEL = 'Model';
    const RESOURCE_TYPE_GENERAL_PHYSICAL_OBJECT = 'PhysicalObject';
    const RESOURCE_TYPE_GENERAL_SERVICE = 'Service';
    const RESOURCE_TYPE_GENERAL_SOFTWARE = 'Software';
    const RESOURCE_TYPE_GENERAL_SOUND = 'Sound';
    const RESOURCE_TYPE_GENERAL_TEXT = 'Text';
    const RESOURCE_TYPE_GENERAL_WORKFLOW = 'Workflow';
    const RESOURCE_TYPE_GENERAL_OTHER = 'Other';

    private $types;

    public function __construct($resourceType, $resourceTypeGeneral, $bibtex = NULL)
    {
        $this->types = new \stdClass();
        $this->types->resourceType = $resourceType;
        $this->types->resourceTypeGeneral = $resourceTypeGeneral;

        if ($bibtex !== NULL) {
            $this->types->bibtex = $bibtex;
        }
    }

    // ---- pre-defined types ------------------------------------

    public static function article()
    {
        return new static(
            self::RESOURCE_TYPE_ARTICLE,
            self::RESOURCE_TYPE_GENERAL_TEXT,
            self::BIBTEX_TYPE_ARTICLE
        );
    }

    public static function book()
    {
        return new static(
            self::RESOURCE_TYPE_BOOK,
            self::RESOURCE_TYPE_GENERAL_AUDIOVISUAL,
            self::BIBTEX_TYPE_BOOK
        );
    }

    public static function conferenceProceedings()
    {
        return new static(
            self::RESOURCE_TYPE_CONFERENCE_PROCEEDINGS,
            self::RESOURCE_TYPE_GENERAL_COLLECTION,
            self::BIBTEX_TYPE_PROCEEDINGS
        );
    }

    public static function conferenceProceedingsPaper()
    {
        return new static(
            self::RESOURCE_TYPE_CONFERENCE_PAPER,
            self::RESOURCE_TYPE_GENERAL_TEXT,
            self::BIBTEX_TYPE_IN_PROCEEDINGS);
    }

    public static function seminarReportsCollection()
    {
        return new static(
            self::RESOURCE_TYPE_SEMINAR_REPORTS,
            self::RESOURCE_TYPE_GENERAL_COLLECTION,
            self::BIBTEX_TYPE_ARTICLE
        );
    }

    public static function seminarReport()
    {
        return new static(
            self::RESOURCE_TYPE_SEMINAR_REPORT,
            self::RESOURCE_TYPE_GENERAL_TEXT,
            self::BIBTEX_TYPE_ARTICLE
        );
    }


    public function toApiObject()
    {
        return $this->types;
    }

}