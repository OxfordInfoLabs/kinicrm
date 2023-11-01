<?php

namespace KiniCRM\Objects\CRM;

use KiniCRM\ValueObjects\CRM\DepartmentItem;

/**
 * @table kcr_department
 * @generate
 */
class Department {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $organisationId;


    /**
     * @var string
     * @required
     */
    private $name;

    /**
     * @var string
     * @sqlType LONGTEXT
     */
    private $notes;


    /**
     * @param DepartmentItem $departmentItem
     */
    public function __construct($department) {
        if ($department instanceof DepartmentItem) {
            $this->id = $department->getId();
            $this->name = $department->getName();
            $this->notes = $department->getNotes();
        }
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
    public function getOrganisationId() {
        return $this->organisationId;
    }

    /**
     * @param int $organisationId
     */
    public function setOrganisationId($organisationId) {
        $this->organisationId = $organisationId;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
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
    public function getNotes() {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes) {
        $this->notes = $notes;
    }


}