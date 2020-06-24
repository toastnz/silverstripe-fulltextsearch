<?php

namespace Toast\Pages;

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\Debug;
use SilverStripe\FullTextSearch\Search\Queries\SearchQuery;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DB;
use SilverStripe\Versioned\Versioned;
use SilverStripe\View\ArrayData;
use Toast\Model\CrombieIndex;

class SearchResults extends \Page
{
    private static $singular_name = 'Toast Search Results';
    private static $plural_name = 'Search Results';
    private static $description = 'Displays search results';
    // private static $icon = 'mysite/dist/images/cms/document-search-result.png';
    private static $can_be_root = true;
    private static $table_name = 'SearchResults';

    private static $defaults = [
        "ShowInMenus"  => 0,
        "ShowInSearch" => 0,
    ];


    /**
     * Add default records to database.
     *
     * This function is called whenever the database is built, after the
     * database tables have all been created.
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        // If there are no search pages
        $searchResultsPages = SearchResults::get();

        if (!$searchResultsPages->count()) {
            /** @var SearchResultsPage $searchResultsPage */
            $searchResultsPage = SearchResults::create([
                'Title'        => 'Search',
                'ShowInMenus'  => 0,
                'ShowInSearch' => 0
            ]);

            $searchResultsPage->write();
            $searchResultsPage->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);
            $searchResultsPage->flushCache();

            DB::alteration_message('Search results page created', 'created');
        }

    }
}

class SearchResultsController extends \PageController
{

    public function getResultsCount($Filter){
//            Debug::show($Filter);
//            die();
    }

    public function getActive(){

        if ($this->getRequest()->getVar('Filter')){
            return $this->getRequest()->getVar('Filter');
        }else{
            return 'all';
        }

    }
}