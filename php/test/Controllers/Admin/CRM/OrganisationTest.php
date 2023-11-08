<?php

namespace KiniCRM\Controllers\Admin\CRM;

use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\OrganisationItem;
use Kinikit\Core\DependencyInjection\Container;

include_once "autoloader.php";

class OrganisationTest extends TestBase {

    /**
     * @var Organisation
     */
    private $organisation;

    public function setUp(): void {
        $this->organisation = Container::instance()->get(Organisation::class);
    }

    public function testOrganisationController() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        $this->organisation->saveOrganisation(new OrganisationItem("Test", null, null, null, [], null, [], [], []));

        $search = $this->organisation->searchForOrganisations([], 1, 0);
        $this->assertNotNull($search[0]->getId());

        $this->organisation->removeOrganisation($search[0]->getId());

    }


}