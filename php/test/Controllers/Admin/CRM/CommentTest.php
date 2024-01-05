<?php

namespace KiniCRM\Controllers\Admin\CRM;

use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\Services\CRM\CommentService;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Core\DependencyInjection\Container;

include_once "autoloader.php";

class CommentTest extends TestBase {

    /**
     * @var Comment
     */
    private $comment;


    public function setUp(): void {
        $this->comment = Container::instance()->get(Comment::class);
    }

    public function testCanCreateUpdateQueryAndDeleteComments() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        // Create a new comment
        $newComment = $this->comment->createScopeComment("Contact", 1, "Hello world of fun and games");
        $this->assertNotNull($newComment->getId());

        $updated = $this->comment->updateComment($newComment->getId(), "Bingo Bango");
        $this->assertEquals($newComment->getId(), $updated->getId());

        // Search for comments
        $comments = $this->comment->searchForComments("Contact", 1);
        $this->assertEquals(1, sizeof($comments));
        $this->assertEquals("Bingo Bango", $comments[0]->getMessage());

        // Delete comment
        $this->comment->deleteComment($newComment->getId());

        $comments = $this->comment->searchForComments("Contact", 1);
        $this->assertEquals(0, sizeof($comments));


    }
}