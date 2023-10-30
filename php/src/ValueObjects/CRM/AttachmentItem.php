<?php

namespace KiniCRM\ValueObjects\CRM;

use Kiniauth\Objects\Attachment\AttachmentSummary;

class AttachmentItem {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @param string $filename
     * @param string $mimeType
     * @param int $id
     */
    public function __construct($filename, $mimeType, $id = null) {
        $this->filename = $filename;
        $this->mimeType = $mimeType;
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getMimeType() {
        return $this->mimeType;
    }


    /**
     * @param AttachmentSummary $attachmentSummary
     * @return AttachmentItem
     */
    public static function fromAttachmentSummary($attachmentSummary) {
        return new AttachmentItem($attachmentSummary->getAttachmentFilename(), $attachmentSummary->getMimeType(), $attachmentSummary->getId());
    }

}