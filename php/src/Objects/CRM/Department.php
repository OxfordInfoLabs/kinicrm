<?php

namespace KiniCRM\Objects\CRM;

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
     */
    private $name;

    /**
     * @var string
     * @sqlType LONGTEXT
     */
    private $notes;

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