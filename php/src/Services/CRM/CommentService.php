<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Account\Account;
use Kiniauth\Objects\Security\User;
use Kiniauth\Objects\Security\UserSummary;
use KiniCRM\Objects\CRM\Comment;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Persistence\ORM\Query\Query;

class CommentService {

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
        $comment = new Comment($scope, $scopeId, new \DateTime(), $userSummary, $message, $accountId);
        $comment->save();

        // Return comment
        return $comment;
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


}