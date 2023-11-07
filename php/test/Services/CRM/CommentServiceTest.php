<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Security\UserSummary;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\Objects\CRM\Comment;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Core\DependencyInjection\Container;

include_once "autoloader.php";

class CommentServiceTest extends TestBase {

    private CommentService $commentService;

    public function setUp(): void {
        $this->commentService = Container::instance()->get(CommentService::class);
    }

    public function testCanCreateCommentsWithScopeForLoggedInUser() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        // Create a comment
        $comment = $this->commentService->createComment(ObjectScope::Contact, 5, "Hello world");

        $this->assertNotNull($comment->getId());

        $reComment = Comment::fetch($comment->getId());

        $this->assertEquals(ObjectScope::Contact, $reComment->getScope());
        $this->assertEquals(5, $reComment->getScopeId());
        $this->assertEquals("Hello world", $reComment->getMessage());
        $this->assertEquals(UserSummary::fetch(1), $reComment->getUserSummary());
        $this->assertEquals(0, $reComment->getAccountId());
        $this->assertEquals((new \DateTime())->format("Y-m-d H:i:s"), $reComment->getDateTime()->format("Y-m-d H:i:s"));


    }


    public function testCanGetFilteredCommentsForScopeAndId() {

        $comment1 = $this->commentService->createComment(ObjectScope::Contact, 1, "Smiling");
        $comment2 = $this->commentService->createComment(ObjectScope::Contact, 1, "Swimming");
        $comment3 = $this->commentService->createComment(ObjectScope::Contact, 2, "Shopping");
        $comment4 = $this->commentService->createComment(ObjectScope::Organisation, 1, "Fighting");
        $comment5 = $this->commentService->createComment(ObjectScope::Organisation, 1, "Laughing");

        $this->assertEquals([$comment2, $comment1], $this->commentService->searchForComments(ObjectScope::Contact, 1));
        $this->assertEquals([$comment3], $this->commentService->searchForComments(ObjectScope::Contact, 2));
        $this->assertEquals([$comment5, $comment4], $this->commentService->searchForComments(ObjectScope::Organisation, 1));


        $this->assertEquals([$comment1], $this->commentService->searchForComments(ObjectScope::Contact, 1, "sm"));
        $this->assertEquals([$comment2], $this->commentService->searchForComments(ObjectScope::Contact, 1, "", 1));
        $this->assertEquals([$comment1], $this->commentService->searchForComments(ObjectScope::Contact, 1, "", 100, 1));

    }

}