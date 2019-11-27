<?php

namespace UmiMood\MultiSiteTree\Controllers;

use UmiMood\MultiSiteTree\MultiSiteTreeEnabled;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_DeletedPages;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\SS_List;
use SilverStripe\Versioned\Versioned;

/**
 * Class MultiSiteTreeFilter_DeletedPages
 * @package UmiMood\MultiSiteTree\Controllers
 */
class MultiSiteTreeFilter_DeletedPages extends CMSSiteTreeFilter_DeletedPages
{
    /**
     * @return SS_List
     */
    public function getFilteredPages()
    {
        $pages = Versioned::get_including_deleted(SiteTree::class);
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
