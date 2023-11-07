<?php

namespace KiniCRM\Objects\CRM;

use KiniCRM\ValueObjects\CRM\CategoryItem;

/**
 * @table kcr_category
 * @generate
 */
class Category extends MetaData {

    /**
     * @param CategoryItem $categoryItem
     * @param integer $accountId
     */
    public function __construct($categoryItem, $accountId = null) {
        parent::__construct($categoryItem?->getName(), $categoryItem?->getDescription(), $accountId, $categoryItem?->getId());
    }

}