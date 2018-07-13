<?php
namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Category;


class CategoryTest extends UnitTestCase
{
    /**
     * @var Category
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new Category();
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }

    /**
     * @test
     */
    public function importIdBeSet()
    {
        $value = 123321;


        $this->subject->setImportId($value);

        $this->assertEquals($value, $this->subject->getImportId());
    }
}
