<?php
namespace UmiMood\MultiSiteTree\Controllers;

use UmiMood\MultiSiteTree\MultiSiteTreeEnabled;
use UmiMood\MultiSiteTree\MultiSiteTreeTrait;
use SilverStripe\CMS\Controllers\CMSPageSettingsController as BaseCMSPageSettingsController;

/**
 * Class CMSPageSettingsController
 * @package UmiMood\MultiSiteTree\Controllers
 */
class CMSPageSettingsController extends BaseCMSPageSettingsController implements MultiSiteTreeEnabled
{
    use MultiSiteTreeTrait;

    private static $url_segment = 'pages/settings';

    /**
     * Defines link between all controllers for one family/section e.g. category, content, system etc
     * @var string
     */
    private static $family_controller = CMSPagesController::class;
}