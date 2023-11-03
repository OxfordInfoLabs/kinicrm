<?php

namespace KiniCRM\Controllers\Admin\CRM;


use KiniCRM\Services\CRM\CommentService;
use KiniCRM\ValueObjects\CRM\CommentItem;
use KiniCRM\ValueObjects\Enum\CommentScope;

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
     * @http POST /$scope/$scopeId
     *
     * @param string $scope
     * @param integer $scopeId
     * @param string $message
     *
     * @return CommentItem
     */
    public function createScopeComment($scope, $scopeId, $message) {
        $comment = $this->commentService->createComment(CommentScope::from($scope), $scopeId, $message);
        return CommentItem::fromComment($comment);
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
        $comments = $this->commentService->searchForComments(CommentScope::from($scope), $scopeId, $searchString, $limit, $offset);
        return array_map(function ($comment) {
            return CommentItem::fromComment($comment);
        }, $comments);
    }


}