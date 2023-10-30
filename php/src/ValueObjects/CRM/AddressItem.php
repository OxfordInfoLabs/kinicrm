<?php

namespace KiniCRM\ValueObjects\CRM;

use KiniCRM\Objects\CRM\Address;

class AddressItem {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $street1;

    /**
     * @var string
     */
    private $street2;


    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $county;

    /**
     * @var string
     */
    private $postcode;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @param string $street1
     * @param string $street2
     * @param string $city
     * @param string $county
     * @param string $postcode
     * @param string $countryCode
     * @param integer $id
     */
    public function __construct($street1, $street2, $city, $county, $postcode, $countryCode, $id = null) {
        $this->street1 = $street1;
        $this->street2 = $street2;
        $this->city = $city;
        $this->county = $county;
        $this->postcode = $postcode;
        $this->countryCode = $countryCode;
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStreet1() {
        return $this->street1;
    }

    /**
     * @return string
     */
    public function getStreet2() {
        return $this->street2;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCounty() {
        return $this->county;
    }

    /**
     * @return string
     */
    public function getPostcode() {
        return $this->postcode;
    }

    /**
     * @return string
     */
    public function getCountryCode() {
        return $this->countryCode;
    }


    /**
     * @param Address $address
     * @return AddressItem
     */
    public static function fromAddress($address) {
        return new AddressItem($address->getStreet1(), $address->getStreet2(), $address->getCity(), $address->getCounty(), $address->getPostcode(), $address->getCountryCode());
    }


}