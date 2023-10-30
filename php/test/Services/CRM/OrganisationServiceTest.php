<?php

namespace KiniCRM\Services\CRM;

use KiniCRM\Objects\CRM\Address;
use KiniCRM\Objects\CRM\Contact;
use KiniCRM\Objects\CRM\Organisation;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\AddressItem;
use KiniCRM\ValueObjects\CRM\ContactItem;
use KiniCRM\ValueObjects\CRM\DepartmentItem;
use KiniCRM\ValueObjects\CRM\OrganisationDepartmentItem;
use KiniCRM\ValueObjects\CRM\OrganisationItem;
use KiniCRM\ValueObjects\CRM\OrganisationSummaryItem;

include_once "autoloader.php";

class OrganisationServiceTest extends TestBase {

    /**
     * @var OrganisationService
     */
    private $organisationService;


    public function setUp(): void {
        $this->organisationService = new OrganisationService();
    }


    public function testCanCreateUpdateSearchAndDeleteOrganisations() {

        // Save an address first
        $address = new Address(new AddressItem("Street1", "Street2", "Oxford", "Oxon", "OX4 2RD", "GB"), 0);
        $address->save();

        $addressItem = new AddressItem("Street1", "Street2", "Oxford", "Oxon", "OX4 2RD", "GB", $address->getId());


        $primaryContact = new Contact(new ContactItem("Mark Robertshaw", "mark@oxil.co.uk", "07589 898888", "TEST PHOTO", $addressItem, "TEST NOTES", [], []), 0);

        $organisation = new OrganisationItem("Test Organisation", $addressItem, $primaryContact, "TEST LOGO", [
            new DepartmentItem("HR", "Human Resources Inc."),
            new DepartmentItem("Tech", "Technical Dept.")
        ], "I love this organisation", []);

        $this->organisationService->saveOrganisation(new Organisation($organisation, 0));


    }

}