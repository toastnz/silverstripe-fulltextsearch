<?php

use SilverStripe\Control\Director;
use SilverStripe\Core\Environment;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\FullTextSearch\Solr\Solr;
use SilverStripe\ORM\Search\FulltextSearchable;

define('TOAST_SEARCH_DIR', basename(__DIR__));

try {
    FulltextSearchable::enable([SiteTree::class]);
} catch (Exception $e) {
    user_error($e->getMessage(), E_USER_NOTICE);
}


$solrConfig = [];
if (Director::isLive() || Director::isTest()) {
    
    $solrConfig = array_merge($solrConfig, [
        'host' => Environment::getEnv('SOLR_SERVER') ? Environment::getEnv('SOLR_SERVER') : 'localhost',
        'port' => Environment::getEnv('SOLR_PORT') ? Environment::getEnv('SOLR_PORT') : 8983,
        'path' => Environment::getEnv('SOLR_PATH') ? Environment::getEnv('SOLR_PATH') : '/solr/',
        'indexstore' => [
            'mode' => Environment::getEnv('SOLR_MODE') ? Environment::getEnv('SOLR_MODE') : 'file',
            'auth' => Environment::getEnv('SOLR_AUTH') ? Environment::getEnv('SOLR_AUTH') : NULL,
            'path' => Environment::getEnv('SOLR_INDEXSTORE_PATH') ? Environment::getEnv('SOLR_INDEXSTORE_PATH') : BASE_PATH . '/.solr',
            'remotepath' => Environment::getEnv('SOLR_REMOTE_PATH') ? Environment::getEnv('SOLR_REMOTE_PATH') : null
        ],
    ]);
} else if (Director::isDev()) {
    // dev details
    $solrConfig = array_merge($solrConfig, [
        'host' => '127.0.0.1',
        'port' => 8983,
        'indexstore' => [
            'mode' => 'file',
            'path' => BASE_PATH . '/.solr',
        ]
    ]);
}

Solr::configure_server($solrConfig);