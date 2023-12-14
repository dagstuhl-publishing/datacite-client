<?php

namespace Dagstuhl\DataCite\Metadata;

class Subject
{
    private string $subject;
    private ?string $subjectScheme;
    private ?string $lang;
    private ?string $schemeURI;
    private ?string $valueURI;

    const SUBJECT_SCHEME_ACM_2012 = 'The 2012 ACM Computing Classification System';

    public function __construct(string $subject, string $language = NULL, string $subjectScheme = NULL, string $schemeURI = NULL, string $valueURI = NULL)
    {
        $this->subject = $subject;
        $this->lang = $language;
        $this->subjectScheme = $subjectScheme;
        $this->schemeURI = $schemeURI;
        $this->valueURI = $valueURI;
    }

    public function toApiObject(): object
    {
        $s = [ 'subject' => $this->subject ];

        foreach([ 'lang', 'subjectScheme', 'schemeURI', 'valueURI' ] as $prop) {
            if ($this->{$prop} !== NULL) {
                $s[$prop] = $this->{$prop};
            }
        }

        return (object) $s;
    }
}