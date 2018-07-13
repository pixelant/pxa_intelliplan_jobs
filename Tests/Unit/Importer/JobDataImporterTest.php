<?php

namespace Pixelant\PxaIntelliplanJobs\Tests\Unit;

use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Category;
use Pixelant\PxaIntelliplanJobs\Domain\Repository\CategoryRepository;
use Pixelant\PxaIntelliplanJobs\Exception\CategoryNotFoundException;
use Pixelant\PxaIntelliplanJobs\Importer\JobDataImporter;
use TYPO3\CMS\Core\DataHandling\DataHandler;


/**
 * Class CategoriesImporterTest
 * @package Pixelant\PxaIntelliplanJobs\Tests\Unit
 */
class JobDataImporterTest extends UnitTestCase
{
    /**
     * @var JobDataImporter|AccessibleMockObjectInterface|MockObject
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            JobDataImporter::class,
            ['getDataHandler'],
            [],
            '',
            false
        );
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }

    /**
     * @test
     */
    public function setImportFieldValueWillSetCorrectValues()
    {
        $existingCategory = new Category();
        $existingCategory->_setProperty('uid', 12);
        $existingCategory->setTitle('First/Category');

        $mocCategoryRepository = $this->createPartialMock(CategoryRepository::class, ['findOneByTitle']);
        $this->subject->_set('categoryRepository', $mocCategoryRepository);

        $mocCategoryRepository
            ->expects($this->once())
            ->method('findOneByTitle')
            ->with('First/Category')
            ->willReturn($existingCategory);

        $data = [
            'pubdateto' => 'Wed, 04 Jul 2018 22:00:00 GMT',
            'category' => 'First/Category',
            'title' => 'My name'
        ];
        $expect = [
            'pub_date_to' => strtotime('Wed, 04 Jul 2018 22:00:00 GMT'),
            'category' => 'First/Category',
            'category_typo3' => 12,
            'title' => 'My name'
        ];
        $result = [];

        foreach (['pubDateTo', 'category', 'title'] as $field) {
            $this->subject->_callRef('setImportFieldValue', $field, $data, $result);
        }

        $this->assertEquals($expect, $result);
    }

    /**
     * @test
     */
    public function setImportFieldValueWillThrownExceptionIfCategoryNotFound()
    {
        $mocCategoryRepository = $this->createMock(CategoryRepository::class);
        $this->subject->_set('categoryRepository', $mocCategoryRepository);

        $data = [
            'pubdateto' => 'Wed, 04 Jul 2018 22:00:00 GMT',
            'category' => 'First/Category',
            'title' => 'My name'
        ];

        $result = [];

        $this->expectException(CategoryNotFoundException::class);

        foreach (['pubDateTo', 'category', 'title'] as $field) {
            $this->subject->_callRef('setImportFieldValue', $field, $data, $result);
        }
    }
}
