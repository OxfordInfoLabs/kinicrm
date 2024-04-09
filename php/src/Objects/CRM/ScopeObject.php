<?php

namespace KiniCRM\Objects\CRM;

/**
 * @table kcr_scope_objects
 * @readOnly
 */
class ScopeObject {

    /**
     * @var string
     * @primaryKey
     */
    private $scope;

    /**
     * @var integer
     * @primaryKey
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    public function getScope(): string {
        return $this->scope;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }
}
