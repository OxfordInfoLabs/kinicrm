<?php

namespace KiniCRM\Objects\CRM;

use KiniCRM\ValueObjects\CRM\TagItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;

/**
 * @table kcr_item_tag
 * @generate
 */
class ItemTag {

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
    private $tagId;


    /**
     * @var Tag
     * @manyToOne
     * @parentJoinColumns tag_id
     * @saveCascade
     */
    private $tag;


    /**
     * @param TagItem $tag
     * @param ObjectScope|null $itemType
     */
    public function __construct(?TagItem $tag, $accountId = null) {
        if ($tag instanceof TagItem) {
            $this->tag = new Tag($tag, $accountId);
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
    public function getTagId(): ?int {
        return $this->tagId;
    }

    /**
     * @param int $tagId
     */
    public function setTagId(int $tagId): void {
        $this->tagId = $tagId;
    }

    /**
     * @return Tag
     */
    public function getTag(): Tag {
        return $this->tag;
    }


}