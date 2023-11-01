<?php

namespace KiniCRM\Services\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;
use Kiniauth\Services\Attachment\AttachmentService;
use KiniCRM\Objects\CRM\Contact;
use KiniCRM\Objects\CRM\Organisation;
use Kinikit\Core\Stream\File\ReadOnlyFileStream;
use Kinikit\MVC\Request\FileUpload;

class OrganisationService {

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
     * Get an organisation by id
     *
     * @param $id
     * @return Organisation
     */
    public function getOrganisation($id){
        return Organisation::fetch($id);
    }


    /**
     * Filter organisations using passed search string
     *
     * @param $searchString
     * @return Organisation[]
     */
    public function filterOrganisations($searchString = "", $limit = 10, $offset = 0) {

        $query = "WHERE CONCAT(name, primaryContact.name, primaryContact.email_address) LIKE ? ORDER BY name ";
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



}