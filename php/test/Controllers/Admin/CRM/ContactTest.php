<?php

namespace KiniCRM\Controllers\Admin\CRM;

use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\Objects\CRM\Tag;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\ContactItem;
use KiniCRM\ValueObjects\CRM\TagItem;
use Kinikit\Core\DependencyInjection\Container;

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

        $tag = new Tag(new TagItem("Zeta", "Zeta Tag"));
        $tag->save();

        $this->contact->saveContact(new ContactItem("Mark", "mark@test.com", "07575 898989", null, null,
            null, [], [], null, [], [], [new TagItem("Zeta", "Zeta Tag", $tag->getId())]));

        // Check we only have one copy of the tag
        $this->assertEquals(1, sizeof(Tag::filter("WHERE name = 'Zeta'")));


        $search = $this->contact->searchForContacts([], 1, 0);
        $this->assertNotNull($search[0]->getId());

        $this->contact->removeContact($search[0]->getId());

    }

}