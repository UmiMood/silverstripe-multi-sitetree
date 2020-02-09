<?php

namespace UmiMood\MultiSiteTree\Extensions;

use SilverStripe\Control\Controller;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\TreeDropdownField;

/**
 * Class SiteTreeExtension
 * @package UmiMood\MultiSiteTree\Extensions
 */
class SiteTreeExtension extends Extension
{
    private static $casting = array(
        'CustomTreeTitle' => 'HTMLFragment',
    );

    public function getCustomTreeTitle()
    {
        $children = $this->owner->creatableChildren();
        $flags = $this->owner->getStatusFlags();
        $treeTitle = sprintf(
            "<span class=\"jstree-pageicon page-icon class-%s\"></span><span class=\"item\" data-allowedchildren=\"%s\">%s</span>",
            Convert::raw2htmlid(static::class),
            Convert::raw2att(Convert::raw2json($children)),
            Convert::raw2xml(str_replace(array("\n","\r"), "", $this->owner->MenuTitle))
        );
        foreach ($flags as $class => $data) {
            if (is_string($data)) {
                $data = array('text' => $data);
            }
            $treeTitle .= sprintf(
                "<span class=\"badge %s\"%s>%s</span>",
                'status-' . Convert::raw2xml($class),
                (isset($data['title'])) ? sprintf(' title="%s"', Convert::raw2xml($data['title'])) : '',
                Convert::raw2xml($data['text'])
            );
        }

        return $treeTitle;
    }

    /**
     * @param FieldList $fields
     */
    public function updateSettingsFields(FieldList $fields)
    {
        $parentField = $fields->dataFieldByName('ParentID');
        if ($parentField && $parentField instanceof TreeDropdownField) {
            $classes = Controller::curr()->getFilteredClasses();
            $parentField->setFilterFunction(function ($node) use ($classes) {
                return (in_array($node->getClassName(), $classes));
            });
        }
    }
}
