<?php
namespace UmiMood\MultiSiteTree\Controllers;

use UmiMood\MultiSiteTree\MultiSiteTreeEnabled;
use UmiMood\MultiSiteTree\MultiSiteTreeTrait;
use SilverStripe\VersionedAdmin\Controllers\CMSPageHistoryViewerController as BaseCMSPageHistoryViewerController;

/**
 * Class CMSPageHistoryViewerController
 * @package UmiMood\MultiSiteTree\Controllers
 */
class CMSPageHistoryViewerController extends BaseCMSPageHistoryViewerController implements MultiSiteTreeEnabled
{
    use MultiSiteTreeTrait;

    private static $url_segment = 'pages/history';

    /**
     * Defines link between all controllers for one family/section e.g. category, content, system etc
     * @var string
     */
    private static $family_controller = CMSPagesController::class;
}