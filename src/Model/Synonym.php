<?php
namespace Toast\Model;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\TagField\TagField;

/**
 * Class Synonym
 * This is the author class that allows attributation from
 * within an MOS Article page or Issue holder page (Directors Cut)
 *
 * @property int    SortOrder
 * @property string $Name
 * @property string $Synonyms
 * @method HasManyList|SynonymItem[] SynonymItems()
 */
class Synonym extends DataObject
{

    private static $db = [
        'SortField' => 'Int',
        'Name'      => 'Varchar(512)',
        'Synonyms'  => 'Text'
    ];

    private static $summary_fields = [
        'Name'        => 'Name',
        'SynonymList' => 'Synonyms'
    ];

    private static $default_sort = 'Created DESC';

    private static $has_many = [
        'SynonymItems' => SynonymItem::class
    ];

    private static $searchable_fields = [
        'Name',
        'SynonymItems.Title'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        /**
         * @var FieldList $fields
         * @var TagField  $SynonymItems
         */
        $fields = parent::getCMSFields();
        $fields->removeByName(['SortField', 'Name', 'Synonyms', 'SynonymItems']);
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Name', 'Name'),
            TagField::create('SynonymItems', 'Synonym Items', SynonymItem::get(), $this->SynonymItems())->setCanCreate(true)
        ]);
        return $fields;
    }

    public function getSynonymList()
    {
        return implode(', ', $this->SynonymItems()->column('Title'));
    }
}