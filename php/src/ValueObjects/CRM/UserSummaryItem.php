<?php

namespace KiniCRM\ValueObjects\CRM;

use Kiniauth\Objects\Security\UserSummary;

class UserSummaryItem {

    /**
     * @var integer
     */
    private $id;


    /**
     * @var string
     */
    private $name;


    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var string
     */
    private $status;


    /**
     * @param int $id
     * @param string $name
     * @param string $emailAddress
     * @param string $status
     * @param string $createdDate
     */
    public function __construct(int $id, string $name, string $emailAddress, string $status) {
        $this->id = $id;
        $this->name = $name;
        $this->emailAddress = $emailAddress;
        $this->status = $status;
    }


    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string {
        return $this->emailAddress;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }


    /**
     * Generate a contact user item from a user summary
     *
     * @param UserSummary $getUserSummary
     * @return UserSummaryItem
     */
    public static function fromUserSummary(UserSummary $userSummary) {
        return new UserSummaryItem($userSummary->getId(), $userSummary->getName(), $userSummary->getEmailAddress(), $userSummary->getStatus());
    }


}