# The Dagstuhl DataCite API client

The aim of this project is to provide an easy-to-use php interface for 
- providing schema-compliant DataCite metadata (Schema 4.4)
- registering/updating the doi metadata via the DataCite API.

Note: This is not a full API implementation; it only covers the use cases required by Dagstuhl Publishing.

### Code Examples:

### 1) Creation of a metadata record 

- General Information:
```php
$dataCiteRecord = new DataCiteRecord();
$dataCiteRecord->setDoi('10.publisher.example-doi');
$dataCiteRecord->addTitle(Title::main('This is an example title', 'en'));
```
- Adding an author with affiliation:
```php
$creator = Creator::personal('Concatenated Names', 'Given Name', 'Family Name');
$creator->addAffiliation(new Affiliation('This is a sample affiliation'));

$dataCiteRecord->addCreator($creator);
```
- Adding an editor with affiliation based on ror Identifier:
```php
$name = Name::organizational('Schloss Dagstuhl - Leibniz-Zentrum für Informatik');
$name->addAffiliation(Affiliation::ror('00k4h2615'));
$contributor = Contributor::editor($name);

$dataCiteRecord->addContributor($contributor);
```
- Adding relationships to other resources / alternative identifiers:
```php
$related = [];
$related[] = RelatedIdentifier::isPartOf('arXiv:....', RelatedIdentifier::TYPE_ARXIV);
$related[] = RelatedIdentifier::cites('10....', RelatedIdentifier::TYPE_DOI);

$alternate = [];
$alternate[] = AlternateIdentifier::urn('this is a demo urn');
$alternate[] = AlternateIdentifier::isbn('this is a demo urn');

$dataCiteRecord->setRelatedIdentifiers($related);
$dataCiteRecord->setAlternateIdentifiers($alternate);
```
- Classifying the type of the Document
```php
$type = new Type(
    TYPE::RESOURCE_TYPE_GENERAL_TEXT,
    TYPE::RESOURCE_TYPE_BOOK,
    TYPE::BIBTEX_TYPE_BOOK
);

// or choose a pre-defined type, e.g.,

$type = Type::conferenceProceedingsPaper();
$type = Type::conferenceJournalPaper();
$type = Type::bookChapter();
...

$dataCiteRecord->setType($type);
```
- Add date-fields:
```php
$dates = [];
$dates[] = Date::created('2020-10-06');
$dates[] = Date::issued('2020-10-06');
$dates[] = Date::copyrighted('2020-10-06');

$dataCiteRecord->setDates($dates);
```
- View the generated JSON:
```php
var_dump($dataCiteRecord->toApiJson());
```
### 2) Registering/Updating DOI Metadata 

- Updating an existing record:
 ```php
$dataCiteClient = new DataCiteClient('username', 'password', 'api-url');

$dataCiteRecord = $dataCiteClient->updateDataCiteRecord($dataCiteRecord);

if ($dataCiteRecord === NULL) {
    // error handling goes here
}
```
- The same lines of code produce a draft record, if the DOI was not registered before. To make this draft data publicly available and finally register the doi, simply call 
```php        
$dataCiteRecord = $dataCiteClient->setDoiState($dataCiteRecord->getDoi(), DataCiteClient::STATE_FINDABLE);
```
- For error handling/debugging, use the following methods showing details on the status of the last HTTP-request:
```php
$dataCiteClient->getException() / ...->getErrorMessage() / ->getResponse() / ->getStatus() 
```        
        
### 3) Connect to your own models by implementing a `DataCiteDataProvider`

- the `DataCiteDataProvider` interface provides a clean interface to your custom models
- just implement this interface on your models and obtain a DataCiteRecord by 
```php
class MyModelDataProvider implements \Dagstuhl\DataCite\DataCiteDataProvider {
   // ... see the interface for details ... 
}

$dataProvider = new MyModelDataProvider($myModel);     
$dataCiteRecord = DataCiteRecord::fromDataProvider($dataProvider);
```

        