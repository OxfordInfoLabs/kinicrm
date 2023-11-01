<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Attachment\Attachment;
use KiniCRM\Objects\CRM\Address;
use KiniCRM\Objects\CRM\Contact;
use KiniCRM\Objects\CRM\Organisation;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\AddressItem;
use KiniCRM\ValueObjects\CRM\ContactItem;
use KiniCRM\ValueObjects\CRM\DepartmentItem;
use KiniCRM\ValueObjects\CRM\OrganisationItem;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\MVC\Request\FileUpload;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

include_once "autoloader.php";

class OrganisationServiceTest extends TestBase {

    /**
     * @var OrganisationService
     */
    private $organisationService;


    public function setUp(): void {
        $this->organisationService = Container::instance()->get(OrganisationService::class);
    }


    public function testCanCreateUpdateSearchAndDeleteOrganisations() {

        // Save an address first
        $address = new Address(new AddressItem("Street1", "Street2", "Oxford", "Oxon", "OX4 2RD", "GB"), 0);
        $address->save();

        $addressItem = new AddressItem("Street1", "Street2", "Oxford", "Oxon", "OX4 2RD", "GB", $address->getId());

        $primaryContact = new Contact(new ContactItem("Mark Robertshaw", "mark@oxil.co.uk", "07589 898888", "TEST PHOTO", $addressItem, "TEST NOTES", [], [], null, []), 0);
        $primaryContact->save();

        $primaryContactItem = new ContactItem("Mark Robertshaw", "mark@oxil.co.uk", "07589 898888", "TEST PHOTO", $addressItem, "TEST NOTES", [], [], null, [], $primaryContact->getId());

        $organisationItem1 = new OrganisationItem("Test Organisation", $addressItem, $primaryContactItem, "TEST LOGO", [
            new DepartmentItem("HR", "Human Resources Inc."),
            new DepartmentItem("Tech", "Technical Dept.")
        ], "I love this organisation", []);

        $organisation1 = new Organisation($organisationItem1, 0);
        $this->organisationService->saveOrganisation($organisation1);
        $organisation1 = Organisation::fetch($organisation1->getId());

        $organisationItem2 = new OrganisationItem("New Org", $addressItem, $primaryContactItem, "TEST LOGO 2", [
            new DepartmentItem("Design", "Design Dept."),
            new DepartmentItem("R&D", "Research and Development")
        ], "Another Company", []);

        $organisation2 = new Organisation($organisationItem2, 0);
        $this->organisationService->saveOrganisation($organisation2);
        $organisation2 = Organisation::fetch($organisation2->getId());

        $this->assertEquals($organisation2, $this->organisationService->getOrganisation($organisation2->getId()));

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
        } catch (ObjectNotFoundException $e) {
            // As expected
        }


    }

    public function testCanAttachUploadedFilesToOrganisationAndRemoveThem() {


        $organisationItem1 = new OrganisationItem("Test Organisation", null, null, "TEST LOGO", [
            new DepartmentItem("HR", "Human Resources Inc."),
            new DepartmentItem("Tech", "Technical Dept.")
        ], "I love this organisation", []);

        $organisation1 = new Organisation($organisationItem1, 0);
        $this->organisationService->saveOrganisation($organisation1);

        $fileUpload1 = new FileUpload("test", ["name" => "test.txt", "tmp_name" => __DIR__ . "/test.txt"]);
        $fileUpload2 = new FileUpload("test", ["name" => "test2.txt", "tmp_name" => __DIR__ . "/test2.txt"]);

        $this->organisationService->attachUploadedFilesToOrganisation($organisation1->getId(), [$fileUpload1, $fileUpload2]);

        $attachments = Attachment::filter("WHERE parent_object_type = ? AND parent_object_id = ?", "CRMOrganisation", $organisation1->getId());
        $this->assertEquals(2, sizeof($attachments));
        $this->assertEquals(file_get_contents(__DIR__ . "/test.txt"), $attachments[0]->getContent());
        $this->assertEquals(file_get_contents(__DIR__ . "/test2.txt"), $attachments[1]->getContent());


        $this->organisationService->removeAttachmentFromOrganisation($organisation1->getId(), $attachments[0]->getId());

        $attachments = Attachment::filter("WHERE parent_object_type = ? AND parent_object_id = ?", "CRMOrganisation", $organisation1->getId());
        $this->assertEquals(1, sizeof($attachments));
        $this->assertEquals(file_get_contents(__DIR__ . "/test2.txt"), $attachments[0]->getContent());

    }


}