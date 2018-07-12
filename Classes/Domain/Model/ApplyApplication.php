<?php
namespace Pixelant\PxaIntelliplanJobs\Domain\Model;

/***
 *
 * This file is part of the "Test" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Andriy
 *
 ***/

/**
 * ApplyAplication
 */
class ApplyApplication extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * job
     *
     * @var \Pixelant\PxaIntelliplanJobs\Domain\Model\Job
     */
    protected $job = null;

    /**
     * accountTicket
     *
     * @var string
     */
    protected $accountTicket = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * @return \Pixelant\PxaIntelliplanJobs\Domain\Model\Job
     */
    public function getJob(): \Pixelant\PxaIntelliplanJobs\Domain\Model\Job
    {
        return $this->job;
    }

    /**
     * @param \Pixelant\PxaIntelliplanJobs\Domain\Model\Job $job
     */
    public function setJob(\Pixelant\PxaIntelliplanJobs\Domain\Model\Job $job)
    {
        $this->job = $job;
    }

    /**
     * @return string
     */
    public function getAccountTicket(): string
    {
        return $this->accountTicket;
    }

    /**
     * @param string $accountTicket
     */
    public function setAccountTicket(string $accountTicket)
    {
        $this->accountTicket = $accountTicket;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
}
