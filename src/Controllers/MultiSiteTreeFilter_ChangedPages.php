<?php

namespace UmiMood\MultiSiteTree\Controllers;

use UmiMood\MultiSiteTree\MultiSiteTreeEnabled;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_ChangedPages;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\SS_List;
use SilverStripe\Versioned\Versioned;

/**
 * Class MultiSiteTreeFilter_ChangedPages
 *
 * @package UmiMood\MultiSiteTree\Controllers
 */
class MultiSiteTreeFilter_ChangedPages extends CMSSiteTreeFilter_ChangedPages
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
        $pages = $this->applyDefaultFilters($pages)
            ->leftJoin('SiteTree_Live', '"SiteTree_Live"."ID" = "SiteTree"."ID"')
            ->where('"SiteTree"."Version" <> "SiteTree_Live"."Version"');
        return $pages;
    }
}
