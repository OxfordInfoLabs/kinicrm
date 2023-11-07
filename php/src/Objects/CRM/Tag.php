<?php

namespace KiniCRM\Objects\CRM;

use KiniCRM\ValueObjects\CRM\TagItem;

/**
 * @table kcr_tag
 * @generate
 */
class Tag extends MetaData {

    /**
     * @param TagItem $tagItem
     * @param integer $accountId
     */
    public function __construct($tagItem, $accountId = null) {
        parent::__construct($tagItem?->getName(), $tagItem?->getDescription(), $accountId, $tagItem?->getId());
    }

}