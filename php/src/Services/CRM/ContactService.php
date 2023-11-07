<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;
use Kiniauth\Services\Attachment\AttachmentService;
use KiniCRM\Objects\CRM\Contact;
use Kinikit\Core\Stream\File\ReadOnlyFileStream;
use Kinikit\MVC\Request\FileUpload;
use Kinikit\Persistence\ORM\Query\Filter\LikeFilter;
use Kinikit\Persistence\ORM\Query\Query;

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
    public function getContact($id) {
        return Contact::fetch($id);
    }

    /**
     * Filter addresses using passed search string
     *
     * @param string $searchString
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return Contact[]
     */
    public function filterContacts($searchString = "", $filters = [], $limit = 10, $offset = 0) {

        $query = new Query(Contact::class);

        // Grab permitted filters
        $filters = array_intersect_key($filters, ["organisationDepartments.department.name" => 1, "categories.name" => 1, "tags.name" => 1]);

        // Combine filters
        $combinedFilters = array_merge($filters, [new LikeFilter(["name", "emailAddress", "telephone"], "%" . $searchString . "%")]);

        return $query->query($combinedFilters, "id DESC", $limit, $offset);


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
