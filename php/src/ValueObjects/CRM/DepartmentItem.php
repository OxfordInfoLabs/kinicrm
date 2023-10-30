<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\Department;

class DepartmentItem {

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
    private $notes;

    /**
     * @param string $name
     * @param string $notes
     * @param int $id
     */
    public function __construct($name, $notes, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->notes = $notes;
    }


    /**
     * @return int
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
     * @return string
     */
    public function getNotes() {
        return $this->notes;
    }


    /**
     * @param Department $department
     * @return DepartmentItem
     */
    public static function fromDepartment($department) {
        return new DepartmentItem($department->getName(), $department->getNotes(), $department->getId());
    }

}