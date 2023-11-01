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
     * @param OrganisationSummaryItem $organisationSummary
     * @param DepartmentItem $department
     */
    public function __construct($organisationSummary, $department) {
        $this->organisationSummary = $organisationSummary;
        $this->department = $department;
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
     * @param ContactOrganisationDepartment $contactOrganisationDepartment
     * @return OrganisationDepartmentItem
     */
    public static function fromContactOrganisationDepartment($contactOrganisationDepartment) {
        
        return new OrganisationDepartmentItem(
            $contactOrganisationDepartment->getOrganisation() ? OrganisationSummaryItem::fromOrganisationSummary($contactOrganisationDepartment->getOrganisation()) : null,
            $contactOrganisationDepartment->getDepartment() ? DepartmentItem::fromDepartment($contactOrganisationDepartment->getDepartment()) : null);
    }

}