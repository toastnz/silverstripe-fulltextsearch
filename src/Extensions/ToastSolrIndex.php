<?php

namespace Toast\Model;

use SilverStripe\Assets\File;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\FullTextSearch\Search\Variants\SearchVariantVersioned;
use SilverStripe\FullTextSearch\Solr\SolrIndex;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Versioned\Versioned;

class ToastSolrIndex extends SolrIndex
{

    public function init()
    {
    
        $classes = (array)$this->config()->classes;
        $this->addClass(SiteTree::class);
        $this->addClass(File::class);
        foreach($classes as $class){
            $this->addClass($class);
        };
        
        $this->addAllFulltextFields();


        // foreach($classes as $class){
        //     $this->setFieldBoosting($class . '_Title', 2);
        // };
        

        $this->addFilterField('ShowInSearch');

        $this->excludeVariantState(['SearchVariantVersioned' => 'Stage']);

    }

    public function getFieldDefinitions()
    {
        $xml = parent::getFieldDefinitions();

        $xml .= "\n\n\t\t<!-- Additional custom fields for spell checking -->";
        $xml .= "\n\t\t<field name='spellcheckData' type='textSpellHtml' indexed='true' stored='false' multiValued='true' />";

        return $xml;
    }

}
