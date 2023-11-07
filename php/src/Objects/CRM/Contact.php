<?php

namespace KiniCRM\Objects\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;
use Kiniauth\Objects\Security\UserSummary;
use KiniCRM\ValueObjects\CRM\ContactItem;
use KiniCRM\ValueObjects\CRM\OrganisationDepartmentItem;
use KiniCRM\ValueObjects\Enum\ObjectScope;
use Kinikit\Persistence\ORM\ActiveRecord;
use Kinimailer\Objects\MailingList\MailingListSubscriber;

/**
 * @table kcr_contact
 * @generate
 */
class Contact extends ActiveRecord {

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
     * @required
     */
    private $name;


    /**
     * @var string
     * @required
     */
    private $emailAddress;

    /**
     * @var string
     */
    private $telephone;

    /**
     * @var string
     * @sqlType LONGTEXT
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
     * @childJoinColumns parent_object_id,parent_object_type=CRMContact
     */
    private $attachments;


    /**
     * @var ContactOrganisationDepartment[]
     * @oneToMany
     * @childJoinColumns contact_id
     */
    private $organisationDepartments;


    /**
     * @var UserSummary
     * @manyToOne
     * @parentJoinColumns email_address=>email_address
     * @readOnly
     */
    private $userSummary;


    /**
     * @var MailingListSubscriber[]
     * @oneToMany
     * @childJoinColumns email_address=>email_address
     * @readOnly
     *
     */
    private $subscribedMailingLists;


    /**
     * @var ItemCategory[]
     * @oneToMany
     * @childJoinColumns item_id,item_type=Contact
     */
    private $categories;

    /**
     * @var ItemTag[]
     * @oneToMany
     * @childJoinColumns item_id,item_type=Contact
     */
    private $tags;


    /**
     * @param ContactItem $contact
     * @param integer $accountId
     */
    public function __construct($contact, $accountId = null) {
        if ($contact instanceof ContactItem) {
            $this->id = $contact->getId();
            $this->name = $contact->getName();
            $this->emailAddress = $contact->getEmailAddress();
            $this->telephone = $contact->getTelephone();
            $this->photo = $contact->getPhoto();
            $this->notes = $contact->getNotes();

            $this->address = $contact->getAddress() ? new Address($contact->getAddress(), $accountId) : null;

            $this->organisationDepartments = [];
            foreach ($contact->getOrganisationDepartments() ?? [] as $organisationDepartment) {
                if (($organisationDepartment instanceof OrganisationDepartmentItem) && $organisationDepartment->getOrganisationSummary())
                    $this->organisationDepartments[] = new ContactOrganisationDepartment($organisationDepartment);
            }

            $this->tags = array_map(function ($tagItem) use ($accountId) {
                return new ItemTag($tagItem, $accountId);
            }, $contact->getTags());

            $this->categories = array_map(function ($categoryItem) use ($accountId) {
                return new ItemCategory($categoryItem, $accountId);
            }, $contact->getCategories());


        }
        $this->accountId = $accountId;
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

    /**
     * @return UserSummary
     */
    public function getUserSummary() {
        return $this->userSummary;
    }

    /**
     * @return MailingListSubscriber[]
     */
    public function getSubscribedMailingLists() {
        return $this->subscribedMailingLists;
    }

    /**
     * @return ItemCategory[]
     */
    public function getCategories(): ?array {
        return $this->categories;
    }

    /**
     * @param ItemCategory[] $categories
     */
    public function setCategories(array $categories): void {
        $this->categories = $categories;
    }

    /**
     * @return ItemTag[]
     */
    public function getTags(): ?array {
        return $this->tags;
    }

    /**
     * @param ItemTag[] $tags
     */
    public function setTags(array $tags): void {
        $this->tags = $tags;
    }


}