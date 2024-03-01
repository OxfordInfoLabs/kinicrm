<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Attachment\Attachment;
use Kiniauth\Objects\Security\UserSummary;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\Objects\CRM\Comment;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\MVC\Request\FileUpload;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

include_once "autoloader.php";

class CommentServiceTest extends TestBase {

    private CommentService $commentService;

    public function setUp(): void {
        $this->commentService = Container::instance()->get(CommentService::class);
    }

    public function testCanCreateUpdateAndDeleteCommentsWithScopeForLoggedInUser() {

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
        $this->assertEquals((new \DateTime())->format("Y-m-d H:i:s"), $reComment->getCreatedDate()->format("Y-m-d H:i:s"));


        $this->commentService->updateComment($reComment->getId(), "Wondering about this one");

        $reReComment = Comment::fetch($comment->getId());
        $this->assertEquals("Wondering about this one", $reReComment->getMessage());
        $this->assertEquals($reComment->getCreatedDate(), $reReComment->getCreatedDate());

        $this->commentService->deleteComment($comment->getId());

        try {
            Comment::fetch($comment->getId());
            $this->fail("Should have thrown here");
        } catch (ObjectNotFoundException $e) {

        }


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

    public function testCanAttachUploadedFilesToCommentAndRemoveThem() {


        // Create a comment
        $comment = $this->commentService->createComment(ObjectScope::Contact, 5, "Hello world");

        $fileUpload1 = new FileUpload("test", ["name" => "test . txt", "tmp_name" => __DIR__ . "/test.txt"]);
        $fileUpload2 = new FileUpload("test", ["name" => "test2 . txt", "tmp_name" => __DIR__ . "/test2.txt"]);

        $this->commentService->attachUploadedFilesToComment($comment->getId(), [$fileUpload1, $fileUpload2]);

        $attachments = Attachment::filter("WHERE parent_object_type = ? and parent_object_id = ?", "CRMComment", $comment->getId());
        $this->assertEquals(2, sizeof($attachments));
        $this->assertEquals(file_get_contents(__DIR__ . "/test.txt"), $attachments[0]->getContent());
        $this->assertEquals(file_get_contents(__DIR__ . "/test2.txt"), $attachments[1]->getContent());

        $this->commentService->removeAttachmentFromComment($comment->getId(), $attachments[0]->getId());

        $attachments = Attachment::filter("WHERE parent_object_type = ? and parent_object_id = ?", "CRMComment", $comment->getId());
        $this->assertEquals(1, sizeof($attachments));
        $this->assertEquals(file_get_contents(__DIR__ . "/test2.txt"), $attachments[0]->getContent());

    }




}