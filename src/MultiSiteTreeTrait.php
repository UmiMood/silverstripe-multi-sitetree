<?php

namespace UmiMood\MultiSiteTree;

use UmiMood\MultiSiteTree\Controllers\CMSPagesController;
use ReflectionClass;
use InvalidArgumentException;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\CMS\Controllers\CMSMain;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter;
use SilverStripe\CMS\Controllers\CMSSiteTreeFilter_Search;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Config\Config;
use SilverStripe\Admin\LeftAndMain_SearchFilter;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\HiddenClass;
use SilverStripe\View\ArrayData;

/**
 * Trait MultiSiteTreeTrait
 *
 * @package UmiMood\MultiSiteTree
 */
trait MultiSiteTreeTrait
{
    /**
     * Method override @see LeftAndMain::getSearchFilter()
     *
     * @return mixed|LeftAndMain_SearchFilter
     * @throws \ReflectionException
     */
    protected function getSearchFilter()
    {
        $filter = parent::getSearchFilter();
        if ($filter === null) {
            // set default filter
            $params = $this->getRequest()->getVar('q');
            $params['FilterClass'] = CMSSiteTreeFilter_Search::class;

            $filterInfo = new ReflectionClass($params['FilterClass']);
            if (!$filterInfo->implementsInterface(LeftAndMain_SearchFilter::class)) {
                throw new InvalidArgumentException(sprintf('Invalid filter class passed: %s', $params['FilterClass']));
            }

            return $this->searchFilterCache = Injector::inst()->createWithArgs($params['FilterClass'], array($params));
        }

        return $filter;
    }

