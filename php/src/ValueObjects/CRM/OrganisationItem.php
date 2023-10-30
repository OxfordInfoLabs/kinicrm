<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\Organisation;

class OrganisationItem extends OrganisationSummaryItem {


    /**
     * @var AddressItem
     */
    private $address;


    /**
     * @var ContactItem
     */
    private $primaryContact;


    /**
     * @var DepartmentItem[]
     */
    private $departments;


    /**
     * @var string
     */
    private $notes;


    /**
     * @var AttachmentItem[]
     */
    private $attachments;

    /**
     * @param string $name
     * @param AddressItem $address
     * @param ContactItem $primaryContact
     * @param string $logo
     * @param DepartmentItem[] $departments
     * @param string $notes
     * @param AttachmentItem[] $attachments
     * @param int $id
     */
    public function __construct($name, $address, $primaryContact, $logo, $departments, $notes, $attachments, $id = null) {
        parent::__construct($name, $logo, $id);
        $this->address = $address;
        $this->primaryContact = $primaryContact;
        $this->departments = $departments;
        $this->notes = $notes;
        $this->attachments = $attachments;
    }

    /**
     * @return AddressItem
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @return ContactItem
     */
    public function getPrimaryContact() {
        return $this->primaryContact;
    }

    /**
     * @return DepartmentItem[]
     */
    public function getDepartments() {
        return $this->departments;
    }

    /**
     * @return string
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * @return AttachmentItem[]
     */
    public function getAttachments() {
        return $this->attachments;
    }

    /**
     * @param Organisation $organisation
     * @return OrganisationItem
     */
    public function fromOrganisation($organisation) {

        $departments = [];
        foreach ($organisation->getDepartments() ?? [] as $department) {
            $departments[] = DepartmentItem::fromDepartment($department);
        }

        $attachments = [];
        foreach ($organisation->getAttachments() ?? [] as $attachment) {
            $attachments[] = AttachmentItem::fromAttachmentSummary($attachment);
        }

        return new OrganisationItem($organisation->getName(), AddressItem::fromAddress($organisation->getAddress()),
            $organisation->getPrimaryContact() ? ContactItem::fromContact($organisation->getPrimaryContact()) : null,
            $organisation->getLogo(), $departments, $organisation->getNotes(), $attachments, $organisation->getId());

    }


}