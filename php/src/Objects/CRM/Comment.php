<?php

namespace KiniCRM\Objects\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;
use Kiniauth\Objects\Security\UserSummary;
use Kiniauth\Traits\Application\Timestamped;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Persistence\ORM\ActiveRecord;

;

/**
 * @table kcr_comment
 * @generate
 */
class Comment extends ActiveRecord {

    use Timestamped;

    /**
     * @var int
     */
    private ?int $id;

    /**
     * @var int
     */
    private ?int $accountId;


    /**
     * @var ObjectScope
     * @required
     */
    private ?ObjectScope $scope;


    /**
     * @var int
     * @required
     */
    private ?int $scopeId;



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
     * @var AttachmentSummary[]
     * @oneToMany
     * @readOnly
     * @childJoinColumns parent_object_id,parent_object_type=CRMComment
     */
    private ?array $attachments = [];

    /**
     * @param ObjectScope $scope
     * @param int $scopeId
     * @param UserSummary $userSummary
     * @param string $message
     * @param int $accountId
     * @param int $id
     */
    public function __construct(?ObjectScope $scope, ?int $scopeId, ?UserSummary $userSummary, ?string $message, ?int $accountId, ?int $id = null) {
        $this->scope = $scope;
        $this->scopeId = $scopeId;
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
     * @return ObjectScope
     */
    public function getScope(): ObjectScope {
        return $this->scope;
    }

    /**
     * @param ObjectScope $scope
     */
    public function setScope(ObjectScope $scope): void {
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

    /**
     * @return AttachmentSummary[]
     */
    public function getAttachments(): array {
        return $this->attachments;
    }

    /**
     * @param AttachmentSummary[] $attachments
     */
    public function setAttachments(array $attachments): void {
        $this->attachments = $attachments;
    }


}