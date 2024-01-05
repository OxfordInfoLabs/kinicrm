<?php

namespace KiniCRM\Objects\CRM;

use KiniCRM\ValueObjects\CRM\CategoryItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;

/**
 * @table kcr_item_category
 * @generate
 */
class ItemCategory {

    /**
     * @var ObjectScope
     * @primaryKey
     */
    private ?ObjectScope $itemType = ObjectScope::Organisation;

    /**
     * @var integer
     * @primaryKey
     */
    private ?int $itemId = null;


    /**
     * @var integer
     * @primaryKey
     */
    private $categoryId;

    /**
     * @var Category
     * @manyToOne
     * @parentJoinColumns category_id
     * @saveCascade
     */
    private $category;


    /**
     * @param CategoryItem $category
     * @param ObjectScope|null $itemType
     */
    public function __construct(?CategoryItem $category,  $accountId = null) {
        if ($category instanceof CategoryItem) {
            $this->category = new Category($category, $accountId);
        }
    }


    /**
     * @return ObjectScope|null
     */
    public function getItemType(): ?ObjectScope {
        return $this->itemType;
    }

    /**
     * @param ObjectScope|null $itemType
     */
    public function setItemType(?ObjectScope $itemType): void {
        $this->itemType = $itemType;
    }

    /**
     * @return int|null
     */
    public function getItemId(): ?int {
        return $this->itemId;
    }

    /**
     * @param int|null $itemId
     */
    public function setItemId(?int $itemId): void {
        $this->itemId = $itemId;
    }

    /**
     * @return int
     */
    public function getCategoryId():?int {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void {
        $this->categoryId = $categoryId;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category {
        return $this->category;
    }


}