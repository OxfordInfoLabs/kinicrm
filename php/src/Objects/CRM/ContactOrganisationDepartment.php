<?php

namespace KiniCRM\Objects\CRM;

use KiniCRM\ValueObjects\CRM\OrganisationDepartmentItem;

/**
 * @table kcr_contact_organisation_department
 * @generate
 */
class ContactOrganisationDepartment {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $contactId;


    /**
     * @var OrganisationSummary
     * @manyToOne
     * @parentJoinColumns organisation_id
     */
    private $organisation;


    /**
     * @var Department
     * @manyToOne
     * @parentJoinColumns department_id
     */
    private $department;


    /**
     * @var string
     */
    private $jobTitle;


    /**
     * @param OrganisationDepartmentItem $organisationDepartment
     */
    public function __construct($organisationDepartment) {
        if ($organisationDepartment instanceof OrganisationDepartmentItem) {
            $this->organisation = $organisationDepartment->getOrganisationSummary() ? new OrganisationSummary($organisationDepartment->getOrganisationSummary()) : null;
            $this->department = $organisationDepartment->getDepartment() ? new Department($organisationDepartment->getDepartment()) : null;
            $this->jobTitle = $organisationDepartment->getJobTitle();
        }
    }


    /**
     * @return int
     */
    public function getContactId() {
        return $this->contactId;
    }

    /**
     * @param int $contactId
     */
    public function setContactId($contactId) {
        $this->contactId = $contactId;
    }

    /**
     * @return OrganisationSummary
     */
    public function getOrganisation() {
        return $this->organisation;
    }

    /**
     * @param OrganisationSummary $organisation
     */
    public function setOrganisation($organisation) {
        $this->organisation = $organisation;
    }

    /**
     * @return Department
     */
    public function getDepartment() {
        return $this->department;
    }

    /**
     * @param Department $department
     */
    public function setDepartment($department) {
        $this->department = $department;
    }

    /**
     * @return string
     */
    public function getJobTitle() {
        return $this->jobTitle;
    }

    /**
     * @param string $jobTitle
     */
    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
    }


}