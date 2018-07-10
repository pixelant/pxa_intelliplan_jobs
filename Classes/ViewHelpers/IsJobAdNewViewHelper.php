<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\ViewHelpers;

use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class IsJobAdNewViewHelper
 * @package Pixelant\PxaIntelliplanJobs\ViewHelpers
 */
class IsJobAdNewViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Register arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('job', Job::class, 'Job ad record', true);
        $this->registerArgument('newForDays', 'int', 'For how many days job is new', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): bool {
        /** @var Job $job */
        $job = $arguments['job'];
        $newForDays = (int)$arguments['newForDays'];

        if ($job !== null && $newForDays > 0) {
            $today = new \DateTime();
            $today->setTime(23, 59, 59); // Set at the end of day

            $pubDate = $job->getPubDate() ?? $job->getCrdate();
            $pubDate->setTime(0, 0); // Set at the beginning of day

            $interval = $today->diff($pubDate);

            $daysDiff = (int)$interval->format('%R%a');

            return ($newForDays + $daysDiff) >= 0;
        }

        return false;
    }
}
