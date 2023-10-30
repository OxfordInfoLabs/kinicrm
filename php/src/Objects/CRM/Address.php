<?php

namespace KiniCRM\Objects\CRM;


use KiniCRM\ValueObjects\CRM\AddressItem;
use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * @table kcr_address
 * @generate
 */
class Address extends ActiveRecord {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $accountId;

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
     * @param AddressItem $addressItem
     * @param $accountId
     */
    public function __construct($addressItem, $accountId = null) {
        if ($addressItem instanceof AddressItem) {
            $this->id = $addressItem->getId();
            $this->street1 = $addressItem->getStreet1();
            $this->street2 = $addressItem->getStreet2();
            $this->city = $addressItem->getCity();
            $this->county = $addressItem->getCounty();
            $this->postcode = $addressItem->getPostcode();
            $this->countryCode = $addressItem->getCountryCode();
        }
        $this->accountId = $accountId;
    }


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     */
    public function setAccountId($accountId) {
        $this->accountId = $accountId;
    }


    /**
     * @return string
     */
    public function getStreet1() {
        return $this->street1;
    }

    /**
     * @param string $street1
     */
    public function setStreet1($street1) {
        $this->street1 = $street1;
    }

    /**
     * @return string
     */
    public function getStreet2() {
        return $this->street2;
    }

    /**
     * @param string $street2
     */
    public function setStreet2($street2) {
        $this->street2 = $street2;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCounty() {
        return $this->county;
    }

    /**
     * @param string $county
     */
    public function setCounty($county) {
        $this->county = $county;
    }

    /**
     * @return string
     */
    public function getPostcode() {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode) {
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getCountryCode() {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode) {
        $this->countryCode = $countryCode;
    }


}