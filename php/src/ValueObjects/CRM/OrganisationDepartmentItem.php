<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\ContactOrganisationDepartment;

class OrganisationDepartmentItem {

    /**
     * @var OrganisationSummaryItem
     */
    private $organisationSummary;

    /**
     * @var DepartmentItem
     */
    private $department;

    /**
     * @var string
     */
    private $jobTitle;

    /**
     * @param OrganisationSummaryItem $organisationSummary
     * @param DepartmentItem $department
     * @param string $jobTitle
     */
    public function __construct($organisationSummary, $department, $jobTitle) {
        $this->organisationSummary = $organisationSummary;
        $this->department = $department;
        $this->jobTitle = $jobTitle;
    }


    /**
     * @return OrganisationSummaryItem
     */
    public function getOrganisationSummary() {
        return $this->organisationSummary;
    }

    /**
     * @return DepartmentItem
     */
    public function getDepartment() {
        return $this->department;
    }

    /**
     * @return string
     */
    public function getJobTitle(): string {
        return $this->jobTitle;
    }


    /**
     * @param ContactOrganisationDepartment $contactOrganisationDepartment
     * @return OrganisationDepartmentItem
     */
    public static function fromContactOrganisationDepartment($contactOrganisationDepartment) {

        return new OrganisationDepartmentItem(
            $contactOrganisationDepartment->getOrganisation() ? OrganisationSummaryItem::fromOrganisationSummary($contactOrganisationDepartment->getOrganisation()) : null,
            $contactOrganisationDepartment->getDepartment() ? DepartmentItem::fromDepartment($contactOrganisationDepartment->getDepartment()) : null,
            $contactOrganisationDepartment->getJobTitle());
    }

}