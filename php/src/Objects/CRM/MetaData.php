<?php

namespace KiniCRM\Objects\CRM;

use Kinikit\Persistence\ORM\ActiveRecord;

abstract class MetaData extends ActiveRecord {

    /**
     * @var integer
     */
    protected ?int $id;

    /**
     * @var integer
     */
    protected ?int $accountId;


    /**
     * @var string
     */
    protected ?string $name;


    /**
     * @var string
     */
    protected ?string $description;

    /**
     * @param string $name
     * @param string $description
     * @param int $accountId
     * @param int $id
     */
    public function __construct($name, $description = null, $accountId = null, $id = null) {
        $this->name = $name;
        $this->description = $description;
        $this->id = $id;
        $this->accountId = $accountId;
    }


    /**
     * @return int
     */
    public function getId(): ?int {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getAccountId(): ?int {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     */
    public function setAccountId(?int $accountId): void {
        $this->accountId = $accountId;
    }

    /**
     * @return string
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void {
        $this->description = $description;
    }


}