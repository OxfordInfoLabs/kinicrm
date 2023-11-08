<?php

namespace KiniCRM\Controllers\Admin\CRM;

use KiniCRM\Services\CRM\MetaDataService;
use KiniCRM\ValueObjects\CRM\CategoryItem;
use KiniCRM\ValueObjects\CRM\TagItem;


class MetaData {

    /**
     * @var MetaDataService
     */
    private $metaDataService;

    /**
     * @param MetaDataService $metaDataService
     */
    public function __construct($metaDataService) {
        $this->metaDataService = $metaDataService;
    }


    /**
     * @http GET /tag
     *
     * Search for tags
     *
     * @param string $searchString
     * @param integer $limit
     * @param integer $offset
     *
     * @return TagItem[]
     */
    public function searchForTags($searchString = "", $limit = 10, $offset = 0) {
        $results = $this->metaDataService->searchForTags($searchString, $limit, $offset);
        return array_map(function ($item) {
            return new TagItem($item->getName(), $item->getDescription(), $item->getId());
        }, $results);
    }


    /**
     * @http DELETE /tag
     *
     * Delete a tag by id
     *
     * @param $tagId
     */
    public function deleteTag($tagId) {
        $this->metaDataService->deleteTag($tagId);
    }


    /**
     * @http GET /category
     *
     * Search for categories
     *
     * @param string $searchString
     * @param integer $limit
     * @param integer $offset
     *
     * @return CategoryItem[]
     */
    public function searchForCategories($searchString = "", $limit = 10, $offset = 0) {
        $results = $this->metaDataService->searchForCategories($searchString, $limit, $offset);
        return array_map(function ($item) {
            return new CategoryItem($item->getName(), $item->getDescription(), $item->getId());
        }, $results);
    }


    /**
     * @http DELETE /category
     *
     * Delete a category by id
     *
     * @param $categoryId
     */
    public function deleteCategory($categoryId) {
        $this->metaDataService->deleteCategory($categoryId);
    }

}