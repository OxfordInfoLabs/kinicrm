<?php

namespace KiniCRM\Controllers\Admin\CRM;

use KiniCRM\Services\CRM\OrganisationService;
use KiniCRM\ValueObjects\CRM\OrganisationItem;
use Kinikit\MVC\Request\FileUpload;

class Organisation {

    /**
     * @var OrganisationService
     */
    private $organisationService;

    /**
     * @param OrganisationService $organisationService
     */
    public function __construct($organisationService) {
        $this->organisationService = $organisationService;
    }

    /**
     * @http GET /
     *
     * @param $searchString
     * @param $limit
     * @param $offset
     *
     * @return OrganisationItem[]
     */
    public function searchForOrganisations($searchString, $limit = 10, $offset = 0) {
        $organisations = $this->organisationService->filterOrganisations($searchString, $limit, $offset);
        return array_map(function ($organisation) {
            return OrganisationItem::fromOrganisationSummary($organisation);
        }, $organisations);
    }


    /**
     * @http POST /
     *
     * @param OrganisationItem $organisation
     */
    public function saveOrganisation($organisation) {
        return $this->organisationService->saveOrganisation(new \KiniCRM\Objects\CRM\Organisation($organisation, 0));
    }

    /**
     * @http DELETE /
     *
     * @param $organisationId
     */
    public function removeOrganisation($organisationId) {
        $this->organisationService->removeOrganisation($organisationId);
    }


    /**
     * @http POST /attachments/$organisationId
     *
     * @param integer $organisationId
     * @param FileUpload[] $fileUploads
     *
     * @return void
     */
    public function uploadOrganisationAttachments($organisationId, $fileUploads) {
        $this->organisationService->attachUploadedFilesToOrganisation($organisationId, $fileUploads);
    }

    /**
     * @http DELETE /attachments/$organisationId/$attachmentId
     *
     * @param integer $organisationId
     * @param integer $attachmentId
     *
     * @return void
     */
    public function removeOrganisationAttachment($organisationId, $attachmentId) {
        $this->organisationService->removeAttachmentFromOrganisation($organisationId, $attachmentId);
    }



}