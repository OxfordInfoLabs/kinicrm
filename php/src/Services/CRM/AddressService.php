<?php

namespace KiniCRM\Services\CRM;

use KiniCRM\Objects\CRM\Address;
use Kinikit\Persistence\ORM\Query\Filter\LikeFilter;
use Kinikit\Persistence\ORM\Query\Query;

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

        $query = new Query(Address::class);
        return $query->query([
            new LikeFilter(["street1", "street2", "city", "county", "postcode", "country_code"],
                "%" . $searchString . "%")
        ], [], $limit, $offset);

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