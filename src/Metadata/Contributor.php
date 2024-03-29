<?php

namespace Dagstuhl\DataCite\Metadata;

class Contributor
{
    private Name $name;
    private string $contributorType;

    public function __construct(Name $name, string $contributorType)
    {
        $this->name = $name;
        $this->contributorType = $contributorType;
    }

    public static function editor(Name $name): static
    {
        return new static($name, 'Editor');
    }

    public static function dataCollector(Name $name): static
    {
        return new static($name, 'DataCollector');
    }

    // TODO: add methods for the following contributorTypes
    // ContactPerson
    // DataCollector
    // DataCurator
    // DataManager
    // Distributor
    // Editor
    // HostingInstitution
    // Producer
    // ProjectLeader
    // ProjectManager
    // ProjectMember
    // RegistrationAgency
    // RegistrationAuthority
    // RelatedPerson
    // Researcher
    // ResearchGroup
    // RightsHolder
    // Sponsor
    // Supervisor
    // WorkPackageLeader
    // Other

    public function toApiObject(): object
    {
        $object = $this->name->toApiObject();
        $object->contributorType = $this->contributorType;

        return $object;
    }
}