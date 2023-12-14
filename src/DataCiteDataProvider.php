<?php

namespace Dagstuhl\DataCite;

use Dagstuhl\DataCite\Metadata\AlternateIdentifier;
use Dagstuhl\DataCite\Metadata\Contributor;
use Dagstuhl\DataCite\Metadata\Creator;
use Dagstuhl\DataCite\Metadata\Date;
use Dagstuhl\DataCite\Metadata\Description;
use Dagstuhl\DataCite\Metadata\RelatedIdentifier;
use Dagstuhl\DataCite\Metadata\Rights;
use Dagstuhl\DataCite\Metadata\Subject;
use Dagstuhl\DataCite\Metadata\Title;
use Dagstuhl\DataCite\Metadata\Type;

interface DataCiteDataProvider
{
    const PROPERTIES = [
        'doi', 'url', 'publisher', 'titles',
        'creators', 'contributors', 'descriptions',
        'sizes', 'formats', 'publicationYear',
        'subjects', 'language',
        'alternateIdentifiers', 'relatedIdentifiers',
        'rightsList', 'type', 'dates'
    ];

    public function getDoi(): string;

    public function getUrl(): string;

    public function getPublisher(): string;

    /**
     * @return Title[]
     */
    public function getTitles(): array;

    /**
     * @return Creator[]
     */
    public function getCreators(): array;

    /**
     * @return Contributor[]
     */
    public function getContributors(): array;

    /**
     * @return Description[]
     */
    public function getDescriptions(): array;

    /**
     * @return string[]
     */
    public function getSizes(): array;

    /**
     * @return string[]
     */
    public function getFormats(): array;

    public function getPublicationYear(): int;

    /**
     * @return Subject[]
     */
    public function getSubjects(): array;

    public function getLanguage(): string;

    /**
     * @return AlternateIdentifier[]
     */
    public function getAlternateIdentifiers(): array;

    /**
     * @return RelatedIdentifier[]
     */
    public function getRelatedIdentifiers(): array;

    /**
     * @return Rights[]
     */
    public function getRightsList(): array;

    /**
     * @return Type
     */
    public function getType(): Type;

    /**
     * @return Date[]
     */
    public function getDates(): array;
}