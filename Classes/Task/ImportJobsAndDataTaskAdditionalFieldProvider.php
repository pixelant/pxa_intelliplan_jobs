<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Task;

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Lang\LanguageService;
use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class ImportJobsAndDataTaskAdditionalFieldProvider implements AdditionalFieldProviderInterface
{
    /**
     * Provide fields
     *
     * @param array $taskInfo
     * @param AbstractTask $task
     * @param SchedulerModuleController $schedulerModule
     * @return array AbstractTask
     */
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule)
    {
        if ($schedulerModule->CMD == 'add') {
            $taskInfo['pid'] = 0;
        }

        if ($schedulerModule->CMD == 'edit') {
            $taskInfo['pid'] = $task->getPid();
        }

        $additionalFields = [
            'pid' => [
                'code' => '<input type="text" name="tx_scheduler[pxa_intelliplan_jobs_pid]" value="' . ((int)$taskInfo['pid'] ?: '') . '" />',
                'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:importJobsAndDataTask.pid',
                'cshKey' => '',
                'cshLabel' => ''
            ]
        ];

        return $additionalFields;
    }

    /**
     * Validate fields
     *
     * @param array $submittedData
     * @param SchedulerModuleController $schedulerModule
     * @return bool
     */
    public function validateAdditionalFields(
        array &$submittedData,
        SchedulerModuleController $schedulerModule
    ) {
        $valid = (int)$submittedData['pxa_intelliplan_jobs_pid'] > 0;

        if (!$valid) {
            /** @var LanguageService $languageService */
            $languageService = $GLOBALS['LANG'];

            $schedulerModule->addMessage(
                $languageService->sL('LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:importJobsAndDataTask.pidRequired'),
                FlashMessage::ERROR
            );
        }

        return $valid;
    }

    /**
     * Saves
     *
     * @param array $submittedData array containing the data submitted by the user
     * @param AbstractTask $task reference to the current task object
     */
    public function saveAdditionalFields(
        array $submittedData,
        AbstractTask $task
    ) {
        $task->setPid((int)$submittedData['pxa_intelliplan_jobs_pid']);
    }
}
