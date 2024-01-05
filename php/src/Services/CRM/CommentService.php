<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Account\Account;
use Kiniauth\Objects\Attachment\AttachmentSummary;
use Kiniauth\Objects\Security\User;
use Kiniauth\Objects\Security\UserSummary;
use Kiniauth\Services\Attachment\AttachmentService;
use KiniCRM\Objects\CRM\Comment;
use KiniCRM\Objects\CRM\Contact;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Core\Stream\File\ReadOnlyFileStream;
use Kinikit\MVC\Request\FileUpload;
use Kinikit\Persistence\ORM\Query\Query;

class CommentService {

    /**
     * @var AttachmentService
     */
    private $attachmentService;


    /**
     * @param AttachmentService $attachmentService
     */
    public function __construct($attachmentService) {
        $this->attachmentService = $attachmentService;
    }

    /**
     * Create a comment
     *
     * @param ObjectScope $scope
     * @param int $scopeId
     * @param string $message
     * @param mixed $userId
     * @param mixed $accountId
     *
     * @return Comment
     */
    public function createComment(ObjectScope $scope, int $scopeId, string $message, $userId = User::LOGGED_IN_USER, $accountId = Account::LOGGED_IN_ACCOUNT) {

        // Fetch the user summary for passed id.
        $userSummary = UserSummary::fetch($userId);

        // Create and save the comment
        $comment = new Comment($scope, $scopeId, $userSummary, $message, $accountId);
        $comment->save();

        // Return comment
        return $comment;
    }


    /**
     * @param int $id
     * @param string $message
     *
     * @return Comment
     */
    public function updateComment($id, $message) {
        $comment = Comment::fetch($id);
        $comment->setMessage($message);
        $comment->save();

        return $comment;
    }

    /**
     * Delete a comment by id
     *
     * @param $id
     * @return void
     */
    public function deleteComment($id) {
        $comment = Comment::fetch($id);
        $comment->remove();
    }

    /**
     * Search for comments
     *
     * @param ObjectScope $scope
     * @param integer $scopeId
     * @param string $searchString
     * @param integer $limit
     * @param integer $offset
     *
     * @return Comment[]
     */
    public function searchForComments(ObjectScope $scope, int $scopeId, string $searchString = "", int $limit = 10, int $offset = 0) {


        $query = new Query(Comment::class);
        return $query->query([
            "scope" => $scope->name,
            "scopeId" => $scopeId,
            "message" => "%" . $searchString . "%"
        ], "id DESC", $limit, $offset);


    }


    /**
     * Upload attachments to a comment
     *
     * @param integer $commentId
     * @param FileUpload[] $uploadedFiles
     *
     * @return void
     */
    public function attachUploadedFilesToComment($commentId, $uploadedFiles) {

        // Check access to contact first
        $comment = Comment::fetch($commentId);

        foreach ($uploadedFiles as $fileUpload) {
            $attachmentSummary = new AttachmentSummary($fileUpload->getClientFilename(), $fileUpload->getMimeType(),
                "CRMComment", $commentId, null, null, $comment->getAccountId());
            $this->attachmentService->saveAttachment($attachmentSummary, new ReadOnlyFileStream($fileUpload->getTemporaryFilePath()));
        }
    }


    /**
     * Remove an attachment from a comment
     *
     * @param integer $contactId
     * @param integer $attachmentId
     * @return void
     */
    public function removeAttachmentFromComment($commentId, $attachmentId) {

        // Check access to comment first
        Comment::fetch($commentId);

        // Remove attachment
        $this->attachmentService->removeAttachment($attachmentId);
    }


}