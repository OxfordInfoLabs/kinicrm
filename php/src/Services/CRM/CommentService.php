<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Account\Account;
use Kiniauth\Objects\Security\User;
use Kiniauth\Objects\Security\UserSummary;
use KiniCRM\Objects\CRM\Comment;
use KiniCRM\Objects\CRM\CommentScope;

class CommentService {

    /**
     * Create a comment
     *
     * @param CommentScope $scope
     * @param int $scopeId
     * @param string $message
     * @param mixed $userId
     * @param mixed $accountId
     *
     * @return Comment
     */
    public function createComment(CommentScope $scope, int $scopeId, string $message, $userId = User::LOGGED_IN_USER, $accountId = Account::LOGGED_IN_ACCOUNT) {

        // Fetch the user summary for passed id.
        $userSummary = UserSummary::fetch($userId);

        // Create and save the comment
        $comment = new Comment($scope, $scopeId, new \DateTime(), $userSummary, $message, $accountId);
        $comment->save();

        // Return comment
        return $comment;
    }


    /**
     * Search for comments
     *
     * @param CommentScope $scope
     * @param integer $scopeId
     * @param string $searchString
     * @param integer $limit
     * @param integer $offset
     *
     * @return Comment[]
     */
    public function searchForComments(CommentScope $scope, int $scopeId, string $searchString = "", int $limit = 10, int $offset = 0) {

        $query = "WHERE scope = ? AND scope_id = ? AND message LIKE ? ORDER BY id DESC";
        $params = [$scope->name, $scopeId, "%" . $searchString . "%"];

        if ($limit) {
            $query .= " LIMIT ?";
            $params[] = $limit;
        }

        if ($offset) {
            $query .= " OFFSET ?";
            $params[] = $offset;
        }

        return Comment::filter($query, $params);

    }


}