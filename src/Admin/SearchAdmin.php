<?php
namespace Toast\Admin;
use SilverStripe\Admin\ModelAdmin;
use Toast\Model\SearchLog;
use Toast\Model\Synonym;

class SearchAdmin extends ModelAdmin
{

    private static $url_segment = 'search';
    private static $menu_title = 'Search';

    private static $managed_models = array(
        Synonym::class => [
            'title' => 'Synonyms'
        ],
        SearchLog::class => [
            'title' => 'Search Logs'
        ]
    );

}