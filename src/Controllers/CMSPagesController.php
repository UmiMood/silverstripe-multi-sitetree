<?php

namespace UmiMood\MultiSiteTree\Controllers;

use UmiMood\MultiSiteTree\MultiSiteTreeEnabled;
use UmiMood\MultiSiteTree\MultiSiteTreeTrait;
use SilverStripe\CMS\Controllers\CMSPagesController as BaseCMSPagesController;

/**
 * Class CMSPagesController
 * @package UmiMood\MultiSiteTree\Controllers
 */
class CMSPagesController extends BaseCMSPagesController implements MultiSiteTreeEnabled
{
    use MultiSiteTreeTrait;

    private static $url_segment = 'pages';

    private static $classes = [];

    /**
     * Defines link between all controllers for one family/section e.g. category, content, system etc
     * @var string
     */
    private static $family_controller = CMSPagesController::class;

    public static function menu_title($class = null, $localise = true)
    {
        return parent::menu_title($class, $localise);
    }
}