<?php
namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Andrii Oprysko <andriy.oprysko@resultify.se>
 */
class JobControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Pixelant\PxaIntelliplanJobs\Controller\JobController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\Pixelant\PxaIntelliplanJobs\Controller\JobController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllJobsFromRepositoryAndAssignsThemToView()
    {

        $allJobs = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jobRepository = $this->getMockBuilder(\::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $jobRepository->expects(self::once())->method('findAll')->will(self::returnValue($allJobs));
        $this->inject($this->subject, 'jobRepository', $jobRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('jobs', $allJobs);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenJobToView()
    {
        $job = new \Pixelant\PxaIntelliplanJobs\Domain\Model\Job();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('job', $job);

        $this->subject->showAction($job);
    }
}
