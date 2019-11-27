<?php

namespace UmiMood\MultiSiteTree\Controllers;

use UmiMood\MultiSiteTree\MultiSiteTreeEnabled;
use UmiMood\MultiSiteTree\MultiSiteTreeTrait;
use SilverStripe\CMS\Controllers\CMSPageAddController as BaseCMSPageAddController;

/**
 * Class CMSPageAddController
 *
 * @package UmiMood\MultiSiteTree\Controllers
 */
class CMSPageAddController extends BaseCMSPageAddController implements MultiSiteTreeEnabled
{
    use MultiSiteTreeTrait;

    private static $url_segment = 'pages/add';

    /**
     * Defines link between all controllers for one family/section e.g. category, content, system etc
     * @var string
     */
    private static $family_controller = CMSPagesController::class;

    private static $allowed_actions = array(
        'AddForm',
        'doAdd',
        'doCancel'
    );

    /**
     * Method override @see BaseCMSPageAddController::AddForm()
     *
     * @return \SilverStripe\Forms\Form
     */
    public function AddForm()
    {
        $form = parent::AddForm();
        $classes = $this->getFilteredClasses();

        if (!empty($classes)) {
            $field = $form->Fields()->dataFieldByName('ParentID');
            $field->setFilterFunction(function ($node) use ($classes) {
                return (in_array($node->getClassName(), $classes));
            });
        }

        return $form;
    }
}