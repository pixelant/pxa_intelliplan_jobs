<?php
namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Andrii Oprysko <andriy.oprysko@resultify.se>
 */
class JobTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Pixelant\PxaIntelliplanJobs\Domain\Model\Job
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Pixelant\PxaIntelliplanJobs\Domain\Model\Job();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );

    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCityReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCity()
        );

    }

    /**
     * @test
     */
    public function setCityForStringSetsCity()
    {
        $this->subject->setCity('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'city',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getRegionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRegion()
        );

    }

    /**
     * @test
     */
    public function setRegionForStringSetsRegion()
    {
        $this->subject->setRegion('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'region',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getCompanyReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCompany()
        );

    }

    /**
     * @test
     */
    public function setCompanyForStringSetsCompany()
    {
        $this->subject->setCompany('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'company',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getExtentReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getExtent()
        );

    }

    /**
     * @test
     */
    public function setExtentForStringSetsExtent()
    {
        $this->subject->setExtent('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'extent',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getStartReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getStart()
        );

    }

    /**
     * @test
     */
    public function setStartForStringSetsStart()
    {
        $this->subject->setStart('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'start',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getApplyStartReturnsInitialValueForDateTime()
    {
        self::assertEquals(
            null,
            $this->subject->getApplyStart()
        );

    }

    /**
     * @test
     */
    public function setApplyStartForDateTimeSetsApplyStart()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setApplyStart($dateTimeFixture);

        self::assertAttributeEquals(
            $dateTimeFixture,
            'applyStart',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getBodyReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBody()
        );

    }

    /**
     * @test
     */
    public function setBodyForStringSetsBody()
    {
        $this->subject->setBody('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'body',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getContentElementsReturnsInitialValueFor()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getContentElements()
        );

    }

    /**
     * @test
     */
    public function setContentElementsForObjectStorageContainingSetsContentElements()
    {
        $contentElement = new ();
        $objectStorageHoldingExactlyOneContentElements = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneContentElements->attach($contentElement);
        $this->subject->setContentElements($objectStorageHoldingExactlyOneContentElements);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneContentElements,
            'contentElements',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function addContentElementToObjectStorageHoldingContentElements()
    {
        $contentElement = new ();
        $contentElementsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $contentElementsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($contentElement));
        $this->inject($this->subject, 'contentElements', $contentElementsObjectStorageMock);

        $this->subject->addContentElement($contentElement);
    }

    /**
     * @test
     */
    public function removeContentElementFromObjectStorageHoldingContentElements()
    {
        $contentElement = new ();
        $contentElementsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $contentElementsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($contentElement));
        $this->inject($this->subject, 'contentElements', $contentElementsObjectStorageMock);

        $this->subject->removeContentElement($contentElement);

    }
}
