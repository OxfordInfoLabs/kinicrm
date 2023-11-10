<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\ItemCategory;
use KiniCRM\Objects\CRM\ItemTag;

class CategoryItem {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @param int $id
     * @param string $name
     * @param string $description
     */
    public function __construct(string $name, ?string $description = null, ?int $id = null) {
        $this->name = $name;
        $this->description = $description;
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function getId(): ?int {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getDescription(): ?string {
        return $this->description;
    }


    /**
     * @param ItemCategory $itemCategory
     * @return CategoryItem
     */
    public static function fromItemCategory($itemCategory) {
        return new CategoryItem($itemCategory->getCategory()?->getName(),
            $itemCategory->getCategory()?->getDescription(), $itemCategory->getCategoryId());
    }


}
