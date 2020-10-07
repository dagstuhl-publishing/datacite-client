<?php

namespace LZI\DataCite;

use LZI\DataCite\Metadata\AlternateIdentifier;
use LZI\DataCite\Metadata\Contributor;
use LZI\DataCite\Metadata\Creator;
use LZI\DataCite\Metadata\Date;
use LZI\DataCite\Metadata\Description;
use LZI\DataCite\Metadata\RelatedIdentifier;
use LZI\DataCite\Metadata\Rights;
use LZI\DataCite\Metadata\Subject;
use LZI\DataCite\Metadata\Title;
use LZI\DataCite\Metadata\Type;

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