<?php

namespace Toast\Model;

use SilverStripe\ORM\DataObject;

class SearchLog extends DataObject
{
    private static $db = [
        'Term' => 'Text',
        'URL' => 'Text',
        'Count' => 'Int',
        'Synonyms' => 'Text'
    ];

    private static $summary_fields = [
        'Term' => 'Term',
        'URL' => 'URL',
        'Count' => 'Count',
        'Synonyms' => 'Synonyms'
    ];
}