<?php

namespace KiniCRM\Services\CRM\Task;

use Kiniauth\Objects\Account\Account;
use Kiniauth\Objects\Security\UserSummary;
use Kiniauth\Objects\Workflow\ObjectWorkflowStep;
use Kiniauth\Services\Application\Session;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\Objects\CRM\Comment;
use KiniCRM\Objects\CRM\Task;
use KiniCRM\Services\CRM\CommentService;
use KiniCRM\Services\CRM\TaskService;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\CommentItem;
use KiniCRM\ValueObjects\CRM\ReferenceTypeItem;
use KiniCRM\ValueObjects\CRM\TaskItem;
use KiniCRM\ValueObjects\CRM\UserSummaryItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\Exception\InsufficientParametersException;
use Kinikit\Core\Testing\MockObjectProvider;
use Kinimailer\Objects\Template\TemplateParameter;
use Kinimailer\Services\Mailing\MailingService;
use Kinimailer\ValueObjects\Mailing\AdhocMailing;
use PHPUnit\Framework\MockObject\MockObject;

include_once "autoloader.php";

class TaskNotificationTaskTest extends TestBase {


    /**
     * @var TaskNotificationTask
     */
    private $task;

    /**
     * @var MockObject
     */
    private $taskService;


    /**
     * @var MockObject
     */
    private $commentService;

    /**
     * @var MockObject
     */
    private $mailingService;


    public function setUp(): void {
        $this->taskService = MockObjectProvider::instance()->getMockInstance(TaskService::class);
        $this->commentService = MockObjectProvider::instance()->getMockInstance(CommentService::class);
        $this->mailingService = MockObjectProvider::instance()->getMockInstance(MailingService::class);
        $this->task = new TaskNotificationTask($this->taskService, $this->commentService, $this->mailingService, Container::instance()->get(Session::class));
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testExceptionRaisedIfMissingMailingIdInTaskParams() {

        $workflowStep = new ObjectWorkflowStep(Account::class, "test", "Test Step", "manual", "test", [

        ]);

        try {
            $this->task->runWorkflowStep($workflowStep, 1);
            $this->fail("Should have thrown here");
        } catch (InsufficientParametersException $e) {
        }

    }


    public function testAdhocMailingSentCorrectlyToMailingServiceWithTaskMatchingPkIfStatusNotCompleted() {


        AuthenticationHelper::login("admin@kinicart.com", "password");

        $workflowStep = new ObjectWorkflowStep(Account::class, "test", "Test Step", "manual", "test", [
            "mailingId" => 25
        ]);


        $task = new Task(new TaskItem("Example Task", "Example Task", "2020-01-01", new ReferenceTypeItem("Pending", "", 1), null, new UserSummaryItem(1, "Google", "google@test.com", UserSummary::STATUS_ACTIVE), [
            new UserSummaryItem(2, "Bob Jones", "bob.jones@test.com", UserSummary::STATUS_ACTIVE),
            new UserSummaryItem(3, "Mary Smith", "mary.smith@test.com", UserSummary::STATUS_ACTIVE)
        ]));

        $this->taskService->returnValue("getTask", $task, [30]);

        $comments = [
            new Comment(ObjectScope::Task, 30, new UserSummary("Bob Jones", UserSummary::STATUS_ACTIVE, "bob.jones@test.com",null,null, 23), "This is an interesting comment", 12,33),
            new Comment(ObjectScope::Task, 30, new UserSummary("Bob Jones", UserSummary::STATUS_ACTIVE, "bob.jones@test.com",null,null, 23), "Another comment", 12,32),
        ];

        $this->commentService->returnValue("searchForComments", $comments, [ObjectScope::Task, 30]);

        // Run workflow step
        $this->task->runWorkflowStep($workflowStep, 30);

        $loggedInUser = Container::instance()->get(Session::class)->__getLoggedInSecurable();



        $bobMailing = new AdhocMailing(25, "Bob Jones", "bob.jones@test.com", [],
            [
                new TemplateParameter("task", "Task", TemplateParameter::TYPE_TEXT, TaskItem::fromTask($task)),
                new TemplateParameter("loggedInUser", "Logged in User", TemplateParameter::TYPE_TEXT, $loggedInUser),
                new TemplateParameter("taskComments", "Task Comments", TemplateParameter::TYPE_TEXT, [CommentItem::fromComment($comments[0]), CommentItem::fromComment($comments[1])]),
                new TemplateParameter("latestTaskComment", "Latest Task Comment", TemplateParameter::TYPE_TEXT, CommentItem::fromComment($comments[0]))
            ]);
        $this->assertTrue($this->mailingService->methodWasCalled("processAdhocMailing", [$bobMailing]));

        $maryMailing = new AdhocMailing(25, "Mary Smith", "mary.smith@test.com", [],
            [
                new TemplateParameter("task", "Task", TemplateParameter::TYPE_TEXT, TaskItem::fromTask($task)),
                new TemplateParameter("loggedInUser", "Logged in User", TemplateParameter::TYPE_TEXT, $loggedInUser),
                new TemplateParameter("taskComments", "Task Comments", TemplateParameter::TYPE_TEXT, [CommentItem::fromComment($comments[0]), CommentItem::fromComment($comments[1])]),
                new TemplateParameter("latestTaskComment", "Latest Task Comment", TemplateParameter::TYPE_TEXT, CommentItem::fromComment($comments[0]))
            ]);
        $this->assertTrue($this->mailingService->methodWasCalled("processAdhocMailing", [$maryMailing]));

    }


    public function testNoMailingSentForTaskMatchingPkIfStatusIsAlreadyCompleted() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        $workflowStep = new ObjectWorkflowStep(Account::class, "test", "Test Step", "manual", "test", [
            "mailingId" => 25
        ]);


        $task = new Task(new TaskItem("Example Task", "Example Task", "2020-01-01", new ReferenceTypeItem("Completed", "", 3), null, new UserSummaryItem(1, "Google", "google@test.com", UserSummary::STATUS_ACTIVE), [
            new UserSummaryItem(2, "Bob Jones", "bob.jones@test.com", UserSummary::STATUS_ACTIVE),
            new UserSummaryItem(3, "Mary Smith", "mary.smith@test.com", UserSummary::STATUS_ACTIVE)
        ]));

        $this->taskService->returnValue("getTask", $task, [30]);


        // Run workflow step
        $this->task->runWorkflowStep($workflowStep, 30);

        $this->assertFalse($this->mailingService->methodWasCalled("processAdhocMailing"));

    }


}