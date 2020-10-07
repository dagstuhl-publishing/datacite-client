# The LZI DataCite Api client

The aim of this project is to provide a simply-to-use php interface for 
- providing scheme-compliant DataCite metadata
- registering/updating the doi metadata via the DataCite API.

####Code Examples:

####1) Creation of a metadata record 

- General Information:

        $dataCiteRecord = new DataCiteRecord();

        $dataCiteRecord->setDoi('10.publisher.example-doi');

        $dataCiteRecord->addTitle(Title::main('This is an example title', 'en'));

- Adding an author with affiliation:

        $creator = Creator::personal('Concatenated Names', 'Given Name', 'Family Name');
        $creator->addAffiliation(new Affiliation('This is a sample affiliation'));

        $dataCiteRecord->addCreator($creator);

- Adding an editor with affiliation based on ror Identifier:

        $name = Name::organizational('Schloss Dagstuhl - Leibniz-Zentrum für Informatik');
        $name->addAffiliation(Affiliation::ror('00k4h2615'));
        $contributor = Contributor::editor($name);

        $dataCiteRecord->addContributor($contributor);

- Adding relationships to other resources / alternative identifiers:

        $related = [];
        $related[] = RelatedIdentifier::isPartOf('arXiv:....', RelatedIdentifier::TYPE_ARXIV);
        $related[] = RelatedIdentifier::cites('10....', RelatedIdentifier::TYPE_DOI);

        $alternate = [];
        $alternate[] = AlternateIdentifier::urn('this is a demo urn');
        $alternate[] = AlternateIdentifier::isbn('this is a demo urn');

        $dataCiteRecord->setRelatedIdentifiers($related);
        $dataCiteRecord->setAlternateIdentifiers($alternate);

- Classifying the type of the Document

        $type = new Type(
            TYPE::RESOURCE_TYPE_BOOK,
            TYPE::RESOURCE_TYPE_GENERAL_TEXT,
            TYPE::BIBTEX_TYPE_BOOK
        );

        $dataCiteRecord->setType($type);

- Add date-fields:

        $dates = [];
        $dates[] = Date::created('2020-10-06');
        $dates[] = Date::issued('2020-10-06');
        $dates[] = Date::copyrighted('2020-10-06');

        $dataCiteRecord->setDates($dates);

- View the generated JSON:

        var_dump($dataCiteRecord->toApiJson());

####2) Registering/Updating DOI Metadata 

- Updating an existing record:
 
        $dataCiteClient = new DataCiteClient('username', 'password', 'api-url');
        
        $dataCiteClient->updateDataCiteRecord($dataCiteRecord);

- The same lines of code produce a draft record, if the DOI was not registered before. To make this draft data publicly available and finally register the doi, simple call 
        
        $dataCiteClient->setDoiState($dataCiteRecord->getDoi(), DataCiteClient::STATE_FINDABLE);
        
####3) Connect to you own models by implementing a DataCiteDataProvider

- the `DataCiteDataProvider` interface provides a clean interface to your custom models
- just implement this interface on your models and obtain a DataCiteRecord by 

        $dataCiteRecord = DataCiteRecord::fromDataProvider($yourModelGoesHere)


        