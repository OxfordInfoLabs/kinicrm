<?php

namespace KiniCRM\Objects\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;
use Kiniauth\Traits\Application\Timestamped;
use KiniCRM\ValueObjects\CRM\OrganisationItem;

/**
 * @table kcr_organisation
 * @generate
 */
class Organisation extends OrganisationSummary {

    use Timestamped;

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
     * @sqlType LONGTEXT
     */
    private $notes;


    /**
     * @var AttachmentSummary[]
     * @oneToMany
     * @readOnly
     * @childJoinColumns parent_object_id,parent_object_type=CRMOrganisation
     */
    private $attachments;


    /**
     * @var Department[]
     * @oneToMany
     * @childJoinColumns organisation_id
     */
    protected $departments;


    /**
     * @var ItemCategory[]
     * @oneToMany
     * @childJoinColumns item_id,item_type=Organisation
     */
    private $categories;

    /**
     * @var ItemTag[]
     * @oneToMany
     * @childJoinColumns item_id,item_type=Organisation
     */
    private $tags;




    /**
     * @param OrganisationItem $organisation
     * @param integer $accountId
     */
    public function __construct($organisation, $accountId = null) {
        parent::__construct($organisation, $accountId);

        // Map back from organisation item objects
        if ($organisation instanceof OrganisationItem) {
            $this->address = $organisation->getAddress() ? new Address($organisation->getAddress(), $accountId) : null;
            $this->primaryContact = $organisation->getPrimaryContact() ? new Contact($organisation->getPrimaryContact(), $accountId) : null;
            $this->notes = $organisation->getNotes();

            // Map all departments
            $this->departments = [];
            foreach ($organisation->getDepartments() ?? [] as $department) {
                $this->departments[] = new Department($department);
            }

            $this->tags = array_map(function ($tagItem) use ($accountId) {
                return new ItemTag($tagItem, $accountId);
            }, $organisation->getTags());

            $this->categories = array_map(function ($categoryItem) use ($accountId) {
                return new ItemCategory($categoryItem, $accountId);
            }, $organisation->getCategories());

        }
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

    /**
     * @return Department[]
     */
    public function getDepartments() {
        return $this->departments;
    }

    /**
     * @param Department[] $departments
     */
    public function setDepartments($departments): void {
        $this->departments = $departments;
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