<?php

namespace KiniCRM\Objects\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;

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
     * @var string
     * @sqlType TEXT
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
     * @var AttachmentSummary[]
     * @oneToMany
     * @readOnly
     * @childJoinColumns parent_object_id,parent_object_type=Contact
     */
    private $attachments;


    /**
     * @var ContactOrganisationDepartment[]
     * @oneToMany
     * @childJoinColumns contact_id
     */
    private $organisationDepartments;


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
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    /**
     * @return AttachmentSummary[]
     */
    public function getAttachments() {
        return $this->attachments;
    }


    /**
     * @return ContactOrganisationDepartment[]
     */
    public function getOrganisationDepartments() {
        return $this->organisationDepartments;
    }

    /**
     * @param ContactOrganisationDepartment[] $organisationDepartments
     */
    public function setOrganisationDepartments($organisationDepartments) {
        $this->organisationDepartments = $organisationDepartments;
    }


}