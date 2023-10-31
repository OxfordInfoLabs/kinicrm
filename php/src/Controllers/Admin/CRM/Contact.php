<?php

namespace KiniCRM\Controllers\Admin\CRM;

use KiniCRM\Services\CRM\ContactService;
use KiniCRM\ValueObjects\CRM\ContactItem;
use Kinikit\MVC\Request\FileUpload;

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
     * @http GET /
     *
     * @param $searchString
     * @param $limit
     * @param $offset
     *
     * @return ContactItem[]
     */
    public function searchForContacts($searchString, $limit = 10, $offset = 0) {
        $contacts = $this->contactService->filterContacts($searchString, $limit, $offset);
        return array_map(function ($contact) {
            return ContactItem::fromContact($contact);
        }, $contacts);
    }


    /**
     * @http POST /
     *
     * @param ContactItem $contact
     */
    public function saveContact($contact) {
        return $this->contactService->saveContact(new \KiniCRM\Objects\CRM\Contact($contact, 0));
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