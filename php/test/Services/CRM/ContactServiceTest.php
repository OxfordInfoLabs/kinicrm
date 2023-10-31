<?php

namespace KiniCRM\Services\CRM;

use KiniCRM\Objects\CRM\Address;
use KiniCRM\Objects\CRM\Contact;
use KiniCRM\Objects\CRM\Organisation;
use KiniCRM\Objects\CRM\OrganisationSummary;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\AddressItem;
use KiniCRM\ValueObjects\CRM\ContactItem;
use KiniCRM\ValueObjects\CRM\DepartmentItem;
use KiniCRM\ValueObjects\CRM\OrganisationDepartmentItem;
use KiniCRM\ValueObjects\CRM\OrganisationItem;
use KiniCRM\ValueObjects\CRM\OrganisationSummaryItem;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

include_once "autoloader.php";

class ContactServiceTest extends TestBase {


    /**
     * @var ContactService
     */
    private $contactService;


    public function setUp(): void {
        $this->contactService = new ContactService();
    }


    public function testCanSaveFilterAndDeleteContacts() {

        // Save an address
        $addressItem = new AddressItem("33 My Lane", "Somewhere", "London", "Greater London", "NW1 2RR", "GB");
        $address = new Address($addressItem, 0);
        $address->save();
        $addressItem = AddressItem::fromAddress($address);

        // Save an organisation
        $organisationItem = new OrganisationItem("Test one", $addressItem, null, "TEST LOGO", [
            new DepartmentItem("HR", "HR Department"),
            new DepartmentItem("Tech", "Tech Dept")
        ], "New Org", []);
        $organisation = new Organisation($organisationItem, 0);
        $organisation->save();
        $organisationSummaryItem = OrganisationSummaryItem::fromOrganisationSummary($organisation);
        $department1 = DepartmentItem::fromDepartment($organisation->getDepartments()[0]);
        $department2 = DepartmentItem::fromDepartment($organisation->getDepartments()[1]);


        $contactItem = new ContactItem("Bobby Jones", "bobby@oxil.co.uk",
            "07595 893322", "BIG IMAGE", $addressItem, "New Contact", [],
            [new OrganisationDepartmentItem($organisationSummaryItem, $department1),
                new OrganisationDepartmentItem($organisationSummaryItem, $department2)]);

        $contact1 = new Contact($contactItem, 0);
        $this->contactService->saveContact($contact1);
        $contact1 = Contact::fetch($contact1->getId());


        $contactItem = new ContactItem("Mr Jones", "smith@oxil.co.uk",
            "07595 543221", "BIG IMAGE", $addressItem, "New Contact", [],
            [new OrganisationDepartmentItem($organisationSummaryItem, $department2)]);

        $contact2 = new Contact($contactItem, 0);
        $this->contactService->saveContact($contact2);
        $contact2 = Contact::fetch($contact2->getId());

        // Check filtering, offset limits
        $this->assertEquals([$contact1, $contact2], $this->contactService->filterContacts("oxil"));
        $this->assertEquals([$contact1], $this->contactService->filterContacts("oxil", 1));
        $this->assertEquals([$contact2], $this->contactService->filterContacts("oxil", 10, 1));

        // Check other fields
        $this->assertEquals([$contact1], $this->contactService->filterContacts("bob"));
        $this->assertEquals([$contact2], $this->contactService->filterContacts("smith"));
        $this->assertEquals([$contact2], $this->contactService->filterContacts("221"));
        $this->assertEquals([$contact1], $this->contactService->filterContacts("893"));

        $this->contactService->removeContact($contact1->getId());

        try {
            Contact::fetch($contact1->getId());
            $this->fail("Should have thrown here");
        } catch (ObjectNotFoundException $e) {

        }

    }

}