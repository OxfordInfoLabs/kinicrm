<?php

namespace KiniCRM\Services\CRM;

use KiniCRM\Objects\CRM\Address;

class AddressService {


    /**
     * Filter addresses using passed search string
     *
     * @param $searchString
     * @return Address[]
     */
    public function filterAddresses($searchString = "", $limit = 10, $offset = 0) {

    }

    /**
     * Save an address
     *
     * @param Address $address
     * @return Address
     */
    public function saveAddress($address) {
        $address->save;
        return $address;
    }


    /**
     * Remove an address
     *
     * @param $addressId
     */
    public function removeAddress($addressId) {

        /**
         * @var Address $address
         */
        $address = Address::fetch($addressId);
        $address->remove();

    }

}