<?php

namespace KiniCRM\Objects\CRM;

use Kiniauth\Controllers\Attachment;

/**
 * @table kcr_contact
 * @generate
 */
class Contact {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $accountId;

    /**
     * @var string
     */
    private $name;


    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var string
     */
    private $telephone;


    /**
     * @var Attachment
     * @oneToOne
     * @childJoinColumns parent_object_id,parent_object_type=Contact
     */
    private $photo;

    /**
     * @var Address
     * @manyToOne
     * @parentJoinColumns address_id
     */
    private $address;


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
     * @return string
     */
    public function getEmailAddress() {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress) {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getTelephone() {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone($telephone) {
        $this->telephone = $telephone;
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