<?php

namespace LZI\DataCite\Metadata;

class Date
{
    private $date;
    private $dateType;
    private $dateInformation;

    protected function __construct(string $date, string $dateType, $dateInformation = NULL)
    {
        $this->date = $date;
        $this->dateType = $dateType;
        $this->dateInformation = $dateInformation;

    }

    public static function issued(string $date, $dateInformation = NULL)
    {
        return new static($date, 'Issued', $dateInformation);
    }

    public static function available(string $date, $dateInformation = NULL)
    {
        return new static($date, 'Available', $dateInformation);
    }

    public static function created(string $date, $dateInformation = NULL)
    {
        return new static($date, 'Created', $dateInformation);
    }

    public static function copyrighted(string $date, $dateInformation = NULL)
    {
        return new static($date, 'Copyrighted', $dateInformation);
    }

    // TODO: add
    // Accepted
    // Collected
    // Submitted
    // Updated
    // Valid
    // Withdrawn
    // Other



    public function toApiObject()
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