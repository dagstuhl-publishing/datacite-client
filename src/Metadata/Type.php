<?php

namespace Dagstuhl\DataCite\Metadata;

class Type
{
    const BIBTEX_TYPE_PROCEEDINGS = 'proceedings';
    const BIBTEX_TYPE_IN_PROCEEDINGS = 'inproceedings';
    const BIBTEX_TYPE_ARTICLE = 'article';
    const BIBTEX_TYPE_BOOK = 'book';
    const BIBTEX_TYPE_TECH_REPORT = 'techreport';

    const RESOURCE_TYPE_ARTICLE = 'Article';
    const RESOURCE_TYPE_BOOK = 'Book';
    const RESOURCE_TYPE_CONFERENCE_PAPER = 'Conference Paper';
    const RESOURCE_TYPE_CONFERENCE_PROCEEDINGS = 'Conference Proceedings';
    const RESOURCE_TYPE_COLLECTION = 'Collection';
    const RESOURCE_TYPE_FRONT_MATTER = 'Front Matter';
    const RESOURCE_TYPE_VOLUME = 'Volume';
    const RESOURCE_TYPE_ISSUE = 'Issue';
    const RESOURCE_TYPE_SEMINAR_REPORT = 'Seminar Report';
    const RESOURCE_TYPE_SEMINAR_REPORTS = 'Seminar Reports';
    const RESOURCE_TYPE_TEXT = 'Text';



    // TODO: complete https://schema.datacite.org/meta/kernel-4.4/doc/DataCite-MetadataKernel_v4.4.pdf, p. 16
    const RESOURCE_TYPE_GENERAL_AUDIOVISUAL = 'Audiovisual';
    const RESOURCE_TYPE_GENERAL_BOOK = 'Book';
    const RESOURCE_TYPE_GENERAL_BOOK_CHAPTER = 'BookChapter';
    const RESOURCE_TYPE_GENERAL_COLLECTION = 'Collection';
    const RESOURCE_TYPE_GENERAL_CONFERENCE_PAPER = 'ConferencePaper';
    const RESOURCE_TYPE_GENERAL_CONFERENCE_PROCEEDING = 'ConferenceProceeding';
    const RESOURCE_TYPE_GENERAL_DATA_PAPER = 'DataPaper';
    const RESOURCE_TYPE_GENERAL_DATASET = 'Dataset';
    const RESOURCE_TYPE_GENERAL_EVENT = 'Event';
    const RESOURCE_TYPE_GENERAL_IMAGE = 'Image';
    const RESOURCE_TYPE_GENERAL_JOURNAL = 'Journal';
    const RESOURCE_TYPE_GENERAL_JOURNAL_ARTICLE = 'JournalArticle';
    const RESOURCE_TYPE_GENERAL_INTERACTIVE_RESOURCE = 'InteractiveResource';
    const RESOURCE_TYPE_GENERAL_MODEL = 'Model';
    const RESOURCE_TYPE_GENERAL_PHYSICAL_OBJECT = 'PhysicalObject';

    const RESOURCE_TYPE_GENERAL_REPORT = 'Report';

    const RESOURCE_TYPE_GENERAL_SERVICE = 'Service';
    const RESOURCE_TYPE_GENERAL_SOFTWARE = 'Software';
    const RESOURCE_TYPE_GENERAL_SOUND = 'Sound';
    const RESOURCE_TYPE_GENERAL_TEXT = 'Text';
    const RESOURCE_TYPE_GENERAL_WORKFLOW = 'Workflow';
    const RESOURCE_TYPE_GENERAL_OTHER = 'Other';

    private object $types;

    public function __construct(string $resourceTypeGeneral, string $resourceType = NULL,  string $bibtex = NULL)
    {
        $this->types = new \stdClass();
        $this->types->resourceTypeGeneral = $resourceTypeGeneral;

        if ($resourceType !== NULL) {
            $this->types->resourceType = $resourceType;
        }

        if ($bibtex !== NULL) {
            $this->types->bibtex = $bibtex;
        }
    }

    // ---- pre-defined types ------------------------------------

    public static function article(): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_TEXT,
            static::RESOURCE_TYPE_ARTICLE,
            static::BIBTEX_TYPE_ARTICLE
        );
    }

    public static function book(string $resourceType = NULL, string $bibtexType = self::BIBTEX_TYPE_BOOK): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_BOOK,
            $resourceType,
            $bibtexType
        );
    }

    public static function frontMatter(?string $bibtexType = self::BIBTEX_TYPE_ARTICLE): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_TEXT,
            static::RESOURCE_TYPE_FRONT_MATTER,
            $bibtexType
        );
    }

    public static function journalArticle(string $resourceType = NULL, string $bibtexType = self::BIBTEX_TYPE_ARTICLE): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_JOURNAL_ARTICLE,
            $resourceType,
            $bibtexType
        );
    }

    public static function journalIssue(string $bibtexType = self::BIBTEX_TYPE_ARTICLE): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_COLLECTION,
            static::RESOURCE_TYPE_ISSUE,
            $bibtexType
        );
    }

    public static function conferenceProceedingsVolume(string $bibtexType = self::BIBTEX_TYPE_PROCEEDINGS): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_CONFERENCE_PROCEEDING,
            static::RESOURCE_TYPE_VOLUME,
            $bibtexType
        );
    }

    public static function conferenceProceedingsPaper(string $resourceType = NULL, string $bibtexType = self::BIBTEX_TYPE_IN_PROCEEDINGS): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_CONFERENCE_PAPER,
            $resourceType,
            $bibtexType
        );
    }

    public static function dataPaper(string $resourceType = NULL, string $bibtexType = self::BIBTEX_TYPE_ARTICLE): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_DATA_PAPER,
            $resourceType,
            $bibtexType
        );
    }

    public static function seminarReportsCollection(string $bibtexType = self::BIBTEX_TYPE_TECH_REPORT): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_COLLECTION,
            static::RESOURCE_TYPE_SEMINAR_REPORTS,
            $bibtexType
        );
    }

    public static function seminarReport(string $bibtexType = self::BIBTEX_TYPE_TECH_REPORT): static
    {
        return new static(
            static::RESOURCE_TYPE_GENERAL_REPORT,
            static::RESOURCE_TYPE_SEMINAR_REPORT,
            $bibtexType
        );
    }


    public function toApiObject(): object
    {
        return $this->types;
    }

}