<?php

namespace KiniCRM\Services\CRM;

use KiniCRM\Objects\CRM\Address;

class AddressService {


    /**
     * Get an address by id.
     *
     * @param $id
     * @return Address
     */
    public function getAddress($id) {
        return Address::fetch($id);
    }

    /**
     * Filter addresses using passed search string
     *
     * @param $searchString
     * @return Address[]
     */
    public function filterAddresses($searchString = "", $limit = 10, $offset = 0) {

        $query = "WHERE CONCAT(street1,street2,city,county,postcode,country_code) LIKE ?";
        $params = ["%" . $searchString . "%"];

        if ($limit) {
            $query .= " LIMIT ?";
            $params[] = $limit;
        }

        if ($offset) {
            $query .= " OFFSET ?";
            $params[] = $offset;
        }


        return Address::filter($query, $params);

    }

    /**
     * Save an address
     *
     * @param Address $address
     * @return Address
     */
    public function saveAddress($address) {
        $address->save();
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