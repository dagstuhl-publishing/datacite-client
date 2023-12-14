<?php

require __DIR__ . '/../vendor/autoload.php';

use Dagstuhl\DataCite\Metadata\Affiliation;
use Dagstuhl\DataCite\Metadata\Contributor;
use Dagstuhl\DataCite\Metadata\Creator;
use Dagstuhl\DataCite\Metadata\DataCiteRecord;
use Dagstuhl\DataCite\Metadata\Name;
use Dagstuhl\DataCite\Metadata\RelatedIdentifier;
use Dagstuhl\DataCite\Metadata\Title;

$dataCiteRecord = new DataCiteRecord();


$dataCiteRecord->addTitle(Title::main('This is an example title', 'en'));

$creator = Creator::personal('Concatenated Names', 'Given Name', 'Family Name');
$creator->addAffiliation(new Affiliation('This is a sample affiliation'));

$dataCiteRecord->addCreator($creator);


$name = Name::organizational('Schloss Dagstuhl - Leibniz-Zentrum für Informatik');
$name->addAffiliation(Affiliation::ror('00k4h2615'));
$contributor = Contributor::editor($name);

$dataCiteRecord->addContributor($contributor);


$related = [];
$related[] = RelatedIdentifier::isPartOf('arXiv:....', RelatedIdentifier::TYPE_ARXIV);
$related[] = RelatedIdentifier::cites('10....', RelatedIdentifier::TYPE_DOI);

$dataCiteRecord->setRelatedIdentifiers($related);


$relatedItem = new \Dagstuhl\DataCite\Metadata\RelatedItem('article', 'cites');
$relatedItem->addCreator($creator);
$relatedItem->setPublicationYear('1994');
$dataCiteRecord->addRelatedItem($relatedItem);

var_dump($dataCiteRecord->toApiJson());



