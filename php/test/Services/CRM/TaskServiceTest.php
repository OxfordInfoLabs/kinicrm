<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Security\UserSummary;
use KiniCRM\Objects\CRM\ReferenceType;
use KiniCRM\Objects\CRM\Task;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\ReferenceTypeItem;
use KiniCRM\ValueObjects\CRM\TaskItem;
use KiniCRM\ValueObjects\CRM\UserSummaryItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;
use Kinikit\Persistence\ORM\Query\SummarisedValue;

class TaskServiceTest extends TestBase {

    /**
     * @var TaskService
     */
    private $taskService;


    public function setUp(): void {
        $this->taskService = new TaskService();
    }


    public function testCanSaveFilterAndDeleteTasksForContact() {


        $user3 = UserSummary::fetch(3);
        $user4 = UserSummary::fetch(4);

        $task = new Task(new TaskItem("Call Mum", "Don't forget to call Mum", "2025-01-01",
            new ReferenceTypeItem("Pending", "Pending", 1),
            new ReferenceTypeItem("High", "High Priority", 6),
            null, [
                new UserSummaryItem(3, "Peter Jones", "peter@jones.com", "ACTIVE"),
                new UserSummaryItem(4, "Simon Carwash", "simon@carwash.com", "ACTIVE"),

            ]), ObjectScope::Contact, 1, 2, $user3);

        $task = $this->taskService->saveTask($task,2, 1);

        $this->assertEquals("Call Mum", $task->getTitle());
        $this->assertEquals("Don't forget to call Mum", $task->getDescription());

        $this->assertEquals(ReferenceType::fetch(1), $task->getStatus());
        $this->assertEquals(ReferenceType::fetch(6), $task->getPriority());

        $this->assertEquals([$user3, $user4], $task->getAssignees());
        $this->assertEquals($user3, $task->getCreator());
        $this->assertEquals(2, $task->getAccountId());
        $this->assertEquals(ObjectScope::Contact, $task->getScope());
        $this->assertEquals(1, $task->getScopeId());
        $this->assertEquals(date("Y-m-d"), $task->getCreatedDate()->format("Y-m-d"));
        $this->assertEquals(date("Y-m-d"), $task->getLastModifiedDate()->format("Y-m-d"));
        $this->assertEquals("2025-01-01", $task->getDueDate()->format("Y-m-d"));


        $task->setTitle("Call mum later");
        $task->setDescription("Revised now");
        $task->setDueDate(date_create_from_format("Y-m-d", "2026-01-01"));
        $task->setStatus(ReferenceType::fetch(2));
        $task->setPriority(ReferenceType::fetch(5));
        $task->setAssignees([$user4]);

        $task1 = $this->taskService->saveTask($task,0, 1);
        $this->assertEquals("Call mum later", $task1->getTitle());
        $this->assertEquals("Revised now", $task1->getDescription());
        $this->assertEquals(ReferenceType::fetch(2), $task1->getStatus());
        $this->assertEquals(ReferenceType::fetch(5), $task1->getPriority());
        $this->assertEquals([$user4], $task1->getAssignees());

        $task2 = new Task(new TaskItem("Ring Doctor", "Ring the doctor", "2024-01-01",
            new ReferenceTypeItem("Completed", "Completed", 3),
            new ReferenceTypeItem("Medium", "Medium Priority", 5),
            null, [
                new UserSummaryItem(3, "Peter Jones", "peter@jones.com", "ACTIVE"),

            ]), ObjectScope::Contact, 1, 2, $user3);

        $task2 = $this->taskService->saveTask($task2, 0, 1);

        $task3 = new Task(new TaskItem("Call Dad", "Don't forget to call Dad", "2023-01-01",
            new ReferenceTypeItem("Pending", "Pending", 1),
            new ReferenceTypeItem("Low", "Low Priority", 4),
            null, [
                new UserSummaryItem(4, "Simon Carwash", "simon@carwash.com", "ACTIVE"),

            ]), ObjectScope::Contact, 1, 2, $user3);

        $task3 = $this->taskService->saveTask($task3, 0, 1);


        // Test filtering
        $filtered = $this->taskService->filterTasks(["search" => "Call"]);
        $this->assertEquals([$task3, $task1], $filtered);

        $filtered = $this->taskService->filterTasks(["search" => "Simon"]);
        $this->assertEquals([$task2], $filtered);

        $filtered = $this->taskService->filterTasks(["status" => "Pending"]);
        $this->assertEquals([$task3], $filtered);

        $filtered = $this->taskService->filterTasks(["priority" => "Low"]);
        $this->assertEquals([$task3], $filtered);

        $this->assertEquals($task3, $this->taskService->getTask($task3->getId()));

        $this->taskService->removeTask($task3->getId());

        try {
            $this->taskService->getTask($task3->getId());
            $this->fail("Should have thrown here");
        } catch (ObjectNotFoundException $e) {
        }


    }


    public function testCanGetFilterValuesForFilter() {

        $this->assertEquals([new SummarisedValue("Medium", 2)], $this->taskService->getTaskFilterValues("priority"));
        $this->assertEquals([new SummarisedValue("Completed", 1), new SummarisedValue("In Progress", 1)], $this->taskService->getTaskFilterValues("status"));

    }




}