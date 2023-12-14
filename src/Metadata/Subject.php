<?php

namespace Dagstuhl\DataCite\Metadata;

class Subject
{
    private $subject;
    private $subjectScheme;
    private $lang;
    private $schemeURI;
    private $valueURI;

    const SUBJECT_SCHEME_ACM_2012 = 'The 2012 ACM Computing Classification System';

    public function __construct($subject, $language = NULL, $subjectScheme = NULL, $schemeURI = NULL, $valueURI = NULL)
    {
        $this->subject = $subject;
        $this->lang = $language;
        $this->subjectScheme = $subjectScheme;
        $this->schemeURI = $schemeURI;
        $this->valueURI = $valueURI;
    }

    public function toApiObject()
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