<?php
namespace Pixelant\PxaIntelliplanJobs\Domain\Model;

/***
 *
 * This file is part of the "Intelliplan jobs integration" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Andrii Oprysko <andriy.oprysko@resultify.se>, Resultify
 *
 ***/

/**
 * Job
 */
class Job extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * city
     *
     * @var string
     */
    protected $city = '';

    /**
     * region
     *
     * @var string
     */
    protected $region = '';

    /**
     * company
     *
     * @var string
     */
    protected $company = '';

    /**
     * extent
     *
     * @var string
     */
    protected $extent = '';

    /**
     * start
     *
     * @var string
     */
    protected $start = '';

    /**
     * applyStart
     *
     * @var \DateTime
     */
    protected $applyStart = null;

    /**
     * body
     *
     * @var string
     */
    protected $body = '';

    /**
     * contentElements
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement>
     */
    protected $contentElements = null;

    /**
     * contentElements
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     * @lazy
     */
    protected $categories = null;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->contentElements = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city
     *
     * @param string $city
     * @return void
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Returns the region
     *
     * @return string $region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Sets the region
     *
     * @param string $region
     * @return void
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * Returns the company
     *
     * @return string $company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Sets the company
     *
     * @param string $company
     * @return void
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * Returns the extent
     *
     * @return string $extent
     */
    public function getExtent()
    {
        return $this->extent;
    }

    /**
     * Sets the extent
     *
     * @param string $extent
     * @return void
     */
    public function setExtent($extent)
    {
        $this->extent = $extent;
    }

    /**
     * Returns the start
     *
     * @return string $start
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Sets the start
     *
     * @param string $start
     * @return void
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Returns the applyStart
     *
     * @return \DateTime $applyStart
     */
    public function getApplyStart()
    {
        return $this->applyStart;
    }

    /**
     * Sets the applyStart
     *
     * @param \DateTime $applyStart
     * @return void
     */
    public function setApplyStart(\DateTime $applyStart)
    {
        $this->applyStart = $applyStart;
    }

    /**
     * Adds a
     *
     * @param \Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement $contentElement
     * @return void
     */
    public function addContentElement(\Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement $contentElement)
    {
        $this->contentElements->attach($contentElement);
    }

    /**
     * Removes a
     *
     * @param \Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement $contentElementToRemove The  to be removed
     * @return void
     */
    public function removeContentElement(\Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement $contentElementToRemove)
    {
        $this->contentElements->detach($contentElementToRemove);
    }

    /**
     * Returns the contentElements
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<> $contentElements
     */
    public function getContentElements()
    {
        return $this->contentElements;
    }

    /**
     * Sets the contentElements
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<> $contentElements
     * @return void
     */
    public function setContentElements(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $contentElements)
    {
        $this->contentElements = $contentElements;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCategories(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns the body
     *
     * @return string $body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets the body
     *
     * @param string $body
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}
