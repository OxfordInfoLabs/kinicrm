<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Account\Account;
use Kiniauth\Objects\Security\User;
use Kiniauth\Objects\Security\UserSummary;
use KiniCRM\Objects\CRM\Task;
use Kinikit\Core\Util\ArrayUtils;
use Kinikit\Persistence\ORM\Query\Filter\LikeFilter;
use Kinikit\Persistence\ORM\Query\Query;
use Kinikit\Persistence\ORM\Query\SummarisedValue;

class TaskService {


    const FILTER_MAP = [
        "priority" => "priority.name",
        "status" => "status.name",
        "assignee" => "assignees.name",
        "assigneeEmail" => "assignees.email_address",
        "search" => "search",
        "scope" => "scope",
        "scopeId" => "scopeId"
    ];


    /**
     * @param $id
     *
     * @return Task
     */
    public function getTask($id) {
        return Task::fetch($id);
    }

    /**
     * Filter tasks using passed search string
     *
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return Task[]
     */
    public function filterTasks($filters = [], $limit = 10, $offset = 0) {

        $query = new Query(Task::class);

        // Process filters
        $filters = $this->processQueryFilters($filters);

        return $query->query($filters, "dueDate", $limit, $offset);


    }


    /**
     * Get task filter values
     *
     * @param string $member
     * @param array $filters
     *
     * @return SummarisedValue[]
     */
    public function getTaskFilterValues($member, $filters = []) {

        // Create query
        $query = new Query(Task::class);

        unset($filters[$member]);

        // Process filters
        $filters = $this->processQueryFilters($filters);

        // Return summarised values
        return $query->summariseByMember(self::FILTER_MAP[$member], $filters);

    }


    /**
     * Save a task
     *
     * @param Task $task
     * @return Task
     */
    public function saveTask($task, $accountId = Account::LOGGED_IN_ACCOUNT, $userId = User::LOGGED_IN_USER) {

        // Update account id and creator if necessary
        $task->setAccountId($accountId);
        if (!$task->getCreator()) {
            $task->setCreator($userSummary = UserSummary::fetch($userId));
        }

        $task->save();
        return Task::fetch($task->getId());
    }


    /**
     * Remove a task
     *
     * @param $taskId
     */
    public function removeTask($taskId) {

        /**
         * @var Task $task
         */
        $task = Task::fetch($taskId);
        $task->remove();

    }



    /**
     * @param array $filters
     * @return array
     */
    private function processQueryFilters(array $filters): array {
        $filters = ArrayUtils::mapArrayKeys($filters, self::FILTER_MAP);

        if (isset($filters["search"])) {
            $filters["search"] = new LikeFilter(["title", "description", "assignees.name", "assignees.email_address"], "%" . $filters["search"] . "%");
        }
        return $filters;
    }


}
