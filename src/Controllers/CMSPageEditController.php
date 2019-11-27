<?php

namespace UmiMood\MultiSiteTree\Controllers;

use UmiMood\MultiSiteTree\MultiSiteTreeEnabled;
use UmiMood\MultiSiteTree\MultiSiteTreeTrait;
use SilverStripe\Admin\AdminRootController;
use SilverStripe\CMS\Controllers\CMSMain;
use SilverStripe\CMS\Controllers\CMSPageEditController as BaseCMSPageEditController;
use SilverStripe\Control\Controller;

/**
 * Class CMSPageEditController
 * @package UmiMood\MultiSiteTree\Controllers
 */
class CMSPageEditController extends BaseCMSPageEditController implements MultiSiteTreeEnabled
{
    use MultiSiteTreeTrait;

    private static $url_segment = 'pages/edit';

    /**
     * Defines link between all controllers for one family/section e.g. category, content, system etc
     * @var string
     */
    private static $family_controller = CMSPagesController::class;

    /**
     * Method override @see CMSMain::Link()
     *
     * @param null $action
     * @return string
     */
    public function Link($action = null)
    {
        $controller = Controller::curr();
        if ($controller instanceof MultiSiteTreeEnabled) {
            $urlSegment = Controller::curr()->getFamilyURLSegment();

            $link = Controller::join_links(
                AdminRootController::admin_url(),
                $urlSegment,
                '/',
                "edit/$action"
            );
            $this->extend('updateLink', $link);
            return $link;
        }

        return parent::Link($action);
    }
}