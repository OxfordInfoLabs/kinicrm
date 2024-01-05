<?php

namespace KiniCRM\Objects\CRM;

use Kiniauth\Objects\Security\UserSummary;
use Kiniauth\Traits\Application\Timestamped;
use KiniCRM\ValueObjects\CRM\TaskItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * @table kcr_task
 * @generate
 */
class Task extends ActiveRecord {

    use Timestamped;

    /**
     * @var integer
     */
    private ?int $id;


    /**
     * @var int
     */
    private ?int $accountId;


    /**
     * @var string
     */
    private ?string $title;


    /**
     * @var string
     * @sqlType TEXT
     */
    private ?string $description;


    /**
     * @var \DateTime
     * @sqlType DATE
     */
    private \DateTime $dueDate;


    /**
     * @var ReferenceType
     * @manyToOne
     * @parentJoinColumns status_reference_type_id
     */
    private $status;


    /**
     * @var ReferenceType
     * @manyToOne
     * @parentJoinColumns priority_reference_type_id
     */
    private $priority;


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
     * @parentJoinColumns creator_user_id
     * @required
     */
    private ?UserSummary $creator = null;


    /**
     * @var UserSummary[]
     * @manyToMany
     * @linkTable kcr_task_assignee
     */
    private ?array $assignees = [];


    public function __construct($taskItem, ?ObjectScope $scope = null, ?int $scopeId = null, ?int $accountId = null, ?UserSummary $creator = null) {
        if ($taskItem instanceof TaskItem) {
            $this->id = $taskItem->getId();
            $this->title = $taskItem->getTitle();
            $this->description = $taskItem->getDescription();
            $this->dueDate = $taskItem->getDueDate() ? date_create_from_format("Y-m-d", $taskItem->getDueDate()) : null;
            if ($taskItem->getStatus()) {
                $this->status = new ReferenceType("Status", $taskItem->getStatus()->getName(), $taskItem->getStatus()->getDescription(), $accountId, $taskItem->getStatus()->getId());
            }
            if ($taskItem->getPriority()) {
                $this->priority = new ReferenceType("Priority", $taskItem->getPriority()->getName(), $taskItem->getPriority()->getDescription(), $accountId, $taskItem->getPriority()->getId());
            }
            if ($taskItem->getAssignees()) {
                foreach ($taskItem->getAssignees() as $assignee) {
                    $this->assignees[] = new UserSummary($assignee->getName(),$assignee->getStatus(),$assignee->getEmailAddress(),0, [], $assignee->getId());
                }
            }
        }
        if (!$this->creator) {
            $this->creator = $creator;
        }
        $this->accountId = $accountId;
        $this->scope = $scope;
        $this->scopeId = $scopeId;

    }


    /**
     * @return int|null
     *
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
     * @param int|null $accountId
     */
    public function setAccountId(?int $accountId): void {
        $this->accountId = $accountId;
    }


    /**
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getDueDate(): \DateTime {
        return $this->dueDate;
    }

    /**
     * @param \DateTime $dueDate
     */
    public function setDueDate(\DateTime $dueDate): void {
        $this->dueDate = $dueDate;
    }

    /**
     * @return ReferenceType
     */
    public function getStatus(): ?ReferenceType {
        return $this->status;
    }

    /**
     * @param ReferenceType $status
     */
    public function setStatus(?ReferenceType $status): void {
        $this->status = $status;
    }

    /**
     * @return ReferenceType
     */
    public function getPriority(): ?ReferenceType {
        return $this->priority;
    }

    /**
     * @param ReferenceType $priority
     */
    public function setPriority(?ReferenceType $priority): void {
        $this->priority = $priority;
    }

    /**
     * @return ObjectScope|null
     */
    public function getScope(): ?ObjectScope {
        return $this->scope;
    }

    /**
     * @param ObjectScope|null $scope
     */
    public function setScope(?ObjectScope $scope): void {
        $this->scope = $scope;
    }

    /**
     * @return int|null
     */
    public function getScopeId(): ?int {
        return $this->scopeId;
    }

    /**
     * @param int|null $scopeId
     */
    public function setScopeId(?int $scopeId): void {
        $this->scopeId = $scopeId;
    }

    /**
     * @return UserSummary|null
     */
    public function getCreator(): ?UserSummary {
        return $this->creator;
    }

    /**
     * @param UserSummary|null $creator
     */
    public function setCreator(?UserSummary $creator): void {
        $this->creator = $creator;
    }

    /**
     * @return array|null
     */
    public function getAssignees(): ?array {
        return $this->assignees;
    }

    /**
     * @param array|null $assignees
     */
    public function setAssignees(?array $assignees): void {
        $this->assignees = $assignees;
    }


}