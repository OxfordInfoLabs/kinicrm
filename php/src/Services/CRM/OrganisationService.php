<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;
use Kiniauth\Services\Attachment\AttachmentService;
use KiniCRM\Objects\CRM\Contact;
use KiniCRM\Objects\CRM\Organisation;
use Kinikit\Core\Stream\File\ReadOnlyFileStream;
use Kinikit\Core\Util\ArrayUtils;
use Kinikit\MVC\Request\FileUpload;
use Kinikit\Persistence\ORM\Query\Filter\LikeFilter;
use Kinikit\Persistence\ORM\Query\Query;
use Kinikit\Persistence\ORM\Query\SummarisedValue;

class OrganisationService {

    /**
     * @var AttachmentService
     */
    private $attachmentService;

    const FILTER_MAP = [
        "tags" => "tags.tag.name",
        "categories" => "categories.category.name",
        "search" => "search"
    ];

    /**
     * @param AttachmentService $attachmentService
     */
    public function __construct($attachmentService) {
        $this->attachmentService = $attachmentService;
    }


    /**
     * Get an organisation by id
     *
     * @param $id
     * @return Organisation
     */
    public function getOrganisation($id) {
        return Organisation::fetch($id);
    }


    /**
     * Filter organisations
     *
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     *
     * @return Organisation[]
     */
    public function filterOrganisations($filters = [], $limit = 10, $offset = 0) {

        $query = new Query(Organisation::class);

        $filters = $this->processFilters($filters);

        return $query->query($filters, "name", $limit, $offset);


    }

    /**
     * Get organisation filter values
     *
     * @param string $member
     * @param array $filters
     *
     * @return SummarisedValue[]
     */
    public function getOrganisationFilterValues($member, $filters = []) {

        $query = new Query(Organisation::class);

        unset($filters[$member]);

        $filters = $this->processFilters($filters);

        // Return summarised values
        return $query->summariseByMember(self::FILTER_MAP[$member], $filters);

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


    /**
     * Upload attachments to an organisation
     *
     * @param integer $organisationId
     * @param FileUpload[] $uploadedFiles
     *
     * @return void
     */
    public function attachUploadedFilesToOrganisation($organisationId, $uploadedFiles) {

        // Check access to contact first
        $contact = Contact::fetch($organisationId);

        foreach ($uploadedFiles as $fileUpload) {
            $attachmentSummary = new AttachmentSummary($fileUpload->getClientFilename(), $fileUpload->getMimeType(),
                "CRMOrganisation", $organisationId, null, null, $contact->getAccountId());
            $this->attachmentService->saveAttachment($attachmentSummary, new ReadOnlyFileStream($fileUpload->getTemporaryFilePath()));
        }
    }

    /**
     * Remove an attachment from a contact
     *
     * @param integer $organisationId
     * @param integer $attachmentId
     * @return void
     */
    public function removeAttachmentFromOrganisation($organisationId, $attachmentId) {

        // Check access to contact first
        Organisation::fetch($organisationId);

        // Remove attachment
        $this->attachmentService->removeAttachment($attachmentId);
    }

    /**
     * @param array $filters
     * @return array
     */
    private function processFilters(array $filters): array {
        $filters = ArrayUtils::mapArrayKeys($filters, self::FILTER_MAP);

        if (isset($filters["search"])) {
            $filters["search"] = new LikeFilter(["name", "primaryContact.name", "primaryContact.email_address"], "%" . $filters["search"] . "%");
        }
        return $filters;
    }


}
