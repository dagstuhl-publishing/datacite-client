<?php

require __DIR__ . '/../vendor/autoload.php';

use LZI\DataCite\Metadata\Affiliation;
use LZI\DataCite\Metadata\Contributor;
use LZI\DataCite\Metadata\Creator;
use LZI\DataCite\Metadata\DataCiteRecord;
use LZI\DataCite\Metadata\Name;
use LZI\DataCite\Metadata\RelatedIdentifier;
use LZI\DataCite\Metadata\Title;

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

var_dump($dataCiteRecord->toApiJson());



