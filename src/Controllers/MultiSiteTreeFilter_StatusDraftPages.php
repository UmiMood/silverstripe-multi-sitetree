<?php

namespace UmiMood\MultiSiteTree\Controllers;

use UmiMood\MultiSiteTree\MultiSiteTreeEnabled;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_StatusDraftPages;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\SS_List;
use SilverStripe\Versioned\Versioned;

/**
 * Class MultiSiteTreeFilter_StatusDraftPages
 *
 * @package UmiMood\MultiSiteTree\Controllers
 */
class MultiSiteTreeFilter_StatusDraftPages extends CMSSiteTreeFilter_StatusDraftPages
{
    /**
     * @return SS_List
     */
    public function getFilteredPages()
    {
        $pages = Versioned::get_by_stage(SiteTree::class, 'Stage');
        $controller = Controller::curr();

        if ($controller && $controller instanceof MultiSiteTreeEnabled) {
            $classes = $controller->getFilteredClasses();
            if (!empty($classes)) {
                $pages = $pages->filterAny('ClassName', $classes);
            }
        }

        $pages = $this->applyDefaultFilters($pages);
        $pages = $pages->filterByCallback(function (SiteTree $page) {
            return $page->isOnDraftOnly();
        });
        return $pages;
    }
}
