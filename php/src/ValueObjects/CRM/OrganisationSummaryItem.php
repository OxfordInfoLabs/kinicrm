<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\OrganisationSummary;

class OrganisationSummaryItem {
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $logo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @param string $name
     * @param string $logo
     * @param int $id
     */
    public function __construct($name, $logo, $id = null) {
        $this->name = $name;
        $this->logo = $logo;
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * @return int|null
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
     * @param OrganisationSummary $organisationSummary
     * @return OrganisationSummaryItem
     */
    public static function fromOrganisationSummary($organisationSummary) {
        return new OrganisationSummaryItem($organisationSummary->getName(), $organisationSummary->getLogo(), $organisationSummary->getId());
    }
}