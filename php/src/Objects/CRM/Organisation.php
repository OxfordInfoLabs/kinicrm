<?php

namespace KiniCRM\Objects\CRM;


/**
 * @table kcr_organisation
 * @generate
 */
class Organisation {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

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




}