<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class ExplodeViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Register arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('delimiter', 'string', 'Explode delimiter', false, ',');
        $this->registerArgument('value', 'string', 'String to explode', false, '');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return array
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
    ): array {
        $delimiter = $arguments['delimiter'];

        $value = $arguments['value'] ?: $renderChildrenClosure();

        return GeneralUtility::trimExplode($delimiter, $value);
    }
}
