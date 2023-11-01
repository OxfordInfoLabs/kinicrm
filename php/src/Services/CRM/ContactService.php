<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;
use Kiniauth\Services\Attachment\AttachmentService;
use KiniCRM\Objects\CRM\Contact;
use Kinikit\Core\Stream\File\ReadOnlyFileStream;
use Kinikit\MVC\Request\FileUpload;

class ContactService {

    /**
     * @var AttachmentService
     */
    private $attachmentService;

    /**
     * @param AttachmentService $attachmentService
     */
    public function __construct($attachmentService) {
        $this->attachmentService = $attachmentService;
    }


    /**
     * @param $id
     *
     * @return Contact
     */
    public function getContact($id){
        return Contact::fetch($id);
    }

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


    /**
     * Upload attachments to a contact
     *
     * @param integer $contactId
     * @param FileUpload[] $uploadedFiles
     *
     * @return void
     */
    public function attachUploadedFilesToContact($contactId, $uploadedFiles) {

        // Check access to contact first
        $contact = Contact::fetch($contactId);

        foreach ($uploadedFiles as $fileUpload) {
            $attachmentSummary = new AttachmentSummary($fileUpload->getClientFilename(), $fileUpload->getMimeType(),
                "CRMContact", $contactId, null, null, $contact->getAccountId());
            $this->attachmentService->saveAttachment($attachmentSummary, new ReadOnlyFileStream($fileUpload->getTemporaryFilePath()));
        }
    }


    /**
     * Remove an attachment from a contact
     *
     * @param integer $contactId
     * @param integer $attachmentId
     * @return void
     */
    public function removeAttachmentFromContact($contactId, $attachmentId) {

        // Check access to contact first
        $contact = Contact::fetch($contactId);

        // Remove attachment
        $this->attachmentService->removeAttachment($attachmentId);
    }


}