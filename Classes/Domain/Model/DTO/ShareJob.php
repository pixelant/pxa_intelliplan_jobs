<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Domain\Model\DTO;

/**
 * Class ShareJob
 * @package Pixelant\PxaIntelliplanJobs\Domain\Model\DTO
 */
class ShareJob
{
    /**
     * @var string
     */
    protected $receiverName = '';

    /**
     * @var string
     */
    protected $receiverEmail = '';

    /**
     * @var string
     */
    protected $senderName = '';

    /**
     * @var string
     */
    protected $senderEmail = '';

    /**
     * @return string
     */
    public function getReceiverName(): string
    {
        return $this->receiverName;
    }

    /**
     * @param string $receiverName
     */
    public function setReceiverName(string $receiverName)
    {
        $this->receiverName = $receiverName;
    }

    /**
     * @return string
     */
    public function getReceiverEmail(): string
    {
        return $this->receiverEmail;
    }

    /**
     * @param string $receiverEmail
     */
    public function setReceiverEmail(string $receiverEmail)
    {
        $this->receiverEmail = $receiverEmail;
    }

    /**
     * @return string
     */
    public function getSenderName(): string
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName(string $senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string
     */
    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    /**
     * @param string $senderEmail
     */
    public function setSenderEmail(string $senderEmail)
    {
        $this->senderEmail = $senderEmail;
    }
}
