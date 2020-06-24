<?php

namespace Toast\Extensions;

use Toast\Model\Synonym;
use SilverStripe\Dev\Debug;
use Toast\Model\SynonymItem;
use SilverStripe\Core\Convert;
use Toast\Pages\SearchResults;
use SilverStripe\ORM\ArrayList;
use Toast\Model\ToastSolrIndex;
use SilverStripe\Core\Extension;
use SilverStripe\View\ArrayData;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\GroupedList;
use SilverStripe\Control\Director;
use SilverStripe\Forms\FormAction;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\CMS\Search\SearchForm;
use SilverStripe\ORM\Search\FulltextSearchable;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\FullTextSearch\Search\Queries\SearchQuery;

class PageSearchExtension extends Extension
{
    private static $allowed_actions = [
        'SearchForm',
        'results',
        'SearchAutoComplete'
    ];
    
    public function getStandardJsonResponse($data, $method = 'json', $message = '', $code = 200, $status = 'success')
    {
        $elapsed = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

        $response = [
            'request' => $this->owner->getRequest()->httpMethod(),
            'status'  => $status, // success, error
            'method'  => $method,
            'elapsed' => number_format($elapsed * 1000, 0) . 'ms',
            'message' => $message,
            'code'    => $code,
            'data'    => $data
        ];

        return json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);
    }
    
    public function createSearchForm($name = 'SearchForm', $searchText = '')
    {

        $request = Controller::curr()->getRequest();
        if ($request && $request->getVar('Search')) {
            $searchText = $this->getRequest()->getVar('Search');
        } else {
            $searchText = ' Looking for something?';
        }

        $fields  = new FieldList(
            $mySearchField = new TextField('Search', null, $searchText)
        );

        $mySearchField->setAttribute('placeholder', '');
        $mySearchField->addExtraClass('js-search-input');
        $actions = new FieldList(
            new FormAction('results', _t('SilverStripe\\CMS\\Search\\SearchForm.GO', 'Go'))
        );

        // Set a new controller
        $searchPage = SearchResults::get()->first();

        if ($searchPage && $searchPage->exists()) {
            $request = ContentController::create($searchPage);
        } else {
            $request = $this;
        }

        /** @skipUpgrade */
        $form = SearchForm::create($request, 'SearchForm', $fields, $actions);
        //        if (!Director::isDev()) {
        $form->classesToSearch(FulltextSearchable::get_searchable_classes());
        //        }
        $form->setPageLength(6);

        $form->setFormAction($request->AbsoluteLink('results'));

        return $form;
    }

    public function results()
    {
        $request = Controller::curr()->getRequest();
        $string = $request->getVar('q');


        $keywords = preg_split('/(?<=\D)(?=\d)|\d+\K/', $string);

        // if (ProductPage::get()->filter('Title:PartialMatch', preg_split('/(?<=\D)(?=\d)|\d+\K/', $request->getVar('Search')))->count() >= 1) {
        //     $TabDefault = 'Products';
        // } elseif (CategoryPage::get()->filter('Title', preg_split('/(?<=\D)(?=\d)|\d+\K/', $request->getVar('Search')))->count() >= 1) {
        //     $TabDefault = 'Products';
        // } elseif (Attribute::get()->filter('CustomTitlesForSearch', $string)->count() >= 1) {
        //     $TabDefault = 'Products';
        // } else {
        //     $TabDefault = 'All';
        // }


        $query = SearchQuery::create();

        $SynonymIDs = SynonymItem::get()->filter(['Title:PartialMatch' => $request->getVar('q')])->map('SynonymID', 'SynonymID')->toArray();

        if (count($SynonymIDs)) {
            $synonyms = Synonym::get()->filter(['ID' => $SynonymIDs]);
        } else {
            $synonyms = Null;
        }
        if (count($SynonymIDs) && count($synonyms)) {
            foreach ($synonyms as $synonym) {
                $query->addSearchTerm($synonym->Name);
            }
        } else {
            if (count($keywords) > 1) {
                foreach ($keywords as $keyword) {
                    $query->addSearchTerm($keyword);
                }
            } else {
                $query->addSearchTerm($string);
            }
        }

       $params = [
           'hl'                 => 'true',
           'hl.fl'              => 'highlightData',
           'hl.simple.pre'      => ':highlight:',
           'hl.simple.post'     => ':/highlight:',
           'hl.fragsize'        => 100,
           'hl.snippets'        => 3,
           'hl.mergeContiguous' => 'true',
           'spellcheck'         => 'true',
           'spellcheck.collate' => 'true',
           'defType'            => 'edismax',
           'qf'                 => ['exactTextData', '_text'],
       ];

        $searchPage = $this->owner->data();

        $query->addExclude('ClassName', array(Folder::class, Image::class));

        /** @var SolrIndex $results */
        $results = ToastSolrIndex::singleton()->search($query, -1, 1000, $params);
        $results->spellcheck;

        // $groupList = GroupedList::create($results->Matches->sort('ClassName'));
        // $orderedList = new ArrayList();
        // $filterTypes = SearchResults::config()->filters;
        // Debug::dump($filterTypes);
        // foreach ($filterTypes as $name => $filterType){
        //     Debug::dump($name);
        //     Debug::dump($filterType);
        //     die();
        //     $pageOrdered = $groupList->filter('ClassName', $filterType);
        // }
    

        // $others = $groupList->exclude('ClassName', [
        // //     ProductPage::class,
        // //     CategoryPage::class,
        // //     Attribute::class,
        // //     Resource::class,
        //     Page::class,
        // //     FAQ::class,
        // //     BlogPost::class
        // ]);
        
        // $orderedList->merge($productOrdered);
        // $orderedList->merge($categoryOrdered);
        // $orderedList->merge($attributeOrdered);
        // $orderedList->merge($resourceOrdered);
        // $orderedList->merge($pageOrdered);
        // $orderedList->merge($faqOrdered);
        // $orderedList->merge($blogpostOrdered);
        // $orderedList->merge($others);
    


        // Suggestions
        if ($results->Suggestion) {
            $results->Suggestion = Convert::raw2xml($this->filterSearchString($results->Suggestion));

            $results->SuggestionLink = $searchPage->AbsoluteLink('/results?Search=' . $results->Suggestion);
        }


        // Matches
        if ($results->Matches) {
            foreach ($results->Matches as $item) {
                if ($item->Excerpt) {
                    $item->Excerpt = strip_tags(html_entity_decode($item->Excerpt->value, ENT_COMPAT, 'UTF-8'));
                    $item->Excerpt = str_replace(':highlight:', '<strong>', $item->Excerpt);
                    $item->Excerpt = str_replace(':/highlight:', '</strong>', $item->Excerpt);
                }
            }
        }else{
            
        }


        $filters = [
            'Products' => 'Products',
            'Resources' => 'Resources',
            'Applications' => 'Applications',
            'Pages' => 'Pages'
        ];
        
        
        $filterResultCounts = new ArrayList();
        
        $filterTypes = SearchResults::config()->filters;
        
        foreach ($filterTypes as $name => $filterType){
        
            $count = $results->Matches->filterAny(['ClassName' => $filterType])->count();
            
            $filterResultCounts->push([
                'Title' => $name,
                'Total' => $count,
            ]);
        }
        
        $PagesCount = $results->Matches->count();
        $groupList = GroupedList::create($results->Matches->sort('ClassName'));
        $data = [
            'InPageSearchForm' => $this->InPageSearchForm(),
            'Matches'     => $results,
            'Items'     => $results->Matches->items,
            'Synonyms'     => $synonyms ? $synonyms : Null,
            'SynonymsString'     => $synonyms ? implode(', ', $synonyms->map('ID', 'Title')->toArray()) : Null,
            'Query'            => $request->getVar('q'),
            'PagesCount' => $PagesCount,
            'Grouped' => $groupList,
            'SearchSuccess' => $PagesCount >= 1 ? 'successful' : 'unsuccessful',
            'Filters' => $this->getFilters($results->Matches->items),
        ];

        

        $searchLog = \Toast\Model\SearchLog::create();
        $searchLog->Term = $request->getVar('Search');
        $searchLog->Count = Count($results->Matches);
        $searchLog->URL = $request->getURL(TRUE);
        $searchLog->Synonyms = $synonyms ? implode(', ',  $synonyms->map('ID', 'Title')->toArray()) : Null;
        $searchLog->write();



        if ($request->isAjax()) {

            $jsonItems = [];
            foreach ($results->Matches as $item) {

                $jsonItems[] = [
                    'id'   => $item->ID,
                    'html' => $item->forSearchTemplate()
                ];
            }
            $jsonData = [
                'items'       => $jsonItems,
                'total_items' => count($results->Matches),
            ];

            return $this->owner->getStandardJsonResponse($jsonData);
        } else {
            return $this->owner->customise($data);
        }
    }

    protected function filterSearchString($val)
    {
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                $val[$k] = $this->filterSearchString($v);
            }
            return $val;
        } else {
            return str_replace(
                ['<', '>', "\n", "\r", '(', ')', '@', '$', ';', '|', ',', '%', ':', '+'],
                '',
                $val
            );
        }
    }

    public function InPageSearchForm($name = 'InPageSearchForm', $searchText = '')
    {

        $request = Controller::curr()->getRequest();
        if ($request && $request->getVar('q')) {
            $searchText = $request->getVar('q');
        } else {
            $searchText = ' Looking for something?';
        }

        $fields  = new FieldList(
            $mySearchField = new TextField('Search', null, $searchText)
        );

        $mySearchField->setAttribute('placeholder', '');
        $mySearchField->addExtraClass('js-search-input');
        $actions = new FieldList(
            new FormAction('results', _t('SilverStripe\\CMS\\Search\\SearchForm.GO', 'Go'))
        );

        // Set a new controller
        $searchPage = SearchResults::get()->first();

        if ($searchPage && $searchPage->exists()) {
            $request = ContentController::create($searchPage);
        } else {
            $request = $this;
        }

        /** @skipUpgrade */
        $form = SearchForm::create($request, 'SearchForm', $fields, $actions);
        //        if (!Director::isDev()) {
        $form->classesToSearch(FulltextSearchable::get_searchable_classes());
        //        }
        $form->setPageLength(6);

        $form->setFormAction($request->AbsoluteLink('results'));

        return $form;
    }

    public function getFilters()
    {
    
        $items = [];
        $filters = SearchResults::config()->filters;
        
        $link = $this->owner->getRequest()->getURL();

        $results = new ArrayList();

        foreach ($filters as $key => $filtertype) {
            $query = $_GET;
            $query['Filter'] = $key;
            $query_result = http_build_query($query);
            // $count = $items->filterAny(['ClassName' => $filtertype])->count();
            $count = 0;

            $results->push(new ArrayData([
                'Title' => $key,
                'Filter' => $key,
                'Link' => $link . '?' . $query_result,
                'FilterLink' => $link . '?' . $query_result,
                'Count' => $count
            ]));
        }

        return $results;
    }

    /**
     * Site search form
     *
     * @return SearchForm
     */
    public function SearchForm()
    {
        return $this->createSearchForm('SearchForm');
    }

    public function SearchPlaceholder()
    {
        $search = $this->getRequest()->getVar('q');
        if (isset($search)) {
            return $this->getRequest()->getVar('q');
        } else {
            return 'Looking for something?';
        }
    }

    /**
     * @param HTTPRequest $request
     * @return HTTPResponse
     */
    public function SearchAutoComplete(HTTPRequest $request)
    {
        $query = $request->getVar('q');
        $Pages = SiteTree::get()->filter(['Title:PartialMatch' => $query])->map('ID', 'Title')->toArray();
        $Files = File::get()->filter(['Title:PartialMatch' => $query])->map('ID', 'Title')->toArray();

        $list = array_merge($Pages, $Files);

        $list = array_unique($list);
        $newList = [];

        foreach ($list as $k => $v) {
            $newList[]['title'] = $v;
        }
        
        return json_encode( array_slice($newList, 0, 5));
    }
}