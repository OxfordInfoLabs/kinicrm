<?php

namespace KiniCRM\Services\CRM;

use KiniCRM\Objects\CRM\Address;
use KiniCRM\Objects\CRM\Organisation;

class OrganisationService {


    /**
     * Filter organisations using passed search string
     *
     * @param $searchString
     * @return Organisation[]
     */
    public function filterOrganisations($searchString = "", $limit = 10, $offset = 0) {

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


        return Organisation::filter($query, $params);

    }

    /**
     * Save an organisation
     *
     * @param Organisation $organisation
     * @return Organisation
     */
    public function saveOrganisation($organisation) {
        $organisation->save();
        return $organisation;
    }


    /**
     * Remove an organisation
     *
     * @param $organisationId
     */
    public function removeOrganisation($organisationId) {

        /**
         * @var Organisation $organisation
         */
        $organisation = Organisation::fetch($organisationId);
        $organisation->remove();

    }

}