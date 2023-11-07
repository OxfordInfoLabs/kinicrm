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
     * @var string
     */
    private $notes;


    /**
     * @var TagItem[]
     */
    private $tags;


    /**
     * @var CategoryItem[]
     */
    private $categories;


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
     * @param CategoryItem[] $categories
     * @param TagItem[] $tags
     * @param int $id
     */
    public function __construct($name, $address, $primaryContact, $logo, $departments, $notes, $attachments, $categories = [], $tags = [], $id = null) {
        parent::__construct($name, $logo, $departments, $id);
        $this->address = $address;
        $this->primaryContact = $primaryContact;
        $this->notes = $notes;
        $this->attachments = $attachments;
        $this->categories = $categories;
        $this->tags = $tags;
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
     * @return TagItem[]
     */
    public function getTags(): array {
        return $this->tags;
    }

    /**
     * @return CategoryItem[]
     */
    public function getCategories(): array {
        return $this->categories;
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
    public static function fromOrganisation($organisation) {

        $departments = [];
        foreach ($organisation->getDepartments() ?? [] as $department) {
            $departments[] = DepartmentItem::fromDepartment($department);
        }

        $tags = array_map(function ($tag) {
            return TagItem::fromItemTag($tag) ?? null;
        }, $organisation->getTags() ?? []);


        $categories = array_map(function ($category) {
            return CategoryItem::fromItemCategory($category) ?? null;
        }, $organisation->getCategories() ?? []);


        $attachments = [];
        foreach ($organisation->getAttachments() ?? [] as $attachment) {
            $attachments[] = AttachmentItem::fromAttachmentSummary($attachment);
        }

        return new OrganisationItem($organisation->getName(), AddressItem::fromAddress($organisation->getAddress()),
            $organisation->getPrimaryContact() ? ContactItem::fromContact($organisation->getPrimaryContact()) : null,
            $organisation->getLogo(), $departments, $organisation->getNotes(), $attachments, $categories, $tags, $organisation->getId());

    }


}