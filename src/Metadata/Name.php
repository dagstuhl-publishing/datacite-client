<?php

namespace Dagstuhl\DataCite\Metadata;

class Name
{
    private string $name;
    private string $nameType;
    private string $givenName;
    private string $familyName;
    /**
     * @var Affiliation[]
     */
    private array $affiliations;
    /**
     * @var NameIdentifier[]
     */
    private array $nameIdentifiers;

    /**
     * Name constructor.
     * @param string $name
     * @param string $nameType
     * @param string|null $givenName
     * @param string|null $familyName
     * @param Affiliation[] $affiliations
     * @param NameIdentifier[] $nameIdentifiers
     */
    private function __construct(string $name, string $nameType, string $givenName = NULL, string $familyName = NULL, array $affiliations = [], array $nameIdentifiers = [])
    {
        $this->name = trim($name);
        $this->nameType = trim($nameType);
        $this->givenName = trim($givenName);
        $this->familyName = trim($familyName);
        $this->affiliations = $affiliations;
        $this->nameIdentifiers = $nameIdentifiers;
    }

    /**
     * @param Affiliation[] $affiliations
     * @param NameIdentifier[] $nameIdentifiers
     *
     * NOTE: If $name contains "," then $givenName and $familyName will be set automatically where
     * $name is assumed to be of the form "family name, first name".
     * By setting $givenName or $familyName to a non-NULL value, one can overwrite these auto-detected values.
     */
    public static function personal(string $name, string $givenName = NULL, string $familyName = NULL, array $affiliations = [], array $nameIdentifiers = []): static
    {
        $parts = explode(',', $name);

        if (count($parts) === 2) {
            $givenName = $givenName ?? $parts[1];
            $familyName = $familyName ?? $parts[0];
        }

        return new static($name, 'Personal', $givenName, $familyName, $affiliations, $nameIdentifiers);
    }

    /**
     * @param Affiliation[] $affiliations
     * @param NameIdentifier[] $nameIdentifiers
     */
    public static function organizational(string $name, string $givenName = NULL, string $familyName = NULL, array $affiliations = [], array $nameIdentifiers = []): static
    {
        return new static($name, 'Organizational', $givenName, $familyName, $affiliations, $nameIdentifiers);
    }

    // ---- name identifiers ----------------------------------------

    public function addNameIdentifier(NameIdentifier $nameIdentifier): void
    {
        $this->nameIdentifiers[] = $nameIdentifier;
    }

    /**
     * @param NameIdentifier[] $nameIdentifiers
     */
    public function setNameIdentifiers(array $nameIdentifiers): void
    {
        $this->nameIdentifiers = [];

        foreach($nameIdentifiers as $nameIdentifier) {
            $this->addNameIdentifier($nameIdentifier);
        }
    }

    // ---- affiliations -------------------------------------------

    public function addAffiliation(Affiliation $affiliation): void
    {
        $this->affiliations[] = $affiliation;
    }

    public function setAffiliations(array $affiliations): void
    {
        $this->affiliations = [];

        foreach($affiliations as $affiliation) {
            $this->addAffiliation($affiliation);
        }
    }


    public function toApiObject(): object
    {
        $n = [];

        $n['name'] = $this->name;
        $n['nameType'] = $this->nameType;

        foreach([ 'givenName', 'familyName' ] as $prop) {
            if ($this->{$prop} !== NULL) {
                $n[$prop] = $this->{$prop};
            }
        }

        if (count($this->affiliations) > 0) {
            $a = [];

            foreach($this->affiliations as $affiliation) {
                $a[] = $affiliation->toApiObject();
            }

            $n['affiliation'] = $a;
        }

        if (count($this->nameIdentifiers) > 0) {
            $i = [];

            foreach($this->nameIdentifiers as $nameIdentifier) {
                $i[] = $nameIdentifier->toApiObject();
            }

            $n['nameIdentifiers'] = $i;
        }

        return (object) $n;
    }
}