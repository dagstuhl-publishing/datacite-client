<?php

namespace Dagstuhl\DataCite\Metadata;

class Date
{
    private string $date;
    private string $dateType;
    private ?string $dateInformation;

    protected function __construct(string $date, string $dateType, $dateInformation = NULL)
    {
        $this->date = $date;
        $this->dateType = $dateType;
        $this->dateInformation = $dateInformation;
    }

    public static function issued(string $date, $dateInformation = NULL): static
    {
        return new static($date, 'Issued', $dateInformation);
    }

    public static function available(string $date, $dateInformation = NULL): static
    {
        return new static($date, 'Available', $dateInformation);
    }

    public static function created(string $date, $dateInformation = NULL): static
    {
        return new static($date, 'Created', $dateInformation);
    }

    public static function copyrighted(string $date, $dateInformation = NULL): static
    {
        return new static($date, 'Copyrighted', $dateInformation);
    }

    public static function accepted(string $date, $dateInformation = NULL): static
    {
        return new static($date, 'Accepted', $dateInformation);
    }

    public static function submitted(string $date, $dateInformation = NULL): static
    {
        return new static($date, 'Submitted', $dateInformation);
    }

    // TODO: add
    // Collected
    // Updated
    // Valid
    // Withdrawn
    // Other

    public function toApiObject(): object
    {
        $d = [];
        $d['date'] = $this->date;
        $d['dateType'] = $this->dateType;

        if ($this->dateInformation !== NULL) {
            $d['dateInformation'] = $this->dateInformation;
        }

        return (object) $d;
    }
}