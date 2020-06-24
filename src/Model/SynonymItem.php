<?php
namespace Toast\Model;
use SilverStripe\ORM\DataObject;

/**
 * Class SynonymItem
 * The actual synonym item that a synonym references
 *
 * @property string $Title
 * @method Synonym Synonym()
 */
class SynonymItem extends DataObject
{

    private static $db = [
        'Title' => 'Varchar(512)'
    ];

    private static $has_one = array(
        'Synonym' => Synonym::class
    );

}