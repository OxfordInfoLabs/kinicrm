<?php

namespace KiniCRM\Services\CRM;

use KiniCRM\Objects\CRM\Category;
use KiniCRM\Objects\CRM\Tag;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\CategoryItem;
use KiniCRM\ValueObjects\CRM\TagItem;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

include_once "autoloader.php";

class MetaDataServiceTest extends TestBase {

    /**
     * @var MetaDataService
     */
    private $metaDataService;


    public function setUp(): void {
        $this->metaDataService = Container::instance()->get(MetaDataService::class);
    }

    public function testCanSearchAndDeleteTags() {

        $tag1 = new Tag(new TagItem("Apple Tag First"));
        $tag2 = new Tag(new TagItem("Apple Tag Second"));
        $tag3 = new Tag(new TagItem("Apple Tag Third"));
        $tag4 = new Tag(new TagItem("Apple Tag Fourth"));
        $tag5 = new Tag(new TagItem("Apple Tag Fifth"));

        $tag1->save();
        $tag2->save();
        $tag3->save();
        $tag4->save();
        $tag5->save();

        $this->assertEquals([$tag5, $tag1, $tag4, $tag2, $tag3], $this->metaDataService->searchForTags("", 5));
        $this->assertEquals([$tag4, $tag2, $tag3], $this->metaDataService->searchForTags("", 3, 2));
        $this->assertEquals([$tag5, $tag1, $tag4], $this->metaDataService->searchForTags("F", 3));

        $this->metaDataService->deleteTag($tag1->getId());

        try {
            Tag::fetch($tag1->getId());
            $this->fail("Should have thrown here");
        } catch (ObjectNotFoundException $e){
            // Good
        }


    }


    public function testCanSearchAndDeleteCategories() {

        $category1 = new Category(new CategoryItem("Apple Category First"));
        $category2 = new Category(new CategoryItem("Apple Category Second"));
        $category3 = new Category(new CategoryItem("Apple Category Third"));
        $category4 = new Category(new CategoryItem("Apple Category Fourth"));
        $category5 = new Category(new CategoryItem("Apple Category Fifth"));

        $category1->save();
        $category2->save();
        $category3->save();
        $category4->save();
        $category5->save();

        $this->assertEquals([$category5, $category1, $category4, $category2, $category3], $this->metaDataService->searchForCategories("", 5));
        $this->assertEquals([$category4, $category2, $category3], $this->metaDataService->searchForCategories("", 3, 2));
        $this->assertEquals([$category5, $category1, $category4], $this->metaDataService->searchForCategories("F", 3));

        $this->metaDataService->deleteCategory($category1->getId());

        try {
            Category::fetch($category1->getId());
            $this->fail("Should have thrown here");
        } catch (ObjectNotFoundException $e){
            // Good
        }


    }

}