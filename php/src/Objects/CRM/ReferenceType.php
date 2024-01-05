<?php

namespace KiniCRM\Objects\CRM;

/**
 * @table kcr_reference_type
 * @generate
 */
class ReferenceType extends MetaData {

    /**
     * @var string
     */
    private ?string $type;

    /**
     * @param string $type
     */
    public function __construct($type, $name, $description = null, $accountId = null, $id = null) {
        $this->type = $type;
        parent::__construct($name, $description, $accountId, $id);
    }


    /**
     * @return string
     */
    public function getType(): ?string {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(?string $type): void {
        $this->type = $type;
    }


}