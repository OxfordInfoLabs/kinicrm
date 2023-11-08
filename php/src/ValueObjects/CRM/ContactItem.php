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
     * @var UserSummaryItem
     */
    private $user;

    /**
     * @var integer[]
     */
    private $subscribedMailingLists;


    /**
     * @var TagItem[]
     */
    private $tags;


    /**
     * @var CategoryItem[]
     */
    private $categories;


    /**
     * @param string $name
     * @param string $emailAddress
     * @param string $telephone
     * @param string $photo
     * @param AddressItem $address
     * @param string $notes
     * @param AttachmentItem[] $attachments
     * @param OrganisationDepartmentItem[] $organisationDepartments
     * @param UserSummaryItem $contactUser
     * @param integer[] $subscribedMailingLists
     * @param CategoryItem[] $categories
     * @param TagItem[] $tags
     * @param int $id
     */
    public function __construct($name, $emailAddress, $telephone, $photo, $address, $notes, $attachments, $organisationDepartments, $contactUser, $subscribedMailingLists, $categories, $tags, $id = null) {
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
        $this->categories = $categories;
        $this->tags = $tags;
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
     * @return UserSummaryItem
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
     * @return TagItem[]
     */
    public function getTags(): ?array {
        return $this->tags;
    }

    /**
     * @return CategoryItem[]
     */
    public function getCategories(): ?array {
        return $this->categories;
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


        $tags = array_map(function ($tag) {
            return TagItem::fromItemTag($tag) ?? null;
        }, $contact->getTags() ?? []);


        $categories = array_map(function ($category) {
            return CategoryItem::fromItemCategory($category) ?? null;
        }, $contact->getCategories() ?? []);



        return new ContactItem($contact->getName(), $contact->getEmailAddress(), $contact->getTelephone(), $contact->getPhoto(), $contact->getAddress() ? AddressItem::fromAddress($contact->getAddress()) : null,
            $contact->getNotes(), $attachments, $organisationDepartments,
            $contact->getUserSummary() ? UserSummaryItem::fromUserSummary($contact->getUserSummary()) : null, $subscribedMailingLists,
            $categories, $tags,
            $contact->getId());
    }


}