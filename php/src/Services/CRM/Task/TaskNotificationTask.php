<?php

namespace KiniCRM\Services\CRM\Task;

use Kiniauth\Services\Application\Session;
use Kiniauth\Services\Workflow\Task\ObjectWorkflowStepTask;
use KiniCRM\Services\CRM\CommentService;
use KiniCRM\Services\CRM\TaskService;
use KiniCRM\ValueObjects\CRM\CommentItem;
use KiniCRM\ValueObjects\CRM\TaskItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Core\Exception\InsufficientParametersException;
use Kinimailer\Objects\Template\TemplateParameter;
use Kinimailer\Services\Mailing\MailingService;
use Kinimailer\ValueObjects\Mailing\AdhocMailing;

class TaskNotificationTask extends ObjectWorkflowStepTask {

    /**
     * @var TaskService
     */
    private TaskService $taskService;

    /**
     * @var CommentService
     */
    private CommentService $commentService;


    /**
     * @var MailingService
     */
    private MailingService $mailingService;


    /**
     * @var Session
     */
    private Session $session;


    /**
     * @param TaskService $taskService
     * @param CommentService $commentService
     * @param MailingService $mailingService
     * @param Session $session
     */
    public function __construct(TaskService $taskService, CommentService $commentService, MailingService $mailingService, Session $session) {
        $this->taskService = $taskService;
        $this->commentService = $commentService;
        $this->mailingService = $mailingService;
        $this->session = $session;
    }


    /**
     * Run the task for a given workflow step
     *
     * @param $workflowStep
     * @param $objectPk
     * @return void
     */
    public function runWorkflowStep($workflowStep, $objectPk) {

        $mailingId = $workflowStep->getTaskConfiguration()["mailingId"] ?? null;

        if (!$mailingId) {
            throw new InsufficientParametersException("You must supply a mailing id parameter as task configuration to task notification workflow steps");
        }

        // Get the task
        $task = $this->taskService->getTask($objectPk);

        // Proceed provided the task is not completed
        if ($task->getStatus()->getName() !== "Completed") {

            // Grab task comments
            $taskComments = array_map(function ($comment) {
                return CommentItem::fromComment($comment);
            }, $this->commentService->searchForComments(ObjectScope::Task, $objectPk));


            foreach ($task->getAssignees() as $assignee) {
                $adhocMailing = new AdhocMailing($mailingId, $assignee->getName(), $assignee->getEmailAddress(), [],
                    [
                        new TemplateParameter("task", "Task", TemplateParameter::TYPE_TEXT, TaskItem::fromTask($task)),
                        new TemplateParameter("loggedInUser", "Logged in User", TemplateParameter::TYPE_TEXT, $this->session->__getLoggedInSecurable()),
                        new TemplateParameter("taskComments", "Task Comments", TemplateParameter::TYPE_TEXT, $taskComments),
                        new TemplateParameter("latestTaskComment", "Latest Task Comment", TemplateParameter::TYPE_TEXT, $taskComments[0] ?? null)
                    ]);

                $this->mailingService->processAdhocMailing($adhocMailing);
            }
        }

    }
}