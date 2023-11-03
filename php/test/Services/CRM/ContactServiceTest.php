<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Attachment\Attachment;
use Kiniauth\Objects\Security\UserSummary;
use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\Objects\CRM\Address;
use KiniCRM\Objects\CRM\Contact;
use KiniCRM\Objects\CRM\ContactUserSummary;
use KiniCRM\Objects\CRM\Organisation;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\AddressItem;
use KiniCRM\ValueObjects\CRM\ContactItem;
use KiniCRM\ValueObjects\CRM\DepartmentItem;
use KiniCRM\ValueObjects\CRM\OrganisationDepartmentItem;
use KiniCRM\ValueObjects\CRM\OrganisationItem;
use KiniCRM\ValueObjects\CRM\OrganisationSummaryItem;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\MVC\Request\FileUpload;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;
use Kinimailer\Objects\MailingList\MailingListSubscriber;

include_once "autoloader.php";

class ContactServiceTest extends TestBase {


    /**
     * @var ContactService
     */
    private $contactService;


    public function setUp(): void {
        $this->contactService = Container::instance()->get(ContactService::class);
    }


    public function testCanSaveFilterAndDeleteContacts() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

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
        $organisation = Organisation::fetch($organisation->getId());

        $organisationSummaryItem = OrganisationSummaryItem::fromOrganisationSummary($organisation);

        $department1 = DepartmentItem::fromDepartment($organisation->getDepartments()[0]);
        $department2 = DepartmentItem::fromDepartment($organisation->getDepartments()[1]);


        $contactItem = new ContactItem("Bobby Jones", "bobby@oxil.co.uk",
            "07595 893322", "BIG IMAGE", $addressItem, "New Contact", [],
            [new OrganisationDepartmentItem($organisationSummaryItem, $department1, "Master"),
                new OrganisationDepartmentItem($organisationSummaryItem, $department2, "Geek")], null, []);

        $contact1 = new Contact($contactItem, 0);
        $this->contactService->saveContact($contact1);
        $contact1 = Contact::fetch($contact1->getId());


        $contactItem = new ContactItem("Mr Jones", "smith@oxil.co.uk",
            "07595 543221", "BIG IMAGE", $addressItem, "New Contact", [],
            [new OrganisationDepartmentItem($organisationSummaryItem, $department2, "Boss")], null, []);

        $contact2 = new Contact($contactItem, 0);
        $this->contactService->saveContact($contact2);
        $contact2 = Contact::fetch($contact2->getId());

        $this->assertEquals($contact2, $this->contactService->getContact($contact2->getId()));


        // Check filtering, offset limits
        $this->assertEquals([$contact2, $contact1], $this->contactService->filterContacts("oxil"));
        $this->assertEquals([$contact2], $this->contactService->filterContacts("oxil", 1));
        $this->assertEquals([$contact1], $this->contactService->filterContacts("oxil", 10, 1));

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

    public function testCanAttachUploadedFilesToContactAndRemoveThem() {


        $contactItem = new ContactItem("Bobby Jones", "bobby@oxil.co.uk",
            "07595 893322", "BIG IMAGE", null, "New Contact", [],
            [], null, []);

        $contact1 = new Contact($contactItem, 0);
        $this->contactService->saveContact($contact1);

        $fileUpload1 = new FileUpload("test", ["name" => "test.txt", "tmp_name" => __DIR__ . "/test.txt"]);
        $fileUpload2 = new FileUpload("test", ["name" => "test2.txt", "tmp_name" => __DIR__ . "/test2.txt"]);

        $this->contactService->attachUploadedFilesToContact($contact1->getId(), [$fileUpload1, $fileUpload2]);

        $attachments = Attachment::filter("WHERE parent_object_type = ? AND parent_object_id = ?", "CRMContact", $contact1->getId());
        $this->assertEquals(2, sizeof($attachments));
        $this->assertEquals(file_get_contents(__DIR__ . "/test.txt"), $attachments[0]->getContent());
        $this->assertEquals(file_get_contents(__DIR__ . "/test2.txt"), $attachments[1]->getContent());

        $this->contactService->removeAttachmentFromContact($contact1->getId(), $attachments[0]->getId());

        $attachments = Attachment::filter("WHERE parent_object_type = ? AND parent_object_id = ?", "CRMContact", $contact1->getId());
        $this->assertEquals(1, sizeof($attachments));
        $this->assertEquals(file_get_contents(__DIR__ . "/test2.txt"), $attachments[0]->getContent());

    }


    public function testContactsWhoAreUsersHaveAUserSummaryObjectAttachedCorrectly() {

        $contactItem = new ContactItem("Sam Davis", "sam@samdavisdesign.co.uk",
            "07595 893322", "BIG IMAGE", null, "New Contact", [],
            [], null, []);

        $contact1 = new Contact($contactItem, 0);
        $this->contactService->saveContact($contact1);
        $contact1 = Contact::fetch($contact1->getId());

        $this->assertEquals(UserSummary::fetch(2), $contact1->getUserSummary());

    }

    public function testContactsWhoAreSubscribedToMailingListsEndUpWithMailingListsAttached() {

        $contactItem = new ContactItem("Sam Davis", "sam@samdavisdesign.co.uk",
            "07595 893322", "BIG IMAGE", null, "New Contact", [],
            [], null, []);

        $contact1 = new Contact($contactItem, 0);
        $this->contactService->saveContact($contact1);

        // Subscribe Sam to mailing list
        $subscriber = new MailingListSubscriber(1, null, "sam@samdavisdesign.co.uk");
        $subscriber->save();

        $subscriber = new MailingListSubscriber(3, null, "sam@samdavisdesign.co.uk");
        $subscriber->save();


        $contact1 = Contact::fetch($contact1->getId());
        $this->assertEquals(UserSummary::fetch(2), $contact1->getUserSummary());

        $this->assertEquals(2, sizeof($contact1->getSubscribedMailingLists()));
        $this->assertEquals(1, $contact1->getSubscribedMailingLists()[0]->getMailingListId());
        $this->assertEquals(3, $contact1->getSubscribedMailingLists()[1]->getMailingListId());


    }

}