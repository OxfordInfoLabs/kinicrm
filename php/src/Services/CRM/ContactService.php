<?php

namespace KiniCRM\Services\CRM;

use KiniCRM\Objects\CRM\Contact;

class ContactService {

    /**
     * Filter addresses using passed search string
     *
     * @param $searchString
     * @return Contact[]
     */
    public function filterContacts($searchString = "", $limit = 10, $offset = 0) {

        $query = "WHERE CONCAT(name,emailAddress,telephone) LIKE ?";
        $params = ["%" . $searchString . "%"];

        if ($limit) {
            $query .= " LIMIT ?";
            $params[] = $limit;
        }

        if ($offset) {
            $query .= " OFFSET ?";
            $params[] = $offset;
        }


        return Contact::filter($query, $params);

    }

    /**
     * Save a contact
     *
     * @param Contact $contact
     * @return Contact
     */
    public function saveContact($contact) {
        $contact->save();
        return $contact;
    }


    /**
     * Remove a contact
     *
     * @param $contactId
     */
    public function removeContact($contactId) {

        /**
         * @var Contact $contact
         */
        $contact = Contact::fetch($contactId);
        $contact->remove();

    }

}