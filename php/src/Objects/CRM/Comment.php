<?php

namespace KiniCRM\Objects\CRM;

use Kiniauth\Objects\Security\UserSummary;
use KiniCRM\ValueObjects\Enum\CommentScope;
use Kinikit\Persistence\ORM\ActiveRecord;

;

/**
 * @table kcr_comment
 * @generate
 */
class Comment extends ActiveRecord {

    /**
     * @var int
     */
    private ?int $id;

    /**
     * @var int
     */
    private ?int $accountId;


    /**
     * @var CommentScope
     * @required
     */
    private ?CommentScope $scope;


    /**
     * @var int
     * @required
     */
    private ?int $scopeId;


    /**
     * @var \DateTime
     * @required
     */
    private ?\DateTime $dateTime;


    /**
     * @var UserSummary
     * @manyToOne
     * @parentJoinColumns user_id
     * @required
     */
    private ?UserSummary $userSummary;

    /**
     * @var string
     * @sqlType TEXT
     * @required
     */
    private ?string $message;

    /**
     * @param CommentScope $scope
     * @param int $scopeId
     * @param \DateTime $dateTime
     * @param UserSummary $userSummary
     * @param string $message
     * @param int $accountId
     * @param int $id
     */
    public function __construct(?CommentScope $scope, ?int $scopeId, ?\DateTime $dateTime, ?UserSummary $userSummary, ?string $message, ?int $accountId, ?int $id = null) {
        $this->scope = $scope;
        $this->scopeId = $scopeId;
        $this->dateTime = $dateTime;
        $this->userSummary = $userSummary;
        $this->message = $message;
        $this->accountId = $accountId;
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getAccountId(): ?int {
        return $this->accountId;
    }


    /**
     * @return CommentScope
     */
    public function getScope(): CommentScope {
        return $this->scope;
    }

    /**
     * @param CommentScope $scope
     */
    public function setScope(CommentScope $scope): void {
        $this->scope = $scope;
    }

    /**
     * @return int
     */
    public function getScopeId(): int {
        return $this->scopeId;
    }

    /**
     * @param int $scopeId
     */
    public function setScopeId(int $scopeId): void {
        $this->scopeId = $scopeId;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setDateTime(\DateTime $dateTime): void {
        $this->dateTime = $dateTime;
    }

    /**
     * @return UserSummary
     */
    public function getUserSummary(): UserSummary {
        return $this->userSummary;
    }

    /**
     * @param UserSummary $userSummary
     */
    public function setUserSummary(UserSummary $userSummary): void {
        $this->userSummary = $userSummary;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void {
        $this->message = $message;
    }


}