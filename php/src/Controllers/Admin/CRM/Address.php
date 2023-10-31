<?php

namespace KiniCRM\Controllers\Admin\CRM;

use KiniCRM\Services\CRM\AddressService;
use KiniCRM\ValueObjects\CRM\AddressItem;

class Address {

    /**
     * @var AddressService
     */
    private $addressService;

    /**
     * @param AddressService $addressService
     */
    public function __construct($addressService) {
        $this->addressService = $addressService;
    }


    /**
     * @http GET /
     *
     * @param $searchString
     * @param $limit
     * @param $offset
     *
     * @return AddressItem[]
     */
    public function searchForAddresses($searchString, $limit = 10, $offset = 0) {
        $addresses = $this->addressService->filterAddresses($searchString, $limit, $offset);
        return array_map(function ($address) {
            return AddressItem::fromAddress($address);
        }, $addresses);
    }


    /**
     * @http POST /
     *
     * @param AddressItem $address
     */
    public function saveAddress($address) {
        return $this->addressService->saveAddress(new \KiniCRM\Objects\CRM\Address($address, 0));
    }

    /**
     * @http DELETE /
     *
     * @param $addressId
     */
    public function removeAddress($addressId) {
        $this->addressService->removeAddress($addressId);
    }


}