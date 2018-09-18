<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class StrReplaceViewHelper
 * @package Pixelant\PxaIntelliplanJobs\ViewHelpers
 */
class StrReplaceViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize
     */
    public function initializeArguments()
    {
        $this->registerArgument('needle', 'mixed', 'Need to replace', true);
        $this->registerArgument('replacement', 'mixed', 'Replace with', true);
        $this->registerArgument('value', 'string', 'String value to replace', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
    ): string {
        $needle = $arguments['needle'];
        $replacement = $arguments['replacement'];
        $value = $arguments['value'];

        return str_replace($needle, $replacement, $value);
    }
}
