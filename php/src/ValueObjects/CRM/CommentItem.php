<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\Comment;

class CommentItem {

    /**
     * @var string
     */
    private string $dateTime;

    /**
     * @var UserSummaryItem
     */
    private UserSummaryItem $user;


    /**
     * @var string
     */
    private string $message;


    /**
     * @param string $dateTime
     * @param UserSummaryItem $user
     * @param string $message
     */
    public function __construct(string $dateTime, UserSummaryItem $user, string $message) {
        $this->dateTime = $dateTime;
        $this->user = $user;
        $this->message = $message;
    }


    /**
     * @return string
     */
    public function getDateTime(): string {
        return $this->dateTime;
    }

    /**
     * @return UserSummaryItem
     */
    public function getUser(): UserSummaryItem {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }


    /**
     * @param Comment $comment
     * @return CommentItem
     */
    public static function fromComment($comment) {
        return new CommentItem($comment->getDateTime() ? $comment->getDateTime()->format("Y-m-d H:i:s") : null,
            $comment->getUserSummary() ? UserSummaryItem::fromUserSummary($comment->getUserSummary()) : null,
            $comment->getMessage());
    }

}