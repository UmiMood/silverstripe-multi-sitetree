<?php

namespace UmiMood\MultiSiteTree\Tests;

use SilverStripe\Dev\SapphireTest;
use UmiMood\MultiSiteTree\Tests\Controllers\CMSProductAddController;
use UmiMood\MultiSiteTree\Tests\Controllers\CMSProductEditController;
use UmiMood\MultiSiteTree\Tests\Controllers\CMSProductHistoryViewerController;
use UmiMood\MultiSiteTree\Tests\Controllers\CMSProductsController;
use UmiMood\MultiSiteTree\Tests\Controllers\CMSProductSettingsController;

/**
 * Class ControllerTest
 * @package UmiMood\MultiSiteTree\Tests
 */
class ControllerTest extends SapphireTest
{
    public function setUp()
    {
        // set config
        CMSProductsController::config()->set('url_segment', 'products');
        CMSProductsController::config()->set('menu_title', 'Products');
        CMSProductsController::config()->set('family_controller', CMSProductsController::class);
        CMSProductsController::config()->set('classes', [
            TestPage::class
        ]);

        // add controller
        CMSProductAddController::config()->set('url_segment', 'products/add');
        CMSProductAddController::config()->set('menu_title', 'Products');
        CMSProductAddController::config()->set('family_controller', CMSProductsController::class);

        // edit controller
        CMSProductEditController::config()->set('url_segment', 'products/edit');
        CMSProductEditController::config()->set('menu_title', 'Products');
        CMSProductEditController::config()->set('family_controller', CMSProductsController::class);

        // history viewer controller
        CMSProductHistoryViewerController::config()->set('url_segment', 'products/history');
        CMSProductHistoryViewerController::config()->set('menu_title', 'Products');
        CMSProductHistoryViewerController::config()->set('family_controller', CMSProductsController::class);

        // settings controller
        CMSProductSettingsController::config()->set('url_segment', 'products/settings');
        CMSProductSettingsController::config()->set('menu_title', 'Products');
        CMSProductSettingsController::config()->set('family_controller', CMSProductsController::class);

        parent::setUp();
    }

    public function testFilteredClasses()
    {
        $controller = CMSProductsController::singleton();
        $classes = $controller->getFilteredClasses();

        $this->assertEquals(1, sizeof($classes));
        $this->assertEquals(TestPage::class, array_pop($classes));
    }

    public function testFamilyControllerMenuTitle()
    {
        $controller = CMSProductsController::singleton();
        $menuTitle = $controller->getFamilyControllerMenuTitle();
        $this->assertEquals('Products', $menuTitle);
    }

    public function testFamilyURLSegment()
    {
        $controller = CMSProductsController::singleton();
        $menuTitle = $controller->getFamilyURLSegment();
        $this->assertEquals('products', $menuTitle);
    }

    public function testFamilyController()
    {
        foreach (
            [
                CMSProductsController::class,
                CMSProductAddController::class,
                CMSProductEditController::class,
                CMSProductHistoryViewerController::class,
                CMSProductSettingsController::class
            ] as $class
        ) {
            $controller = $class::singleton()->getFamilyController();
            $this->assertEquals(CMSProductsController::class, $controller);
        }
    }
}