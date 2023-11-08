<?php

namespace KiniCRM\Controllers\Admin\CRM;

use KiniCRM\Services\CRM\OrganisationService;
use KiniCRM\ValueObjects\CRM\OrganisationItem;
use Kinikit\MVC\Request\FileUpload;
use Kinikit\Persistence\ORM\Query\SummarisedValue;

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
     * @http GET /$id
     *
     * @param $id
     * @return OrganisationItem
     */
    public function getOrganisation($id) {
        return OrganisationItem::fromOrganisation($this->organisationService->getOrganisation($id));
    }

    /**
     * @http POST /search
     *
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     *
     * @return OrganisationItem[]
     */
    public function searchForOrganisations($filters = [], $limit = 10, $offset = 0) {
        $organisations = $this->organisationService->filterOrganisations($filters, $limit, $offset);
        return array_map(function ($organisation) {
            return OrganisationItem::fromOrganisation($organisation);
        }, $organisations);
    }


    /**
     * @http POST /filterValues/$memberName
     *
     * @param string $memberName
     * @param array $filters
     *
     * @return SummarisedValue[]
     */
    public function getOrganisationFilterValues($memberName, $filters) {
        return $this->organisationService->getOrganisationFilterValues($memberName, $filters);
    }


    /**
     * @http POST /
     *
     * @param OrganisationItem $organisation
     */
    public function saveOrganisation($organisation) {
        return OrganisationItem::fromOrganisation($this->organisationService->saveOrganisation(new \KiniCRM\Objects\CRM\Organisation($organisation, 0)));
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