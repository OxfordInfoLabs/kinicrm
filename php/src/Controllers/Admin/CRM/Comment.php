<?php

namespace KiniCRM\Controllers\Admin\CRM;


use KiniCRM\Services\CRM\CommentService;
use KiniCRM\ValueObjects\CRM\CommentItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\MVC\Request\FileUpload;

class Comment {

    /**
     * @var CommentService
     */
    private CommentService $commentService;

    /**
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;
    }


    /**
     * @http GET /$scope/$scopeId
     *
     * @param string $scope
     * @param int $scopeId
     * @param string $searchString
     * @param int $limit
     * @param int $offset
     *
     * @return CommentItem[]
     */
    public function searchForComments(string $scope, int $scopeId, string $searchString = "", int $limit = 10, int $offset = 0) {
        $comments = $this->commentService->searchForComments(ObjectScope::from($scope), $scopeId, $searchString, $limit, $offset);
        return array_map(function ($comment) {
            return CommentItem::fromComment($comment);
        }, $comments);
    }

    /**
     * @http POST /$scope/$scopeId
     *
     * @param string $scope
     * @param integer $scopeId
     * @param string $message
     *
     * @return CommentItem
     */
    public function createScopeComment($scope, $scopeId, $message) {
        $comment = $this->commentService->createComment(ObjectScope::from($scope), $scopeId, $message);
        return CommentItem::fromComment($comment);
    }

    /**
     * @http PUT /$id
     *
     * @param $id
     * @param $message
     *
     * @return CommentItem
     */
    public function updateComment($id, $message) {
        return CommentItem::fromComment($this->commentService->updateComment($id, $message));
    }


    /**
     * @http DELETE /
     *
     * @param $id
     * @return void
     */
    public function deleteComment($id) {
        $this->commentService->deleteComment($id);
    }


    /**
     * @http POST /attachments/$contactId
     *
     * @param integer $commentId
     * @param FileUpload[] $fileUploads
     *
     * @return void
     */
    public function uploadCommentAttachments($commentId, $fileUploads) {
        $this->commentService->attachUploadedFilesToComment($commentId, $fileUploads);
    }

    /**
     * @http DELETE /attachments/$commentId/$attachmentId
     *
     * @param integer $commentId
     * @param integer $attachmentId
     *
     * @return void
     */
    public function removeCommentAttachment($commentId, $attachmentId) {
        $this->commentService->removeAttachmentFromComment($commentId, $attachmentId);
    }


}