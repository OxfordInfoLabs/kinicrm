<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\OrganisationSummary;

class OrganisationSummaryItem {

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
    private $logo;

    /**
     * @var DepartmentItem[]
     */
    private $departments;


    /**
     * @param string $name
     * @param string $logo
     * @param DepartmentItem[] $departments
     * @param int $id
     */
    public function __construct($name, $logo, $departments, $id = null) {
        $this->name = $name;
        $this->logo = $logo;
        $this->departments = $departments;
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return DepartmentItem[]
     */
    public function getDepartments() {
        return $this->departments;
    }


    /**
     * @param OrganisationSummary $organisationSummary
     * @return OrganisationSummaryItem
     */
    public static function fromOrganisationSummary($organisationSummary) {
        $departments = array_map(function ($department) {
            return DepartmentItem::fromDepartment($department);
        }, $organisationSummary->getDepartments() ?? []);
        return new OrganisationSummaryItem($organisationSummary->getName(), $organisationSummary->getLogo(), $departments, $organisationSummary->getId());
    }
}