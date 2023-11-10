<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;
use Kiniauth\Services\Attachment\AttachmentService;
use KiniCRM\Objects\CRM\Contact;
use Kinikit\Core\Stream\File\ReadOnlyFileStream;
use Kinikit\Core\Util\ArrayUtils;
use Kinikit\MVC\Request\FileUpload;
use Kinikit\Persistence\ORM\Query\Filter\LikeFilter;
use Kinikit\Persistence\ORM\Query\Query;
use Kinikit\Persistence\ORM\Query\SummarisedValue;

class ContactService {

    /**
     * @var AttachmentService
     */
    private $attachmentService;

    const FILTER_MAP = [
        "organisations" => "organisationDepartments.organisation.name",
        "departments" => "organisationDepartments.department.name",
        "tags" => "tags.tag.name",
        "categories" => "categories.category.name",
        "search" => "search",
        "emailAddress" => "emailAddress"
    ];

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
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return Contact[]
     */
    public function filterContacts($filters = [], $limit = 10, $offset = 0) {

        $query = new Query(Contact::class);

        // Process filters
        $filters = $this->processQueryFilters($filters);

        return $query->query($filters, "name ASC", $limit, $offset);


    }


    /**
     * Get contact filter values
     *
     * @param string $member
     * @param array $filters
     *
     * @return SummarisedValue[]
     */
    public function getContactFilterValues($member, $filters = []) {

        // Create query
        $query = new Query(Contact::class);

        unset($filters[$member]);

        // Process filters
        $filters = $this->processQueryFilters($filters);

        // Return summarised values
        return $query->summariseByMember(self::FILTER_MAP[$member], $filters);

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


    /**
     * @param array $filters
     * @return array
     */
    private function processQueryFilters(array $filters): array {
        $filters = ArrayUtils::mapArrayKeys($filters, self::FILTER_MAP);

        if (isset($filters["search"])) {
            $filters["search"] = new LikeFilter(["name", "emailAddress", "telephone"], "%" . $filters["search"] . "%");
        }
        return $filters;
    }


}
