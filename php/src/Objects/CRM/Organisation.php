<?php

namespace KiniCRM\Objects\CRM;


/**
 * @table kcr_organisation
 * @generate
 */
class Organisation {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Address
     * @manyToOne
     * @parentJoinColumns address_id
     */
    private $address;

    /**
     * @var Contact
     * @manyToOne
     * @parentJoinColumns primary_contact_id
     */
    private $primaryContact;


    /**
     * @var Department[]
     * @oneToMany
     * childJoinColumns organisation_id
     */
    private $departments;


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
     * @return Address
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address) {
        $this->address = $address;
    }

    /**
     * @return Contact
     */
    public function getPrimaryContact() {
        return $this->primaryContact;
    }

    /**
     * @param Contact $primaryContact
     */
    public function setPrimaryContact($primaryContact) {
        $this->primaryContact = $primaryContact;
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

    /**
     * @return Department[]
     */
    public function getDepartments() {
        return $this->departments;
    }

    /**
     * @param Department[] $departments
     */
    public function setDepartments($departments) {
        $this->departments = $departments;
    }




}