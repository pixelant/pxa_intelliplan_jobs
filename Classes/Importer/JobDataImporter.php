<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Importer;

use Pixelant\PxaIntelliplanJobs\Domain\Model\Category;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;
use Pixelant\PxaIntelliplanJobs\Exception\CategoryNotFoundException;
use Pixelant\PxaIntelliplanJobs\Provider\IntelliplanDataProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class JobDataImporter
 * @package Pixelant\PxaIntelliplanJobs\Importer
 */
class JobDataImporter extends AbstractImporter
{
    /**
     * While checking job list, save categories here in order to import it later
     *
     * @var array
     */
    protected $categories = [];

    /**
     * Import fields from feed to TYPO3
     *
     * @var array
     */
    protected $importFields = [
        'title',
        'pubDate',
        'description',
        'category',
        'id',
        'numberOfPositionsToFill',
        'type',
        'jobPositionTitle',
        'jobPositionTitleId',
        'jobPositionCategoryId',
        'jobLocation',
        'jobLocationId',
        'jobOccupation',
        'jobOccupationId',
        'jobCategory',
        'jobCategoryId',
        'service',
        'serviceCategory',
        'country',
        'countryId',
        'state',
        'stateId',
        'municipality',
        'municipalityId',
        'company',
        'companyLogoUrl',
        'employmentExtent',
        'employmentExtentId',
        'employmentType',
        'employmentTypeId',
        'jobLevel',
        'jobLevelId',
        'contact1name',
        'contact1email',
        'pubDateTo',
        'lastUpdated'
    ];

    /**
     * Import jobs data
     *
     * @param array $importData
     * @param int $pid
     */
    public function import(array $importData, int $pid)
    {
        $jobDataToTypo3 = [];
        foreach ($importData as $jobData) {
            /** @var Job $job */
            if ($job = $this->jobRepository->findById((int)$jobData['id'])) {
                $updateJobData = [];

                foreach ($this->importFields as $importField) {
                    $importValue = $jobData[strtolower($importField)];
                    $dbField = GeneralUtility::camelCaseToLowerCaseUnderscored($importField);

                    if ($importField === 'category') {
                        $currentValue = $job->getCategoryTypo3()
                            ? $job->getCategoryTypo3()->getTitle()
                            : '';

                        if ($currentValue !== $importValue) {
                            /** @var Category $newCategory */
                            if ($newCategory = $this->categoryRepository->findOneByTitle($importValue)) {
                                $updateJobData['category'] = $importValue;
                                $updateJobData['category_typo3'] = $newCategory->getUid();
                            } else {
                                // @codingStandardsIgnoreStart
                                throw new CategoryNotFoundException('Category "' . $importValue . '" not found while importing job', 1531211636899);
                                // @codingStandardsIgnoreEnd
                            }
                        }
                    } else {
                        $currentValue = ObjectAccess::getProperty($job, $importField);
                        if ($importField === 'description') {
                            $importValue = $this->processDescriptionField($importValue);
                        }

                        if (is_int($currentValue) && $currentValue !== (int)$importValue) {
                            $updateJobData[$dbField] = (int)$importValue;
                        } elseif (is_string($currentValue) && $currentValue !== (string)$importValue) {
                            $updateJobData[$dbField] = (string)$importValue;
                        } elseif (is_object($currentValue) && $currentValue instanceof \DateTime) {
                            $importValue = strtotime($importValue);

                            // TYPO3 save in DB value with time zone difference
                            if (($currentValue->getOffset() + $currentValue->getTimestamp()) !== $importValue) {
                                $updateJobData[$dbField] = $importValue;
                            }
                        }
                    }
                }

                if (!empty($updateJobData)) {
                    $jobDataToTypo3[$job->getUid()] = $updateJobData;
                }
            } else {
                $newId = StringUtility::getUniqueId('NEW');
                $jobDataToTypo3[$newId] = [
                    'pid' => $pid
                ];

                foreach ($this->importFields as $importField) {
                    $this->setImportFieldValue($importField, $jobData, $jobDataToTypo3[$newId]);
                }
            }
        }

        if (!empty($jobDataToTypo3)) {
            $jobDataToTypo3 = [
                'tx_pxaintelliplanjobs_domain_model_job' => $jobDataToTypo3
            ];

            $dataHandler = $this->getDataHandler();
            $dataHandler->start($jobDataToTypo3, []);
            $dataHandler->process_datamap();

            $this->requireClearCache = true;
        }
    }

    /**
     * Type of job importer
     *
     * @return int
     */
    public function getImporterType(): int
    {
        return IntelliplanDataProvider::JOBS_IMPORTER;
    }

    /**
     * Get value for import field
     *
     * @param string $importField
     * @param array $data
     * @param array &$jobData
     * @return void
     */
    protected function setImportFieldValue(string $importField, array $data, array &$jobData)
    {
        switch ($importField) {
            // Date fields
            case 'pubDateTo':
            case 'lastUpdated':
            case 'pubDate':
                $value = strtotime($data[strtolower($importField)]);
                break;
            case 'description':
                $value = $this->processDescriptionField($data[strtolower($importField)]);
                break;
            case 'category':
                $value = $data[strtolower($importField)];

                /** @var Category $category */
                $category = $this->categoryRepository->findOneByTitle($value);
                if ($category === null) {
                    // @codingStandardsIgnoreStart
                    throw new CategoryNotFoundException('Category "' . $value . '" not found while importing job', 1531209414046);
                    // @codingStandardsIgnoreEnd
                }

                // Set id to categories
                $jobData['category_typo3'] = $category->getUid();
                break;
            default:
                $value = $data[strtolower($importField)];
        }

        if (!empty($value)) {
            $jobData[GeneralUtility::camelCaseToLowerCaseUnderscored($importField)] = $value;
        }
    }

    /**
     * Prepare description field before for RTE for saving
     *
     * @param string $description
     * @return string
     */
    protected function processDescriptionField(string $description): string
    {
        $descriptionParts = explode("\n", $description);
        $result = [];

        $line = 0;
        foreach ($descriptionParts as $i => $descriptionPart) {
            if (!isset($result[$line])) {
                $result[$line] = '';
            }

            if (!empty($descriptionPart)) {
                $result[$line] .= ((empty($result[$line]) ? '' : '<br />') . $descriptionPart);
            } else {
                if ($descriptionParts[$i - 1] === '') {
                    $result[$line - 1] .= '<br />';
                } elseif (isset($descriptionParts[$i + 1])) {
                    $line++;
                }
            }
        }

        if ($result[count($result) - 1] === '') {
            unset($result[count($result) - 1]);
        }

        return '<p>' . implode('</p><p>', $result) . '</p>';
    }
}
