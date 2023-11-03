<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Test\Services\Security\AuthenticationHelper;
use KiniCRM\Objects\CRM\Address;
use KiniCRM\TestBase;
use KiniCRM\ValueObjects\CRM\AddressItem;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;

include_once "autoloader.php";

class AddressServiceTest extends TestBase {

    /**
     * @var AddressService
     */
    private $addressService;

    public function setUp(): void {
        $this->addressService = new AddressService();
    }


    public function testCanSaveFilterAndRemoveAddresses() {

        AuthenticationHelper::login("admin@kinicart.com", "password");


        $address1 = new Address(new AddressItem("3 The Lane", "Somewhere", "Oxford", "Oxon", "OX1 6DD", "GB"), 0);
        $this->addressService->saveAddress($address1);

        $address2 = new Address(new AddressItem("4 The Lane", "Nowhere", "Cambridge", "Cambs", "CB4 2WW", "FR"), 0);
        $this->addressService->saveAddress($address2);


        $this->assertEquals($address2, $this->addressService->getAddress($address2->getId()));

        // Check string, limit offset
        $this->assertEquals([$address1, $address2], $this->addressService->filterAddresses("lane"));
        $this->assertEquals([$address1], $this->addressService->filterAddresses("lane", 1));
        $this->assertEquals([$address2], $this->addressService->filterAddresses("lane", 5, 1));

        // Check other search fields
        $this->assertEquals([$address2], $this->addressService->filterAddresses("Nowh"));
        $this->assertEquals([$address1], $this->addressService->filterAddresses("Oxfo"));
        $this->assertEquals([$address2], $this->addressService->filterAddresses("Cambs"));
        $this->assertEquals([$address2], $this->addressService->filterAddresses("CB"));
        $this->assertEquals([$address2], $this->addressService->filterAddresses("FR"));

        $this->addressService->removeAddress($address1->getId());

        try {
            Address::fetch($address1->getId());
            $this->fail("Should have thrown here");
        } catch (ObjectNotFoundException $e) {
            // Success
        }

    }

}