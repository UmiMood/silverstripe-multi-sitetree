<?php

\SilverStripe\Admin\CMSMenu::remove_menu_class(\SilverStripe\CMS\Controllers\CMSPagesController::class);

$classesToRemove = [
    '\UmiMood\MultiSiteTree\Controllers\CMSPageEditController',
    '\UmiMood\MultiSiteTree\Controllers\CMSPageAddController',
    '\UmiMood\MultiSiteTree\Controllers\CMSPageHistoryViewerController',
    '\UmiMood\MultiSiteTree\Controllers\CMSPageSettingsController',
];

foreach ($classesToRemove as $classToRemove) {
    \SilverStripe\Admin\CMSMenu::remove_menu_class($classToRemove);

    // remove subclasses
    $subClasses = \SilverStripe\Core\ClassInfo::subclassesFor($classToRemove);
    foreach ($subClasses as $subClass) {
        \SilverStripe\Admin\CMSMenu::remove_menu_class($subClass);
    }
}