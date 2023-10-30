<?php

namespace KiniCRM\Objects\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;

/**
 * @table kcr_organisation
 * @generate
 */
class Organisation extends OrganisationSummary {

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
     * @var string
     * @sqlType TEXT
     */
    private $logo;

    /**
     * @var Department[]
     * @oneToMany
     * @childJoinColumns organisation_id
     */
    private $departments;


    /**
     * @var string
     * @sqlType LONGTEXT
     */
    private $notes;


    /**
     * @var AttachmentSummary[]
     * @oneToMany
     * @readOnly
     * @childJoinColumns parent_object_id,parent_object_type=Organisation
     */
    private $attachments;


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

    /**
     * @return string
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo) {
        $this->logo = $logo;
    }

    /**
     * @return AttachmentSummary[]
     */
    public function getAttachments() {
        return $this->attachments;
    }

    /**
     * @param AttachmentSummary[] $attachments
     */
    public function setAttachments($attachments) {
        $this->attachments = $attachments;
    }


}