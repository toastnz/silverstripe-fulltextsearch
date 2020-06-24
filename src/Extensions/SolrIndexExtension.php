<?php

namespace Toast\Extensions;

use Apache_Solr_Response;
use SilverStripe\Blog\Model\BlogPost;
use SilverStripe\CMS\Model\VirtualPage;
use SilverStripe\Core\Extension;
use SilverStripe\Dev\Debug;
use SilverStripe\FullTextSearch\Solr\SolrIndex;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\View\ArrayData;
use Toast\Model\Branch;
use Toast\Model\Broker;
use Toast\Pages\IndustryPage;
use Toast\Pages\ProductPage;

/**
 * Class SolrIndexExtension
 * @package Toast\Extensions
 *
 * @property SolrIndex $owner
 */
class SolrIndexExtension extends Extension
{
    /**
     * @param ArrayData            $returnData
     * @param Apache_Solr_Response $results
     */
    public function updateSearchResults($returnData, $results)
    {
        // Get the matches list
        /** @var PaginatedList $matches */
        $matches = $returnData->getField('Matches');

        if ($matches) {
            // Create filters based on the list
            /** @var ArrayList $originalList */
            $originalList = $matches->getList();

            $originalList = $originalList->exclude('ClassName', VirtualPage::class);

            // Change the type - we don't need pages for masonry
            $returnData->setField('Matches', $originalList);

            $returnData->setField('TotalItems', $originalList->count());

            /** -----------------------------------------
             * Filters
             * ----------------------------------------*/

            $filters = ArrayList::create();

            // News
            $newsCount = $originalList->filter('ClassName', BlogPost::class)->count();

            if ($newsCount) {
                $filters->push(ArrayData::create([
                    'Tag'   => 'news',
                    'Label' => 'NEWS',
                    'Count' => $newsCount
                ]));
            }

            // Brokers
            $brokersCount = $originalList->filter('ClassName', Broker::class)->count();

            if ($brokersCount) {
                $filters->push(ArrayData::create([
                    'Tag'   => 'brokers',
                    'Label' => 'BROKERS',
                    'Count' => $brokersCount
                ]));
            }

            // Branches
            $branchesCount = $originalList->filter('ClassName', Branch::class)->count();

            if ($branchesCount) {
                $filters->push(ArrayData::create([
                    'Tag'   => 'branches',
                    'Label' => 'BRANCHES',
                    'Count' => $branchesCount
                ]));
            }

            // Products
            $productsCount = $originalList->filter('ClassName', [ProductPage::class, IndustryPage::class])->count();

            if ($productsCount) {
                $filters->push(ArrayData::create([
                    'Tag'   => 'products',
                    'Label' => 'PRODUCTS',
                    'Count' => $productsCount
                ]));
            }

            // Other
            $otherCount = $originalList->exclude('ClassName',
                [ProductPage::class, IndustryPage::class, Branch::class, Broker::class, BlogPost::class]
            )->count();


            if ($otherCount) {
                $filters->push(ArrayData::create([
                    'Tag'   => 'pages',
                    'Label' => 'OTHER',
                    'Count' => $otherCount
                ]));
            }

            $returnData->setField('Filters', $filters);


        } else {
            $returnData->setField('TotalItems', 0);
        }
    }

}