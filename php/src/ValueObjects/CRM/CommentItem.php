<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\Comment;

class CommentItem {

    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $createdDate;

    /**
     * @var string
     */
    private string $lastModifiedDate;

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
    public function __construct(string $createdDate, string $lastModifiedDate, UserSummaryItem $user, string $message, ?int $id) {
        $this->createdDate = $createdDate;
        $this->lastModifiedDate = $lastModifiedDate;
        $this->user = $user;
        $this->message = $message;
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getCreatedDate(): string {
        return $this->createdDate;
    }

    /**
     * @return string
     */
    public function getLastModifiedDate(): string {
        return $this->lastModifiedDate;
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
        return new CommentItem($comment->getCreatedDate() ? $comment->getCreatedDate()->format("Y-m-d H:i:s") : null,
            $comment->getLastModifiedDate() ? $comment->getLastModifiedDate()->format("Y-m-d H:i:s") : null,
            $comment->getUserSummary() ? UserSummaryItem::fromUserSummary($comment->getUserSummary()) : null,
            $comment->getMessage(), $comment->getId());
    }

}