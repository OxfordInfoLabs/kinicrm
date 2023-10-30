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
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

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
        $primaryContact->save();

        $primaryContactItem = new ContactItem("Mark Robertshaw", "mark@oxil.co.uk", "07589 898888", "TEST PHOTO", $addressItem, "TEST NOTES", [], [], $primaryContact->getId());

        $organisationItem1 = new OrganisationItem("Test Organisation", $addressItem, $primaryContactItem, "TEST LOGO", [
            new DepartmentItem("HR", "Human Resources Inc."),
            new DepartmentItem("Tech", "Technical Dept.")
        ], "I love this organisation", []);

        $organisation1 = new Organisation($organisationItem1, 0);
        $this->organisationService->saveOrganisation($organisation1);

        $organisationItem2 = new OrganisationItem("New Org", $addressItem, $primaryContactItem, "TEST LOGO 2", [
            new DepartmentItem("Design", "Design Dept."),
            new DepartmentItem("R&D", "Research and Development")
        ], "Another Company", []);

        $organisation2 = new Organisation($organisationItem2, 0);
        $this->organisationService->saveOrganisation($organisation2);

        // Check simple filtering, offset, limit
        $this->assertEquals([$organisation2, $organisation1], $this->organisationService->filterOrganisations("Org"));
        $this->assertEquals([$organisation2], $this->organisationService->filterOrganisations("Org", 1));
        $this->assertEquals([$organisation1], $this->organisationService->filterOrganisations("Org", 5, 1));

        // Check search by contact fields
        $this->assertEquals([$organisation2, $organisation1], $this->organisationService->filterOrganisations("Mark"));
        $this->assertEquals([$organisation2, $organisation1], $this->organisationService->filterOrganisations("oxil"));


        // Do delete
        $this->organisationService->removeOrganisation($organisation1->getId());

        try {
            Organisation::fetch($organisation1->getId());
            $this->fail("Should have thrown here");
        } catch (ObjectNotFoundException $e){
            // As expected
        }


    }

}