    /**
     * Method override @see CMSMain::getQueryFilter()
     *
     * @param array $params
     * @return CMSSiteTreeFilter
     */
    protected function getQueryFilter($params)
    {
        if (empty($params['FilterClass'])) {
            $params['FilterClass'] = CMSSiteTreeFilter_Search::class;
        }
        $filterClass = $params['FilterClass'];
        if (!is_subclass_of($filterClass, CMSSiteTreeFilter::class)) {
            throw new InvalidArgumentException("Invalid filter class passed: {$filterClass}");
        }
        return $filterClass::create($params);
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    final public function getFilteredClasses()
    {
        return Config::forClass($this->getFamilyController())->get('classes', Config::UNINHERITED);
    }

    final public function getFamilyControllerMenuTitle()
    {
        return Config::forClass($this->getFamilyController())->get('menu_title', Config::UNINHERITED);
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    final public function getFamilyURLSegment()
    {
        return Config::forClass(
            $this->getFamilyController()
        )->get('url_segment', Config::UNINHERITED);
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    final public function getFamilyController()
    {
        $familyController = $this->config()->get('family_controller', Config::UNINHERITED);
        $reflection = new ReflectionClass($familyController);
        if (
            $familyController &&
            (!$reflection->isSubclassOf(CMSPagesController::class) && $reflection->getName() !== CMSPagesController::class)
        ) {
            throw new InvalidArgumentException(sprintf('%s::family_controller needs to be a sub class of %s. See docs for more info.', $reflection->getName(), CMSPagesController::class));
        }
        return $familyController;
    }

    /**
     * Method override @see CMSMain::LinkTreeView()
     * @return mixed
     * @throws \ReflectionException
     */
    public function LinkTreeView()
    {
        $familyController = $this->getFamilyController();
        return $familyController::singleton()->Link();
    }

    /**
     * Method override @see CMSMain::LinkListView()
     * @return mixed
     * @throws \ReflectionException
     */
    public function LinkListView()
    {
        $familyController = $this->getFamilyController();
        return $this->LinkWithSearch($familyController::singleton()->Link('listview'));
    }

    /**
     * Method override @see CMSMain::LinkListViewChildren()
     */
    public function LinkListViewChildren($parentID)
    {
        $familyController = $this->getFamilyController();
        return sprintf(
            '%s?ParentID=%s',
            $familyController::singleton()->Link(),
            $parentID
        );
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function LinkPages()
    {
        $familyController = $this->getFamilyController();
        return $familyController::singleton()->Link();
    }

    /**
     * Method override @see CMSMain::LinkPageEdit()
     *
     * @param null $id
     * @return mixed
     * @throws \ReflectionException
     */
    public function LinkPageEdit($id = null)
    {
        if (!$id) {
            $id = $this->currentPageID();
        }
        $familyController = $this->getFamilyController();
        return $this->LinkWithSearch(
            Controller::join_links($familyController::singleton()->Link('edit/show'), $id)
        );
    }

    /**
     * Method override @see CMSMain::LinkPageSettings()
     * @return null
     * @throws \ReflectionException
     */
    public function LinkPageSettings()
    {
        if ($id = $this->currentPageID()) {
            $familyController = $this->getFamilyController();
            return $this->LinkWithSearch(
                Controller::join_links($familyController::singleton()->Link('settings/show'), $id)
            );
        } else {
            return null;
        }
    }

    /**
     * Method override @see CMSMain::LinkPageHistory()
     * @return null
     * @throws \ReflectionException
     */
    public function LinkPageHistory()
    {
        if ($id = $this->currentPageID()) {
            $familyController = $this->getFamilyController();
            return $this->LinkWithSearch(
                Controller::join_links($familyController::singleton()->Link('history/show'), $id)
            );
        } else {
            return null;
        }
    }

    /**
     * Method override @see CMSMain::LinkPageAdd()
     */
    public function LinkPageAdd($extra = null, $placeholders = null)
    {
        $familyController = $this->getFamilyController();
        $link = $familyController::singleton()->Link('add');

        if ($extra) {
            $link = Controller::join_links($link, $extra);
        }

        if ($placeholders) {
            $link .= (strpos($link, '?') === false ? "?$placeholders" : "&$placeholders");
        }

        return $link;
    }

    /**
     * Method override @see CMSMain::getSearchForm()
     * @return null
     * @throws \ReflectionException
     */
    public function getSearchForm()
    {
        $form = parent::getSearchForm();
        $familyController = $this->getFamilyController();

        $form->setFormAction($familyController::singleton()->Link());
        return $form;
    }

    /**
     * Method override @see CMSMain::getPageTypes()
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getPageTypes()
    {
        $pageTypes = array();
        $pageTypeClasses = $this->getFilteredClasses();
        foreach ($pageTypeClasses as $pageTypeClass) {
            $pageTypes[$pageTypeClass] = SiteTree::singleton($pageTypeClass)->i18n_singular_name();
        }
        asort($pageTypes);
        return $pageTypes;
    }

    /**
     * Method override @see CMSMain::PageTypes()
     *
     * @return ArrayList|\SilverStripe\ORM\SS_List
     * @throws \ReflectionException
     */
    public function PageTypes()
    {
        $result = new ArrayList();
        $classes = $this->getFilteredClasses();
        if (!empty($classes)) {

            foreach ($classes as $class) {
                $instance = SiteTree::singleton($class);
                if ($instance instanceof HiddenClass) {
                    continue;
                }

                $needPermissions = $instance->config()->get('need_permission');
                if ($needPermissions && !$this->can($needPermissions)) {
                    continue;
                }

                $result->push(new ArrayData(array(
                    'ClassName' => $class,
                    'AddAction' => $instance->i18n_singular_name(),
                    'Description' => $instance->i18n_classDescription(),
                    'IconURL' => $instance->getPageIconURL(),
                    'Title' => $instance->i18n_singular_name(),
                )));
            }

            $result = $result->sort('AddAction');
            return $result;
        }

        return parent::PageTypes();
    }

    /**
     * Method override @see CMSMain::getSearchFieldSchema()
     * @return mixed
     */
    public function getSearchFieldSchema()
    {
        $json = parent::getSearchFieldSchema();
        return str_replace('\"Pages\"', '\"Content\"', $json);
    }
}