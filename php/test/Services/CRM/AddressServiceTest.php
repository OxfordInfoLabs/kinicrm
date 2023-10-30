<?php

namespace KiniCRM\Services\CRM;

use KiniCRM\Objects\CRM\Address;

class AddressServiceTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var AddressService
     */
    private $addressService;

    /**
     *
     *
     * @return void
     */
    public function setUp(): void {

    }


    public function testCanSaveFilterAndRemoveAddresses() {

        $address1 = new Address(0, "3 The Lane", "Somewhere", "Oxford","Oxon", "OX33 1RW","GB");


    }

}