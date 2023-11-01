<?php

namespace KiniCRM\Objects\CRM;


use KiniCRM\ValueObjects\CRM\OrganisationSummaryItem;
use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * @table kcr_organisation
 */
class OrganisationSummary extends ActiveRecord {

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
     * @required
     */
    protected $name;

    /**
     * @var string
     * @sqlType LONGTEXT
     */
    protected $logo;

    /**
     * @var Department[]
     * @oneToMany
     * @childJoinColumns organisation_id
     * @readOnly
     */
    private $departmentSummaries;


    /**
     * Construct with required stuff
     *
     * @param OrganisationSummaryItem $organisationItem
     * @param $accountId
     */
    public function __construct($organisationItem, $accountId = null) {
        if ($organisationItem instanceof OrganisationSummaryItem) {
            $this->id = $organisationItem->getId();
            $this->name = $organisationItem->getName();
            $this->logo = $organisationItem->getLogo();

            // Map all departments
            $this->departmentSummaries = [];
            foreach ($organisationItem->getDepartments() ?? [] as $department) {
                $this->departmentSummaries[] = new Department($department);
            }
        }
        $this->accountId = $accountId;
    }

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

    /**
     * @param string $logo
     */
    public function setLogo($logo) {
        $this->logo = $logo;
    }

    /**
     * @return string
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * @return Department[]
     */
    public function getDepartments() {
        return $this->departmentSummaries;
    }

    /**
     * @param Department[] $departments
     */
    public function setDepartments($departments) {
        $this->departmentSummaries = $departments;
    }


}