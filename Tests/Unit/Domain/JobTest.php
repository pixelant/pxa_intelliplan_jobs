<?php

namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaIntelliplanJobs\Domain\Model\ApplyApplication;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Category;
use Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class JobTest extends UnitTestCase
{
    /**
     * @var Job
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new Job();
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }

    /**
     * @test
     */
    public function uidCanBeSet()
    {
        $this->subject->_setProperty('uid', 123);

        $this->assertEquals(123, $this->subject->getUid());
    }

    /**
     * @test
     */
    public function categoryTypo3CanBeSet()
    {
        $category = new Category();

        $this->subject->setCategoryTypo3($category);

        $this->assertSame($category, $this->subject->getCategoryTypo3());
    }

    /**
     * @test
     */
    public function applyApplicationsCanBeSet()
    {
        $storage = new ObjectStorage();

        $this->subject->setApplyApplications($storage);

        $this->assertSame($storage, $this->subject->getApplyApplications());
    }

    /**
     * @test
     */
    public function addApplicationWillAddApplicationToStorage()
    {
        $application = new ApplyApplication();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($application);

        $this->subject->addApplyApplication($application);

        $this->assertEquals(
            $objectStorage,
            $this->subject->getApplyApplications()
        );
    }

    /**
     * @test
     */
    public function removeApplicationFromObjectStorage()
    {
        $application = new ApplyApplication();
        $objectStorage = new ObjectStorage();

        $objectStorage->attach($application);
        $objectStorage->detach($application);

        $this->subject->addApplyApplication($application);
        $this->subject->removeApplyApplication($application);

        $this->assertEquals(
            $objectStorage,
            $this->subject->getApplyApplications()
        );
    }

    /**
     * @test
     */
    public function titleCanBeSet()
    {
        $value = 'test';

        $this->subject->setTitle($value);

        $this->assertEquals($value, $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function companyCanBeSet()
    {
        $value = 'test';

        $this->subject->setCompany($value);

        $this->assertEquals($value, $this->subject->getCompany());
    }

    /**
     * @test
     */
    public function descriptionCanBeSet()
    {
        $value = 'test';

        $this->subject->setDescription($value);

        $this->assertEquals($value, $this->subject->getDescription());
    }

    /**
     * @test
     */
    public function crdateCanBeSet()
    {
        $value = new \DateTime();

        $this->subject->setCrdate($value);

        $this->assertSame($value, $this->subject->getCrdate());
    }

    /**
     * @test
     */
    public function pubDateCanBeSet()
    {
        $value = new \DateTime();

        $this->subject->setPubDate($value);

        $this->assertSame($value, $this->subject->getPubDate());
    }

    /**
     * @test
     */
    public function categoryCanBeSet()
    {
        $value = 'test';

        $this->subject->setCategory($value);

        $this->assertEquals($value, $this->subject->getCategory());
    }

    /**
     * @test
     */
    public function idCanBeSet()
    {
        $value = 123;

        $this->subject->setId($value);

        $this->assertEquals($value, $this->subject->getId());
    }

    /**
     * @test
     */
    public function numberOfPositionsToFillCanBeSet()
    {
        $value = 123;

        $this->subject->setNumberOfPositionsToFill($value);

        $this->assertEquals($value, $this->subject->getNumberOfPositionsToFill());
    }

    /**
     * @test
     */
    public function typeCanBeSet()
    {
        $value = 'test';

        $this->subject->setType($value);

        $this->assertEquals($value, $this->subject->getType());
    }

    /**
     * @test
     */
    public function jobPositionTitleCanBeSet()
    {
        $value = 'test';

        $this->subject->setJobPositionTitle($value);

        $this->assertEquals($value, $this->subject->getJobPositionTitle());
    }

    /**
     * @test
     */
    public function jobPositionTitleIdCanBeSet()
    {
        $value = 123;

        $this->subject->setJobPositionTitleId($value);

        $this->assertEquals($value, $this->subject->getJobPositionTitleId());
    }

    /**
     * @test
     */
    public function jobPositionCategoryIdCanBeSet()
    {
        $value = 123;

        $this->subject->setJobPositionCategoryId($value);

        $this->assertEquals($value, $this->subject->getJobPositionCategoryId());
    }

    /**
     * @test
     */
    public function jobLocationCanBeSet()
    {
        $value = 'test';

        $this->subject->setJobLocation($value);

        $this->assertEquals($value, $this->subject->getJobLocation());
    }

    /**
     * @test
     */
    public function jobLocationIdCanBeSet()
    {
        $value = 123;

        $this->subject->setJobLocationId($value);

        $this->assertEquals($value, $this->subject->getJobLocationId());
    }

    /**
     * @test
     */
    public function contentElementsCanBeSet()
    {
        $value = new ObjectStorage();

        $this->subject->setContentElements($value);

        $this->assertSame($value, $this->subject->getContentElements());
    }

    /**
     * @test
     */
    public function addContentElement()
    {
        $object = new ContentElement();
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($object);

        $this->subject->addContentElement($object);

        $this->assertEquals(
            $objectStorage,
            $this->subject->getContentElements()
        );
    }

    /**
     * @test
     */
    public function removeContentElementFromObjectStorage()
    {
        $object = new ContentElement();
        $objectStorage = new ObjectStorage();

        $objectStorage->attach($object);
        $objectStorage->detach($object);

        $this->subject->addContentElement($object);
        $this->subject->removeContentElement($object);

        $this->assertEquals(
            $objectStorage,
            $this->subject->getContentElements()
        );
    }

    /**
     * @test
     */
    public function jobOccupationCanBeSet()
    {
        $value = 'test';

        $this->subject->setJobOccupation($value);

        $this->assertEquals($value, $this->subject->getJobOccupation());
    }

    /**
     * @test
     */
    public function jobOccupationIdCanBeSet()
    {
        $value = 123;

        $this->subject->setJobOccupationId($value);

        $this->assertEquals($value, $this->subject->getJobOccupationId());
    }

    /**
     * @test
     */
    public function jobCategoryCanBeSet()
    {
        $value = 'test';

        $this->subject->setJobCategory($value);

        $this->assertEquals($value, $this->subject->getJobCategory());
    }

    /**
     * @test
     */
    public function jobCategoryIdCanBeSet()
    {
        $value = 123;

        $this->subject->setJobCategoryId($value);

        $this->assertEquals($value, $this->subject->getJobCategoryId());
    }

    /**
     * @test
     */
    public function serviceCategoryCanBeSet()
    {
        $value = 'test';

        $this->subject->setServiceCategory($value);

        $this->assertEquals($value, $this->subject->getServiceCategory());
    }

    /**
     * @test
     */
    public function serviceCanBeSet()
    {
        $value = 'test';

        $this->subject->setService($value);

        $this->assertEquals($value, $this->subject->getService());
    }

    /**
     * @test
     */
    public function countryCanBeSet()
    {
        $value = 'test';

        $this->subject->setCountry($value);

        $this->assertEquals($value, $this->subject->getCountry());
    }

    /**
     * @test
     */
    public function countryIdCanBeSet()
    {
        $value = 123;

        $this->subject->setCountryId($value);

        $this->assertEquals($value, $this->subject->getCountryId());
    }

    /**
     * @test
     */
    public function stateCanBeSet()
    {
        $value = 'test';

        $this->subject->setState($value);

        $this->assertEquals($value, $this->subject->getState());
    }

    /**
     * @test
     */
    public function stateIdCanBeSet()
    {
        $value = 123;

        $this->subject->setStateId($value);

        $this->assertEquals($value, $this->subject->getStateId());
    }

    /**
     * @test
     */
    public function municipalityCanBeSet()
    {
        $value = 'test';

        $this->subject->setMunicipality($value);

        $this->assertEquals($value, $this->subject->getMunicipality());
    }

    /**
     * @test
     */
    public function municipalityIdCanBeSet()
    {
        $value = 123;

        $this->subject->setMunicipalityId($value);

        $this->assertEquals($value, $this->subject->getMunicipalityId());
    }

    /**
     * @test
     */
    public function companyLogoUrlCanBeSet()
    {
        $value = 'test';

        $this->subject->setCompanyLogoUrl($value);

        $this->assertEquals($value, $this->subject->getCompanyLogoUrl());
    }

    /**
     * @test
     */
    public function employmentExtentCanBeSet()
    {
        $value = 'test';

        $this->subject->setEmploymentExtent($value);

        $this->assertEquals($value, $this->subject->getEmploymentExtent());
    }

    /**
     * @test
     */
    public function employmentExtentIdCanBeSet()
    {
        $value = 123;

        $this->subject->setEmploymentExtentId($value);

        $this->assertEquals($value, $this->subject->getEmploymentExtentId());
    }

    /**
     * @test
     */
    public function employmentTypeCanBeSet()
    {
        $value = 'test';

        $this->subject->setEmploymentType($value);

        $this->assertEquals($value, $this->subject->getEmploymentType());
    }

    /**
     * @test
     */
    public function employmentTypeIdCanBeSet()
    {
        $value = 123;

        $this->subject->setEmploymentTypeId($value);

        $this->assertEquals($value, $this->subject->getEmploymentTypeId());
    }

    /**
     * @test
     */
    public function jobLevelCanBeSet()
    {
        $value = 'test';

        $this->subject->setJobLevel($value);

        $this->assertEquals($value, $this->subject->getJobLevel());
    }

    /**
     * @test
     */
    public function jobLevelIdCanBeSet()
    {
        $value = 123;

        $this->subject->setJobLevelId($value);

        $this->assertEquals($value, $this->subject->getJobLevelId());
    }

    /**
     * @test
     */
    public function contact1nameBeSet()
    {
        $value = 'test';

        $this->subject->setContact1name($value);

        $this->assertEquals($value, $this->subject->getContact1name());
    }

    /**
     * @test
     */
    public function contact1emailBeSet()
    {
        $value = 'test';

        $this->subject->setContact1email($value);

        $this->assertEquals($value, $this->subject->getContact1email());
    }

    /**
     * @test
     */
    public function pubDateToCanBeSet()
    {
        $value = new \DateTime();

        $this->subject->setPubDateTo($value);

        $this->assertSame($value, $this->subject->getPubDateTo());
    }

    /**
     * @test
     */
    public function lastUpdatedCanBeSet()
    {
        $value = new \DateTime();

        $this->subject->setLastUpdated($value);

        $this->assertSame($value, $this->subject->getLastUpdated());
    }

    /**
     * @test
     */
    public function topImagesCanSet()
    {
        $value = new ObjectStorage();

        $this->subject->setTopImages($value);

        $this->assertSame($value, $this->subject->getTopImages());
    }

    /**
     * @test
     */
    public function bottomImagesCanSet()
    {
        $value = new ObjectStorage();

        $this->subject->setBottomImages($value);

        $this->assertSame($value, $this->subject->getBottomImages());
    }
}
