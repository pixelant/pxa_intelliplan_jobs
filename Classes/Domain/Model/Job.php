<?php

namespace Pixelant\PxaIntelliplanJobs\Domain\Model;

use Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Extbase\Domain\Model\Category;

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
     * contentElements
     *
     * @var \Pixelant\PxaIntelliplanJobs\Domain\Model\Category>
     * @lazy
     */
    protected $categoryTypo3 = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pixelant\PxaIntelliplanJobs\Domain\Model\ApplyApplication>
     * @cascade remove
     */
    protected $applyApplications = null;

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * company
     *
     * @var string
     */
    protected $company = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * crdate
     *
     * @var \DateTime
     */
    protected $crdate = null;

    /**
     * pubDate
     *
     * @var \DateTime
     */
    protected $pubDate = null;

    /**
     * category
     *
     * @var string
     */
    protected $category = '';

    /**
     * id
     *
     * @var int
     */
    protected $id = 0;

    /**
     * numberOfPositionsToFill
     *
     * @var int
     */
    protected $numberOfPositionsToFill = 0;

    /**
     * @var string
     */
    protected $type = '';

    /**
     * jobPositionTitle
     *
     * @var string
     */
    protected $jobPositionTitle = '';

    /**
     * jobPositionTitleId
     *
     * @var int
     */
    protected $jobPositionTitleId = 0;

    /**
     * jobPositionTitleCategoryId
     *
     * @var int
     */
    protected $jobPositionCategoryId = 0;

    /**
     * jobLocation
     *
     * @var string
     */
    protected $jobLocation = '';

    /**
     * jobLocationId
     *
     * @var int
     */
    protected $jobLocationId = 0;

    /**
     * contentElements
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement>
     */
    protected $contentElements = null;

    /**
     * jobOccupation
     *
     * @var string
     */
    protected $jobOccupation = '';

    /**
     * jobOccupationId
     *
     * @var int
     */
    protected $jobOccupationId = 0;

    /**
     * jobCategory
     *
     * @var string
     */
    protected $jobCategory = '';

    /**
     * jobCategoryId
     *
     * @var int
     */
    protected $jobCategoryId = 0;

    /**
     * serviceCategory
     *
     * @var string
     */
    protected $serviceCategory = '';

    /**
     * service
     *
     * @var string
     */
    protected $service = '';

    /**
     * country
     *
     * @var string
     */
    protected $country = '';

    /**
     * countryId
     *
     * @var string
     */
    protected $countryId = '';

    /**
     * state
     *
     * @var string
     */
    protected $state = '';

    /**
     * stateId
     *
     * @var int
     */
    protected $stateId = 0;

    /**
     * municipality
     *
     * @var string
     */
    protected $municipality = '';

    /**
     * municipalityId
     *
     * @var int
     */
    protected $municipalityId = 0;

    /**
     * companyLogoUrl
     *
     * @var string
     */
    protected $companyLogoUrl = '';

    /**
     * employmentExtent
     *
     * @var string
     */
    protected $employmentExtent = '';

    /**
     * employmentExtentId
     *
     * @var int
     */
    protected $employmentExtentId = 0;

    /**
     * @var string
     */
    protected $employmentType = '';

    /**
     * @var int
     */
    protected $employmentTypeId = 0;

    /**
     * jobLevel
     *
     * @var string
     */
    protected $jobLevel = '';

    /**
     * jobLevelId
     *
     * @var int
     */
    protected $jobLevelId = 0;

    /**
     * contact1name
     *
     * @var string
     */
    protected $contact1name = '';

    /**
     * contact1email
     *
     * @var string
     */
    protected $contact1email = '';

    /**
     * pubDateTo
     *
     * @var \DateTime
     */
    protected $pubDateTo = null;

    /**
     * lastUpdated
     *
     * @var \DateTime
     */
    protected $lastUpdated = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $topImages = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $bottomImages = null;

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
        $this->applyApplications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->topImages = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->bottomImages = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * Adds a
     *
     * @param ContentElement $contentElement
     * @return void
     */
    public function addContentElement(ContentElement $contentElement)
    {
        $this->contentElements->attach($contentElement);
    }

    /**
     * Removes a
     *
     * @param ContentElement $contentElementToRemove The  to be removed
     * @return void
     */
    public function removeContentElement(ContentElement $contentElementToRemove)
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
     * Content elements uids list
     *
     * @return string
     */
    public function getContentElementsUids()
    {
        $uids = array();
        /** @var ContentElement $contentElement */
        foreach ($this->getContentElements() as $contentElement) {
            $uids[] = $contentElement->getUid();
        }
        return implode(',', $uids);
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
     * @return \Pixelant\PxaIntelliplanJobs\Domain\Model\Category
     */
    public function getCategoryTypo3()
    {
        return $this->categoryTypo3;
    }

    /**
     * @param \Pixelant\PxaIntelliplanJobs\Domain\Model\Category $categoryTypo3
     */
    public function setCategoryTypo3(\Pixelant\PxaIntelliplanJobs\Domain\Model\Category $categoryTypo3)
    {
        $this->categoryTypo3 = $categoryTypo3;
    }

    /**
     * Get identifier for FE filtering
     *
     * @return string
     */
    public function getFilteringIdentifiers()
    {
        $parts = [];
        if ($this->getCategoryTypo3() !== null) {
            $parts[] = 'cat' . $this->getCategoryTypo3()->getUid();
        }
        if ($this->getMunicipalityId()) {
            $parts[] = 'city' . $this->getMunicipalityId();
        }

        return implode(',', $parts);
    }

    /**
     * Returns the description
     *
     * @return string description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the category
     *
     * @return string $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param string $category
     * @return void
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Returns the id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id
     *
     * @param int $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the numberOfPositionsToFill
     *
     * @return int $numberOfPositionsToFill
     */
    public function getNumberOfPositionsToFill()
    {
        return $this->numberOfPositionsToFill;
    }

    /**
     * Sets the numberOfPositionsToFill
     *
     * @param int $numberOfPositionsToFill
     * @return void
     */
    public function setNumberOfPositionsToFill($numberOfPositionsToFill)
    {
        $this->numberOfPositionsToFill = $numberOfPositionsToFill;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Returns the jobPositionTitle
     *
     * @return string $jobPositionTitle
     */
    public function getJobPositionTitle()
    {
        return $this->jobPositionTitle;
    }

    /**
     * Sets the jobPositionTitle
     *
     * @param string $jobPositionTitle
     * @return void
     */
    public function setJobPositionTitle($jobPositionTitle)
    {
        $this->jobPositionTitle = $jobPositionTitle;
    }

    /**
     * Returns the jobPositionTitleId
     *
     * @return int $jobPositionTitleId
     */
    public function getJobPositionTitleId()
    {
        return $this->jobPositionTitleId;
    }

    /**
     * Sets the jobPositionTitleId
     *
     * @param int $jobPositionTitleId
     * @return void
     */
    public function setJobPositionTitleId($jobPositionTitleId)
    {
        $this->jobPositionTitleId = $jobPositionTitleId;
    }

    /**
     * Returns the jobPositionTitleCategoryId
     *
     * @return int $jobPositionTitleCategoryId
     */
    public function getJobPositionCategoryId()
    {
        return $this->jobPositionCategoryId;
    }

    /**
     * Sets the jobPositionTitleCategoryId
     *
     * @param int $jobPositionCategoryId
     * @return void
     */
    public function setJobPositionCategoryId($jobPositionCategoryId)
    {
        $this->jobPositionCategoryId = $jobPositionCategoryId;
    }

    /**
     * Returns the jobLocation
     *
     * @return string $jobLocation
     */
    public function getJobLocation()
    {
        return $this->jobLocation;
    }

    /**
     * Sets the jobLocation
     *
     * @param string $jobLocation
     * @return void
     */
    public function setJobLocation($jobLocation)
    {
        $this->jobLocation = $jobLocation;
    }

    /**
     * Returns the jobLocationId
     *
     * @return int $jobLocationId
     */
    public function getJobLocationId()
    {
        return $this->jobLocationId;
    }

    /**
     * Sets the jobLocationId
     *
     * @param int $jobLocationId
     * @return void
     */
    public function setJobLocationId($jobLocationId)
    {
        $this->jobLocationId = $jobLocationId;
    }

    /**
     * Returns the pubDate
     *
     * @return \DateTime pubDate
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * Sets the pubDate
     *
     * @param \DateTime $pubDate
     * @return void
     */
    public function setPubDate(\DateTime $pubDate)
    {
        $this->pubDate = $pubDate;
    }

    /**
     * Returns the jobOccupation
     *
     * @return string $jobOccupation
     */
    public function getJobOccupation()
    {
        return $this->jobOccupation;
    }

    /**
     * Sets the jobOccupation
     *
     * @param string $jobOccupation
     * @return void
     */
    public function setJobOccupation($jobOccupation)
    {
        $this->jobOccupation = $jobOccupation;
    }

    /**
     * Returns the jobOccupationId
     *
     * @return int $jobOccupationId
     */
    public function getJobOccupationId()
    {
        return $this->jobOccupationId;
    }

    /**
     * Sets the jobOccupationId
     *
     * @param int $jobOccupationId
     * @return void
     */
    public function setJobOccupationId($jobOccupationId)
    {
        $this->jobOccupationId = $jobOccupationId;
    }

    /**
     * Returns the jobCategory
     *
     * @return string $jobCategory
     */
    public function getJobCategory()
    {
        return $this->jobCategory;
    }

    /**
     * Sets the jobCategory
     *
     * @param string $jobCategory
     * @return void
     */
    public function setJobCategory($jobCategory)
    {
        $this->jobCategory = $jobCategory;
    }

    /**
     * Returns the jobCategoryId
     *
     * @return int $jobCategoryId
     */
    public function getJobCategoryId()
    {
        return $this->jobCategoryId;
    }

    /**
     * Sets the jobCategoryId
     *
     * @param int $jobCategoryId
     * @return void
     */
    public function setJobCategoryId($jobCategoryId)
    {
        $this->jobCategoryId = $jobCategoryId;
    }

    /**
     * Returns the serviceCategory
     *
     * @return string $serviceCategory
     */
    public function getServiceCategory()
    {
        return $this->serviceCategory;
    }

    /**
     * Sets the serviceCategory
     *
     * @param string $serviceCategory
     * @return void
     */
    public function setServiceCategory($serviceCategory)
    {
        $this->serviceCategory = $serviceCategory;
    }

    /**
     * Returns the service
     *
     * @return string $service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Sets the service
     *
     * @param string $service
     * @return void
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * Returns the country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country
     *
     * @param string $country
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Returns the countryId
     *
     * @return string $countryId
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Sets the countryId
     *
     * @param string $countryId
     * @return void
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;
    }

    /**
     * Returns the state
     *
     * @return string $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the state
     *
     * @param string $state
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Returns the stateId
     *
     * @return int $stateId
     */
    public function getStateId()
    {
        return $this->stateId;
    }

    /**
     * Sets the stateId
     *
     * @param int $stateId
     * @return void
     */
    public function setStateId($stateId)
    {
        $this->stateId = $stateId;
    }

    /**
     * Returns the municipality
     *
     * @return string $municipality
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * Sets the municipality
     *
     * @param string $municipality
     * @return void
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;
    }

    /**
     * Returns the municipalityId
     *
     * @return int $municipalityId
     */
    public function getMunicipalityId()
    {
        return $this->municipalityId;
    }

    /**
     * Sets the municipalityId
     *
     * @param int $municipalityId
     * @return void
     */
    public function setMunicipalityId($municipalityId)
    {
        $this->municipalityId = $municipalityId;
    }

    /**
     * Returns the companyLogoUrl
     *
     * @return string $companyLogoUrl
     */
    public function getCompanyLogoUrl()
    {
        return $this->companyLogoUrl;
    }

    /**
     * Sets the companyLogoUrl
     *
     * @param string $companyLogoUrl
     * @return void
     */
    public function setCompanyLogoUrl($companyLogoUrl)
    {
        $this->companyLogoUrl = $companyLogoUrl;
    }

    /**
     * Returns the employmentExtent
     *
     * @return string $employmentExtent
     */
    public function getEmploymentExtent()
    {
        return $this->employmentExtent;
    }

    /**
     * Sets the employmentExtent
     *
     * @param string $employmentExtent
     * @return void
     */
    public function setEmploymentExtent($employmentExtent)
    {
        $this->employmentExtent = $employmentExtent;
    }

    /**
     * Returns the employmentExtentId
     *
     * @return int $employmentExtentId
     */
    public function getEmploymentExtentId()
    {
        return $this->employmentExtentId;
    }

    /**
     * Sets the employmentExtentId
     *
     * @param int $employmentExtentId
     * @return void
     */
    public function setEmploymentExtentId($employmentExtentId)
    {
        $this->employmentExtentId = $employmentExtentId;
    }

    /**
     * @return string
     */
    public function getEmploymentType(): string
    {
        return $this->employmentType;
    }

    /**
     * @param string $employmentType
     */
    public function setEmploymentType(string $employmentType)
    {
        $this->employmentType = $employmentType;
    }

    /**
     * @return int
     */
    public function getEmploymentTypeId(): int
    {
        return $this->employmentTypeId;
    }

    /**
     * @param int $employmentTypeId
     */
    public function setEmploymentTypeId(int $employmentTypeId)
    {
        $this->employmentTypeId = $employmentTypeId;
    }

    /**
     * Returns the jobLevel
     *
     * @return string $jobLevel
     */
    public function getJobLevel()
    {
        return $this->jobLevel;
    }

    /**
     * Sets the jobLevel
     *
     * @param string $jobLevel
     * @return void
     */
    public function setJobLevel($jobLevel)
    {
        $this->jobLevel = $jobLevel;
    }

    /**
     * Returns the jobLevelId
     *
     * @return int $jobLevelId
     */
    public function getJobLevelId()
    {
        return $this->jobLevelId;
    }

    /**
     * Sets the jobLevelId
     *
     * @param int $jobLevelId
     * @return void
     */
    public function setJobLevelId($jobLevelId)
    {
        $this->jobLevelId = $jobLevelId;
    }

    /**
     * Returns the contact1name
     *
     * @return string $contact1name
     */
    public function getContact1name()
    {
        return $this->contact1name;
    }

    /**
     * Sets the contact1name
     *
     * @param string $contact1name
     * @return void
     */
    public function setContact1name($contact1name)
    {
        $this->contact1name = $contact1name;
    }

    /**
     * Returns the contact1email
     *
     * @return string $contact1email
     */
    public function getContact1email()
    {
        return $this->contact1email;
    }

    /**
     * Sets the contact1email
     *
     * @param string $contact1email
     * @return void
     */
    public function setContact1email($contact1email)
    {
        $this->contact1email = $contact1email;
    }

    /**
     * Returns the pubDateTo
     *
     * @return \DateTime $pubDateTo
     */
    public function getPubDateTo()
    {
        return $this->pubDateTo;
    }

    /**
     * Sets the pubDateTo
     *
     * @param \DateTime $pubDateTo
     * @return void
     */
    public function setPubDateTo(\DateTime $pubDateTo)
    {
        $this->pubDateTo = $pubDateTo;
    }

    /**
     * Returns the lastUpdated
     *
     * @return \DateTime $lastUpdated
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Sets the lastUpdated
     *
     * @param \DateTime $lastUpdated
     * @return void
     */
    public function setLastUpdated(\DateTime $lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * @return \DateTime
     */
    public function getCrdate(): \DateTime
    {
        return $this->crdate;
    }

    /**
     * @param \DateTime $crdate
     */
    public function setCrdate(\DateTime $crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * Adds application
     *
     * @param \Pixelant\PxaIntelliplanJobs\Domain\Model\ApplyApplication $applyApplication
     * @return void
     */
    public function addApplyApplication(\Pixelant\PxaIntelliplanJobs\Domain\Model\ApplyApplication $applyApplication)
    {
        $this->applyApplications->attach($applyApplication);
    }

    /**
     * Removes application
     *
     * @param \Pixelant\PxaIntelliplanJobs\Domain\Model\ApplyApplication $applyApplication The  to be removed
     * @return void
     */
    public function removeApplyApplication(\Pixelant\PxaIntelliplanJobs\Domain\Model\ApplyApplication $applyApplication)
    {
        $this->applyApplications->detach($applyApplication);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getApplyApplications(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->applyApplications;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $applyApplications
     */
    public function setApplyApplications(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $applyApplications)
    {
        $this->applyApplications = $applyApplications;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getTopImages(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->topImages;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $topImages
     */
    public function setTopImages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $topImages)
    {
        $this->topImages = $topImages;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getBottomImages(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->bottomImages;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $bottomImages
     */
    public function setBottomImages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $bottomImages)
    {
        $this->bottomImages = $bottomImages;
    }
}
