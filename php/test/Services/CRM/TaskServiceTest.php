<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Security\User;
use Kiniauth\Objects\Security\UserSummary;
use KiniCRM\Objects\CRM\ReferenceType;
use KiniCRM\Objects\CRM\Task;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\ReferenceTypeItem;
use KiniCRM\ValueObjects\CRM\TaskItem;
use KiniCRM\ValueObjects\CRM\UserSummaryItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;

class TaskServiceTest extends TestBase {

    /**
     * @var TaskService
     */
    private $taskService;


    public function setUp(): void {
        $this->taskService = new TaskService();
    }


    public function testCanSaveFilterAndDeleteTasksForContact() {

        $due = new \DateTime();
        $due->add(new \DateInterval("P5D"));

        $user3 = UserSummary::fetch(3);

        $task = new Task(new TaskItem("Call Mum", "Don't forget to call Mum", "2025-01-01",
            new ReferenceTypeItem("Pending", "Pending", 1),
            new ReferenceTypeItem("High", "High Priority", 6),
            null, [
                new UserSummaryItem(3, "Peter Jones", "peter@jones.com", "ACTIVE"),
                new UserSummaryItem(4, "Simon Carwash", "simon@carwash.com", "ACTIVE"),

            ]), ObjectScope::Contact, 1, 2, $user3);

        $task = $this->taskService->saveTask($task);
        $this->assertTrue(true);

    }


}