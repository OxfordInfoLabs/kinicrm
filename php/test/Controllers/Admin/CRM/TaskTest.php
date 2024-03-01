<?php

namespace KiniCRM\Controllers\Admin\CRM;

use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\ReferenceTypeItem;
use KiniCRM\ValueObjects\CRM\TaskItem;
use KiniCRM\ValueObjects\CRM\UserSummaryItem;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

class TaskTest extends TestBase {

    /**
     * @var Task
     */
    private $task;

    public function setUp(): void {
        $this->task = Container::instance()->get(Task::class);
    }

    public function testTaskController() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        $taskItem = $this->task->saveTask("Contact", 1, new TaskItem("Hello world", "Test Task 2", "2011-01-01", new ReferenceTypeItem("Pending", "Pending", 1),
            new ReferenceTypeItem("Low", "Low Priority", 4), null, [
                new UserSummaryItem(3, "Simon", "simon@test.com", "ACTIVE"),
                new UserSummaryItem(4, "Pete", "pete@test.com", "ACTIVE"),
            ]));

        $search = $this->task->searchTasks([], 1, 0);
        $this->assertNotNull($search[0]->getId());

        // Remove
        $this->task->removeTask($taskItem->getId());

        try {
            $this->task->getTask($taskItem->getId());
            $this->fail("Should have thrown");
        } catch (ObjectNotFoundException $e) {
            // Success
        }

    }


}