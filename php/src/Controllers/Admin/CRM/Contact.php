<?php

namespace KiniCRM\Controllers\Admin\CRM;

use KiniCRM\Services\CRM\ContactService;
use KiniCRM\ValueObjects\CRM\ContactItem;
use Kinikit\Core\Logging\Logger;
use Kinikit\MVC\Request\FileUpload;
use Kinikit\Persistence\ORM\Query\SummarisedValue;

class Contact {

    /**
     * @var ContactService
     */
    private $contactService;

    /**
     * @param ContactService $contactService
     */
    public function __construct($contactService) {
        $this->contactService = $contactService;
    }


    /**
     * Get a contact by id
     *
     * @http GET /$id
     *
     * @param $id
     * @return ContactItem
     */
    public function getContact($id) {
        return ContactItem::fromContact($this->contactService->getContact($id));
    }


    /**
     * @http POST /search
     *
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     *
     * @return ContactItem[]
     */
    public function searchForContacts($filters = [], $limit = 10, $offset = 0) {
        $contacts = $this->contactService->filterContacts($filters, $limit, $offset);
        return array_map(function ($contact) {
            return ContactItem::fromContact($contact);
        }, $contacts);
    }


    /**
     * @http POST /filterValues/$memberName
     *
     * @param string $memberName
     * @param array $filters
     *
     * @return SummarisedValue[]
     */
    public function getContactFilterValues($memberName, $filters) {
        return $this->contactService->getContactFilterValues($memberName, $filters);
    }


    /**
     * @http POST /
     *
     * @param ContactItem $contact
     */
    public function saveContact($contact) {
        return ContactItem::fromContact($this->contactService->saveContact(new \KiniCRM\Objects\CRM\Contact($contact, 0)));
    }

    /**
     * @http DELETE /
     *
     * @param $contactId
     */
    public function removeContact($contactId) {
        $this->contactService->removeContact($contactId);
    }


    /**
     * @http POST /attachments/$contactId
     *
     * @param integer $contactId
     * @param FileUpload[] $fileUploads
     *
     * @return void
     */
    public function uploadContactAttachments($contactId, $fileUploads) {
        $this->contactService->attachUploadedFilesToContact($contactId, $fileUploads);
    }

    /**
     * @http DELETE /attachments/$contactId/$attachmentId
     *
     * @param integer $contactId
     * @param integer $attachmentId
     *
     * @return void
     */
    public function removeContactAttachment($contactId, $attachmentId) {
        $this->contactService->removeAttachmentFromContact($contactId, $attachmentId);
    }


}
