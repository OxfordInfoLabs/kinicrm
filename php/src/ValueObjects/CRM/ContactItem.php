<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\Contact;

class ContactItem {

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
    private $emailAddress;


    /**
     * @var string
     */
    private $telephone;

    /**
     * @var string
     */
    private $photo;

    /**
     * @var AddressItem
     */
    private $address;


    /**
     * @var string
     */
    private $notes;


    /**
     * @var AttachmentItem[]
     */
    private $attachments;


    /**
     * @var OrganisationDepartmentItem[]
     */
    private $organisationDepartments;


    /**
     * @var ContactUserItem
     */
    private $user;

    /**
     * @var integer[]
     */
    private $subscribedMailingLists;


    /**
     * @param string $name
     * @param string $emailAddress
     * @param string $telephone
     * @param string $photo
     * @param AddressItem $address
     * @param string $notes
     * @param AttachmentItem[] $attachments
     * @param OrganisationDepartmentItem[] $organisationDepartments
     * @param ContactUserItem $contactUser
     * @param $subscribedMailingLists
     * @param int $id
     */
    public function __construct($name, $emailAddress, $telephone, $photo, $address, $notes, $attachments, $organisationDepartments, $contactUser, $subscribedMailingLists, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->emailAddress = $emailAddress;
        $this->telephone = $telephone;
        $this->photo = $photo;
        $this->address = $address;
        $this->notes = $notes;
        $this->attachments = $attachments;
        $this->organisationDepartments = $organisationDepartments;
        $this->user = $contactUser;

        $this->subscribedMailingLists = $subscribedMailingLists;
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
    public function getEmailAddress() {
        return $this->emailAddress;
    }

    /**
     * @return string
     */
    public function getTelephone() {
        return $this->telephone;
    }

    /**
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * @return AddressItem
     */
    public function getAddress() {
        return $this->address;
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
     * @return OrganisationDepartmentItem[]
     */
    public function getOrganisationDepartments() {
        return $this->organisationDepartments;
    }

    /**
     * @return ContactUserItem
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @return integer[]
     */
    public function getSubscribedMailingLists() {
        return $this->subscribedMailingLists;
    }


    /**
     * @param Contact $contact
     * @return ContactItem
     */
    public static function fromContact($contact) {

        $attachments = [];
        foreach ($contact->getAttachments() ?? [] as $attachment) {
            $attachments[] = AttachmentItem::fromAttachmentSummary($attachment);
        }

        $organisationDepartments = [];
        foreach ($contact->getOrganisationDepartments() as $department) {
            $organisationDepartments[] = OrganisationDepartmentItem::fromContactOrganisationDepartment($department);
        }

        $subscribedMailingLists = array_map(function ($mailingList) {
            return $mailingList->getMailingListId();
        }, $contact->getSubscribedMailingLists() ?? []);


        return new ContactItem($contact->getName(), $contact->getEmailAddress(), $contact->getTelephone(), $contact->getPhoto(), $contact->getAddress() ? new AddressItem($contact->getAddress()) : null,
            $contact->getNotes(), $attachments, $organisationDepartments,
            $contact->getUserSummary() ? ContactUserItem::fromUserSummary($contact->getUserSummary()) : null, $subscribedMailingLists,
            $contact->getId());
    }


}