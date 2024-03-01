<?php

namespace KiniCRM;

use Kiniauth\Services\Workflow\Task\Task;
use KiniCRM\Services\CRM\Task\TaskNotificationTask;
use Kinikit\Core\ApplicationBootstrap;
use Kinikit\Core\DependencyInjection\Container;

class Bootstrap implements ApplicationBootstrap {

    public function setup() {
        Container::instance()->addInterfaceImplementation(Task::class, "task-notification", TaskNotificationTask::class);
    }
}
