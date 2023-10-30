<?php

namespace KiniCRM\Objects\CRM;


/**
 * @table kcr_organisation
 */
class OrganisationSummary {

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     */
    public function setAccountId($accountId) {
        $this->accountId = $accountId;
    }


    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}