<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Task;

class IntelliplanImportService
{
    /**
     * Storage
     *
     * @var int
     */
    protected $pid = 0;

    /**
     * Initialize
     *
     * @param int $pid
     */
    public function __construct(int $pid)
    {
        $this->pid = $pid;
    }

    /**
     */
    public function import()
    {

    }
}
