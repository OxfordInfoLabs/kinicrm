<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\Task;

class TaskItem {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $dueDate;


    /**
     * @var ReferenceTypeItem
     */
    private $status;


    /**
     * @var ReferenceTypeItem
     */
    private $priority;

    /**
     * @var UserSummaryItem
     */
    private ?UserSummaryItem $createdBy;

    /**
     * @var UserSummaryItem[]
     */
    private array $assignees;

    /**
     * @param string $title
     * @param string $description
     * @param ?string $dueDate
     * @param ?ReferenceTypeItem $status
     * @param ?ReferenceTypeItem $priority
     * @param ?UserSummaryItem $createdBy
     * @param ?UserSummaryItem[] $assignees
     * @param ?int $id
     */
    public function __construct(string $title, string $description, ?string $dueDate = null, ?ReferenceTypeItem $status = null, ?ReferenceTypeItem $priority = null, ?UserSummaryItem $createdBy = null, ?array $assignees = null, ?int $id = null) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->status = $status;
        $this->priority = $priority;
        $this->createdBy = $createdBy;
        $this->assignees = $assignees;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getDueDate(): ?string {
        return $this->dueDate;
    }

    /**
     * @param string|null $dueDate
     */
    public function setDueDate(?string $dueDate): void {
        $this->dueDate = $dueDate;
    }

    /**
     * @return ReferenceTypeItem|null
     */
    public function getStatus(): ?ReferenceTypeItem {
        return $this->status;
    }

    /**
     * @param ReferenceTypeItem|null $status
     */
    public function setStatus(?ReferenceTypeItem $status): void {
        $this->status = $status;
    }

    /**
     * @return ReferenceTypeItem|null
     */
    public function getPriority(): ?ReferenceTypeItem {
        return $this->priority;
    }

    /**
     * @param ReferenceTypeItem|null $priority
     */
    public function setPriority(?ReferenceTypeItem $priority): void {
        $this->priority = $priority;
    }

    /**
     * @return UserSummaryItem
     */
    public function getCreatedBy(): UserSummaryItem {
        return $this->createdBy;
    }

    /**
     * @param UserSummaryItem $createdBy
     */
    public function setCreatedBy(UserSummaryItem $createdBy): void {
        $this->createdBy = $createdBy;
    }

    /**
     * @return array
     */
    public function getAssignees(): array {
        return $this->assignees;
    }

    /**
     * @param array $assignees
     */
    public function setAssignees(array $assignees): void {
        $this->assignees = $assignees;
    }


    /**
     * @param Task $task
     * @return TaskItem
     */
    public static function fromTask($task) {

        $assignees = array_map(function ($userSummary) {
            return $userSummary ?: UserSummaryItem::fromUserSummary($userSummary);
        }, $task->getAssignees());

        return new TaskItem($task->getTitle(), $task->getDescription(),
            $task->getDueDate()?->format("Y-m-d"),
            $task->getStatus() ? ReferenceTypeItem::fromReferenceType($task->getStatus()) : null,
            $task->getPriority() ? ReferenceTypeItem::fromReferenceType($task->getPriority()) : null,
            $task->getCreator() ? UserSummaryItem::fromUserSummary($task->getCreator()) : null,
            $assignees, $task->getId());
    }


}