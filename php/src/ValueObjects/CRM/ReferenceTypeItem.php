<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\ReferenceType;

class ReferenceTypeItem {

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
    private $description;


    /**
     * @param int $id
     * @param string $name
     * @param string $description
     */
    public function __construct(string $name, string $description, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }


    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }


    /**
     * Convert a reference type into an item
     *
     * @param ReferenceType $referenceType
     * @return ReferenceTypeItem
     */
    public static function fromReferenceType($referenceType) {
        return new ReferenceTypeItem($referenceType->getName(), $referenceType->getDescription(), $referenceType->getId());
    }


}