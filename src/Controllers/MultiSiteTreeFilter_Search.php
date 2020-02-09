<?php

namespace UmiMood\MultiSiteTree\Controllers;

use UmiMood\MultiSiteTree\MultiSiteTreeEnabled;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_Search;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Versioned\Versioned;
use SilverStripe\ORM\SS_List;

/**
 * Class MultiSiteTreeFilter_Search
 * @package UmiMood\MultiSiteTree\Controllers
 */
class MultiSiteTreeFilter_Search extends CMSSiteTreeFilter_Search
{
    /**
     * @return SS_List
     */
    public function getFilteredPages()
    {
        $pages = Versioned::get_by_stage(SiteTree::class, Versioned::DRAFT);
        $controller = Controller::curr();
        
        if ($controller && $controller instanceof MultiSiteTreeEnabled) {
            $classes = $controller->getFilteredClasses();
            if (!empty($classes)) {
                $pages = $pages->filterAny('ClassName', $classes);
            }
        }

        $pages = $this->applyDefaultFilters($pages);
        return $pages;
    }
}