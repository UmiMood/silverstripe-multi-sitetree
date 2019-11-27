<?php

namespace UmiMood\MultiSiteTree\Extensions;

use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_ChangedPages;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_DeletedPages;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_PublishedPages;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_StatusDeletedPages;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_StatusDraftPages;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_StatusRemovedFromDraftPages;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\Form;
use SilverStripe\View\ArrayData;

/**
 * Class MultiSiteTreeCMSMainExtension
 * @package UmiMood\MultiSiteTree\Extensions
 */
class MultiSiteTreeCMSMainExtension extends Extension
{
    /**
     * @param Form $form
     */
    public function updateSearchForm(Form $form)
    {

        // filter fields
        $filterField = $form->Fields()->fieldByName('Search__FilterClass');
        $source = $filterField->getSource();

        // unset default SS filter classes
        unset($source[CMSSiteTreeFilter_ChangedPages::class]);
        unset($source[CMSSiteTreeFilter_DeletedPages::class]);
        unset($source[CMSSiteTreeFilter_PublishedPages::class]);
        unset($source[CMSSiteTreeFilter_StatusDeletedPages::class]);
        unset($source[CMSSiteTreeFilter_StatusDraftPages::class]);
        unset($source[CMSSiteTreeFilter_StatusRemovedFromDraftPages::class]);

        $filterField->setSource($source);
    }

    /**
     * @param $items
     */
    public function updateBreadcrumbs($items)
    {
        if (!$this->owner->currentPage()) {
            $unlinked = false;
            $last = $items->pop();
            $items->push(new ArrayData(array(
                'Title' => $this->getOwner()->config()->get('menu_title'),
                'Link' => ($unlinked) ? false : $this->getOwner()->Link()
            )));
        }
    }
}
