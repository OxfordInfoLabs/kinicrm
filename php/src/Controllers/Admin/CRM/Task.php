<?php

namespace KiniCRM\Controllers\Admin\CRM;

use KiniCRM\Services\CRM\TaskService;
use KiniCRM\ValueObjects\CRM\TaskItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Persistence\ORM\Query\SummarisedValue;

class Task {

    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;
    }


    /**
     * Get a task by id
     *
     * @http GET /$id
     *
     * @param $id
     * @return TaskItem
     */
    public function getTask($id) {
        return TaskItem::fromTask($this->taskService->getTask($id));
    }

    /**
     * @http POST /search
     *
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     *
     * @return TaskItem[]
     */
    public function searchTasks($filters = [], $limit = 10, $offset = 0) {
        $tasks = $this->taskService->filterTasks($filters, $limit, $offset);
        return array_map(function ($task) {
            return TaskItem::fromTask($task);
        }, $tasks);
    }


    /**
     * @http POST /filterValues/$memberName
     *
     * @param string $memberName
     * @param array $filters
     *
     * @return SummarisedValue[]
     */
    public function getTaskFilterValues($memberName, $filters) {
        return $this->taskService->getTaskFilterValues($memberName, $filters);
    }


    /**
     * Save a task
     *
     * @http POST /$scope/$scopeId
     *
     * @param TaskItem $taskItem
     * @return TaskItem
     */
    public function saveTask($scope, $scopeId, $taskItem) {
        return TaskItem::fromTask($this->taskService->saveTask(new \KiniCRM\Objects\CRM\Task($taskItem, ObjectScope::from($scope), $scopeId, 0)));
    }


    /**
     * Update a task
     *
     * @http PUT /$id
     *
     * @param integer $id
     * @param TaskItem $taskItem
     * @return TaskItem
     */
    public function updateTask($id, $taskItem) {
        $task = $this->taskService->getTask($id);
        return TaskItem::fromTask($this->taskService->saveTask(new \KiniCRM\Objects\CRM\Task($taskItem, $task->getScope(), $task->getScopeId(), 0)));
    }

    /**
     * Remove a task by id
     *
     * @http DELETE /
     *
     * @param $taskId
     */
    public function removeTask($taskId) {
        $this->taskService->removeTask($taskId);
    }

}
