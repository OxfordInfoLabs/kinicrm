<?php

namespace KiniCRM\Controllers\Admin\CRM;

use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\ContactItem;
use Kinikit\Core\DependencyInjection\Container;
use function PHPUnit\Framework\assertNotNull;

include_once "autoloader.php";

class ContactTest extends TestBase {

    /**
     * @var Contact
     */
    private $contact;

    public function setUp(): void {
        $this->contact = Container::instance()->get(Contact::class);
    }

    public function testContactController() {

        AuthenticationHelper::login("admin@kinicart.com", "password");

        $this->contact->saveContact(new ContactItem("Mark", "mark@test.com", "07575 898989", null, null,
            null, [], [], null, [], [], []));

        $search = $this->contact->searchForContacts([], 1, 0);
        $this->assertNotNull($search[0]->getId());

        $this->contact->removeContact($search[0]->getId());

    }

}