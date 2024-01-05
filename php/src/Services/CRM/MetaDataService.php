<?php

namespace KiniCRM\Services\CRM;


use KiniCRM\Objects\CRM\Category;
use KiniCRM\Objects\CRM\ReferenceType;
use KiniCRM\Objects\CRM\Tag;
use Kinikit\Persistence\ORM\Query\Filter\LikeFilter;
use Kinikit\Persistence\ORM\Query\Query;

class MetaDataService {


    /**
     * Search for tags
     *
     * @param string $searchString
     * @param integer $limit
     * @param integer $offset
     *
     * @return Tag[]
     */
    public function searchForTags($searchString = "", $limit = 10, $offset = 0) {

        $filters = [];
        if (isset($searchString)) {
            $filters["search"] = new LikeFilter(["name", "description"], "%" . $searchString . "%");
        }

        $query = new Query(Tag::class);
        return $query->query($filters, ["name"], $limit, $offset);
    }


    /**
     * Delete a tag by id
     *
     * @param $tagId
     */
    public function deleteTag($tagId) {
        $tag = Tag::fetch($tagId);
        $tag->remove();
    }


    /**
     * Search for categories
     *
     * @param string $searchString
     * @param integer $limit
     * @param integer $offset
     *
     * @return Category[]
     */
    public function searchForCategories($searchString = "", $limit = 10, $offset = 0) {

        $filters = [];
        if (isset($searchString)) {
            $filters["search"] = new LikeFilter(["name", "description"], "%" . $searchString . "%");
        }

        $query = new Query(Category::class);
        return $query->query($filters, ["name"], $limit, $offset);
    }


    /**
     * Delete a category by id
     *
     * @param $categoryId
     */
    public function deleteCategory($categoryId) {
        $category = Category::fetch($categoryId);
        $category->remove();
    }


    /**
     * Return all reference types for a given type.
     *
     * @param $type
     * @return ReferenceType[]
     */
    public function getReferenceTypes($type): array {
        return ReferenceType::filter("WHERE type = ?", $type);
    }

}